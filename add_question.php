<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$user = require_login();
$subjects = db()->query('SELECT id, name FROM subjects ORDER BY name')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectId = (int) ($_POST['subject_id'] ?? 0);
    $type = $_POST['type'] ?? '';
    $question = trim($_POST['question'] ?? '');
    $answer = trim($_POST['answer'] ?? '');
    $marks = max(1, (int) ($_POST['marks'] ?? 1));
    $validTypes = ['one_word', 'brief', 'mcq'];

    if (!$subjectId || !in_array($type, $validTypes, true) || $question === '') {
        $_SESSION['flash'] = 'Please select subject, question type, and question text.';
    } else {
        $options = [
            trim($_POST['option_a'] ?? ''),
            trim($_POST['option_b'] ?? ''),
            trim($_POST['option_c'] ?? ''),
            trim($_POST['option_d'] ?? ''),
        ];

        if ($type === 'mcq' && in_array('', $options, true)) {
            $_SESSION['flash'] = 'Please fill all four MCQ options.';
        } else {
            $stmt = db()->prepare(
                'INSERT INTO questions
                (subject_id, user_id, type, question, answer, option_a, option_b, option_c, option_d, marks)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $subjectId,
                $user['id'],
                $type,
                $question,
                $answer,
                $options[0] ?: null,
                $options[1] ?: null,
                $options[2] ?: null,
                $options[3] ?: null,
                $marks,
            ]);
            $_SESSION['flash'] = 'Question added successfully.';
            header('Location: index.php');
            exit;
        }
    }
}

render_header('Add Question');
?>
<section class="panel wide">
    <h1>Add Question</h1>
    <?php if ($message = flash()): ?>
        <p class="alert"><?= e($message) ?></p>
    <?php endif; ?>
    <form method="post" class="form two-column">
        <label>Subject
            <select name="subject_id" required>
                <option value="">Select subject</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= (int) $subject['id'] ?>"><?= e($subject['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Question Type
            <select name="type" id="questionType" required>
                <option value="one_word">One Word</option>
                <option value="brief">Brief</option>
                <option value="mcq">MCQ</option>
            </select>
        </label>
        <label class="full">Question
            <textarea name="question" rows="4" required></textarea>
        </label>
        <label class="full">Answer
            <textarea name="answer" rows="3"></textarea>
        </label>
        <div class="mcq-options full" id="mcqOptions">
            <label>Option A <input type="text" name="option_a"></label>
            <label>Option B <input type="text" name="option_b"></label>
            <label>Option C <input type="text" name="option_c"></label>
            <label>Option D <input type="text" name="option_d"></label>
        </div>
        <label>Marks
            <input type="number" name="marks" min="1" value="1" required>
        </label>
        <div class="actions full">
            <button type="submit">Save Question</button>
        </div>
    </form>
</section>
<script>
const typeInput = document.querySelector('#questionType');
const mcqOptions = document.querySelector('#mcqOptions');
function toggleMcqOptions() {
    mcqOptions.hidden = typeInput.value !== 'mcq';
}
typeInput.addEventListener('change', toggleMcqOptions);
toggleMcqOptions();
</script>
<?php render_footer(); ?>
