<section class="admin-page admin-form-page">
    <h1 class="admin-title"><?= $category ? 'Редактировать категорию' : 'Новая категория' ?></h1>

    <form class="form admin-form" action="<?= $category ? '/admin/categories/' . (int)$category['id'] . '/update' : '/admin/categories/store' ?>" method="post">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

        <label>Название
            <input name="name" value="<?= e($category['name'] ?? '') ?>" required>
        </label>

        <label>Slug
            <input name="slug" value="<?= e($category['slug'] ?? '') ?>">
        </label>

        <label>Описание
            <textarea name="description"><?= e($category['description'] ?? '') ?></textarea>
        </label>

        <div class="admin-actions-row">
            <button class="admin-btn admin-btn-primary" type="submit">Сохранить</button>
            <a href="/admin/categories" class="admin-btn admin-btn-secondary">Назад</a>
        </div>
    </form>
</section>