<?php require ROOT_PATH . '/views/partials/breadcrumbs.php'; ?>
<section class="checkout-grid">
    <div>
        <h1>Оформление заказа</h1>
        <form action="/checkout/place" method="post" class="form" data-validate>
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <label>Имя получателя<input name="customer_name" required value="<?= e($user['first_name'] . ' ' . $user['last_name']) ?>"></label>
            <label>Телефон<input name="customer_phone" required value="<?= e($user['phone']) ?>"></label>
            <label>Email<input type="email" name="customer_email" required value="<?= e($user['email']) ?>"></label>
            <label>Адрес доставки<textarea name="delivery_address" required></textarea></label>
            <label>Комментарий<textarea name="comment"></textarea></label>
            <label>Способ оплаты
                <select name="payment_method" required>
                    <option value="card">Онлайн-карта</option>
                    <option value="cash">Наличными</option>
                </select>
            </label>
            <label>Способ доставки
                <select name="delivery_method" required>
                    <option value="courier">Курьер</option>
                    <option value="pickup">Самовывоз</option>
                </select>
            </label>
            <button class="btn" type="submit">Подтвердить заказ</button>
        </form>
    </div>

    <article class="receipt-card checkout-receipt-preview">
        <header class="receipt-head">
            <div>
                <p class="receipt-kicker">Предварительный чек</p>
                <h2>Ваш заказ</h2>
                <p class="receipt-meta">Финальный чек будет сформирован после подтверждения</p>
            </div>
            <div class="receipt-dates">
                <div>
                    <span>Время заказа</span>
                    <strong><?= e($previewOrderDateText) ?></strong>
                </div>
                <div>
                    <span>Ожидаемая доставка</span>
                    <strong><?= e($previewDeliveryDateText) ?></strong>
                </div>
            </div>
        </header>

        <section class="receipt-info-grid">
            <div class="receipt-block">
                <h3>Заказчик</h3>
                <p><strong><?= e($user['first_name'] . ' ' . $user['last_name']) ?></strong></p>
                <p><?= e($user['phone']) ?></p>
                <p><?= e($user['email']) ?></p>
            </div>
            <div class="receipt-block">
                <h3>Поставщик</h3>
                <p><strong><?= e($supplier['name']) ?></strong></p>
                <p><?= e($supplier['contact']) ?></p>
                <p><?= e($supplier['email']) ?></p>
                <p><?= e($supplier['address']) ?></p>
            </div>
            <div class="receipt-block">
                <h3>Позиции заказа</h3>
                <?php foreach ($items as $item): ?>
                    <p><?= e($item['name']) ?> x <?= (int)$item['quantity'] ?> - <strong><?= money_format_local((float)$item['price'] * (int)$item['quantity']) ?></strong></p>
                <?php endforeach; ?>
            </div>
        </section>

        <footer class="receipt-total-row">
            <div>
                <span>Итого</span>
                <strong><?= money_format_local((float)$total) ?></strong>
            </div>
        </footer>
    </article>
</section>