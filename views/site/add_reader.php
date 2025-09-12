<?php if (!empty($message)): ?>
    <p style="color:green"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <h2>Добавить читателя</h2>
    <label>Номер читательского билета:</label><br>
    <input type="text" name="card_number" required><br><br>

    <label>ФИО:</label><br>
    <input type="text" name="full_name" required><br><br>

    <label>Адрес:</label><br>
    <textarea name="address" required></textarea><br><br>

    <label>Телефон:</label><br>
    <input type="text" name="phone_number" required><br><br>

    <button type="submit">Добавить читателя</button>
</form>
