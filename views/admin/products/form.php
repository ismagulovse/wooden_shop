<section class="admin-page admin-form-page">
    <h1 class="admin-title"><?= $product ? 'Редактировать товар' : 'Новый товар' ?></h1>

    <form class="form admin-form" action="<?= $product ? '/admin/products/' . (int)$product['id'] . '/update' : '/admin/products/store' ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

        <label>Категория
            <select name="category_id" required>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= (int)($product['category_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="grid cols-2">
            <label>Название
                <input name="name" value="<?= e($product['name'] ?? '') ?>" required>
            </label>
            <label>Slug
                <input name="slug" value="<?= e($product['slug'] ?? '') ?>">
            </label>
        </div>

        <div class="grid cols-2">
            <label>SKU
                <input name="sku" value="<?= e($product['sku'] ?? '') ?>" required>
            </label>
            <label>Размеры
                <input name="dimensions" value="<?= e($product['dimensions'] ?? '') ?>">
            </label>
        </div>

        <div class="grid cols-2">
            <label>Цена
                <input type="number" name="price" step="0.01" value="<?= e((string)($product['price'] ?? '')) ?>" required>
            </label>
            <label>Старая цена
                <input type="number" name="old_price" step="0.01" value="<?= e((string)($product['old_price'] ?? '')) ?>">
            </label>
        </div>

        <div class="grid cols-2">
            <label>Материал
                <input name="material" value="<?= e($product['material'] ?? '') ?>">
            </label>
            <label>Цвет
                <input name="color" value="<?= e($product['color'] ?? '') ?>">
            </label>
        </div>

        <label>Краткое описание
            <textarea name="short_description"><?= e($product['short_description'] ?? '') ?></textarea>
        </label>

        <label>Полное описание
            <textarea name="full_description"><?= e($product['full_description'] ?? '') ?></textarea>
        </label>

        <label>Остаток
            <input type="number" name="stock" value="<?= e((string)($product['stock'] ?? 0)) ?>" min="0" required>
        </label>

        <label>Изображения (можно несколько)
            <input type="file" name="images[]" multiple accept="image/*">
        </label>

        <?php if (!empty($images)): ?>
            <div class="grid cards-4">
                <?php foreach ($images as $img): ?>
                    <img class="preview" src="<?= e($img['image_path']) ?>" alt="Изображение товара">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="grid cols-2">
            <label><input type="checkbox" name="is_active" <?= !isset($product['is_active']) || (int)$product['is_active'] === 1 ? 'checked' : '' ?>> Активен</label>
            <label><input type="checkbox" name="is_featured" <?= isset($product['is_featured']) && (int)$product['is_featured'] === 1 ? 'checked' : '' ?>> Популярный</label>
        </div>

        <div class="admin-actions-row">
            <button class="admin-btn admin-btn-primary" type="submit">Сохранить</button>
            <a href="/admin/products" class="admin-btn admin-btn-secondary">Назад</a>
        </div>
    </form>
</section>