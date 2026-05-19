# Exam Paper Generator

A PHP and MySQL web app for maintaining a question bank and generating printable exam papers. This project is a modernized version of an older exam-paper system, using PDO prepared statements and a cleaner file structure.

## Features

- Admin/teacher login
- Teacher registration
- Subject-wise question bank
- One word, brief, and MCQ question types
- Select questions and generate a printable question paper
- MySQL database schema included
- Browser-based setup page
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
├── setup.php
├── install.sql
├── schema.sql
└── README.md
```

## Local Installation

1. Clone or download this repository.
2. Put the project folder inside your PHP server folder:
   - XAMPP: `htdocs`
   - WAMP: `www`
   - Laragon: `www`
3. Create a MySQL database in phpMyAdmin, for example `dbexam_modern`.
4. Open the setup page:

```text
http://localhost/exam-paper-generator/setup.php
```

5. Enter your database details and click **Install Web App**.
6. Open the login page:

```text
http://localhost/exam-paper-generator/login.php
```

## Live Hosting

Use any hosting that supports PHP and MySQL.

1. Upload all project files to `public_html` or your hosting web root.
2. Create a MySQL database from your hosting panel.
3. Open `https://your-domain.com/setup.php`.
4. Enter the database host, database name, database user, and password.
5. Click **Install Web App**.
6. Login from `https://your-domain.com/login.php`.

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
- After setup works online, you can delete `setup.php` from the live server for extra safety.

## License

This project is licensed under the MIT License.
