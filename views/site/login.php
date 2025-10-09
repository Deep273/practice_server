<div>
    <div class="auth-wrapper">
        <div class="auth-box">
            <h2>Авторизация</h2>
            <form method="post">
                <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
                <?php if (!empty($errors['login'])): ?>
                    <p class="error"><?= implode(', ', $errors['login']) ?></p>
                <?php endif; ?>

                <label for="password">Пароль</label>
                <input type="password" id="password" name="password">
                <?php if (!empty($errors['password'])): ?>
                    <p class="error"><?= implode(', ', $errors['password']) ?></p>
                <?php endif; ?>

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
</div>
