<?php $flashAuth = flash('auth'); ?>
<section class="auth-wrap">
    <h1>Регистрация</h1>
    <?php if ($flashAuth): ?><div class="flash flash-<?= e($flashAuth['type']) ?>"><?= e($flashAuth['message']) ?></div><?php endif; ?>

    <form class="form" action="/register" method="post" data-validate>
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

        <div class="grid cols-2">
            <label>Имя
                <input type="text" name="first_name" required>
            </label>
            <label>Фамилия
                <input type="text" name="last_name" required>
            </label>
        </div>

        <label>Email
            <input type="email" name="email" required>
        </label>

        <label>Телефон
            <input type="tel" name="phone" required>
        </label>

        <div class="grid cols-2">
            <label>Пароль
                <input type="password" name="password" minlength="8" required>
            </label>
            <label>Подтверждение пароля
                <input type="password" name="password_confirm" minlength="8" required>
            </label>
        </div>

        <button type="submit" class="btn">Создать аккаунт</button>
    </form>

    <p class="auth-switch">Уже есть аккаунт? <a href="/login">Авторизоваться</a></p>
</section>