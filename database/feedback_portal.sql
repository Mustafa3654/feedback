-- Feedback Portal Database Setup
-- Run this SQL in phpMyAdmin or MySQL command line

-- Create database
CREATE DATABASE IF NOT EXISTS feedback_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE feedback_portal;

-- Create feedbacks table
CREATE TABLE IF NOT EXISTS `feedbacks` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(320) DEFAULT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `rating` INT NOT NULL DEFAULT 0,
    `message` TEXT DEFAULT NULL,
    `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create telegram_settings table
CREATE TABLE IF NOT EXISTS `telegram_settings` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `botToken` VARCHAR(255) DEFAULT NULL,
    `chatId` VARCHAR(100) DEFAULT NULL,
    `isEnabled` INT NOT NULL DEFAULT 0,
    `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default telegram settings (disabled by default)
INSERT INTO `telegram_settings` (`botToken`, `chatId`, `isEnabled`) 
VALUES ('', '', 0)
ON DUPLICATE KEY UPDATE `id` = `id`;

-- Optional: Create users table for future admin management
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(64) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Success message
SELECT 'Database setup completed successfully!' AS Status;
