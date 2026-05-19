<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$user = require_login();

$counts = db()->query(
    'SELECT s.name, q.type, COUNT(q.id) total
     FROM subjects s
     LEFT JOIN questions q ON q.subject_id = s.id
     GROUP BY s.id, q.type
     ORDER BY s.name, q.type'
)->fetchAll();

render_header('Dashboard');
?>
<section class="hero">
    <div>
        <p class="eyebrow">Welcome, <?= e($user['name']) ?></p>
        <h1>Question Bank Dashboard</h1>
    </div>
    <a class="button" href="create_paper.php">Create Paper</a>
</section>

<?php if ($message = flash()): ?>
    <p class="success"><?= e($message) ?></p>
<?php endif; ?>

<section class="grid">
    <a class="tile" href="add_question.php">
        <span>Add Question</span>
        <strong>One word, brief, and MCQ</strong>
    </a>
    <a class="tile" href="create_paper.php">
        <span>Generate Paper</span>
        <strong>Select questions and print</strong>
    </a>
</section>

<section class="panel">
    <h2>Question Summary</h2>
    <table>
        <thead>
        <tr>
            <th>Subject</th>
            <th>Type</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($counts as $row): ?>
            <tr>
                <td><?= e($row['name']) ?></td>
                <td><?= e($row['type'] ?: 'No questions') ?></td>
                <td><?= (int) $row['total'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php render_footer(); ?>
