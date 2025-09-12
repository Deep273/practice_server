<?php if (!empty($message)): ?>
    <p style="color:green;"><?= $message ?></p>
<?php endif; ?>

<form method="POST">
    <h2>Добавить книгу</h2>
    <label>Название:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Автор:</label><br>
    <input type="text" name="author" required><br><br>

    <label>Год издания:</label><br>
    <input type="number" name="published_year" required><br><br>

    <label>Цена:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label><input type="checkbox" name="is_new_edition"> Новое издание</label><br><br>

    <label>Аннотация:</label><br>
    <textarea name="description" rows="4" cols="50"></textarea><br><br>

    <button type="submit">Добавить книгу</button>
</form>
