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
<div class="books-page">
    <h2 class="page-title">Список книг</h2>

    <div class="books-actions">
        <button class="btn add-book">Добавить книгу</button>
        <button class="btn delete-book">Удалить книгу</button>
    </div>

    <?php if (empty($books)): ?>
        <p class="empty-msg">Книг пока нет.</p>
    <?php else: ?>
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <img src="<?= htmlspecialchars($book->cover_url ?? 'placeholder.jpg') ?>" alt="Обложка">
                    <div class="book-info">
                        <h3 class="book-title">“<?= htmlspecialchars($book->title) ?>”</h3>
                        <p class="book-author">Автор: <?= htmlspecialchars($book->author) ?></p>
                        <p class="book-desc">Описание: <?= htmlspecialchars($book->description) ?></p>
                        <p class="book-meta">
                            Год публикации: <?= htmlspecialchars($book->published_year) ?>.<br>
                            Издание: <?= $book->is_new_edition ? 'Новое издание' : 'Обычное издание' ?>.
                        </p>
                        <p class="book-price">Цена: <?= htmlspecialchars($book->price) ?>₽</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
