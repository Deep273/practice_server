<?php if (!empty($message)): ?>
    <p class="form-message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" class="book-form">
    <h2>Добавить книгу</h2>

    <label>Название:</label>
    <input type="text" name="title" required>

    <label>Автор:</label>
    <input type="text" name="author" required>

    <label>Год издания:</label>
    <input type="number" name="published_year" required>

    <label>Цена:</label>
    <input type="number" step="0.01" name="price" required>

    <label class="checkbox-label">
        <input type="checkbox" name="is_new_edition"> Новое издание
    </label>

    <label>Аннотация:</label>
    <textarea name="description" rows="4"></textarea>

    <button type="submit" class="btn-submit">Добавить книгу</button>
</form>
