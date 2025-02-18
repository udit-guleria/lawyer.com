sudo apachectl start

sudo ln -s /Users/sirius/git/lawyer.com /Library/WebServer/Documents/lawyerdotcom

## Tasks API

GET /api/tasks.php
- Get all tasks: http://localhost:8080/lawyerdotcom/api/tasks.php
- Get specific task: http://localhost:8080/lawyerdotcom/api/tasks.php?id=1

POST /api/tasks.php
- Create task: http://localhost:8080/lawyerdotcom/api/tasks.php
  Body: {
    "title": "New Task",
    "description": "Task description",
    "status": "pending"
  }

PUT /api/tasks.php?id=1
- Update task: http://localhost:8080/lawyerdotcom/api/tasks.php?id=1
  Body: {
    "title": "Updated Task",
    "status": "completed"
  }

DELETE /api/tasks.php?id=1
- Delete task: http://localhost:8080/lawyerdotcom/api/tasks.php?id=1

## Leads API

GET /api/leads.php
- Get all leads: http://localhost:8080/lawyerdotcom/api/leads.php
- Get specific lead: http://localhost:8080/lawyerdotcom/api/leads.php?id=1

POST /api/leads.php
- Create lead: http://localhost:8080/lawyerdotcom/api/leads.php
  Body: {
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890"
  }

PUT /api/leads.php?id=1
- Update lead: http://localhost:8080/lawyerdotcom/api/leads.php?id=1
  Body: {
    "name": "John Smith",
    "status": "qualified"
  }

DELETE /api/leads.php?id=1
- Delete lead: http://localhost:8080/lawyerdotcom/api/leads.php?id=1

## Users API

GET /api/users.php
- Get all users: http://localhost:8080/lawyerdotcom/api/users.php
- Get specific user: http://localhost:8080/lawyerdotcom/api/users.php?id=1

POST /api/users.php
- Create user: http://localhost:8080/lawyerdotcom/api/users.php
  Body: {
    "username": "newuser",
    "email": "user@example.com",
    "password": "securepassword"
  }

PUT /api/users.php?id=1
- Update user: http://localhost:8080/lawyerdotcom/api/users.php?id=1
  Body: {
    "email": "newemail@example.com",
    "role": "admin"
  }

DELETE /api/users.php?id=1
- Delete user: http://localhost:8080/lawyerdotcom/api/users.php?id=1

