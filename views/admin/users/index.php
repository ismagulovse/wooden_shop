<section class="admin-page">
    <h1 class="admin-title">Пользователи</h1>

    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Статус</th>
                <th>Удален</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= (int)$u['id'] ?></td>
                    <td><?= e($u['first_name'] . ' ' . $u['last_name']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td><?= e($u['role']) ?></td>
                    <td><?= e($u['status']) ?></td>
                    <td><?= $u['deleted_at'] ? 'Да' : 'Нет' ?></td>
                    <td class="actions-cell">
                        <a href="/admin/users/<?= (int)$u['id'] ?>" class="admin-btn admin-btn-secondary">Редактировать</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>