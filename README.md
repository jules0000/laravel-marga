# Laravel Marga - Dynamic CMS with RBAC

A powerful Laravel 10-based Content Management System with Role-Based Access Control (RBAC) and a flexible webpage builder.

## Features

- 🔐 **Role-Based Access Control (RBAC)**: Users, Roles, and Permissions management
- 📄 **Dynamic Webpage Builder**: Create landing pages, articles, and shop pages
- 🎨 **Modular Sections**: 13+ section types (Hero, Grid, Testimonials, etc.)
- 🖼️ **Image Management**: Upload and manage images for sections
- 🎯 **Permission-Based Access**: Fine-grained access control
- 📱 **Responsive Design**: Bootstrap 5 with custom styling

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- WampServer (or similar local server)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/jules0000/laravel-marga.git
cd laravel-marga
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
copy .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_marga
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations and seeders:
```bash
php artisan migrate
php artisan db:seed
```

7. Create storage link:
```bash
php artisan storage:link
```

8. Start the development server:
```bash
php artisan serve
```

## Default Credentials

- **Email**: admin@example.com
- **Password**: password

## Documentation

See [CODEBASE_DOCUMENTATION.md](CODEBASE_DOCUMENTATION.md) for complete codebase documentation.

## License

MIT License

