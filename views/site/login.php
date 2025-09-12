<main>
    <?php if (!app()->auth::check()): ?>
        <div class="auth-wrapper">
            <div class="auth-box">
                <h2>Авторизация</h2>
                <form method="post">
                    <label for="login">Логин</label>
                    <input type="text" id="login" name="login" required>

                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit">Войти</button>

                    <div class="auth-options">
                        <label>
                            <input type="checkbox" name="remember"> Запомнить меня
                        </label>
                        <a href="#">Забыли пароль?</a>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <h3>Добро пожаловать, <?= app()->auth::user()->name ?>!</h3>
    <?php endif; ?>
</main>
