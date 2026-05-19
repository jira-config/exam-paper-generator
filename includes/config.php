<?php
declare(strict_types=1);

$localConfig = __DIR__ . '/config.local.php';
$config = is_file($localConfig) ? require $localConfig : [];

define('DB_HOST', $config['db_host'] ?? getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', $config['db_name'] ?? getenv('DB_NAME') ?: 'dbexam_modern');
define('DB_USER', $config['db_user'] ?? getenv('DB_USER') ?: 'root');
define('DB_PASS', $config['db_pass'] ?? getenv('DB_PASS') ?: '');

define('APP_NAME', $config['app_name'] ?? getenv('APP_NAME') ?: 'Exam Paper Generator');
