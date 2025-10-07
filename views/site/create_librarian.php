<div class="librarian-page">
    <h2 class="page-title">Добавить библиотекаря</h2>

    <?php if (!empty($message)): ?>
        <p class="error-msg"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" action="" class="librarian-form">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <label>Имя:</label>
        <input type="text" name="name" required>

        <label>Логин:</label>
        <input type="text" name="login" required>

        <label>Пароль:</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn submit-btn">Создать библиотекаря</button>
    </form>
</div>
