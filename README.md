# Laravel Event Ticket System

## Setup Instructions

1. Clone the repository
2. Make sure you have PHP 8.2+ and Composer installed
3. Make sure you have Node.js and npm installed
4. Run setup:
   ```bash
   composer setup
   ```

This will automatically:
- Install PHP dependencies
- Create .env file if not exists
- Generate application key
- Create storage symbolic link
- Run database migrations
- Install npm packages
- Build frontend assets

## Manual Setup (if needed)

If you prefer to set up manually, follow these steps:

1. Install dependencies:
   ```bash
   composer install
   npm install
   ```

2. Environment setup:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Create storage link:
   ```bash
   php artisan storage:link
   ```

4. Database setup:
   ```bash
   php artisan migrate
   ```

5. Build assets:
   ```bash
   npm run build
   ```

## Development Server

To start the development server:
```bash
composer dev
```

This will start:
- Laravel development server
- Queue worker
- Log viewer
- Vite development server

## Portable Database

The project uses SQLite by default for better portability. The database file will be automatically created at `database/database.sqlite`.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



##
[X] - Adatbázis és modellek 3 pont 
[X] - Seeder 4 pont 
[X] - Főoldal (események listája) 2 pont 
[X] - Esemény megjelenítése 2 pont 
[X] - Jegy vásárlása 6 pont 
[X] - Megvásárolt jegyeim listája 2 pont 
[X] - Irányítópult 8 pont 
[X] - Esemény létrehozása 5 pont 
[X] - Esemény módosítása 5 pont 
[X] - Esemény törlése 2 pont 
[X] - Ülőhelyek létrehozása, módosítása és törlése 4 pont 
[X] - Jegykezelés 3 pont
