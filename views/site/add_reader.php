<div class="reader-form">
    <?php if (!empty($message)): ?>
        <p class="form-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <h2 class="form-title">Добавить читателя</h2>

        <label>Номер читательского билета:</label>
        <input type="text" name="card_number" required>

        <label>ФИО:</label>
        <input type="text" name="full_name" required>

        <label>Адрес:</label>
        <textarea name="address" required></textarea>

        <label>Телефон:</label>
        <input type="text" name="phone_number" required>

        <button type="submit" class="btn-submit">Добавить читателя</button>
    </form>
</div>
