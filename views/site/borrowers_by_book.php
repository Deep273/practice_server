<div class="history-page">
    <div class="history-header">
        <h2 class="page-title">Читатели, брали книгу</h2>

        <form method="GET" action="/practic/borrowers-by-book" class="filter-form">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <label>Выберите книгу:
                <select name="book_id">
                    <option value="">Выберите книгу</option>
                    <?php foreach ($books as $book): ?>
                        <option value="<?= $book->id ?>" <?= isset($selectedBook) && $selectedBook && $selectedBook->id == $book->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($book->title) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit" class="btn">Показать</button>
        </form>
    </div>

    <?php if ($borrowers && $borrowers->count() > 0): ?>
        <table class="history-table">
            <thead>
            <tr>
                <th>ФИО читателя</th>
                <th>Дата выдачи</th>
                <th>Дата возврата</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($borrowers as $reader): ?>
                <tr>
                    <td><?= htmlspecialchars($reader->full_name) ?></td>
                    <td><?= htmlspecialchars($reader->pivot->issued_at ?? 'Неизвестна') ?></td>
                    <td>
                        <?= $reader->pivot->returned_at
                            ? htmlspecialchars($reader->pivot->returned_at)
                            : '<em class="not-returned">Не возвращена</em>' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($selectedBook)): ?>
        <p class="empty-msg">Нет информации о выдачах этой книги.</p>
    <?php endif; ?>
</div>
