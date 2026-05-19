<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || strlen($password) < 6) {
        $_SESSION['flash'] = 'Please fill all fields. Password must be at least 6 characters.';
    } else {
        try {
            $stmt = db()->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), 'teacher']);
            $_SESSION['flash'] = 'Account created. You can login now.';
            header('Location: login.php');
            exit;
        } catch (PDOException $exception) {
            $_SESSION['flash'] = 'This email is already registered.';
        }
    }
}

render_header('Register');
?>
<section class="auth-panel">
    <h1>Create Teacher Account</h1>
    <?php if ($message = flash()): ?>
        <p class="alert"><?= e($message) ?></p>
    <?php endif; ?>
    <form method="post" class="form">
        <label>Name
            <input type="text" name="name" required>
        </label>
        <label>Email
            <input type="email" name="email" required>
        </label>
        <label>Password
            <input type="password" name="password" minlength="6" required>
        </label>
        <button type="submit">Register</button>
    </form>
</section>
<?php render_footer(); ?>
