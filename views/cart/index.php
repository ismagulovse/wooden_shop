<?php require ROOT_PATH . '/views/partials/breadcrumbs.php'; ?>
<section>
    <h1>Корзина</h1>
    <?php if (!$items): ?>
        <div class="empty-state">
            <h2>Корзина пуста</h2>
            <p>Добавьте товары из каталога, чтобы оформить заказ.</p>
            <a href="/catalog" class="btn">Перейти в каталог</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Товар</th><th>Цена</th><th>Количество</th><th>Сумма</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="cell-product"><img src="<?= e($item['image'] ?: '/assets/images/placeholder.svg') ?>" alt=""><span><?= e($item['name']) ?></span></td>
                        <td><?= money_format_local((float)$item['price']) ?></td>
                        <td>
                            <form action="/cart/update" method="post" class="inline-form js-auto-qty-form">
                                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="item_id" value="<?= (int)$item['id'] ?>">
                                <input type="number" name="quantity" value="<?= (int)$item['quantity'] ?>" min="1" class="qty-input js-auto-qty">
                            </form>
                        </td>
                        <td><?= money_format_local((float)$item['price'] * (int)$item['quantity']) ?></td>
                        <td>
                            <form action="/cart/remove" method="post">
                                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="item_id" value="<?= (int)$item['id'] ?>">
                                <button class="link-btn" type="submit">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="cart-total">
            <strong>Итого: <?= money_format_local((float)$total) ?></strong>
            <div class="card-actions">
                <a href="/checkout" class="btn">Оформить заказ</a>
                <form action="/cart/clear" method="post">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <button class="btn btn-light" type="submit">Очистить</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</section>