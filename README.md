# Madrasa Management System

A comprehensive meal management system for madrasas built with Laravel 12 and Filament.

## 🚀 Quick Start with Docker

### Prerequisites
- Docker & Docker Compose installed
- At least 2GB RAM available

### Setup & Run

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd madrasa_management
   ```

2. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

3. **Generate application key**
   ```bash
   # If you have PHP locally installed
   php artisan key:generate

   # Or generate a random key and add to .env
   echo "APP_KEY=base64:$(openssl rand -base64 32)" >> .env
   ```

4. **Start the application**
   ```bash
   docker-compose up -d
   ```

5. **Access the application**
   - **Main App**: http://localhost:8000
   - **Admin Panel**: http://localhost:8000/admin
   - **phpMyAdmin**: http://localhost:8080

### Default Credentials
- **Admin Email**: admin@madrasa.com
- **Admin Password**: (check database/seeders/DatabaseSeeder.php)

## 📋 Docker Services

| Service | Port | Description |
|---------|------|-------------|
| **Laravel App** | 8000 | Main application |
| **MySQL** | 3306 | Database server |
| **phpMyAdmin** | 8080 | Database management interface |

## 🛠 Development Commands

### Using Docker
```bash
# Run artisan commands
docker-compose exec app php artisan <command>

# Run migrations
docker-compose exec app php artisan migrate

# Run seeders
docker-compose exec app php artisan db:seed

# Clear cache
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Run tests
docker-compose exec app php artisan test
```

### Local Development (without Docker)
```bash
# Install dependencies
composer install
npm install

# Build assets
npm run build

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

## 📊 Features

### Meal Management
- ✅ **Daily Meal Entry**: Record attendance for breakfast, lunch, dinner
- ✅ **Automatic Defaults**: All students marked present by default daily
- ✅ **Meal Rates**: Manage pricing with effective dates
- ✅ **Monthly Billing**: Generate and view meal bills
- ✅ **Payment Tracking**: Track paid/unpaid status

### Admin Interface
- **Filament Admin Panel**: Modern admin interface
- **Student Management**: CRUD operations for students
- **Class Management**: Organize students by classes
- **Reports**: Monthly billing reports with filtering

## 🔧 Configuration

### Environment Variables
Key variables in `.env`:
```env
APP_NAME=MadrasaManagement
APP_ENV=local
APP_KEY=your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=madrasa_db
DB_USERNAME=madrasa_user
DB_PASSWORD=madrasa_password
```

### Scheduled Tasks
The system includes automated daily meal creation:
- **Command**: `php artisan meals:create-daily`
- **Schedule**: Runs daily at 6:00 AM
- **Function**: Creates meal records for all active students with default attendance

## 📁 Project Structure

```
├── app/
│   ├── Console/Commands/CreateDailyMeals.php
│   ├── Filament/
│   │   ├── Pages/MealEntry.php
│   │   ├── Resources/
│   │       ├── MealBills/
│   │       └── MealRates/
│   └── Models/
│       ├── Meal.php
│       ├── MealBill.php
│       └── MealRate.php
├── database/
│   ├── migrations/
│   │   ├── *_create_meals_table.php
│   │   ├── *_create_meal_rates_table.php
│   │   └── *_create_meal_bills_table.php
│   └── seeders/
│       ├── MealRateSeeder.php
│       └── DatabaseSeeder.php
├── docker-compose.yml
├── Dockerfile
└── .env.example
```

## 🐳 Docker Details

### Dockerfile Features
- **PHP 8.2** with Apache web server
- **Composer** for PHP dependency management
- **Node.js & npm** for frontend asset building
- **MySQL client** for database connectivity
- **Automated setup** with migrations and seeding

### Docker Compose Services
- **app**: Laravel application container
- **mysql**: MySQL 8.0 database
- **phpmyadmin**: Database management interface

## 🔒 Security Notes

- Change default database passwords in production
- Set `APP_DEBUG=false` in production
- Use strong `APP_KEY`
- Configure proper firewall rules
- Use HTTPS in production

## 📞 Support

For issues or questions:
1. Check the logs: `docker-compose logs app`
2. Verify environment variables
3. Ensure database connectivity
4. Check file permissions in storage directories

---

**Happy coding! 🎉**

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
