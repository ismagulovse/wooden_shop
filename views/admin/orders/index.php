<section class="admin-page">
    <h1 class="admin-title">Заказы</h1>

    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Статус</th>
                <th>Сумма</th>
                <th>Дата</th>
                <th>Удален</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= (int)$o['id'] ?></td>
                    <td><?= e($o['email'] ?? '-') ?></td>
                    <td><?= e(translate_order_status($o['status'])) ?></td>
                    <td><?= money_format_local((float)$o['total_amount']) ?></td>
                    <td><?= e($o['created_at']) ?></td>
                    <td><?= $o['deleted_at'] ? 'Да' : 'Нет' ?></td>
                    <td class="actions-cell">
                        <a href="/admin/orders/<?= (int)$o['id'] ?>" class="admin-btn admin-btn-secondary">Открыть</a>
                        <?php if (!$o['deleted_at']): ?>
                            <form action="/admin/orders/<?= (int)$o['id'] ?>/delete" method="post" class="inline-form">
                                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                <button class="admin-btn admin-btn-danger" type="submit">Удалить</button>
                            </form>
                        <?php else: ?>
                            <form action="/admin/orders/<?= (int)$o['id'] ?>/restore" method="post" class="inline-form">
                                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                <button class="admin-btn admin-btn-success" type="submit">Восстановить</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>