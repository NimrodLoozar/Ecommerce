## Quick context for AI coding agents

This repository is a Laravel 12 skeleton-based web application (PHP 8.2). The codebase follows standard PSR-4 layout with App\\ namespace mapping to `app/`. Frontend assets use Vite + Tailwind and live under `resources/`.

Key files to inspect before making changes
- `composer.json` — project dependencies and npm/composer script shortcuts (see `scripts.setup`, `scripts.dev`, `scripts.test`).
- `routes/web.php` — web routes. Example: root route returns `view('welcome')`.
- `bootstrap/app.php` — application boot configuration used to create the Application.
- `app/Models/User.php` — example Eloquent model (uses `HasFactory`, `Notifiable`, and a typed `casts()` method). Use it as a model pattern.
- `config/app.php` — environment-driven config; prefers `.env` values.
- `phpunit.xml` — test environment. Tests run with an in-memory SQLite DB and `QUEUE_CONNECTION=sync`.

Developer workflows / useful commands (PowerShell)
- Install and prepare (first-time):
  composer install; php -r "file_exists('.env') || copy('.env.example', '.env');"; php artisan key:generate; php artisan migrate --force; npm install; npm run build
- Local dev (starts server, queue worker and vite):
  composer dev  (note: `composer dev` runs an npx concurrently command that invokes `php artisan serve`, queue listener and `npm run dev`)
- Run tests: `composer test` or `php artisan test`. The test environment uses sqlite in-memory so DB setup is not required for CI.

Project-specific conventions and patterns
- Migration & factories: database migrations are under `database/migrations` and factories under `database/factories` (see `Database\\Factories\\UserFactory`). Prefer factories for test data.
- Artisan scripts are wired in `composer.json` (e.g., `setup`, `dev`, `test`). When automating changes, prefer these scripted workflows to replicate local dev steps.
- Frontend pipeline: Vite + Tailwind. Edit `resources/js` and `resources/css`; run `npm run dev` for hot rebuilds and `npm run build` for production assets.
- Tests: `phpunit.xml` sets many env overrides (cache=array, session=array, mail=array). When writing tests, assume no external mail/queue/db unless explicitly configured.

Integration points & external dependencies
- Auth scaffolding: `laravel/breeze` is present — authentication UI/flows may be scaffolded.
- Background jobs: queue connection default is present; tests use `sync`. Production queue drivers may be set in `.env`.
- External services are configured via `.env` + config files under `config/`. Avoid hardcoding credentials in code; read `config/*.php` to find service keys.

When editing code — quick checklist for PRs
1. Run `composer test` (or `php artisan test`) and fix failing tests. Tests expect sqlite in-memory by default.
2. If you changed assets, run `npm run build` and include updated files from `public/build`/manifest.
3. Keep API and route changes in `routes/web.php` (or create API routes in `routes/api.php` if applicable). Update any related controllers under `app/Http/Controllers`.
4. Preserve config-driven behavior — add `.env` keys and default values in `config/*.php` when introducing new external dependencies.

Examples from the codebase (use these as templates)
- Root route: `routes/web.php`:

  Route::get('/', function () {
      return view('welcome');
  });

- User model: `app/Models/User.php` — use `HasFactory`, set `$fillable`, hide `password`, and use typed `casts()` returning `['password' => 'hashed']`.

Notes & gotchas for AI agents
- The repo's composer scripts will copy `.env.example` to `.env` automatically in several lifecycle scripts; however, tests and CI often rely on env overrides in `phpunit.xml`.
- Use `php artisan migrate --force` in scripted setup (composer.setup uses this). For local edits you may prefer `php artisan migrate` interactively.
- The codebase is Laravel-first; prefer idiomatic Eloquent, Jobs, Events, and the service container when wiring new functionality.

If something in this file is unclear or you want more examples (controllers, factories, or common PR checks), tell me which area to expand and I will iterate.
