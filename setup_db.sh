#!/bin/bash

# Database credentials
DB_USER="root"
DB_PASS="lawyer.com"  # Change this to your MySQL root password

# Execute the SQL script
mysql -u "$DB_USER" -p"$DB_PASS" < setup_database.sql

# Check if the script executed successfully
if [ $? -eq 0 ]; then
    echo "Database setup completed successfully!"
    echo "Admin credentials:"
    echo "Email: admin@lawyer.com"
    echo "Password: admin123"
else
    echo "Error setting up the database!"
fi 