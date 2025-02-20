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

# Sales CRM Platform

## Deployment Information

### Database Configuration
The application uses JawsDB MySQL on Heroku. Database credentials are automatically configured through the `JAWSDB_URL` environment variable.

### Deployment Steps
1. Push changes to Heroku:
   ```bash
   git push heroku main
   ```

2. Update database schema (if changed):
   ```bash
   ./deploy_db.sh
   ```

3. View application logs:
   ```bash
   heroku logs --tail
   ```

### Important URLs
- Application: https://sales-crm-platform-by-udit.herokuapp.com
- Database Management: Available through JawsDB dashboard in Heroku

### Environment Variables
- `JAWSDB_URL`: Database connection string (automatically set by JawsDB addon)

### Troubleshooting
- Database connection issues: Check `heroku config | grep JAWSDB_URL`
- Application errors: Check `heroku logs --tail`
- Restart application: `heroku restart`

### Maintenance
- Regular backups are handled by JawsDB
- Monitor database usage through Heroku dashboard