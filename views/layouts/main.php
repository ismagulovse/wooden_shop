<?php
$flashMain = flash('main');
$user = current_user();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="/">Wooden</a>
        <button class="burger" id="burgerBtn" aria-label="menu">&#9776;</button>
        <nav class="nav" id="mainNav">
            <a href="/">Главная</a>
            <a href="/catalog">Каталог</a>
            <?php if ($user): ?>
                <a href="/favorites">Избранное</a>
                <a href="/cart">Корзина</a>
                <a href="/profile">Кабинет</a>
                <?php if (($user['role'] ?? '') === 'admin'): ?>
                    <a href="/admin">Админка</a>
                <?php endif; ?>
                <form method="post" action="/logout" class="inline-form">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <button type="submit" class="link-btn">Выйти</button>
                </form>
            <?php else: ?>
                <a href="/login">Вход</a>
                <a href="/register" class="btn btn-sm">Регистрация</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container page">
    <?php if ($flashMain && ($flashMain['type'] ?? '') === 'error'): ?>
        <div class="flash flash-<?= e($flashMain['type']) ?>"><?= e($flashMain['message']) ?></div>
    <?php endif; ?>
    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <h4>Wooden</h4>
            <p>Натуральная мебель из дерева для вашего дома.</p>
        </div>
        <div>
            <h4>Навигация</h4>
            <a href="/catalog">Каталог</a>
            <a href="/favorites">Избранное</a>
            <a href="/cart">Корзина</a>
        </div>
        <div>
            <h4>Контакты</h4>
            <p>+7 (900) 000-00-00</p>
            <p>hello@wooden.local</p>
        </div>
        <div class="footer-map">
            <h4>Карта</h4>
            <div class="footer-map-card">
                <div class="footer-map-head">
                    <span class="dot"></span>
                    <span>ЮГУ, Ханты-Мансийск</span>
                </div>
                <iframe
                    src="https://www.google.com/maps?q=%D0%AE%D0%93%D0%A3+%D0%A5%D0%B0%D0%BD%D1%82%D1%8B-%D0%9C%D0%B0%D0%BD%D1%81%D0%B8%D0%B9%D1%81%D0%BA&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="ЮГУ Ханты-Мансийск"
                    allowfullscreen>
                </iframe>
                <a class="footer-map-link" target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/search/?api=1&query=%D0%AE%D0%93%D0%A3+%D0%A5%D0%B0%D0%BD%D1%82%D1%8B-%D0%9C%D0%B0%D0%BD%D1%81%D0%B8%D0%B9%D1%81%D0%BA">Открыть в карте</a>
            </div>
        </div>
    </div>
</footer>
<script src="/assets/js/app.js"></script>
</body>
</html>
