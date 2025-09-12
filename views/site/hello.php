<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная - Literatur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Хедер -->
<header class="main-header">
    <form class="search-form">
        <div class="search-wrap">
            <input type="text" class="search-input" placeholder="Поиск">
            <button class="search-btn" type="submit">
                <img src="public/img/search_icon.svg" alt="Профиль" class="avatar">
            </button>
        </div>
    </form>
    <div class="header-avatar">
        <img src="public/img/user.png" alt="Профиль" class="avatar">
    </div>
</header>

<!-- Контент -->
<main class="main-content">
    <section class="content">
        <h2>Добро пожаловать на главную страницу!</h2>
        <h3>Книги, пользующиеся наибольшим спросом</h3>

        <div class="books-grid">
            <div class="book-card">
                <img src="public/img/witcher.png" alt="Ведьмак">
                <h4>“Ведьмак”<br>Анджей Сапковский</h4>
                <p>Цена: 1 080р.</p>
            </div>

            <div class="book-card">
                <img src="public/img/star.png" alt="Виноваты звезды">
                <h4>“Виноваты звезды”<br>Джон Грин</h4>
                <p>Цена: 1 080р.</p>
            </div>
        </div>
    </section>
</main>
</body>
</html>
