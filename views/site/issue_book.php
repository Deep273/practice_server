<h2>Выдача книги читателю</h2>

<?php if (!empty($message)): ?>
    <p style="color: green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Книга:
        <select name="book_id">
            <option value="">Выберите книгу</option>
            <?php foreach ($books as $book): ?>
                <option value="<?= $book->id ?>"><?= htmlspecialchars($book->title) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <br><br>
    <label>Читатель:
        <select name="reader_id">
            <option value="">Выберите читателя</option>
            <?php foreach ($readers as $reader): ?>
                <option value="<?= $reader->id ?>"><?= htmlspecialchars($reader->full_name) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <br><br>
    <button type="submit">Выдать книгу</button>
</form>
