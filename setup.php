<?php
declare(strict_types=1);

$message = '';
$success = false;

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function split_sql(string $sql): array
{
    $statements = [];
    $buffer = '';
    $inString = false;
    $length = strlen($sql);

    for ($i = 0; $i < $length; $i++) {
        $char = $sql[$i];
        $next = $sql[$i + 1] ?? '';

        if ($char === "'" && $next === "'") {
            $buffer .= "''";
            $i++;
            continue;
        }

        if ($char === "'") {
            $inString = !$inString;
        }

        if ($char === ';' && !$inString) {
            $statement = trim($buffer);
            if ($statement !== '') {
                $statements[] = $statement;
            }
            $buffer = '';
            continue;
        }

        $buffer .= $char;
    }

    $statement = trim($buffer);
    if ($statement !== '') {
        $statements[] = $statement;
    }

    return $statements;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim($_POST['db_host'] ?? 'localhost');
    $dbName = trim($_POST['db_name'] ?? '');
    $dbUser = trim($_POST['db_user'] ?? '');
    $dbPass = $_POST['db_pass'] ?? '';
    $appName = trim($_POST['app_name'] ?? 'Exam Paper Generator');

    if ($dbHost === '' || $dbName === '' || $dbUser === '') {
        $message = 'Please fill database host, name, and user.';
    } else {
        try {
            $dsn = 'mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8mb4';
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            $sql = file_get_contents(__DIR__ . '/install.sql');
            if ($sql === false) {
                throw new RuntimeException('install.sql file not found.');
            }

            foreach (split_sql($sql) as $statement) {
                $pdo->exec($statement);
            }

            $config = "<?php\n"
                . "declare(strict_types=1);\n\n"
                . "return [\n"
                . "    'db_host' => " . var_export($dbHost, true) . ",\n"
                . "    'db_name' => " . var_export($dbName, true) . ",\n"
                . "    'db_user' => " . var_export($dbUser, true) . ",\n"
                . "    'db_pass' => " . var_export($dbPass, true) . ",\n"
                . "    'app_name' => " . var_export($appName, true) . ",\n"
                . "];\n";

            $configPath = __DIR__ . '/includes/config.local.php';
            if (file_put_contents($configPath, $config) === false) {
                throw new RuntimeException('Could not write includes/config.local.php. Check folder permissions.');
            }

            $success = true;
            $message = 'Setup completed. You can login now.';
        } catch (Throwable $exception) {
            $message = 'Setup failed: ' . $exception->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup | Exam Paper Generator</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
<main class="page">
    <section class="auth-panel">
        <h1>Web App Setup</h1>
        <?php if ($message !== ''): ?>
            <p class="<?= $success ? 'success' : 'alert' ?>"><?= h($message) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <a class="button" href="login.php">Open Login</a>
        <?php else: ?>
            <form method="post" class="form">
                <label>App Name
                    <input type="text" name="app_name" value="Exam Paper Generator" required>
                </label>
                <label>Database Host
                    <input type="text" name="db_host" value="localhost" required>
                </label>
                <label>Database Name
                    <input type="text" name="db_name" placeholder="your_database_name" required>
                </label>
                <label>Database User
                    <input type="text" name="db_user" placeholder="your_database_user" required>
                </label>
                <label>Database Password
                    <input type="password" name="db_pass">
                </label>
                <button type="submit">Install Web App</button>
            </form>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
