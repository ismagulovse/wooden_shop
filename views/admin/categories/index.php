<section class="admin-page">
    <div class="admin-head-row">
        <h1 class="admin-title">Категории</h1>
        <a class="admin-btn admin-btn-primary" href="/admin/categories/create">Добавить категорию</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Slug</th>
                <th>Удален</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($categories as $c): ?>
                <tr>
                    <td><?= (int)$c['id'] ?></td>
                    <td><?= e($c['name']) ?></td>
                    <td><?= e($c['slug']) ?></td>
                    <td><?= $c['deleted_at'] ? 'Да' : 'Нет' ?></td>
                    <td class="actions-cell">
                        <a href="/admin/categories/<?= (int)$c['id'] ?>/edit" class="admin-btn admin-btn-secondary">Редактировать</a>
                        <?php if (!$c['deleted_at']): ?>
                            <form action="/admin/categories/<?= (int)$c['id'] ?>/delete" method="post" class="inline-form">
                                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                <button type="submit" class="admin-btn admin-btn-danger">Удалить</button>
                            </form>
                        <?php else: ?>
                            <form action="/admin/categories/<?= (int)$c['id'] ?>/restore" method="post" class="inline-form">
                                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                <button type="submit" class="admin-btn admin-btn-success">Восстановить</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>