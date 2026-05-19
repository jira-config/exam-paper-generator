<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

require_login();
$subjects = db()->query('SELECT id, name FROM subjects ORDER BY name')->fetchAll();
$selectedSubject = (int) ($_GET['subject_id'] ?? 0);
$selectedType = $_GET['type'] ?? 'one_word';
$validTypes = ['one_word', 'brief', 'mcq'];

if (!in_array($selectedType, $validTypes, true)) {
    $selectedType = 'one_word';
}

$questions = [];
if ($selectedSubject > 0) {
    $stmt = db()->prepare(
        'SELECT q.*, s.name subject_name
         FROM questions q
         JOIN subjects s ON s.id = q.subject_id
         WHERE q.subject_id = ? AND q.type = ?
         ORDER BY q.created_at DESC'
    );
    $stmt->execute([$selectedSubject, $selectedType]);
    $questions = $stmt->fetchAll();
}

render_header('Create Paper');
?>
<section class="panel wide">
    <h1>Create Question Paper</h1>
    <form method="get" class="form toolbar">
        <label>Subject
            <select name="subject_id" required>
                <option value="">Select subject</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= (int) $subject['id'] ?>" <?= $selectedSubject === (int) $subject['id'] ? 'selected' : '' ?>>
                        <?= e($subject['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Question Type
            <select name="type">
                <option value="one_word" <?= $selectedType === 'one_word' ? 'selected' : '' ?>>One Word</option>
                <option value="brief" <?= $selectedType === 'brief' ? 'selected' : '' ?>>Brief</option>
                <option value="mcq" <?= $selectedType === 'mcq' ? 'selected' : '' ?>>MCQ</option>
            </select>
        </label>
        <button type="submit">Load Questions</button>
    </form>
</section>

<?php if ($selectedSubject > 0): ?>
    <form method="post" action="paper.php" class="panel wide">
        <input type="hidden" name="subject_id" value="<?= $selectedSubject ?>">
        <input type="hidden" name="type" value="<?= e($selectedType) ?>">
        <div class="paper-settings">
            <label>Exam Date <input type="date" name="exam_date" required></label>
            <label>Time <input type="text" name="exam_time" placeholder="2 Hours" required></label>
            <label>Total Marks <input type="number" name="total_marks" min="1" value="50" required></label>
        </div>

        <h2>Select Questions</h2>
        <?php if (!$questions): ?>
            <p class="empty">No questions found for this filter.</p>
        <?php else: ?>
            <div class="question-list">
                <?php foreach ($questions as $question): ?>
                    <label class="question-row">
                        <input type="checkbox" name="question_ids[]" value="<?= (int) $question['id'] ?>">
                        <span>
                            <strong><?= e($question['question']) ?></strong>
                            <?php if ($question['type'] === 'mcq'): ?>
                                <small>
                                    A. <?= e($question['option_a']) ?> |
                                    B. <?= e($question['option_b']) ?> |
                                    C. <?= e($question['option_c']) ?> |
                                    D. <?= e($question['option_d']) ?>
                                </small>
                            <?php endif; ?>
                        </span>
                        <em><?= (int) $question['marks'] ?> marks</em>
                    </label>
                <?php endforeach; ?>
            </div>
            <button type="submit">Generate Paper</button>
        <?php endif; ?>
    </form>
<?php endif; ?>
<?php render_footer(); ?>
