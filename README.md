# Professional Contract Management System

A comprehensive Laravel-based contract management system with advanced workflow automation, professional logging, and multi-language support (English/Arabic).

## 🚀 Features

### Core Functionality
- **Contract Management**: Complete contract lifecycle from creation to archiving
- **Multi-Language Support**: Full RTL support for Arabic and LTR for English
- **Workflow Automation**: Multi-stage approval processes with electronic signatures
- **Financial Integration**: Contract quantities, approvals, journal entries, and amendments
- **Advanced Logging**: Professional logging with audit trails and security monitoring
- **Role-Based Access Control**: Fine-grained permissions and access management

### Professional Logging System
- **Activity Logging**: Track all user actions and system events
- **Audit Trail**: Complete change tracking with before/after values
- **Security Logging**: Monitor security-related events and access attempts
- **Error Logging**: Capture system errors with full exception details
- **Log Levels**: Support for info, warning, error, and debug levels
- **Metadata Storage**: Rich metadata with each log entry

### Technical Features
- **Laravel Framework**: Built on the robust Laravel ecosystem
- **Responsive Design**: Works seamlessly on desktop and mobile devices
- **RESTful APIs**: Comprehensive API endpoints for integration
- **Database Relations**: Properly normalized database with foreign key constraints
- **File Management**: Secure file uploads with validation
- **Caching**: Performance optimization with Laravel caching

## 🛠️ Tech Stack

- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Blade templates, Bootstrap 5, JavaScript
- **Database**: MySQL/PostgreSQL
- **Queue System**: Laravel Queues for background jobs
- **File Storage**: Laravel Filesystem with multiple disk support
- **Authentication**: Laravel Sanctum for API authentication
- **Authorization**: Laravel Policies and Gates

## 📋 Prerequisites

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- NPM or Yarn
- MySQL >= 8.0 or PostgreSQL >= 12
- Git

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
# Or if using yarn
yarn install
```

### 3. Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Configure your database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### 5. Build Assets

```bash
# For development
npm run dev
# Or for production
npm run build
```

### 6. Start the Development Server

```bash
# Start Laravel development server
php artisan serve

# Or using Laravel Sail (if configured)
./vendor/bin/sail up
```

## 🔧 Configuration

### Logging Configuration

The system comes with a professional logging system that can be configured in `config/logging.php`. The logging service provides:

- **Activity Logs**: For user actions and system events
- **Security Events**: For security-related monitoring
- **Error Tracking**: For system errors and exceptions
- **Audit Trails**: For change tracking with before/after values

### Multi-Language Configuration

The system supports both English and Arabic with proper RTL/LTR handling:

```php
// Switch languages
App::setLocale('ar'); // Arabic
App::setLocale('en'); // English
```

## 🏗️ Project Structure

```
app/
├── Http/              # Controllers, Middleware
├── Models/           # Eloquent Models
├── Services/         # Business Logic Services
├── Helpers/          # Helper Functions
├── Console/          # Artisan Commands
└── Providers/        # Service Providers
config/               # Configuration Files
database/
├── migrations/       # Database Migrations
├── seeders/          # Database Seeders
└── factories/        # Model Factories
resources/
├── views/            # Blade Templates
├── js/              # JavaScript
└── css/             # Stylesheets
routes/               # Route Definitions
storage/              # Storage Directory
└── logs/            # Log Files
```

## 📊 Professional Logging Usage

### Using Helper Functions

The system provides convenient helper functions for logging:

```php
// Log a general activity
logActivity($action, $entityType, $entityId, $description, $metadata = [], $level = 'info');

// Example usage
logActivity('contract_created', 'contract', $contract->id, 'New contract created', [
    'user_id' => auth()->id(),
    'contract_value' => $contract->value
]);

// Log an error
logError($context, $message, $exception = null, $metadata = []);

// Log a security event
logSecurityEvent($eventType, $description, $metadata = []);

// Log an audit trail entry
logAuditTrail($action, $entityType, $entityId, $oldValues = [], $newValues = [], $metadata = []);

// Retrieve activity logs
getActivityLogs($filters = [], $limit = 50);
```

### Using the Logging Service Directly

```php
use App\Services\LoggingService;

// Get the logging service
$loggingService = app('logging');

// Or through dependency injection
public function __construct(LoggingService $loggingService)
{
    $this->loggingService = $loggingService;
}

// Use the service
$this->loggingService->logActivity(/* parameters */);
```

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run specific test file
php artisan test --filter=YourTestClassName
```

## 🚢 Deployment

### Production Build

```bash
# Install production dependencies only
composer install --no-dev --optimize-autoloader

# Build assets for production
npm run build

# Run production migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Server Configuration

#### Apache (.htaccess)
```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### Nginx
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass php:9000;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    include fastcgi_params;
}
```

## 🔐 Security

- **Input Validation**: All inputs are validated and sanitized
- **XSS Protection**: Built-in XSS protection with Blade templating
- **CSRF Protection**: Automatic CSRF token verification
- **SQL Injection Prevention**: Query parameter binding
- **Authentication**: Robust authentication with password hashing
- **Authorization**: Role-based access control with permissions

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Add tests for new functionality
5. Run tests (`php artisan test`)
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

If you encounter any issues or have questions:

- Check the [documentation](documentation/)
- Open an issue on GitHub
- Contact the development team

---

## 🎯 About

This system was developed to provide a professional, scalable solution for contract management with advanced features like workflow automation, comprehensive logging, and multi-language support. The architecture follows Laravel best practices and industry standards for enterprise-grade applications.

**Made with ❤️ using Laravel**