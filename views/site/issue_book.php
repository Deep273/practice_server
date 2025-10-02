<div class="form-container">
    <div>
        <h2 class="form-title">Выдача книги читателю</h2>

        <?php if (!empty($message)): ?>
            <p class="form-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" action="" class="styled-form">
            <label>Книга:</label>
            <select name="book_id" required>
                <option value="">Выберите книгу</option>
                <?php foreach ($books as $book): ?>
                    <option value="<?= $book->id ?>"><?= htmlspecialchars($book->title) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Читатель:</label>
            <select name="reader_id" required>
                <option value="">Выберите читателя</option>
                <?php foreach ($readers as $reader): ?>
                    <option value="<?= $reader->id ?>"><?= htmlspecialchars($reader->full_name) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-submit">Выдать книгу</button>
        </form>
    </div>
</div>
