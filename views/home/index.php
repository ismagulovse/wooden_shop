<div class="home-page">
<?php
$categoryImages = [
    'krovati' => '/assets/images/bed.jpg',
    'shkafy' => '/assets/images/shkaf.jpg',
    'stulya' => '/assets/images/kreslo.jpg',
    'stoly' => '/assets/images/divan.jpg',
];
?>

<section class="hero hero-full reveal">
    <div>
        <p class="eyebrow">WOODEN ИНТЕРЬЕР</p>
        <h1>Мебель, которую хочется видеть дома каждый день</h1>
        <p>Сдержанная современная мебель из натурального дерева: визуально легко, тактильно тепло, функционально надолго.</p>
        <div class="hero-actions">
            <a href="/catalog" class="btn">Купить сейчас</a>
            <a href="/catalog" class="btn btn-light">Смотреть каталог</a>
        </div>
    </div>

    <div class="hero-photo-slot" aria-label="Фотография баннера Wooden">
        <img src="/assets/images/banner.svg" alt="Баннер Wooden">
    </div>
</section>

<section class="section reveal">
    <div class="section-head">
        <h2>Категории мебели</h2>
    </div>
    <div class="grid cards-4">
        <?php foreach ($categories as $category): ?>
            <?php $catImage = $categoryImages[$category['slug']] ?? '/assets/images/placeholder.svg'; ?>
            <a class="card category-card" href="/catalog?category=<?= (int)$category['id'] ?>" aria-label="<?= e($category['name']) ?>" title="<?= e($category['name']) ?>">
                <div class="category-card-image" aria-hidden="true">
                    <img src="<?= e($catImage) ?>" alt="<?= e($category['name']) ?>">
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section reveal">
    <div class="section-head">
        <h2>Популярные товары</h2>
    </div>
    <div class="grid cards-4">
        <?php foreach ($featured as $item): ?>
            <article class="card product-card">
                <a class="card-cover-link" href="/catalog/<?= e($item['slug']) ?>" aria-label="Подробнее о <?= e($item['name']) ?>"></a>
                <img src="<?= e($item['image'] ?: '/assets/images/placeholder.svg') ?>" alt="<?= e($item['name']) ?>">
                <h3><?= e($item['name']) ?></h3>
                <p><?= e($item['short_description']) ?></p>
                <div class="price-row">
                    <strong><?= money_format_local((float)$item['price']) ?></strong>
                    <?php if (!empty($item['old_price'])): ?>
                        <span class="old-price"><?= money_format_local((float)$item['old_price']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="product-card-actions-bottom">
                    <form action="/cart/add" method="post" class="to-cart-form">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>">
                        <button class="btn btn-sm btn-cart-wide" type="submit">В корзину</button>
                    </form>
                    <form action="/favorites/add" method="post" class="inline-form">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>">
                        <?php $isFavorite = in_array((int)$item['id'], $favoriteIds ?? [], true); ?>
                        <button class="icon-btn icon-btn-heart <?= $isFavorite ? 'is-active' : '' ?>" type="submit" formaction="<?= $isFavorite ? '/favorites/remove' : '/favorites/add' ?>" aria-label="<?= $isFavorite ? 'Убрать из избранного' : 'В избранное' ?>" title="<?= $isFavorite ? 'Убрать из избранного' : 'В избранное' ?>">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 20.4s-7-4.2-9.2-8.4C1 8.9 2.4 5.8 5.6 5.2A5 5 0 0 1 12 8a5 5 0 0 1 6.4-2.8c3.2.6 4.6 3.7 2.8 6.8-2.2 4.2-9.2 8.4-9.2 8.4Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section atelier-section atelier-tail reveal">
    <div class="atelier-inner">
        <div class="atelier-intro">
            <h2>Три принципа Wooden</h2>
            <p>Мы делаем предметы, которые не кричат о себе, а работают на атмосферу пространства: тактильно, архитектурно и по-взрослому спокойно.</p>
        </div>

        <div class="atelier-grid">
            <article class="atelier-item">
                <span>01</span>
                <h3>Тактильная древесина</h3>
                <p>Финиш на маслах и восках сохраняет живую текстуру массива. Поверхности приятны в руке даже без визуальных эффектов.</p>
            </article>
            <article class="atelier-item">
                <span>02</span>
                <h3>Чистая архитектура форм</h3>
                <p>Никаких случайных декоративных деталей. Только точные пропорции, скрытая фурнитура и спокойный ритм линий.</p>
            </article>
            <article class="atelier-item">
                <span>03</span>
                <h3>Коллекционный подход</h3>
                <p>Предметы сочетаются между собой по тону дерева, масштабу и пластике, чтобы интерьер выглядел цельной композицией.</p>
            </article>
        </div>
    </div>
</section>
</div>
