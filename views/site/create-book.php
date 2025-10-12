<form method="POST" enctype="multipart/form-data" class="book-form">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <h2>Добавить книгу</h2>

    <?php if (!empty($errors) || !empty($message)): ?>
        <div class="form-message <?= !empty($errors) ? 'error' : 'success' ?>">
            <?php if (!empty($message)): ?>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>


    <label>Название:</label>
    <input type="text" name="title" required value="<?= htmlspecialchars($old['title'] ?? '') ?>">

    <label>Автор:</label>
    <input type="text" name="author" required value="<?= htmlspecialchars($old['author'] ?? '') ?>">

    <label>Год издания:</label>
    <input type="date" name="published_year" required class="date-input" max="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($old['published_year'] ?? '') ?>">

    <label>Цена:</label>
    <input type="number" step="0.01" name="price" required min="0" value="<?= htmlspecialchars($old['price'] ?? '') ?>">

    <label class="checkbox-label">
        <input type="checkbox" name="is_new_edition" <?= !empty($old['is_new_edition']) ? 'checked' : '' ?>> Новое издание
    </label>

    <label>Аннотация:</label>
    <textarea name="description" rows="4"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>

    <label>Обложка книги:</label>
    <div class="file-upload">
        <input type="file" id="cover" name="cover" accept="image/*">
        <label for="cover" class="file-label">Выберите файл</label>
        <span class="file-name">Файл не выбран</span>
    </div>

    <button type="submit" class="btn-submit">Добавить книгу</button>
</form>

<script>
    // Показываем имя выбранного файла
    const fileInput = document.getElementById('cover');
    const fileNameSpan = document.querySelector('.file-name');

    fileInput.addEventListener('change', function() {
        fileNameSpan.textContent = this.files.length > 0 ? this.files[0].name : 'Файл не выбран';
    });
</script>
