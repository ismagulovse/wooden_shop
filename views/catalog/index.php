<?php require ROOT_PATH . '/views/partials/breadcrumbs.php'; ?>
<section class="catalog-layout">
    <aside class="filters" id="filtersPanel">
        <h3>Фильтры</h3>
        <form method="get" class="form compact">
            <label>Поиск
                <input type="text" name="q" value="<?= e($filters['q']) ?>">
            </label>

            <label>Категория
                <select name="category">
                    <option value="">Все</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int)$cat['id'] ?>" <?= (string)$cat['id'] === (string)$filters['category'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <div class="grid cols-2">
                <label>Цена от
                    <input type="number" name="price_min" value="<?= e($filters['price_min']) ?>">
                </label>
                <label>Цена до
                    <input type="number" name="price_max" value="<?= e($filters['price_max']) ?>">
                </label>
            </div>

            <label>Материал
                <input type="text" name="material" value="<?= e($filters['material']) ?>">
            </label>

            <label>Цвет
                <input type="text" name="color" value="<?= e($filters['color']) ?>">
            </label>

            <label>Тип мебели (slug категории)
                <input type="text" name="type" value="<?= e($filters['type']) ?>">
            </label>

            <label><input type="checkbox" name="in_stock" value="1" <?= $filters['in_stock'] ? 'checked' : '' ?>> Только в наличии</label>

            <label>Сортировка
                <select name="sort">
                    <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>По дате</option>
                    <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Цена по возрастанию</option>
                    <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Цена по убыванию</option>
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>По названию</option>
                </select>
            </label>

            <div class="admin-actions-row">
                <button class="btn" type="submit">Применить</button>
                <a href="/catalog" class="btn btn-light">Сбросить фильтры</a>
            </div>
        </form>
    </aside>

    <section>
        <div class="mobile-filter-bar">
            <button class="btn btn-sm" id="toggleFilters">Фильтры</button>
        </div>

        <?php if (!$products): ?>
            <div class="empty-state">
                <h2>Ничего не найдено</h2>
                <p>Попробуйте изменить фильтры или сбросить поиск.</p>
                <a href="/catalog" class="btn btn-light">Сбросить фильтры</a>
            </div>
        <?php else: ?>
            <div class="grid cards-3 catalog-products-grid">
                <?php foreach ($products as $item): ?>
                    <article class="card product-card reveal">
                        <a class="card-cover-link" href="/catalog/<?= e($item['slug']) ?>" aria-label="Подробнее о <?= e($item['name']) ?>"></a>
                        <img src="<?= e($item['image'] ?: '/assets/images/placeholder.svg') ?>" alt="<?= e($item['name']) ?>">
                        <h3><?= e($item['name']) ?></h3>
                        <p><?= e($item['short_description']) ?></p>
                        <div class="price-row">
                            <strong><?= money_format_local((float)$item['price']) ?></strong>
                            <?php if (!empty($item['old_price'])): ?><span class="old-price"><?= money_format_local((float)$item['old_price']) ?></span><?php endif; ?>
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

            <div class="pagination">
                <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                    <a class="<?= $i === $pagination['page'] ? 'active' : '' ?>" href="?<?= e(http_build_query(array_merge($_GET, ['page' => $i]))) ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </section>
</section>