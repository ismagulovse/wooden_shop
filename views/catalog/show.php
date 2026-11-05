<?php require ROOT_PATH . '/views/partials/breadcrumbs.php'; ?>
<section class="product-detail reveal">
    <div class="gallery">
        <?php if (!empty($product['images'])): ?>
            <?php foreach ($product['images'] as $img): ?>
                <img src="<?= e($img['image_path']) ?>" alt="<?= e($product['name']) ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <img src="/assets/images/placeholder.svg" alt="placeholder">
        <?php endif; ?>
    </div>
    <div>
        <h1><?= e($product['name']) ?></h1>
        <div class="price-row big">
            <strong><?= money_format_local((float)$product['price']) ?></strong>
            <?php if (!empty($product['old_price'])): ?><span class="old-price"><?= money_format_local((float)$product['old_price']) ?></span><?php endif; ?>
        </div>
        <ul class="meta-list">
            <li>Артикул: <?= e($product['sku']) ?></li>
            <li>Категория: <?= e($product['category_name']) ?></li>
            <li>Материал: <?= e($product['material']) ?></li>
            <li>Цвет: <?= e($product['color']) ?></li>
            <li>Размеры: <?= e($product['dimensions']) ?></li>
            <li>Наличие: <?= (int)$product['stock'] > 0 ? 'В наличии' : 'Нет в наличии' ?></li>
        </ul>
        <p><?= e($product['short_description']) ?></p>
        <p><?= nl2br(e($product['full_description'])) ?></p>
        <div class="card-actions">
            <form action="/cart/add" method="post" class="inline-form">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <button type="submit" class="btn">В корзину</button>
            </form>
            <form action="/favorites/add" method="post" class="inline-form">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <button type="submit" class="btn btn-light">В избранное</button>
            </form>
        </div>
    </div>
</section>

<section class="section">
    <h2>Похожие товары</h2>
    <div class="grid cards-4">
        <?php foreach ($similar as $item): ?>
            <a class="card" href="/catalog/<?= e($item['slug']) ?>">
                <img src="<?= e($item['image'] ?: '/assets/images/placeholder.svg') ?>" alt="<?= e($item['name']) ?>">
                <h3><?= e($item['name']) ?></h3>
                <p><?= money_format_local((float)$item['price']) ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</section>


