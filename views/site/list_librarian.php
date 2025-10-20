<div class="librarians-page">
    <h2 class="page-title">Список библиотекарей</h2>

    <!-- Сообщение об успехе или ошибке -->
    <?php if (!empty($message)): ?>
        <p class="form-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($librarians->isEmpty()): ?>
        <p class="empty-msg">Библиотекарей пока нет.</p>
    <?php else: ?>

        <form method="POST" action="/practic/delete-librarians">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>

            <div class="actions">
                <a href="<?= app()->route->getUrl('/create-librarian') ?>" class="btn">
                    Добавить библиотекаря
                </a>
                <button type="submit" class="btn delete-btn">Удалить выбранных</button>
            </div>

            <table class="librarians-table">
                <thead>
                <tr>
                    <th>№</th>
                    <th>ФИО</th>
                    <th>Логин</th>
                    <th>Роль</th>
                    <th>Выбрать</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($librarians as $i => $librarian): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($librarian->name) ?></td>
                        <td><?= htmlspecialchars($librarian->login) ?></td>
                        <td><?= htmlspecialchars($librarian->role) ?></td>
                        <td class="checkbox-cell">
                            <input type="checkbox" name="librarian_ids[]" value="<?= $librarian->id ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>

    <?php endif; ?>
</div>

<script>
    // Подтверждение удаления
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm('Вы уверены, что хотите удалить выбранных библиотекарей?')) {
                e.preventDefault();
            }
        });
    });
</script>
