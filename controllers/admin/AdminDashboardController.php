<?php

class AdminDashboardController extends AdminBaseController
{
    public function index(): void
    {
        $db = db();
        $counts = [
            'products' => (int) $db->query('SELECT COUNT(*) FROM products WHERE deleted_at IS NULL')->fetchColumn(),
            'categories' => (int) $db->query('SELECT COUNT(*) FROM categories WHERE deleted_at IS NULL')->fetchColumn(),
            'orders' => (int) $db->query('SELECT COUNT(*) FROM orders WHERE deleted_at IS NULL')->fetchColumn(),
            'users' => (int) $db->query('SELECT COUNT(*) FROM users WHERE deleted_at IS NULL')->fetchColumn(),
        ];

        $this->renderAdmin('admin/dashboard', [
            'title' => 'Админ-панель',
            'counts' => $counts,
        ]);
    }
}