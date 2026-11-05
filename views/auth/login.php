<?php $flashAuth = flash('auth'); ?>
<section class="auth-wrap">
    <h1>Вход</h1>
    <?php if ($flashAuth): ?><div class="flash flash-<?= e($flashAuth['type']) ?>"><?= e($flashAuth['message']) ?></div><?php endif; ?>

    <form class="form" action="/login" method="post" data-validate>
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

        <label>Email
            <input type="email" name="email" required>
        </label>

        <label>Пароль
            <input type="password" name="password" minlength="8" required>
        </label>

        <button type="submit" class="btn">Войти</button>
    </form>

    <p class="auth-switch">Нет аккаунта? <a href="/register">Зарегистрироваться</a></p>
</section>