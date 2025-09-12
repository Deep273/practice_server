<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/practic/public/css/style.css"

</head>
<body>
<!-- Боковая панель -->
<aside class="sidebar">
    <div>
        <div class="logo">Literatur</div>
        <nav>
            <a href="<?= app()->route->getUrl('/hello') ?>" class="active">
                <img src="public/img/main_icon.svg" alt="Главная"> Главная
            </a>

            <?php if (app()->auth::check()): ?>
                <a href="<?= app()->route->getUrl('/readers') ?>">
                    <img src="public/img/readers_icon.png" alt="Читатели"> Читатели
                </a>
                <a href="<?= app()->route->getUrl('/books') ?>">
                    <img src="public/img/books_icon.png" alt="Книги"> Книги
                </a>
            <?php else: ?>
                <a href="<?= app()->route->getUrl('/login') ?>">
                    <img src="public/img/login.svg" alt="Вход"> Вход
                </a>
                <a href="<?= app()->route->getUrl('/signup') ?>">
                    <img src="public/img/registration.svg" alt="Регистрация"> Регистрация
                </a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="bottom">
        <a href="<?= app()->route->getUrl('/logout') ?>">
            <img src="public/img/exit_icon.svg" alt="Выход"> Выход
        </a>
    </div>
</aside>

<!-- Основной контент -->
<main>
    <?= $content ?? '' ?>
</main>
</body>
</html>
