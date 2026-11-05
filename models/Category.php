<?php

class Category extends BaseModel
{
    public function active(): array
    {
        return $this->db->query('SELECT * FROM categories WHERE deleted_at IS NULL ORDER BY name')->fetchAll();
    }

    public function all(bool $withDeleted = true): array
    {
        $sql = 'SELECT * FROM categories';
        if (!$withDeleted) {
            $sql .= ' WHERE deleted_at IS NULL';
        }
        $sql .= ' ORDER BY created_at DESC';

        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare('INSERT INTO categories (name,slug,description,created_at,updated_at) VALUES (:name,:slug,:description,NOW(),NOW())');
        $stmt->execute($data);
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE categories SET name=:name,slug=:slug,description=:description,updated_at=NOW() WHERE id=:id');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'],
        ]);
    }

    public function softDelete(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE categories SET deleted_at = NOW(), updated_at = NOW() WHERE id=:id');
        $stmt->execute(['id' => $id]);
    }

    public function restore(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE categories SET deleted_at = NULL, updated_at = NOW() WHERE id=:id');
        $stmt->execute(['id' => $id]);
    }
}

