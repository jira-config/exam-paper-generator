CREATE DATABASE IF NOT EXISTS dbexam_modern CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dbexam_modern;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'teacher') NOT NULL DEFAULT 'teacher',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('one_word', 'brief', 'mcq') NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NULL,
    option_a VARCHAR(255) NULL,
    option_b VARCHAR(255) NULL,
    option_c VARCHAR(255) NULL,
    option_d VARCHAR(255) NULL,
    marks INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT IGNORE INTO subjects (id, name) VALUES
(1, 'Maths'),
(2, 'English');

INSERT INTO users (name, email, password_hash, role)
SELECT 'Admin', 'admin@exam.com', 'admin123', 'admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@exam.com');

INSERT INTO questions (subject_id, user_id, type, question, answer, option_a, option_b, option_c, option_d, marks)
SELECT 1, u.id, 'one_word', 'What is 2 + 2?', '4', NULL, NULL, NULL, NULL, 1
FROM users u WHERE u.email = 'admin@exam.com'
AND NOT EXISTS (SELECT 1 FROM questions WHERE question = 'What is 2 + 2?');

INSERT INTO questions (subject_id, user_id, type, question, answer, option_a, option_b, option_c, option_d, marks)
SELECT 2, u.id, 'mcq', 'Have you found your missing pen yet?', 'your', 'your', 'you', 'yours', 'your''s', 1
FROM users u WHERE u.email = 'admin@exam.com'
AND NOT EXISTS (SELECT 1 FROM questions WHERE question = 'Have you found your missing pen yet?');

INSERT INTO questions (subject_id, user_id, type, question, answer, option_a, option_b, option_c, option_d, marks)
SELECT 1, u.id, 'brief', 'Write 254 in words.', 'Two hundred and fifty four.', NULL, NULL, NULL, NULL, 5
FROM users u WHERE u.email = 'admin@exam.com'
AND NOT EXISTS (SELECT 1 FROM questions WHERE question = 'Write 254 in words.');
