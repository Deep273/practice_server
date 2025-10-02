<h2 class="page-title">Выданные книги</h2>

<form method="GET" action="/practic/borrowed-books" class="filter-form">
    <label>Выберите читателя:
        <select name="reader_id">
            <option value="">Все читатели</option>
            <?php foreach ($readers as $reader): ?>
                <option value="<?= $reader->id ?>" <?= isset($selectedReader) && $selectedReader && $selectedReader->id == $reader->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($reader->full_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <div class="form-actions">
        <button type="submit" class="btn">Показать</button>
        <a href="<?= app()->route->getUrl('/issue-book') ?>" class="btn btn-add">Выдать книгу</a>
        <a href="<?= app()->route->getUrl('/borrowers-by-book') ?>" class="btn btn-history">История книг</a>
    </div>
</form>

<?php if (!empty($books) && $books->count() > 0): ?>
    <table class="borrowed-table">
        <thead>
        <tr>
            <th>Название книги</th>
            <th>Автор</th>
            <th>Выдана читателю</th>
            <th>Дата выдачи</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book->title) ?></td>
                <td><?= htmlspecialchars($book->author) ?></td>
                <td>
                    <?= isset($book->reader_name)
                        ? htmlspecialchars($book->reader_name)
                        : (isset($selectedReader) && $selectedReader ? htmlspecialchars($selectedReader->full_name) : 'Неизвестно') ?>
                </td>
                <td>
                    <?= htmlspecialchars($book->pivot->issued_at ?? 'Неизвестна') ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="empty-msg">Нет выданных книг.</p>
<?php endif; ?>
