<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function render_header(string $title): void
{
    $user = current_user();
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?> | <?= APP_NAME ?></title>
        <link rel="stylesheet" href="public/style.css">
    </head>
    <body>
    <header class="topbar">
        <a class="brand" href="index.php"><?= APP_NAME ?></a>
        <nav>
            <?php if ($user): ?>
                <a href="index.php">Dashboard</a>
                <a href="add_question.php">Add Question</a>
                <a href="create_paper.php">Create Paper</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="page">
    <?php
}

function render_footer(): void
{
    ?>
    </main>
    </body>
    </html>
    <?php
}

function flash(): ?string
{
    if (empty($_SESSION['flash'])) {
        return null;
    }

    $message = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $message;
}
