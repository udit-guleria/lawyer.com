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
- Create local MySQL database
- Run initialization scripts
- Seed with sample data

### Detailed Steps
1. Install MySQL locally
   ```bash
   sudo apt-get install mysql-server    # Ubuntu/Debian
   brew install mysql                   # macOS
   ```

2. Create database and user
   ```bash
   mysql -u root -p
   CREATE DATABASE your_database;
   CREATE USER 'your_user'@'localhost' IDENTIFIED BY 'your_password';
   GRANT ALL PRIVILEGES ON your_database.* TO 'your_user'@'localhost';
   ```

3. Run setup scripts
   ```bash
   ./setup_db.sh
   ```

4. Verify database setup
   ```bash
   mysql -u your_user -p your_database
   SHOW TABLES;
   ```

## 2. API Development

### Summary
- Set up PHP environment
- Create database connection
- Implement CRUD endpoints

### Detailed Steps
1. Install PHP and dependencies
   ```bash
   composer install
   ```

2. Configure database connection
   Edit `db_connect.php` with your credentials:
   ```php
   $host = "localhost";
   $user = "your_user";
   $password = "your_password";
   $database = "your_database";
   ```

3. Available API Endpoints
   - GET `/api/items` - Retrieve all items
   - POST `/api/items` - Create new item
   - PUT `/api/items/{id}` - Update item
   - DELETE `/api/items/{id}` - Delete item

4. Test API endpoints
   ```bash
   curl http://localhost:8000/api/items
   ```

## 3. Frontend Development

### Summary
- Create HTML structure
- Style with CSS
- Implement JavaScript functionality

### Detailed Steps
1. HTML Structure
   - Create basic layout in `index.html`
   - Set up navigation and content areas
   - Add forms and display elements

2. CSS Styling
   - Implement responsive design
   - Style forms and buttons
   - Add animations and transitions

3. JavaScript Implementation
   - Set up API calls
   - Handle form submissions
   - Implement dynamic content updates
   - Add error handling

## 4. Deployment

### Summary
- Prepare application for Heroku
- Configure database
- Deploy application

### Detailed Steps
1. Heroku Setup
   ```bash
   heroku login
   heroku create your-app-name
   ```

2. Configure Database
   ```bash
   heroku addons:create cleardb:ignite
   heroku config:get CLEARDB_DATABASE_URL
   ```

3. Update Configuration
   - Set environment variables
   - Update database connection settings
   - Configure Procfile

4. Deploy Application
   ```bash
   git push heroku main
   heroku open
   ```

## Development Tips
- Always backup database before major changes
- Use version control for all code changes
- Test API endpoints thoroughly
- Follow security best practices
- Keep dependencies updated

## Troubleshooting
- Check database connection settings
- Verify API endpoint URLs
- Review server logs for errors
- Ensure proper environment variables

## Contributing
Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.

## License
This project is licensed under the MIT License - see the LICENSE.md file for details
