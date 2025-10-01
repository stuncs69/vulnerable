# Docker Setup for Vulnerable Message Board

This educational PHP application can be easily hosted using Docker and Docker Compose.

## Prerequisites

- Docker
- Docker Compose

## Quick Start

1. **Build and start the containers:**
   ```bash
   docker-compose up -d
   ```

2. **Access the application:**
   - Main application: http://localhost:8080
   - Admin panel: http://localhost:8080/admin.php
   - Login page: http://localhost:8080/validate.php

3. **Default credentials:**
   - Username: `admin`
   - Password: `admin123`

## Services

- **Web Server**: PHP 8.2 with Apache on port 8080
- **Database**: MySQL 8.0 on port 3306

## Database Access

If you need to access the MySQL database directly:
```bash
docker-compose exec db mysql -u root -p
# Password: rootpassword
```

## Stopping the Application

```bash
docker-compose down
```

To also remove the database volume:
```bash
docker-compose down -v
```

## Development

The application files are mounted as a volume, so any changes you make to the PHP files will be immediately reflected in the running application.

## Security Note

⚠️ **This is an intentionally vulnerable application for educational purposes only!** 
- Do not use in production
- Contains SQL injection vulnerabilities
- Uses weak authentication
- Designed for security testing and learning

## Troubleshooting

If you encounter database connection issues:
1. Make sure both containers are running: `docker-compose ps`
2. Check the logs: `docker-compose logs`
3. Restart the services: `docker-compose restart`

If you get "Table 'messageboard.messages' doesn't exist" error:
1. Stop the containers: `docker-compose down -v` (this removes the database volume)
2. Start fresh: `docker-compose up -d`
3. Wait for the database to fully initialize (check logs: `docker-compose logs db`)

To manually check if tables were created:
```bash
docker-compose exec db mysql -u root -prootpassword -e "USE messageboard; SHOW TABLES;"
```