<?php require ROOT_PATH . '/views/partials/breadcrumbs.php'; ?>
<section>
    <h1>Избранное</h1>
    <?php if (!$favorites): ?>
        <div class="empty-state">
            <h2>Избранное пусто</h2>
            <p>Сохраняйте товары в избранное, чтобы быстро вернуться к ним позже.</p>
            <a href="/catalog" class="btn">Перейти в каталог</a>
        </div>
    <?php else: ?>
    <div class="grid cards-3">
        <?php foreach ($favorites as $item): ?>
            <article class="card product-card">
                <a class="card-cover-link" href="/catalog/<?= e($item['slug']) ?>" aria-label="Подробнее о <?= e($item['name']) ?>"></a>
                <img src="<?= e($item['image'] ?: '/assets/images/placeholder.svg') ?>" alt="<?= e($item['name']) ?>">
                <h3><?= e($item['name']) ?></h3>
                <div class="price-row">
                    <strong><?= money_format_local((float)$item['price']) ?></strong>
                </div>
                <div class="card-actions favorites-actions">
                    <form action="/favorites/move-to-cart" method="post" class="inline-form">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>">
                        <button class="btn btn-sm btn-light" type="submit">В корзину</button>
                    </form>
                    <form action="/favorites/remove" method="post" class="inline-form">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>">
                        <button class="btn btn-sm" type="submit">Убрать</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>