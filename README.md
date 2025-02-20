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

#### Users Table
Stores user account information including credentials and timestamps.
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Items Table
Contains product information with references to sellers (users).
```sql
CREATE TABLE items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### Orders Table
Tracks customer orders with their status and total amounts.
```sql
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### Order_Items Table
Junction table linking orders to items with quantity and price information.
```sql
CREATE TABLE order_items (
    order_id INT,
    item_id INT,
    quantity INT NOT NULL,
    price_at_time DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (order_id, item_id),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);
```

### Setup Process
1. **Database Installation**: Install MySQL on your development machine. The installation process varies by operating system, with package managers available for most platforms.

2. **Database Creation**: Create a new database instance and a dedicated user with appropriate permissions. This ensures secure and isolated database access for the application.

3. **Schema Setup**: Run the provided setup scripts to create the database structure. These scripts handle table creation, relationships, and initial data seeding.

4. **Verification**: Verify the setup by checking table structures and relationships. Ensure all foreign key constraints are properly established.

## 2. API Development

### Summary
The API layer provides RESTful endpoints for interacting with the database. Built with PHP, it handles data validation, processing, and response formatting.

### Implementation Details
1. **Environment Setup**: The application requires PHP 7.4+ and uses Composer for dependency management. Key dependencies are managed through composer.json.

2. **Database Connection**: Connection management is handled through a centralized configuration file, supporting both development and production environments.

3. **API Endpoints**:
   - **Items Management**:
     - List Items: Retrieves paginated list of available items
     - Item Details: Fetches detailed information for specific items
     - Create/Update Items: Handles item creation and modifications
     - Delete Items: Manages item removal with proper validation
   
   - **User Operations**:
     - Authentication: Handles user login and session management
     - Account Management: Supports user registration and profile updates
   
   - **Order Processing**:
     - Order Creation: Processes new orders with validation
     - Order Status: Tracks and updates order status
     - Order History: Retrieves user order history

## 3. Frontend Development

### Summary
The frontend provides an intuitive user interface for interacting with the API. It's built with modern web technologies focusing on responsiveness and user experience.

### Implementation Details
1. **HTML Structure**:
   - Semantic markup for better accessibility
   - Modular components for reusability
   - Responsive layout structure
   - Form implementations for data entry

2. **CSS Architecture**:
   - Responsive design system
   - Component-based styling
   - Animation and transition effects
   - Cross-browser compatibility

3. **JavaScript Features**:
   - Dynamic content loading
   - Form validation and submission
   - API integration
   - Error handling and user feedback
   - State management

## 4. Deployment

### Summary
The application is deployed on Heroku's cloud platform, utilizing their PHP and MySQL (ClearDB) services.

### Deployment Process
1. **Heroku Setup**: Create a new Heroku application and configure the necessary buildpacks for PHP support.

2. **Database Configuration**: Set up ClearDB MySQL add-on and configure the database connection parameters.

3. **Environment Setup**: Configure environment variables for sensitive information and deployment-specific settings.

4. **Application Deployment**: Deploy the application using Heroku's Git integration, ensuring proper build and startup processes.

## Development Tips
- Maintain comprehensive test coverage
- Follow security best practices for data handling
- Implement proper error logging and monitoring
- Keep dependencies updated
- Use version control effectively

## Troubleshooting
- Monitor application logs for errors
- Verify environment configurations
- Check database connectivity
- Validate API responses
- Review browser console for frontend issues

## Contributing
Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.

## License
This project is licensed under the MIT License - see the LICENSE.md file for details
