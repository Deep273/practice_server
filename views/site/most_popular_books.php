<h2>Самые популярные книги</h2>

<?php if ($books->count()): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
        <tr>
            <th>Название книги</th>
            <th>Автор</th>
            <th>Количество выдач</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book->title) ?></td>
                <td><?= htmlspecialchars($book->author) ?></td>
                <td><?= $book->borrowings_count ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Нет данных по выдачам книг.</p>
<?php endif; ?>
