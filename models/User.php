<?php
class User extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id AND deleted_at IS NULL LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (first_name,last_name,email,phone,password_hash,role,status,created_at,updated_at) VALUES (:first_name,:last_name,:email,:phone,:password_hash,:role,:status,NOW(),NOW())');
        $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password_hash' => $data['password_hash'],
            'role' => $data['role'] ?? 'customer',
            'status' => $data['status'] ?? 'active',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateProfile(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE users SET first_name=:first_name,last_name=:last_name,email=:email,phone=:phone,updated_at=NOW() WHERE id=:id AND deleted_at IS NULL');
        $stmt->execute([
            'id' => $id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]);
    }

    public function updatePassword(int $id, string $hash): void
    {
        $stmt = $this->db->prepare('UPDATE users SET password_hash=:hash,updated_at=NOW() WHERE id=:id AND deleted_at IS NULL');
        $stmt->execute(['id' => $id, 'hash' => $hash]);
    }

    public function list(bool $withDeleted = false): array
    {
        $sql = 'SELECT * FROM users';
        if (!$withDeleted) {
            $sql .= ' WHERE deleted_at IS NULL';
        }
        $sql .= ' ORDER BY created_at DESC';

        return $this->db->query($sql)->fetchAll();
    }

    public function softDelete(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE users SET deleted_at = NOW(), updated_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function restore(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE users SET deleted_at = NULL, updated_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE users SET status = :status, updated_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function updateRoleAndStatus(int $id, string $role, string $status): void
    {
        $stmt = $this->db->prepare('
            UPDATE users
            SET role = :role, status = :status, updated_at = NOW()
            WHERE id = :id
        ');
        $stmt->execute([
            'id' => $id,
            'role' => $role,
            'status' => $status,
        ]);
    }
}

