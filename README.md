
# Blog Website

A lightweight blog/CMS built with Laravel. This repository provides admin and public-facing functionality including posts, categories, media, SEO, newsletter subscriptions, likes/bookmarks, and an admin panel with CRUD (including soft deletes, restore, and permanent delete).

Admin Side Url is http://127.0.0.1:8000/admin/login
email: admin@blog.com
password: password

## Table of Contents
- **Project:** Brief description and features
- **Requirements:** PHP, Composer, MySQL, Node
- **Quick Setup:** Steps to get the app running locally
- **Database & Migrations:** running and rolling back migrations
- **Queues & Mail:** configuring mail and queue workers
- **Development:** run dev server and assets
- **Testing:** basic testing commands
- **Security Notes:** important configuration and recommended fixes
- **Troubleshooting:** common issues and commands

## Project
- Framework: Laravel (PHP)
- Frontend: Blade + Tailwind CSS, some vanilla JS
- Key features: posts (with slug routing), categories, media, admin panel, CKEditor integration for rich content, newsletter subscription, likes/bookmarks, soft deletes + restore/force-delete

## Requirements
- PHP 8.0+ (match your project's composer.json)
- Composer
- MySQL (or compatible DB)
- Node.js + npm (for building assets)
- Windows (this repo was edited on Windows - commands below use PowerShell syntax)

## Quick Setup (local)
1. Clone repository

	 git clone <repo-url>
	 cd blog-website

2. Install PHP dependencies

```powershell
composer install
```

3. Install JS dependencies and build assets (optional for development)

```powershell
npm install
npm run dev
```

4. Copy `.env` and set environment values

```powershell
copy .env.example .env
# then edit .env with your DB and mail settings
```

- `APP_URL` should point to your local URL (e.g. `http://localhost`)
- Set `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

5. Generate application key

```powershell
php artisan key:generate
```

6. Run migrations & seeders

```powershell
php artisan migrate --seed
```

7. Create storage symlink (if using public disk)

```powershell
php artisan storage:link
```

8. Run local development server

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Then open `http://127.0.0.1:8000`.

## Database & Migrations
- Run migrations: `php artisan migrate`
- Rollback: `php artisan migrate:rollback`
- Recreate: `php artisan migrate:fresh --seed`

If you added `deleted_at`/SoftDeletes migrations (the project includes such migrations), run migrations after pulling latest code.

## Queues & Mail
- By default this project may use `QUEUE_CONNECTION=database` for queued mail.
- For local testing you can set `QUEUE_CONNECTION=sync` in `.env` to avoid running a worker (emails will be sent synchronously).

Better local flow with Gmail (not recommended for production):
- Enable 2-Step Verification on your Google account and create an App Password. Use the 16-char App Password in `MAIL_PASSWORD`.
- Or use Mailtrap for safe testing (replace SMTP settings with Mailtrap credentials).

Run a queue worker to process queued jobs:

```powershell
php artisan queue:work --tries=3
```

Check failed jobs:

```powershell
php artisan queue:failed
php artisan queue:retry all
php artisan queue:flush
```

## Development
- Build assets in watch mode:

```powershell
npm run dev
# or
npm run watch
```

- Run PHP built-in server:

```powershell
php artisan serve
```

## Testing
- Run PHPUnit tests (if present):

```powershell
vendor\\bin\\phpunit
# or
php artisan test
```

## Security Notes (important)
This project is functional but requires a few security hardenings before production. Priorities:

- **Rate limiting**: Add throttle middleware to sensitive endpoints (login, newsletter subscribe, like API) to prevent abuse.
	- Example: `Route::post('/login', ...)->middleware('throttle:5,1');`

- **File upload validation**: Enforce strict image validation (mime, max size, dimensions). Do not rely solely on extension.

- **Escape user content in server-rendered HTML**: When you return raw HTML from controllers (e.g., DataTables HTML columns), make sure content is escaped to avoid XSS. Use `e()` or `htmlspecialchars()`.

- **Security headers**: Add middleware to inject `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, and `Strict-Transport-Security` (if using HTTPS).

- **Queue & Mail**: Ensure `MAIL_PASSWORD` is an app-specific password (for Gmail) and `.env` is never committed. Use a transactional email provider for production.

- **Tokens in URLs**: Newsletter verification and unsubscribe links use tokens in GET URLs. Consider POST verification or short-lived tokens if this is a concern.

- **Session cookie settings**: In `.env` / config ensure `SESSION_SECURE_COOKIE=true` in production and `SESSION_HTTP_ONLY=true`.

## Troubleshooting
- `Auth guard [admin] is not defined` → run `php artisan config:clear` after adding the `admin` guard to `config/auth.php`.
- `Target class [admin] does not exist` → ensure middleware alias is registered correctly in `bootstrap/app.php` (use `$middleware->alias([...])`).
- Mail failures with Gmail: use App Passwords or Mailtrap; check `storage/logs/laravel.log` for SMTP errors.

## Useful Commands
- Clear config & cache:

```powershell
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

- Run migrations:

```powershell
php artisan migrate
```

- Start worker:

```powershell
php artisan queue:work
```