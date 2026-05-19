<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    $passwordMatches = $user && (
        password_verify($password, $user['password_hash'])
        || hash_equals($user['password_hash'], $password)
    );

    if ($passwordMatches) {
        $_SESSION['user_id'] = (int) $user['id'];

        if (hash_equals($user['password_hash'], $password)) {
            $update = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $update->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
        }

        header('Location: index.php');
        exit;
    }

    $_SESSION['flash'] = 'Invalid email or password.';
}

render_header('Login');
?>
<section class="auth-panel">
    <h1>Login</h1>
    <?php if ($message = flash()): ?>
        <p class="alert"><?= e($message) ?></p>
    <?php endif; ?>
    <form method="post" class="form">
        <label>Email
            <input type="email" name="email" value="admin@exam.com" required>
        </label>
        <label>Password
            <input type="password" name="password" placeholder="admin123" required>
        </label>
        <button type="submit">Login</button>
    </form>
</section>
<?php render_footer(); ?>
