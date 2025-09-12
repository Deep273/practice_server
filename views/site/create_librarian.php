<h1>Добавить нового библиотекаря</h1>

<?php if (!empty($message)): ?>
    <p style="color: red"><?= $message ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Имя:</label><br>
    <input type="text" name="name"><br><br>

    <label>Логин:</label><br>
    <input type="text" name="login"><br><br>

    <label>Пароль:</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">Создать библиотекаря</button>
</form>
