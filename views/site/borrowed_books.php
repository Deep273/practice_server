<h2>Выданные книги</h2>

<form method="GET" action="/practic/borrowed-books">
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
    <button type="submit">Показать</button>
</form>

<?php if (!empty($books) && $books->count() > 0): ?>
    <table border="1" cellpadding="5" cellspacing="0">
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
                    <?php
                    $issuedAt = $book->pivot->issued_at ?? 'Неизвестна';
                    echo htmlspecialchars($issuedAt);
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Нет выданных книг.</p>
<?php endif; ?>
