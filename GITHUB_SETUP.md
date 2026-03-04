# Professional Laravel Contract Management System - GitHub Setup Guide

## Overview

This is a comprehensive Laravel-based contract management system with professional logging capabilities. The system includes advanced features such as contract lifecycle management, workflow automation, multi-language support (English/Arabic), and a robust logging system.

## Features

- **Professional Contract Management**: Complete contract lifecycle from creation to archiving
- **Advanced Logging System**: Professional logging with different log levels and audit trails
- **Multi-Language Support**: Full RTL support for Arabic and LTR for English
- **Workflow Automation**: Multi-stage approval processes and electronic signatures
- **Financial Integration**: Contract quantities, approvals, journal entries, and amendments
- **Security Features**: Role-based access control and permission management

## Professional Logging System

The system implements a comprehensive logging solution with the following capabilities:

### 1. Activity Logging
- Track all user actions and system events
- Store metadata with each log entry
- Support for different log levels (info, warning, error, debug)

### 2. Audit Trail
- Complete change tracking with before/after values
- Entity-based logging (contracts, quantities, approvals, etc.)
- User identification and IP tracking

### 3. Security Logging
- Monitor security-related events
- Track unauthorized access attempts
- Log security configuration changes

### 4. Error Logging
- Capture system errors with full exception details
- Store stack traces and context information
- Automatic error categorization

## Logging Helper Functions

The system provides convenient helper functions for logging:

```php
// Log a general activity
logActivity($action, $entityType, $entityId, $description, $metadata = [], $level = 'info');

// Log an error
logError($context, $message, $exception = null, $metadata = []);

// Log a security event
logSecurityEvent($eventType, $description, $metadata = []);

// Log an audit trail entry
logAuditTrail($action, $entityType, $entityId, $oldValues = [], $newValues = [], $metadata = []);

// Retrieve activity logs
getActivityLogs($filters = [], $limit = 50);
```

## GitHub Repository Setup

### 1. Initial Repository Creation

1. Create a new repository on GitHub
2. Clone the repository to your local machine
3. Copy all project files to the repository directory

### 2. Git Configuration

```bash
# Initialize git repository
git init

# Add remote origin
git remote add origin https://github.com/your-username/your-repository-name.git

# Add all files to staging
git add .

# Create initial commit
git commit -m "Initial commit: Professional Contract Management System with Advanced Logging"

# Push to main branch
git branch -M main
git push -u origin main
```

### 3. Gitignore Configuration

Ensure your `.gitignore` file is properly configured:

```gitignore
# See https://github.com/johnwyles/laravel-gitignore/blob/main/gitignores/php-laravel.gitignore

/node_modules
/public/hot
/public/robots.txt
/storage/*.key
/vendor
/.idea
/.vscode
.DS_Store
Thumbs.db
npm-debug.log
yarn-error.log
node_modules/
bower_components/
.cache/
.webpack/
.nyc_output/
coverage/
build/
dist/
*.log
.env
.env.local
.env.*
!.env.example
.env.testing
.php_cs.cache
.phpunit.result.cache
Homestead.json
Homestead.yaml
Vagrantfile
nova/
nova-components/
.php_cs.dist
.php_cs
package-lock.json
yarn.lock
npm-shrinkwrap.json
```

### 4. Branch Strategy

Use a proper branching strategy:

```bash
# Create feature branches
git checkout -b feature/new-feature

# Create release branches
git checkout -b release/v1.0.0

# Create hotfix branches for urgent fixes
git checkout -b hotfix/critical-fix
```

### 5. Commit Message Guidelines

Follow conventional commits for better history:

```
feat: Add new contract approval workflow
fix: Resolve issue with Arabic PDF generation
docs: Update logging system documentation
style: Format code according to PSR-12
refactor: Improve logging service architecture
test: Add unit tests for activity logging
chore: Update dependencies and configuration
```

### 6. GitHub Actions Setup

Create `.github/workflows/ci.yml` for continuous integration:

```yaml
name: CI

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest

    steps:
    - uses: actions/checkout@v3

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, bcmath, soap, intl, gd, exif, iconv, imagick
        
    - name: Install Dependencies
      run: composer install --no-interaction --no-progress

    - name: Copy Environment File
      run: cp .env.example .env

    - name: Generate App Key
      run: php artisan key:generate

    - name: Run Tests
      run: php artisan test
```

### 7. Environment Configuration

Create a `.env.example` file with all required environment variables:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Additional configuration for the contract management system
PDF_GENERATION_ENGINE=dompdf # Options: dompdf, tcpdf, gpdf
RTLPDF_ACTIVE=true
ARABIC_FONT_PATH=/path/to/arabic/font.ttf
```

### 8. Documentation Files

Create comprehensive documentation:

- `README.md`: Main project overview
- `INSTALLATION.md`: Detailed installation guide
- `CONFIGURATION.md`: Configuration guide
- `DEPLOYMENT.md`: Deployment instructions
- `CONTRIBUTING.md`: Contribution guidelines
- `SECURITY.md`: Security policies

### 9. Security Considerations

- Enable two-factor authentication on your GitHub account
- Use SSH keys instead of HTTPS for authentication
- Regularly update dependencies
- Scan for vulnerabilities using tools like `composer audit`
- Review pull requests thoroughly

### 10. Release Management

Use semantic versioning (MAJOR.MINOR.PATCH):

```bash
# Create a new tag for releases
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

## Deployment to Production

### 1. Server Requirements

- PHP >= 8.0
- MySQL >= 5.7 or PostgreSQL >= 9.6
- Composer
- Node.js and npm/yarn (for asset compilation)
- Git

### 2. Deployment Steps

```bash
# Clone the repository
git clone https://github.com/your-username/your-repository-name.git
cd your-repository-name

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
npm ci --only=production

# Build assets for production
npm run build

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate --force

# Run database migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Add tests for new functionality
5. Run tests (`php artisan test`)
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

## License

This project is licensed under the [LICENSE] license - see the LICENSE file for details.

## Support

For support, please contact [your-email@example.com] or open an issue on GitHub.