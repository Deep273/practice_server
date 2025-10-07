<form method="POST" enctype="multipart/form-data" class="book-form">
    <h2>Добавить книгу</h2>

    <label>Название:</label>
    <input type="text" name="title" required>

    <label>Автор:</label>
    <input type="text" name="author" required>

    <label>Год издания:</label>
    <input type="date" name="published_year" required class="date-input">

    <label>Цена:</label>
    <input type="number" step="0.01" name="price" required>

    <label class="checkbox-label">
        <input type="checkbox" name="is_new_edition"> Новое издание
    </label>

    <label>Аннотация:</label>
    <textarea name="description" rows="4"></textarea>

    <label>Обложка книги:</label>
    <div class="file-upload">
        <input type="file" id="cover" name="cover" accept="image/*">
        <label for="cover" class="file-label">Выберите файл</label>
        <span class="file-name">Файл не выбран</span>
    </div>

    <button type="submit" class="btn-submit">Добавить книгу</button>
</form>

<script>
    // Установка только года (если нужно сохранить именно год)
    const dateInput = document.querySelector('.date-input');
    dateInput.addEventListener('change', function() {
        const date = new Date(this.value);
        this.dataset.year = date.getFullYear(); // сохраняем год отдельно
    });
</script>
