<h2>Список книг</h2>

<?php if (empty($books)): ?>
    <p>Книг пока нет.</p>
<?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
        <tr>
            <th>Название</th>
            <th>Автор</th>
            <th>Год издания</th>
            <th>Цена</th>
            <th>Новое издание</th>
            <th>Описание</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book->title) ?></td>
                <td><?= htmlspecialchars($book->author) ?></td>
                <td><?= htmlspecialchars($book->published_year) ?></td>
                <td><?= htmlspecialchars(number_format($book->price, 2)) ?> руб.</td>
                <td><?= $book->is_new_edition ? 'Да' : 'Нет' ?></td>
                <td><?= nl2br(htmlspecialchars($book->description)) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
