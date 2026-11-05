<section class="admin-page admin-form-page">
    <h1 class="admin-title">Учебные расчёты Wooden</h1>
    <p>Демонстрационный модуль для курса рефакторинга. Тарифы условные. Расчёты не изменяют заказы, оплату и остатки магазина.</p>
    <p>В каждой строке сравниваются два альтернативных варианта. В строке скидок показана экономия; в остальных строках — стоимость услуги. Вес для вывоза относится к старой мебели, для доставки — к новой; здесь используется общее демонстрационное значение.</p>
    <?php if ($error): ?><div class="flash flash-error"><?= e($error) ?></div><?php endif; ?>
    <form method="get" action="/admin/training" class="form admin-form">
        <div class="grid cols-2">
            <?php foreach (['subtotal' => 'Стоимость мебели, руб.', 'units' => 'Количество предметов', 'distance' => 'Расстояние, км', 'weight' => 'Вес, кг', 'months' => 'Срок дополнительной гарантии, мес.'] as $key => $label): ?>
                <label><?= e($label) ?> (<?= (int)$limits[$key][0] ?>–<?= (int)$limits[$key][1] ?>)
                    <input type="number" name="<?= e($key) ?>" value="<?= (int)$input[$key] ?>" min="<?= (int)$limits[$key][0] ?>" max="<?= (int)$limits[$key][1] ?>" step="1" required>
                </label>
            <?php endforeach; ?>
        </div>
        <div class="admin-actions-row"><button class="admin-btn admin-btn-primary" type="submit">Рассчитать</button><a class="admin-btn admin-btn-secondary" href="/admin/training">Сбросить</a></div>
    </form>
    <?php foreach ($rows as $row): ?>
        <article class="admin-info-card">
            <h2><?= e($row['id'] . '. ' . $row['title']) ?></h2>
            <div class="admin-info-grid">
                <p><?= e($row['first']) ?>: <strong><?= e(number_format($row['a'] / 100, 2, ',', ' ')) ?> ₽</strong></p>
                <p><?= e($row['second']) ?>: <strong><?= e(number_format($row['b'] / 100, 2, ',', ' ')) ?> ₽</strong></p>
            </div>
        </article>
    <?php endforeach; ?>
</section>
