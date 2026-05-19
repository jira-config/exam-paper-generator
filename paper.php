<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

require_login();

$questionIds = array_map('intval', $_POST['question_ids'] ?? []);
$subjectId = (int) ($_POST['subject_id'] ?? 0);
$type = $_POST['type'] ?? '';
$examDate = $_POST['exam_date'] ?? '';
$examTime = trim($_POST['exam_time'] ?? '');
$totalMarks = (int) ($_POST['total_marks'] ?? 0);

if (!$questionIds || !$subjectId || $totalMarks < 1) {
    $_SESSION['flash'] = 'Please select at least one question.';
    header('Location: create_paper.php');
    exit;
}

$placeholders = implode(',', array_fill(0, count($questionIds), '?'));
$stmt = db()->prepare(
    "SELECT q.*, s.name subject_name
     FROM questions q
     JOIN subjects s ON s.id = q.subject_id
     WHERE q.subject_id = ? AND q.type = ? AND q.id IN ($placeholders)
     ORDER BY FIELD(q.id, $placeholders)"
);
$stmt->execute(array_merge([$subjectId, $type], $questionIds, $questionIds));
$questions = $stmt->fetchAll();
$subjectName = $questions[0]['subject_name'] ?? 'Exam';

render_header('Question Paper');
?>
<section class="paper">
    <div class="paper-head">
        <h1><?= e($subjectName) ?> Question Paper</h1>
        <dl>
            <div><dt>Date</dt><dd><?= e($examDate) ?></dd></div>
            <div><dt>Time</dt><dd><?= e($examTime) ?></dd></div>
            <div><dt>Marks</dt><dd><?= $totalMarks ?></dd></div>
        </dl>
    </div>

    <ol class="print-questions">
        <?php foreach ($questions as $question): ?>
            <li>
                <div class="question-title">
                    <span><?= e($question['question']) ?></span>
                    <em><?= (int) $question['marks'] ?> marks</em>
                </div>
                <?php if ($question['type'] === 'mcq'): ?>
                    <ul class="options">
                        <li>A. <?= e($question['option_a']) ?></li>
                        <li>B. <?= e($question['option_b']) ?></li>
                        <li>C. <?= e($question['option_c']) ?></li>
                        <li>D. <?= e($question['option_d']) ?></li>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>

    <button class="print-button" onclick="window.print()">Print Paper</button>
</section>
<?php render_footer(); ?>
