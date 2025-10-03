<main>
    <div class="auth-wrapper">
        <div class="auth-box">
            <h2>Регистрация</h2>
            <form method="post">
                <label for="name">Имя</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                <?php if (!empty($errors['name'])): ?>
                    <p class="error"><?= implode(', ', $errors['name']) ?></p>
                <?php endif; ?>

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

                <button type="submit">Зарегистрироваться</button>
            </form>
        </div>
    </div>
</main>
