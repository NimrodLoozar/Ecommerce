## Quick context for AI coding agents

This is a Laravel 12 (PHP 8.2) ecommerce application for selling and leasing new and used cars from various brands. Built with Vite + Tailwind CSS + Alpine.js for frontend. The codebase follows PSR-4 with `App\\` namespace mapped to `app/`.

### Project Status (December 10, 2025)

**Phase:** Dealer management interface complete, moving to customer views  
**Progress:** 31/78 views complete (40%)

-   ✅ Public customer-facing pages (homepage, listings, car details with image gallery, brands)
-   ✅ Shopping cart, checkout, and order management
-   ✅ Address management
-   ✅ Dealer dashboard with statistics
-   ✅ Dealer cars index page (inventory list)
-   ✅ Dealer car create form (8-section comprehensive form)
-   ✅ Dealer car edit form (pre-populated with existing data)
-   ✅ Image gallery with lightbox (filesystem-based)
    ✅ Dealer order management (index and show views)
    ✅ Dealer analytics dashboard with Chart.js visualizations
    ✅ Dealer commissions tracking
    ✅ Dealer profile pages (show and edit with file uploads)
    🔄 **IN PROGRESS:** Customer dashboard and views
    ⏳ Dealer inquiries management
-   ⏳ Admin control panel
-   ⏳ Wishlist and advanced search

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

**Access application:** Visit `http://localhost:5173` (Vite proxies to Laravel backend)

**Vite configuration:** `vite.config.js` includes proxy: `^(?!/@vite|/@fs|/@id|/node_modules|/resources).*` → `http://localhost:8000`

**Using composer scripts (recommended):**

```powershell
composer setup  # Full setup: install deps, copy .env, generate key, migrate, build assets
composer dev    # Runs serve + queue:listen + vite dev (uses concurrently)
composer test   # Clears config cache and runs PHPUnit
```

**Database setup:**

```powershell
docker compose up -d           # Start MySQL 8.0 + phpMyAdmin
php artisan migrate:fresh      # Reset database
php artisan migrate:fresh --seed  # Reset + seed test data
```

**Test credentials after seeding:**

-   Dealer: `dealer@example.com` / `password` (Premium Auto Sales)
-   9 test cars created (7 Renault + 2 Lynk & Co)
-   22 brands, 50 car models, 45 features seeded

### Frontend architecture

**UI Framework:** Tailwind CSS 3.x + TailwindPlus Elements (CDN for dialog/popover commands)

**Custom Blade components** (in `resources/views/components/`):

-   `<x-ecommerce-nav />` — sticky nav with mega menus, cart trigger, mobile drawer, logout button
-   `<x-ecommerce-footer />` — responsive footer with newsletter form, social links
-   `<x-shopping-cart />` — slide-out drawer with custom animations
-   `<x-category-home />` — category cards with gradient overlays (homepage)
-   `<x-product-list />` — featured cars grid (homepage)
-   `<x-promo-section />` — promotional hero section ("Find Your Perfect Drive")
-   `<x-partner-logos />` — car brand logos grid (Audi, BMW, Mercedes-Benz, etc.)

**Reusable partials** (in `resources/views/*/partials/`):

-   `cars/partials/car-card.blade.php` — car card with image, price, specs
-   `cars/partials/filters-form.blade.php` — advanced filter sidebar for car listings

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

### User roles & access control

**Three-tier role system:**

-   **Customer:** Regular user who can browse, purchase, and manage orders
-   **Dealer:** User with `dealerProfile` relationship (approved dealer account)
    -   Test account: `dealer@example.com` / `password`
    -   Company: Premium Auto Sales
    -   Access to `/dealer/*` routes (protected by `auth` middleware)
    -   Dashboard shows: Total Inventory, Available/Sold Cars, Revenue, Inquiries, Test Drives
-   **Admin:** System administrator (not yet implemented)
    -   Will have access to `/admin/*` routes
    -   Intended for brand/model/category management, dealer approval

**Navigation logic** (`resources/views/components/ecommerce-nav.blade.php`):

-   Check `auth()->user()->dealerProfile` to determine if user is dealer
-   Dealer: Show "Dealer Dashboard", "My Inventory", "My Orders"
-   Customer: Show "Dashboard", "My Orders"
-   Always show logout button (red, POST form with CSRF token)

**Route organization pattern:**

```php
// Public routes - no auth required
Route::get('/', [HomeController::class, 'index']);
Route::get('/cars', [CarController::class, 'index']);

// Customer routes - auth middleware
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::get('/checkout', [CheckoutController::class, 'index']);
});

// Dealer routes - auth middleware (add dealer check later)
Route::prefix('dealer')->name('dealer.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [Dealer\DashboardController::class, 'index']);
    Route::get('/cars', [Dealer\CarController::class, 'index']);
});

// Admin routes - auth + admin middleware (future)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index']);
});
```

### Database schema & critical gotchas

**Column naming conventions (IMPORTANT - common mistakes):**

-   ✅ `orders.total` — Order total (NOT `total_amount`)
-   ✅ `cars.views_count` — View counter (NOT `views`)
-   ✅ `cars.dealer_id` — Foreign key to `dealer_profiles.id` (nullable)
-   ✅ `cars.user_id` — User who created the listing (dealer or admin)
-   ✅ Order has `orderItems()` alias method (points to `items()` relationship)

