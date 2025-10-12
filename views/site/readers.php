<header class="main-header">
    <form class="search-form">
        <div class="search-wrap">
            <input type="text" class="search-input" placeholder="Поиск">
            <button class="search-btn" type="submit">
                <img src="public/img/search_icon.svg" alt="Поиск" class="avatar">
            </button>
        </div>
    </form>
    <div class="header-avatar">
        <img src="public/img/user.png" alt="Профиль" class="avatar">
    </div>
</header>

<div class="readers-page">
    <h2 class="page-title">Список читателей</h2>

    <!--Сообщение об успехе или ошибке-->
    <?php if (!empty($message)): ?>
        <p class="form-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($readers)): ?>
        <p class="empty-msg">Читателей пока нет.</p>
    <?php else: ?>

        <!--Оборачиваем таблицу и кнопку в форму-->
        <form method="POST" action="/practic/delete-readers">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <div class="actions">
                <a href="<?= app()->route->getUrl('/create-reader') ?>" class="btn">
                    Добавить читателя
                </a>
                <button type="submit" class="btn delete-btn">Удалить выбранных</button>
            </div>

            <table class="readers-table">
                <thead>
                <tr>
                    <th>№</th>
                    <th>ФИО читателя</th>
                    <th>Номер читательского билета</th>
                    <th>Номер телефона</th>
                    <th>Адрес</th>
                    <th>Выбрать</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($readers as $i => $reader): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($reader->full_name) ?></td>
                        <td><?= htmlspecialchars($reader->card_number) ?></td>
                        <td><?= htmlspecialchars($reader->phone_number) ?></td>
                        <td><?= htmlspecialchars($reader->address) ?></td>
                        <td class="checkbox-cell">
                            <input type="checkbox" name="reader_ids[]" value="<?= $reader->id ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>

    <?php endif; ?>
</div>

<script>
    document.querySelector('.delete-btn')?.addEventListener('click', function (e) {
        if (!confirm('Вы уверены, что хотите удалить выбранных читателей?')) {
            e.preventDefault();
        }
    });
</script>
