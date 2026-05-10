-- ============================================================
--  Run this in phpMyAdmin or MySQL CLI to set up the database
-- ============================================================

-- 1. Create the database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS auspicious_consultants
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE auspicious_consultants;

-- 2. Create the contact_messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    name         VARCHAR(150)    NOT NULL,
    email        VARCHAR(255)    NOT NULL,
    subject      VARCHAR(300)    NOT NULL,
    message      TEXT            NOT NULL,
    submitted_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_read      TINYINT(1)      NOT NULL DEFAULT 0,   -- 0 = unread, 1 = read
    PRIMARY KEY (id),
    INDEX idx_email        (email),
    INDEX idx_submitted_at (submitted_at),
    INDEX idx_is_read      (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
