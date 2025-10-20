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
<?php

$current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<aside class="sidebar">
    <div>
        <div class="logo">Literatur</div>
        <nav>
            <a href="<?= app()->route->getUrl('/hello') ?>"
               class="<?= $current === '/practic/hello' ? 'active' : '' ?>">
                <img src="public/img/main_icon.svg" alt="Главная"> Главная
            </a>

            <?php if (app()->auth::check()): ?>
                <a href="<?= app()->route->getUrl('/readers') ?>"
                   class="<?= $current === '/practic/readers' ? 'active' : '' ?>">
                    <img src="public/img/readers_icon.png" alt="Читатели"> Читатели
                </a>

                <a href="<?= app()->route->getUrl('/books') ?>"
                   class="<?= $current === '/practic/books' ? 'active' : '' ?>">
                    <img src="public/img/books_icon.png" alt="Книги"> Книги
                </a>

                <?php if (app()->auth::user()->isLibrarian() || app()->auth::user()->isAdmin()): ?>
                    <a href="<?= app()->route->getUrl('/borrowed-books') ?>"
                       class="<?= $current === '/practic/borrowed-books' ? 'active' : '' ?>">
                        <img src="public/img/give_out_book.svg" alt="Выданные книги"> Выданные книги
                    </a>
                <?php endif; ?>

                <?php if (app()->auth::user()->isAdmin()): ?>
                    <a href="<?= app()->route->getUrl('/librarians') ?>"
                       class="<?= $current === '/practic/create-librarian' ? 'active' : '' ?>">
                        <img src="public/img/library.svg" alt="Добавить библиотекаря"> Добавить библиотекаря
                    </a>
                <?php endif; ?>

            <?php else: ?>
                <a href="<?= app()->route->getUrl('/login') ?>"
                   class="<?= $current === '/practic/login' ? 'active' : '' ?>">
                    <img src="public/img/login.svg" alt="Вход"> Вход
                </a>
                <a href="<?= app()->route->getUrl('/signup') ?>"
                   class="<?= $current === '/practic/signup' ? 'active' : '' ?>">
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
