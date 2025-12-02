CREATE DATABASE task_manager;
USE task_manager;

-- جدول المهام
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    points INT NOT NULL,
    duration INT NOT NULL, -- بالدقائق
    difficulty ENUM('سهلة', 'متوسطة', 'صعبة') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- جدول توزيع المهام اليومية
CREATE TABLE daily_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT,
    assigned_date DATE NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id)
);

-- جدول المستخدمين (للتوسع المستقبلي)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);