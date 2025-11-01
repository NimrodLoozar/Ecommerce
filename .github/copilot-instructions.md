## Quick context for AI coding agents

This is a Laravel 12 (PHP 8.2) ecommerce application for selling and leasing new and used cars from various brands. Built with Vite + Tailwind CSS for frontend. The codebase follows PSR-4 with `App\\` namespace mapped to `app/`.

### Key directories

-   `composer.json` — scripts: `setup`, `dev`, `test` (use these for consistent workflows)
-   `routes/web.php` — primary HTTP routing
-   `app/Http/Controllers/` — controllers and request handling
-   `app/Models/User.php` — canonical model pattern (uses `HasFactory`, `$fillable`, `casts()` for `password => 'hashed'`)
-   `database/migrations` and `database/factories` — migrations & factories (prefer factories for test data)
-   `resources/views/components/` — Blade components (ecommerce UI)
-   `resources/css/app.css` — Tailwind + custom animations
-   `resources/js/app.js` — Alpine.js + custom UI interactions
-   `public/build/` — Vite build output (manifest-driven)
-   `tests/` and `phpunit.xml` — tests run with in-memory SQLite; CI uses these settings
-   `docker-compose.yml` — MySQL 8.0 + phpMyAdmin (localhost:8081)

### Development workflow (Windows/PowerShell)

**Known issue:** `php artisan serve` fails on some Windows machines with Hyper-V. Use workaround:

```powershell
# Terminal 1 - PHP server
php -S localhost:8000 -t public

# Terminal 2 - Vite dev server (hot reload)
npm run dev
```

Or use Docker for database + direct PHP server (recommended):

```powershell
docker compose up -d  # MySQL + phpMyAdmin
npm run dev           # Vite hot reload in separate terminal
```

**Setup from scratch:**

```powershell
composer setup  # installs deps, copies .env, generates key, migrates, builds assets
```

**Run tests:**

```powershell
composer test
```

### Frontend architecture

**UI Framework:** Tailwind CSS 3.x + TailwindPlus Elements (CDN for dialog/popover commands)

**Custom Blade components** (in `resources/views/components/`):

-   `<x-ecommerce-nav />` — sticky nav with mega menus, cart trigger, mobile drawer
-   `<x-ecommerce-footer />` — responsive footer with newsletter form, social links
-   `<x-shopping-cart />` — slide-out drawer with custom animations
-   `<x-category-home />` — category cards with gradient overlays
-   `<x-promo-section />` — promotional hero section ("Find Your Perfect Drive")
-   `<x-partner-logos />` — car brand logos grid (Audi, BMW, Mercedes-Benz, etc.)

**Critical patterns:**

1. **TailwindPlus Elements usage** (loaded via CDN in `welcome.blade.php`):

    - Use `command="show-modal"` and `commandfor="drawer"` to trigger dialogs
    - Buttons must be `<button type="button">`, NOT `<a href="#">` (causes page jump)
    - Custom elements: `<el-dialog>`, `<el-dialog-panel>`, `<el-dialog-backdrop>`, `<el-popover>`, `<el-tab-group>`

2. **Custom animations** (`resources/css/app.css`):

    - Shopping cart drawer uses CSS `@keyframes` + JS-triggered `.closing` class
    - Drawer animations: `slideInFromRight` (300ms), `slideOutToRight` (250ms)
    - Rotating text banner: `slideInFromTop` (600ms), `slideOutToBottom` (600ms)
    - JS intercepts close clicks to delay actual close until animation completes (see `resources/js/app.js`)
    - Rotating text: displays one message at a time, cycles every 3.6s (see `welcome.blade.php` banner)

3. **Sticky footer pattern** (`resources/views/layouts/app.blade.php`, `welcome.blade.php`):

    - Body: `min-h-screen flex flex-col`
    - Main: `flex-1`
    - Footer sits at bottom on short pages

4. **Navigation positioning:**
    - Nav is `sticky top-0 z-50` (not `fixed`)
    - Desktop popovers use `anchor="bottom"` to appear under nav and scroll away
    - Popover triggers wrapped in `relative` for positioning context

### Docker & database

-   **MySQL 8.0** runs in Docker (port 3306)
-   **phpMyAdmin** at `http://localhost:8081` (bound to 127.0.0.1 for security)
-   Default credentials: `ecommerce` / `ecommerce_pass` (see `docker-compose.yml`)
-   Start: `docker compose up -d`

### Testing & CI

-   Tests use in-memory SQLite (`phpunit.xml` sets `DB_CONNECTION=sqlite`, `QUEUE_CONNECTION=sync`)
-   No external mail/queue/db in tests
-   Run with `composer test` or `php artisan test`

### Asset pipeline

-   **Dev:** `npm run dev` (Vite watches and hot-reloads CSS/JS)
-   **Prod:** `npm run build` (outputs to `public/build/` with manifest)
-   Commit `public/build/` files if deploying without build step

### Code conventions

-   **Auth:** Laravel Breeze scaffolding (follow its patterns for auth flows)
-   **Password hashing:** Use Eloquent `casts()` with `['password' => 'hashed']`
-   **Factories over seeders:** Use `Database\\Factories` for test data
-   **Config:** Wire external services through `config/*.php` + `.env` (never hardcode secrets)

### PR checklist

1. Run `composer test` and fix failures
2. If assets changed, run `npm run build` and include `public/build/` output
3. Update routes (`routes/web.php`) and controllers for new endpoints
4. Add new `.env` keys to `config/*.php` defaults

### Examples

**Route:**

```php
Route::get('/', function () { return view('welcome'); });
```

**Blade component usage:**

```blade
<x-ecommerce-nav />
<x-shopping-cart />  <!-- Must be in DOM for cart button to work -->
```

**Dialog trigger (correct):**

```blade
<button type="button" command="show-modal" commandfor="drawer">Open Cart</button>
```

**Dialog trigger (wrong - causes page jump):**

```blade
<a href="#" command="show-modal" commandfor="drawer">Open Cart</a>
```
