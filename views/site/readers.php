<h2>Список читателей</h2>

<?php if (empty($readers)): ?>
    <p>Читателей пока нет.</p>
<?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
        <tr>
            <th>Номер читательского билета</th>
            <th>ФИО</th>
            <th>Адрес</th>
            <th>Телефон</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($readers as $reader): ?>
            <tr>
                <td><?= htmlspecialchars($reader->card_number) ?></td>
                <td><?= htmlspecialchars($reader->full_name) ?></td>
                <td><?= htmlspecialchars($reader->address) ?></td>
                <td><?= htmlspecialchars($reader->phone_number) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
