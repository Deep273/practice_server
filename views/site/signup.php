<h3><?= $message ?? ''; ?></h3>
<form method="post">
    <h2>Регистрация</h2><br>
    <label>Имя <input type="text" name="name"></label>
    <label>Логин <input type="text" name="login"></label>
    <label>Пароль <input type="password" name="password"></label>
    <button>Зарегистрироваться</button>
</form>