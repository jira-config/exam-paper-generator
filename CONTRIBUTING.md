# Contributing

Thanks for improving this project.

## Local setup

1. Import `schema.sql` in MySQL or phpMyAdmin.
2. Copy `includes/config.local.example.php` to `includes/config.local.php`.
3. Update database credentials in `includes/config.local.php`.
4. Run the app from a PHP server such as XAMPP, WAMP, or Laragon.

## Code style

- Keep PHP files small and readable.
- Use PDO prepared statements for database queries.
- Escape output with `e()` before printing user-provided text.
- Do not commit local database passwords or generated files.
