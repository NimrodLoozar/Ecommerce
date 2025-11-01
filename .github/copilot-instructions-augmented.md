## Quick context for AI coding agents

This is a Laravel 12 (PHP 8.2) MVC application with Vite + Tailwind for frontend assets. The codebase follows PSR-4 with the `App\` namespace mapped to `app/`.

Key places to look (fast):

-   `composer.json` — scripts used by maintainers: `setup`, `dev`, `test` (prefer these for consistent local/CI runs).
-   `routes/web.php`, `routes/api.php` — primary HTTP routing.
-   `app/Http/Controllers/` — controllers and request handling.
-   `app/Models/User.php` — canonical model pattern (uses `HasFactory`, `$fillable`, `casts()` for `password => 'hashed'`).
-   `database/migrations` and `database/factories` — migrations & factories (prefer factories for test data).
-   `resources/js`, `resources/css` — frontend source; built output is in `public/build` (Vite manifest).
-   `tests/` and `phpunit.xml` — tests run with an in-memory SQLite DB and many env overrides; CI relies on those settings.
-   `config/*.php` — environment-driven config; never hardcode secrets.

Quick commands (PowerShell used by maintainers):

```powershell
composer install; php -r "file_exists('.env') || copy('.env.example', '.env');"; php artisan key:generate; php artisan migrate --force; npm install; npm run build
```

Local development (recommended):

```powershell
composer dev
```

`composer dev` starts `php artisan serve`, a queue worker/listener, and `npm run dev` (via `npx concurrently`) — use it to mirror maintainer workflows.

Run tests:

```powershell
composer test
```

Project-specific patterns and gotchas (concrete):

-   Auth: `laravel/breeze` scaffolding is present — follow its controllers/views pattern in `resources/views/auth` and `app/Http/Controllers/Auth` when extending login/register flows.
-   Tests: `phpunit.xml` sets `QUEUE_CONNECTION=sync`, `MAIL_MAILER=array`, `CACHE_DRIVER=array` and uses an in-memory SQLite DB — write tests with factories (`Database\Factories`) and avoid relying on external services.
-   Factories: prefer `Database\Factories` (e.g., `UserFactory.php`) for test data; tests and CI expect this pattern.
-   Passwords: `app/Models/User.php` uses typed `casts()` with `['password' => 'hashed']` — do not call bcrypt directly; rely on casting.
-   Composer scripts: `composer.json` contains helper scripts (`setup`, `dev`, `test`) that copy `.env.example` to `.env` in CI flows — use those in automation to match CI behavior.

Integration & configuration notes:

-   Docker: this repo contains a `docker-compose.yml` with a `db` MySQL service. A `phpmyadmin` service may be added for local convenience — if present it should be bound to `127.0.0.1` only.
-   External services: use `config/*.php` + `.env` to wire credentials (see `config/services.php`, `config/mail.php`). Do not hardcode secrets.
-   Queues & jobs: background jobs exist; local and CI tests use `QUEUE_CONNECTION=sync`. Use `php artisan queue:work` for local long-running workers if needed.
-   Frontend: edit `resources/js` and `resources/css`. Use `npm run dev` during development and `npm run build` for production; commit `public/build` when changing assets.

PR checklist for AI agents (do before creating a PR):

1. Run `composer test` (or `php artisan test`) and fix failing tests (CI mirrors this).
2. If assets changed, run `npm run build` and include `public/build` output (Vite manifest references these files).
3. Update `routes/web.php` or `routes/api.php` and any controllers in `app/Http/Controllers` for new endpoints. Update views under `resources/views` if applicable.
4. Add new `.env` keys to `config/*.php` defaults when introducing external services.
5. If you add Docker services (debugging consoles, phpMyAdmin, Adminer), keep them bound to localhost or in compose override files and document usage in README.

Quick examples to copy from the codebase:

-   Root route (`routes/web.php`):
    Route::get('/', function () { return view('welcome'); });
-   Migrations & factories: check `database/migrations/*` and `database/factories/UserFactory.php` for patterns used in tests.
-   Start a local DB & admin UI (dev): `docker compose up -d` then open phpMyAdmin if added at `http://127.0.0.1:8081` (do NOT expose publicly).

If you'd like, I can:

-   replace the current `.github/copilot-instructions.md` with this updated version, or
-   expand this document with a "how to run locally" section, secure phpMyAdmin/nginx config snippets, or a PR checklist with exact git commands.

Tell me which and I'll update the repo file accordingly.
