# Exam Paper Generator

A PHP and MySQL web app for maintaining a question bank and generating printable exam papers. This project is a modernized version of an older exam-paper system, using PDO prepared statements and a cleaner file structure.

## Features

- Admin/teacher login
- Teacher registration
- Subject-wise question bank
- One word, brief, and MCQ question types
- Select questions and generate a printable question paper
- MySQL database schema included
- Local configuration kept out of Git

## Tech Stack

- PHP 8+
- MySQL / MariaDB
- HTML, CSS, JavaScript
- PDO for database access

## Folder Structure

```text
exam-paper-modern/
├── includes/
│   ├── auth.php
│   ├── config.php
│   ├── config.local.example.php
│   ├── db.php
│   └── layout.php
├── public/
│   └── style.css
├── add_question.php
├── create_paper.php
├── index.php
├── login.php
├── logout.php
├── paper.php
├── register.php
├── schema.sql
└── README.md
```

## Installation

1. Clone or download this repository.
2. Put the project folder inside your PHP server folder:
   - XAMPP: `htdocs`
   - WAMP: `www`
   - Laragon: `www`
3. Open phpMyAdmin and import `schema.sql`.
4. Copy the local config template:

```text
includes/config.local.example.php
```

Rename the copied file to:

```text
includes/config.local.php
```

5. Update database credentials in `includes/config.local.php` if needed.
6. Start Apache and MySQL.
7. Open the app:

```text
http://localhost/exam-paper-modern/login.php
```

## Default Login

```text
Email: admin@exam.com
Password: admin123
```

After the first successful login, the app automatically upgrades the default password into a secure hash in the database.

## GitHub Upload Steps

If Git is installed:

```bash
cd exam-paper-modern
git init
git add .
git commit -m "Initial exam paper generator project"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
git push -u origin main
```

If Git is not installed, create a new GitHub repository and upload the files from this folder using GitHub's web upload option.

## Important

- Do not upload `includes/config.local.php`.
- Use `includes/config.local.example.php` as the safe public template.
- Keep `schema.sql` in the repository so the database can be recreated easily.

## License

This project is licensed under the MIT License.
