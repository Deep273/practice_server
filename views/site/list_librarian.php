<div class="librarians-page">
    <h2 class="page-title">Список библиотекарей</h2>

    <div class="actions">

        <button class="btn">Добавить библиотекаря</button>
        <button class="btn">Удалить выбранных</button>
    </div>

    <?php if ($librarians->isEmpty()): ?>
        <p class="empty-msg">Библиотекарей пока нет.</p>
    <?php else: ?>
        <table class="librarians-table">
            <thead>
            <tr>
                <th>№</th>
                <th>ФИО</th>
                <th>Логин</th>
                <th>Роль</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($librarians as $index => $librarian): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($librarian->name) ?></td>
                    <td><?= htmlspecialchars($librarian->login) ?></td>
                    <td><?= htmlspecialchars($librarian->role) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
