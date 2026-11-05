<section class="admin-page">
    <h1 class="admin-title">Dashboard</h1>

    <div class="admin-kpi-grid">
        <article class="admin-kpi-card">
            <span>Товары</span>
            <strong><?= (int)$counts['products'] ?></strong>
        </article>
        <article class="admin-kpi-card">
            <span>Категории</span>
            <strong><?= (int)$counts['categories'] ?></strong>
        </article>
        <article class="admin-kpi-card">
            <span>Заказы</span>
            <strong><?= (int)$counts['orders'] ?></strong>
        </article>
        <article class="admin-kpi-card">
            <span>Пользователи</span>
            <strong><?= (int)$counts['users'] ?></strong>
        </article>
    </div>
</section>