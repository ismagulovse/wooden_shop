<?php
class Order extends BaseModel
{
    public const STATUSES = ['new', 'processing', 'confirmed', 'shipped', 'delivered', 'cancelled'];

    public function createFromCart(int $userId, array $payload, array $items, float $total): int
    {
        $this->db->beginTransaction();

        try {
            $this->decreaseStockByItems($items);

            $stmt = $this->db->prepare('INSERT INTO orders (user_id,status,customer_name,customer_phone,customer_email,delivery_address,comment,payment_method,delivery_method,total_amount,created_at,updated_at) VALUES (:user_id,:status,:customer_name,:customer_phone,:customer_email,:delivery_address,:comment,:payment_method,:delivery_method,:total_amount,NOW(),NOW())');
            $stmt->execute([
                'user_id' => $userId,
                'status' => 'new',
                'customer_name' => $payload['customer_name'],
                'customer_phone' => $payload['customer_phone'],
                'customer_email' => $payload['customer_email'],
                'delivery_address' => $payload['delivery_address'],
                'comment' => $payload['comment'],
                'payment_method' => $payload['payment_method'],
                'delivery_method' => $payload['delivery_method'],
                'total_amount' => $total,
            ]);

            $orderId = (int) $this->db->lastInsertId();

            $itemStmt = $this->db->prepare('INSERT INTO order_items (order_id,product_id,product_name,price,quantity,total,created_at) VALUES (:order_id,:product_id,:product_name,:price,:quantity,:total,NOW())');
            foreach ($items as $item) {
                $lineTotal = ((float) $item['price']) * ((int) $item['quantity']);
                $itemStmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $lineTotal,
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function listByUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE user_id=:uid AND deleted_at IS NULL ORDER BY created_at DESC');
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function all(bool $withDeleted = true): array
    {
        $sql = 'SELECT o.*, u.email FROM orders o LEFT JOIN users u ON u.id=o.user_id';
        if (!$withDeleted) {
            $sql .= ' WHERE o.deleted_at IS NULL';
        }
        $sql .= ' ORDER BY o.created_at DESC';

        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT o.*, u.email FROM orders o LEFT JOIN users u ON u.id=o.user_id WHERE o.id=:id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }

        $items = $this->db->prepare('SELECT * FROM order_items WHERE order_id=:id ORDER BY id');
        $items->execute(['id' => $id]);
        $order['items'] = $items->fetchAll();
        return $order;
    }

    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, self::STATUSES, true)) {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $lockStmt = $this->db->prepare('SELECT status FROM orders WHERE id = :id LIMIT 1 FOR UPDATE');
            $lockStmt->execute(['id' => $id]);
            $currentStatus = $lockStmt->fetchColumn();
            if ($currentStatus === false) {
                $this->db->rollBack();
                return false;
            }

            $currentStatus = (string) $currentStatus;
            if ($currentStatus === $status) {
                $this->db->commit();
                return true;
            }

            $items = $this->orderItemsForStock((int) $id);

            if ($currentStatus === 'cancelled' && $status !== 'cancelled') {
                $this->decreaseStockByItems($items);
            }

            $stmt = $this->db->prepare('UPDATE orders SET status=:status,updated_at=NOW() WHERE id=:id');
            $stmt->execute(['id' => $id, 'status' => $status]);

            if ($currentStatus !== 'cancelled' && $status === 'cancelled') {
                $this->increaseStockByItems($items);
            }

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function softDelete(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE orders SET deleted_at=NOW(),updated_at=NOW() WHERE id=:id');
        $stmt->execute(['id' => $id]);
    }

    public function restore(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE orders SET deleted_at=NULL,updated_at=NOW() WHERE id=:id');
        $stmt->execute(['id' => $id]);
    }

    public function cancelByUser(int $orderId, int $userId): bool
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                UPDATE orders
                SET status = 'cancelled', updated_at = NOW()
                WHERE id = :id
                  AND user_id = :user_id
                  AND deleted_at IS NULL
                  AND status = 'new'
                  AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) <= 15
            ");
            $stmt->execute([
                'id' => $orderId,
                'user_id' => $userId,
            ]);

            if ($stmt->rowCount() < 1) {
                $this->db->rollBack();
                return false;
            }

            $this->increaseStockByItems($this->orderItemsForStock($orderId));
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    private function orderItemsForStock(int $orderId): array
    {
        $stmt = $this->db->prepare('SELECT product_id, quantity FROM order_items WHERE order_id = :order_id');
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    private function decreaseStockByItems(array $items): void
    {
        $stmt = $this->db->prepare('
            UPDATE products
            SET stock = stock - :qty, updated_at = NOW()
            WHERE id = :product_id
              AND deleted_at IS NULL
              AND is_active = 1
              AND stock >= :qty
        ');

        foreach ($items as $item) {
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $productId = (int) ($item['product_id'] ?? 0);

            if ($productId < 1) {
                throw new RuntimeException('Некорректный товар в заказе.');
            }

            $stmt->execute([
                'qty' => $qty,
                'product_id' => $productId,
            ]);

            if ($stmt->rowCount() < 1) {
                throw new RuntimeException('Недостаточно товара на складе.');
            }
        }
    }

    private function increaseStockByItems(array $items): void
    {
        $stmt = $this->db->prepare('
            UPDATE products
            SET stock = stock + :qty, updated_at = NOW()
            WHERE id = :product_id
        ');

        foreach ($items as $item) {
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $productId = (int) ($item['product_id'] ?? 0);
            if ($productId < 1) {
                continue;
            }

            $stmt->execute([
                'qty' => $qty,
                'product_id' => $productId,
            ]);
        }
    }
}

