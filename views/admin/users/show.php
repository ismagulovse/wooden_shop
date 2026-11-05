<section class="admin-page admin-form-page">
    <div class="admin-head-row">
        <h1 class="admin-title">Пользователь #<?= (int)$user['id'] ?></h1>
        <a href="/admin/users" class="admin-btn admin-btn-secondary">К списку</a>
    </div>

    <div class="admin-stack">
        <div class="admin-info-card">
            <h2>Профиль</h2>
            <div class="admin-info-grid">
                <p><strong>Имя:</strong> <?= e($user['first_name'] . ' ' . $user['last_name']) ?></p>
                <p><strong>Email:</strong> <?= e($user['email']) ?></p>
                <p><strong>Телефон:</strong> <?= e($user['phone']) ?></p>
                <p><strong>Регистрация:</strong> <?= e($user['created_at']) ?></p>
            </div>
        </div>

        <div class="admin-info-card">
            <h2>Права и доступ</h2>
            <form action="/admin/users/<?= (int)$user['id'] ?>/status" method="post" class="form admin-form-inline">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

                <div class="grid cols-2">
                    <label>Роль
                        <select name="role">
                            <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Покупатель</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
                        </select>
                    </label>

                    <label>Статус
                        <select name="status">
                            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Активен</option>
                            <option value="blocked" <?= $user['status'] === 'blocked' ? 'selected' : '' ?>>Заблокирован</option>
                        </select>
                    </label>
                </div>

                <button class="admin-btn admin-btn-primary" type="submit">Сохранить</button>
            </form>
        </div>

        <div class="admin-actions-row">
            <?php if (!$user['deleted_at']): ?>
                <form action="/admin/users/<?= (int)$user['id'] ?>/delete" method="post" class="inline-form">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <button class="admin-btn admin-btn-danger" type="submit">Удалить</button>
                </form>
            <?php else: ?>
                <form action="/admin/users/<?= (int)$user['id'] ?>/restore" method="post" class="inline-form">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <button class="admin-btn admin-btn-success" type="submit">Восстановить</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>