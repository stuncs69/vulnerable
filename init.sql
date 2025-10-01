-- Database initialization script for messageboard
-- This script will be automatically executed when the MySQL container starts

USE messageboard;

-- Create messages table
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `posted` timestamp NOT NULL DEFAULT current_timestamp(),
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert a default admin user (password: admin123)
-- Note: This is for educational purposes only - in production, use proper password hashing
INSERT IGNORE INTO `users` (`username`, `password`) VALUES ('admin', 'admin123');

-- Insert some sample messages for testing
INSERT IGNORE INTO `messages` (`message`, `hidden`) VALUES 
('Welcome to the message board!', 0),
('This is a sample message.', 0),
('This message is hidden by default.', 1);