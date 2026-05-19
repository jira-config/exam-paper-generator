# Security

Do not commit real database passwords or production configuration.

Local secrets should be stored in:

```text
includes/config.local.php
```

That file is ignored by Git. Use `includes/config.local.example.php` as the template.
