<section>
    <h1>Личный кабинет</h1>
    <div class="grid cols-2 profile-grid">
        <article class="card">
            <h2>Персональная информация</h2>
            <form class="form" action="/profile/update" method="post" data-validate>
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <label>Имя<input type="text" name="first_name" required value="<?= e($user['first_name']) ?>"></label>
                <label>Фамилия<input type="text" name="last_name" required value="<?= e($user['last_name']) ?>"></label>
                <label>Email<input type="email" name="email" required value="<?= e($user['email']) ?>"></label>
                <label>Телефон<input type="text" name="phone" required value="<?= e($user['phone']) ?>"></label>
                <button class="btn" type="submit">Сохранить профиль</button>
            </form>
        </article>

        <article class="card">
            <h2>Смена пароля</h2>
            <form class="form" action="/profile/password" method="post" data-validate>
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <label>Текущий пароль<input type="password" name="current_password" required></label>
                <label>Новый пароль<input type="password" name="new_password" minlength="8" required></label>
                <label>Повтор нового пароля<input type="password" name="new_password_confirm" minlength="8" required></label>
                <button class="btn" type="submit">Обновить пароль</button>
            </form>
            <p>Избранное: <?= (int)$favoriteCount ?> товаров</p>
        </article>
    </div>

    <article class="card">
        <h2>Мои заказы</h2>
        <p class="receipt-meta">Отмена доступна только для нового заказа в течение 15 минут после оформления.</p>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>#</th><th>Статус</th><th>Сумма</th><th>Дата</th><th>Действие</th></tr></thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= (int)$order['id'] ?></td>
                        <td><?= e(translate_order_status($order['status'])) ?></td>
                        <td><?= money_format_local((float)$order['total_amount']) ?></td>
                        <td><?= e($order['created_at']) ?></td>
                        <td>
                            <?php if ($order['can_cancel']): ?>
                                <form action="/profile/orders/<?= (int)$order['id'] ?>/cancel" method="post" class="inline-form js-cancel-form" data-remaining="<?= (int)$order['cancel_remaining_seconds'] ?>">
                                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                    <button class="btn btn-sm" type="submit">Отменить заказ</button>
                                    <span class="cancel-timer js-cancel-timer"></span>
                                </form>
                            <?php else: ?>
                                <span class="cancel-disabled">Недоступно</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </article>
</section>