<h2>Возврат книг</h2>

<?php if (!empty($message)): ?>
    <p class="success-message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<div class="return-books">
    <?php foreach ($readers as $reader): ?>
        <?php foreach ($reader->books as $book): ?>
            <form method="POST" action="" class="return-card">
                <p><strong>Читатель:</strong> <?= htmlspecialchars($reader->full_name) ?></p>
                <p><strong>Книга:</strong> <?= htmlspecialchars($book->title) ?></p>
                <p><strong>Выдана:</strong> <?= htmlspecialchars($book->pivot->issued_at) ?></p>
                <input type="hidden" name="book_id" value="<?= $book->id ?>">
                <input type="hidden" name="reader_id" value="<?= $reader->id ?>">
                <button type="submit" class="btn">Принять обратно</button>
            </form>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
