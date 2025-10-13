<div class="reader-form">
    <?php if (!empty($message)): ?>
        <p class="form-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <h2 class="form-title">Добавить читателя</h2>

        <label>Номер читательского билета:</label>
        <input type="number" name="card_number" required
               min="10000000000000" max="99999999999999"
               oninput="if(this.value.length > 14) this.value = this.value.slice(0,14)">

        <label>ФИО:</label>
        <input type="text" name="full_name" required>

        <label>Адрес:</label>
        <textarea name="address" required></textarea>

        <label>Телефон:</label>
        <input type="text" name="phone_number" required>

        <button type="submit" class="btn-submit">Добавить читателя</button>
    </form>
</div>