**Key relationships (models):**

```php
// Car.php
public function dealer(): BelongsTo  // dealer_id → dealer_profiles.id
public function user(): BelongsTo    // user_id → users.id
public function brand(): BelongsTo
public function carModel(): BelongsTo
public function images(): HasMany
public function features(): BelongsToMany

// User.php
public function dealerProfile(): HasOne
public function cars(): HasMany  // Cars created by this user
public function orders(): HasMany
public function reviews(): HasMany

// DealerProfile.php
public function user(): BelongsTo
public function cars(): HasMany  // dealer_id foreign key
public function commissions(): HasMany

// Order.php
public function user(): BelongsTo
public function items(): HasMany  // Primary relationship
public function orderItems(): HasMany  // ALIAS for items() - use for compatibility
public function shippingAddress(): BelongsTo
public function billingAddress(): BelongsTo
```

**Filesystem-based images (no database storage):**

-   Path structure: `public/img/{BrandName}/{Year BrandName ModelName}/`
-   Example: `public/img/Renault/2024 Renault Clio/image1.jpg`
-   `Car::getFilesystemImages()` method scans folders and returns array of paths
-   Handles variations: "Megane" vs "Megan", "E-Tech" suffix, spaces removed
-   Used in `CarController@show` → pass `$filesystemImages` to view
-   No `car_images` table records needed for filesystem images

### Alpine.js patterns

**Key pattern:** Use `x-data` for component state, `x-cloak` to prevent flash of unstyled content

**Image gallery example** (`resources/views/cars/show.blade.php`):

```blade
<div x-data="imageGallery()" x-cloak>
    <!-- Thumbnail grid -->
    <div @click="selectImage(index)">
        <img :class="currentIndex === index ? 'ring-2 ring-indigo-500' : ''">
    </div>

    <!-- Main image with navigation -->
    <img :src="images[currentIndex]" @click="openLightbox">
    <button @click="prevImage">←</button>
    <button @click="nextImage">→</button>

    <!-- Lightbox modal -->
    <div x-show="lightboxOpen" @keydown.escape.window="closeLightbox">
        <!-- Lightbox content -->
    </div>
</div>
```

**Component pattern:**

-   Define `x-data` functions inline or in `resources/js/app.js`
-   Use `x-show` for conditional rendering (keeps in DOM)
-   Use `x-if` for conditional rendering (removes from DOM)
-   Use `x-cloak` + CSS `[x-cloak] { display: none !important; }` to prevent FOUC

**Image gallery system:**

-   **Alpine.js component:** `imageGallery()` manages state and navigation
-   **Thumbnail grid:** Click to select image, highlights current selection
-   **Main image:** Shows current image with navigation arrows on hover
-   **Lightbox modal:** Click main image to open full-screen view
-   **Keyboard navigation:** Arrow keys (←/→) and ESC work in lightbox
-   **Image counter:** Shows "X / Y" on both main view and lightbox

**Key files:**

-   `app/Models/Car.php` — `getFilesystemImages()` method
-   `app/Http/Controllers/CarController.php` — passes `$filesystemImages` to view
-   `resources/css/app.css` — `[x-cloak]` rule and animations
-   `resources/js/app.js` — Alpine initialization and custom behaviors

**Folder structure example:**

```
public/img/Renault/
  ├── 2024 Renault Clio/
  │   ├── image1.jpg
  │   └── image2.jpg
  └── 2022 Renault Megane E-Tech/
      └── image1.jpg
```

### Common tasks & patterns

**Creating a new controller action:**

1. Add route in `routes/web.php` (use route groups for auth/prefix)
2. Create controller method in `app/Http/Controllers/`
3. Eager load relationships to avoid N+1 queries
4. Pass data to view using compact or array syntax
5. Create corresponding Blade view in `resources/views/`

**Eager loading pattern (avoid N+1):**

```php
// Dealer\CarController@index example
public function index()
{
    $dealer = auth()->user()->dealerProfile;

    $cars = Car::where('dealer_id', $dealer->id)
        ->with(['brand', 'carModel', 'category', 'condition', 'images'])
        ->paginate(15);

    return view('dealer.cars.index', compact('cars'));
}
```

**Model factory pattern (for tests/seeds):**

```php
// Use factories for test data, not manual DB inserts
Car::factory()->count(10)->create([
    'dealer_id' => $dealer->id,
    'is_featured' => true,
]);
```

**Blade component pattern:**

```blade
<!-- Reusable partial with props -->
@include('cars.partials.car-card', ['car' => $car])

<!-- Custom component (x- prefix) -->
<x-ecommerce-nav />
<x-shopping-cart />
```

### PR checklist

1. Run `composer test` and fix failures
2. If assets changed, run `npm run build` and include `public/build/` output
3. Update routes (`routes/web.php`) and controllers for new endpoints
4. Add new `.env` keys to `config/*.php` defaults
5. Update `.github/NEXT_STEPS_PLAN.md` to mark completed tasks
6. Check for N+1 queries (use `with()` for eager loading)

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
