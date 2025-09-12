<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/practic/public/css/style.css">
    <title>Pop it MVC</title>
</head>
<body>
<header>
    <nav class="navigation">
            <a href="<?= app()->route->getUrl('/hello') ?>" class="nav-link">Главная</a>
            <?php if (!app()->auth::check()): ?>
                <a href="<?= app()->route->getUrl('/login') ?>" class="nav-link">Вход</a>
                <a href="<?= app()->route->getUrl('/signup') ?>" class="nav-link">Регистрация</a>
            <?php else: ?>
                <a href="<?= app()->route->getUrl('/logout') ?>" class="nav-link">Выход (<?= app()->auth::user()->name ?>)</a>
            <?php endif; ?>
    </nav>
</header>
<main>
    <?= $content ?? '' ?>
</main>

</body>
</html>