# Project Setup and Development Guide

## Table of Contents
- [Overview](#overview)
- [1. Database Setup](#1-database-setup)
- [2. API Development](#2-api-development)
- [3. Frontend Development](#3-frontend-development)
- [4. Deployment](#4-deployment)

## Overview
This guide outlines the complete development process from setting up a local MySQL database to deploying the final application on Heroku. The project consists of a MySQL database, PHP API endpoints, and a web frontend.

## 1. Database Setup

### Summary
The database layer consists of a MySQL database with tables for users, items, orders, and order tracking. The setup process involves installing MySQL, creating the database structure, and populating it with initial data.

### Database Structure
The database consists of the following tables:

#### User Table
Stores information about system users including sales representatives, managers, and administrators.
```sql
CREATE TABLE User (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    type ENUM('SALES REP', 'MANAGER', 'ADMIN') NOT NULL,
    password VARCHAR(255) NULL
);
```

#### Leads Table
Manages potential customer information and tracks their status in the sales pipeline.
```sql
CREATE TABLE Leads (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    industry VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    status ENUM('NEW', 'CONTACTED', 'PROPOSAL', 'NEGOTIATION', 'CLOSED-WON', 'CLOSED-LOST') NOT NULL DEFAULT 'NEW'
);
```

#### Task Table
Tracks tasks associated with leads and assigns them to users.
```sql
CREATE TABLE Task (
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
```

#### Task_Assignees Table
Junction table managing the many-to-many relationship between tasks and assigned users.
```sql
CREATE TABLE Task_Assignees (
    task_id VARCHAR(36),
    user_id VARCHAR(36),
    PRIMARY KEY (task_id, user_id),
    FOREIGN KEY (task_id) REFERENCES Task(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
);
```

### Setup Process
1. **Database Installation**: Install MySQL on your development machine. The installation process varies by operating system, with package managers available for most platforms.

2. **Database Creation**: Create a new database instance and a dedicated user with appropriate permissions. This ensures secure and isolated database access for the application.

3. **Schema Setup**: Run the provided setup scripts to create the database structure. These scripts handle table creation, relationships, and initial data seeding.

4. **Verification**: Verify the setup by checking table structures and relationships. Ensure all foreign key constraints are properly established.

## 2. API Development

### Summary
The API layer provides RESTful endpoints for managing users, leads, and tasks in the CRM system. Built with PHP, it handles data validation, processing, and response formatting using PDO for secure database operations.

### Implementation Details
1. **Environment Setup**
   - PHP with PDO extension for database operations
   - JSON response formatting
   - Centralized database connection management

2. **API Endpoints**

   #### User Management (`/api/users.php`)
   - **GET /api/users**
     - List all users (returns id, name, email, type)
     - Filter by email: `?email=user@example.com`
   - **POST /api/users**
     - Create new user with UUID
     - Required fields: name, email, type, password
     - Password is hashed using SHA2
   - **PUT /api/users**
     - Update user details by email
     - Updateable fields: name, type
   - **DELETE /api/users**
     - Delete user by email parameter

   #### Lead Management (`/api/leads.php`)
   - **GET /api/leads**
     - List all leads
     - Filter by email: `?email=lead@example.com`
   - **POST /api/leads**
     - Create new lead with UUID
     - Required fields: name, email, phone, industry, company
     - Optional: status (defaults to 'NEW')
   - **PUT /api/leads**
     - Update lead by email
     - Updateable fields: name, phone, industry, company, status
   - **DELETE /api/leads**
     - Delete lead by email parameter

   #### Task Management (`/api/tasks.php`)
   - **GET /api/tasks**
     - List all tasks with assignees
     - Get specific task: `?id=task_uuid`
   - **POST /api/tasks**
     - Create new task with UUID
     - Required fields: title, due_date
     - Optional fields: description, lead_id, created_by, priority, status
     - Supports multiple assignees through Task_Assignees table
   - **PUT /api/tasks**
     - Update task by ID
     - Updateable fields: title, description, lead_id, due_date, priority, status
     - Can update task assignees
   - **DELETE /api/tasks**
     - Delete task by ID parameter

### Data Validation & Security
- Input validation (fields, dates, emails, enums)
- Error handling with standard HTTP codes and JSON responses
- SHA2 password hashing and PDO prepared statements
- Data protection and secure transactions

## 3. Frontend Development

### Summary
The frontend provides a modern, responsive interface built with Bootstrap 5 and custom CSS. It features a secure authentication system and an intuitive dashboard for sales CRM management.

### Implementation Details
1. **Authentication**:
   - Login/signup forms with email/password
   - Social login options (Google, Microsoft)
   - Session management with localStorage

2. **Dashboard Interface**:
   - Responsive navigation with tabs
   - Interactive charts and metrics display
   - Task management cards with drag-and-drop
   - Customer interaction logs
   - Lead management system

3. **Technical Stack**:
   - Bootstrap 5 for responsive layout
   - Custom CSS with CSS variables
   - Vanilla JavaScript for interactivity
   - RESTful API integration
   - Bootstrap Icons for UI elements

## 4. Deployment

### Summary
The application is deployed on Heroku's cloud platform, utilizing their PHP runtime and JawsDB MySQL add-on for database services.

### Deployment Process
1. **Heroku Setup**: Create a new Heroku application and configure the necessary buildpacks for PHP support.

2. **Database Configuration**: Set up JawsDB MySQL add-on and configure the database connection parameters.

3. **Environment Setup**: Configure environment variables for sensitive information and deployment-specific settings.

4. **Application Deployment**: Deploy the application using Heroku's Git integration, ensuring proper build and startup processes.