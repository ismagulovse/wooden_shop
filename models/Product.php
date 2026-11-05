<?php

class Product extends BaseModel
{
    public function featured(int $limit = 6): array
    {
        $stmt = $this->db->prepare('SELECT p.*, c.name AS category_name, (SELECT image_path FROM product_images WHERE product_id=p.id AND deleted_at IS NULL ORDER BY sort_order, id LIMIT 1) as image FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.deleted_at IS NULL AND p.is_active = 1 AND p.is_featured = 1 ORDER BY p.created_at DESC LIMIT :lim');
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function catalog(array $filters, string $sort, int $page, int $perPage): array
    {
        $where = ['p.deleted_at IS NULL', 'p.is_active = 1'];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(p.name LIKE :q_name OR p.short_description LIKE :q_desc)';
            $search = '%' . $filters['q'] . '%';
            $params['q_name'] = $search;
            $params['q_desc'] = $search;
        }
        if (!empty($filters['category'])) {
            $where[] = 'p.category_id = :category';
            $params['category'] = (int) $filters['category'];
        }
        if (!empty($filters['material'])) {
            $where[] = 'p.material = :material';
            $params['material'] = $filters['material'];
        }
        if (!empty($filters['color'])) {
            $where[] = 'p.color = :color';
            $params['color'] = $filters['color'];
        }
        if (!empty($filters['type'])) {
            $where[] = 'c.slug = :type';
            $params['type'] = $filters['type'];
        }
        if (!empty($filters['in_stock'])) {
            $where[] = 'p.stock > 0';
        }
        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $where[] = 'p.price >= :price_min';
            $params['price_min'] = (float) $filters['price_min'];
        }
        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $where[] = 'p.price <= :price_max';
            $params['price_max'] = (float) $filters['price_max'];
        }

        $sortMap = [
            'price_asc' => 'p.price ASC',
            'price_desc' => 'p.price DESC',
            'name_asc' => 'p.name ASC',
            'date_desc' => 'p.created_at DESC',
        ];
        $sortSql = $sortMap[$sort] ?? $sortMap['date_desc'];

        $whereSql = implode(' AND ', $where);

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT p.*, c.name AS category_name, (SELECT image_path FROM product_images WHERE product_id=p.id AND deleted_at IS NULL ORDER BY sort_order, id LIMIT 1) AS image FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE {$whereSql} ORDER BY {$sortSql} LIMIT :lim OFFSET :off";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $k => $v) {
            $type = is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(':' . $k, $v, $type);
        }
        $stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.slug=:slug AND p.deleted_at IS NULL AND p.is_active=1 LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $product = $stmt->fetch();
        if (!$product) {
            return null;
        }

        $product['images'] = $this->images((int) $product['id']);
        return $product;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id=:id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function similar(int $categoryId, int $productId, int $limit = 4): array
    {
        $stmt = $this->db->prepare('SELECT p.*, (SELECT image_path FROM product_images WHERE product_id=p.id AND deleted_at IS NULL ORDER BY sort_order LIMIT 1) as image FROM products p WHERE p.deleted_at IS NULL AND p.is_active=1 AND p.category_id=:category_id AND p.id != :id ORDER BY p.created_at DESC LIMIT :lim');
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function all(bool $withDeleted = true): array
    {
        $sql = 'SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id';
        if (!$withDeleted) {
            $sql .= ' WHERE p.deleted_at IS NULL';
        }
        $sql .= ' ORDER BY p.created_at DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO products (category_id,name,slug,sku,price,old_price,short_description,full_description,material,color,dimensions,stock,is_active,is_featured,created_at,updated_at) VALUES (:category_id,:name,:slug,:sku,:price,:old_price,:short_description,:full_description,:material,:color,:dimensions,:stock,:is_active,:is_featured,NOW(),NOW())');
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE products SET category_id=:category_id,name=:name,slug=:slug,sku=:sku,price=:price,old_price=:old_price,short_description=:short_description,full_description=:full_description,material=:material,color=:color,dimensions=:dimensions,stock=:stock,is_active=:is_active,is_featured=:is_featured,updated_at=NOW() WHERE id=:id');
        $stmt->execute(array_merge(['id' => $id], $data));
    }

    public function softDelete(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE products SET deleted_at=NOW(), updated_at=NOW() WHERE id=:id');
        $stmt->execute(['id' => $id]);
    }

    public function restore(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE products SET deleted_at=NULL, updated_at=NOW() WHERE id=:id');
        $stmt->execute(['id' => $id]);
    }

    public function addImage(int $productId, string $path, int $sortOrder): void
    {
        $stmt = $this->db->prepare('INSERT INTO product_images (product_id,image_path,sort_order,created_at) VALUES (:product_id,:image_path,:sort_order,NOW())');
        $stmt->execute([
            'product_id' => $productId,
            'image_path' => $path,
            'sort_order' => $sortOrder,
        ]);
    }

    public function images(int $productId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM product_images WHERE product_id=:id AND deleted_at IS NULL ORDER BY sort_order, id');
        $stmt->execute(['id' => $productId]);
        return $stmt->fetchAll();
    }
}

