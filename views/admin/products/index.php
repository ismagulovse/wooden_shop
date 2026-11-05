<section class="admin-page">
    <div class="admin-head-row">
        <h1 class="admin-title">Товары</h1>
        <a class="admin-btn admin-btn-primary" href="/admin/products/create">Добавить товар</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Остаток</th>
                <th>Удален</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= (int)$p['id'] ?></td>
                    <td><?= e($p['name']) ?></td>
                    <td><?= e($p['category_name']) ?></td>
                    <td><?= money_format_local((float)$p['price']) ?></td>
                    <td><?= (int)$p['stock'] ?></td>
                    <td><?= $p['deleted_at'] ? 'Да' : 'Нет' ?></td>
                    <td class="actions-cell">
                        <a href="/admin/products/<?= (int)$p['id'] ?>/edit" class="admin-btn admin-btn-secondary">Редактировать</a>
                        <?php if (!$p['deleted_at']): ?>
                            <form action="/admin/products/<?= (int)$p['id'] ?>/delete" method="post" class="inline-form">
                                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                <button type="submit" class="admin-btn admin-btn-danger">Удалить</button>
                            </form>
                        <?php else: ?>
                            <form action="/admin/products/<?= (int)$p['id'] ?>/restore" method="post" class="inline-form">
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