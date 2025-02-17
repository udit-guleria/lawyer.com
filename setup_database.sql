-- Check if database exists, if not create it
CREATE DATABASE IF NOT EXISTS crm_system;
USE crm_system;

-- Create tables if they don't exist
CREATE TABLE IF NOT EXISTS User (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    type ENUM('SALES REP', 'MANAGER', 'ADMIN') NOT NULL,
    password VARCHAR(255) NULL
);

CREATE TABLE IF NOT EXISTS Leads (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    industry VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    status ENUM('NEW', 'CONTACTED', 'PROPOSAL', 'NEGOTIATION', 'CLOSED-WON', 'CLOSED-LOST') NOT NULL DEFAULT 'NEW'
);

CREATE TABLE IF NOT EXISTS Task (
    id VARCHAR(36) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    lead_id VARCHAR(36) NOT NULL,
    created_by VARCHAR(36),
    due_date DATE,
    priority ENUM('LOW', 'MEDIUM', 'HIGH') NOT NULL DEFAULT 'MEDIUM',
    status ENUM('NEW', 'IN-PROGRESS', 'COMPLETED', 'DROPPED', 'OVERDUE') NOT NULL DEFAULT 'NEW',
    FOREIGN KEY (lead_id) REFERENCES Leads(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES User(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Task_Assignees (
    task_id VARCHAR(36),
    user_id VARCHAR(36),
    PRIMARY KEY (task_id, user_id),
    FOREIGN KEY (task_id) REFERENCES Task(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
);

-- Insert admin user if it doesn't exist
-- Using UUID() for id generation and SHA2 for password hashing
INSERT IGNORE INTO User (id, name, email, type, password)
VALUES (
    UUID(),
    'System Admin',
    'admin@lawyer.com',
    'ADMIN',
    SHA2('admin123', 256)
); 