# Car Ecommerce Platform - Database Migration Plan

**Project:** Laravel 12 Car Sales & Leasing Platform  
**Date:** November 1, 2025  
**Database:** MySQL 8.0

---

## 1. Site Purpose & Overview

### Platform Description

A comprehensive ecommerce platform for selling and leasing new and used cars from various brands (Audi, BMW, Mercedes-Benz, Citroën, Volkswagen, Toyota, etc.).

### Core Features

-   **Car Browsing:** Browse by brand, category, condition (new/used)
-   **Advanced Search:** Filter by price, year, mileage, fuel type, transmission, features
-   **Car Details:** Comprehensive specifications, multiple images, 360° views
-   **Purchase Options:** Direct purchase or flexible leasing plans
-   **Shopping Experience:** Cart, wishlist, comparison tools
-   **Order Management:** Full order lifecycle from cart to delivery
-   **Customer Engagement:** Reviews, ratings, test drive bookings, inquiries
-   **Admin Dashboard:** Complete inventory and order management

---

## 2. User Roles & Access Control

| Role              | Permissions                                                                                   |
| ----------------- | --------------------------------------------------------------------------------------------- |
| **Guest**         | Browse cars, view details, search & filter, view reviews                                      |
| **Customer**      | Guest permissions + manage cart, place orders, save wishlist, write reviews, book test drives |
| **Dealer/Vendor** | Manage own car listings, view own sales analytics, respond to inquiries                       |
| **Admin**         | Full access - manage all cars, brands, users, orders, content, site configuration             |

---

## 3. Database Tables Structure

### 3.1 Core Car Management

#### `brands`

Car manufacturers (Audi, BMW, Mercedes-Benz, etc.)

-   `id` - Primary key
-   `name` - Brand name (unique)
-   `slug` - URL-friendly identifier
-   `logo` - Brand logo path
-   `description` - Brand description
-   `is_active` - Visibility flag
-   `sort_order` - Display order
-   `timestamps`

#### `car_models`

Specific models per brand (A4, 3 Series, C-Class)

-   `id` - Primary key
-   `brand_id` - Foreign key to brands
-   `name` - Model name
-   `slug` - URL-friendly identifier
-   `description` - Model description
-   `is_active` - Visibility flag
-   `timestamps`

#### `cars`

Individual car listings (main inventory table)

-   `id` - Primary key
-   `brand_id` - Foreign key to brands
-   `car_model_id` - Foreign key to car_models
-   `category_id` - Foreign key to categories
-   `condition_id` - Foreign key to conditions
-   `user_id` - Foreign key to users (dealer/admin who listed)
-   `vin` - Vehicle Identification Number (unique, nullable)
-   `title` - Car listing title
-   `slug` - URL-friendly identifier
-   `description` - Full description
-   `year` - Manufacturing year
-   `mileage` - Current mileage (km)
-   `price` - Purchase price (decimal)
-   `lease_price_monthly` - Monthly lease price (decimal, nullable)
-   `fuel_type` - Enum: petrol, diesel, electric, hybrid, plugin_hybrid
-   `transmission` - Enum: manual, automatic, semi_automatic
-   `engine_size` - Engine displacement (liters)
-   `horsepower` - Engine power (HP)
-   `exterior_color` - Exterior color
-   `interior_color` - Interior color
-   `doors` - Number of doors
-   `seats` - Number of seats
-   `stock_quantity` - Number available (default: 1)
-   `status` - Enum: available, reserved, sold, pending
-   `is_featured` - Featured listing flag
-   `views_count` - View counter
-   `meta_title` - SEO title
-   `meta_description` - SEO description
-   `timestamps`
-   `deleted_at` - Soft deletes

#### `car_images`

Multiple images per car

-   `id` - Primary key
-   `car_id` - Foreign key to cars
-   `image_path` - Image file path
-   `is_primary` - Primary image flag
-   `sort_order` - Display order
-   `alt_text` - Image alt text for SEO
-   `timestamps`

#### `features`

Catalog of car features (leather seats, sunroof, GPS, etc.)

-   `id` - Primary key
-   `name` - Feature name
-   `slug` - URL-friendly identifier
-   `icon` - Icon class or path
-   `category` - Feature category (safety, comfort, technology, etc.)
-   `timestamps`

#### `car_feature` (pivot table)

Links cars to their features

-   `car_id` - Foreign key to cars
-   `feature_id` - Foreign key to features
-   Primary key: composite (car_id, feature_id)

---

### 3.2 Categories & Organization

#### `categories`

Car types and categories

-   `id` - Primary key
-   `name` - Category name (SUV, Sedan, Coupe, Electric, etc.)
-   `slug` - URL-friendly identifier
-   `description` - Category description
-   `image` - Category image path
-   `parent_id` - Self-referencing for subcategories (nullable)
-   `is_active` - Visibility flag
-   `sort_order` - Display order
-   `timestamps`

#### `conditions`

Car condition types

-   `id` - Primary key
-   `name` - Condition name (New, Used, Certified Pre-Owned)
-   `slug` - URL-friendly identifier
-   `description` - Condition description
-   `timestamps`

---

### 3.3 Ecommerce Functionality

#### `carts`

Shopping carts

-   `id` - Primary key
-   `user_id` - Foreign key to users (nullable for guest carts)
-   `session_id` - Session identifier for guest carts
-   `timestamps`

#### `cart_items`

Items in each cart

-   `id` - Primary key
-   `cart_id` - Foreign key to carts
-   `car_id` - Foreign key to cars
-   `quantity` - Quantity (typically 1 for cars)
-   `purchase_type` - Enum: purchase, lease
-   `lease_duration` - Lease duration in months (nullable)
-   `price_snapshot` - Price at time of adding to cart
-   `timestamps`

#### `orders`

Purchase and lease orders

-   `id` - Primary key
-   `user_id` - Foreign key to users
-   `order_number` - Unique order identifier
-   `purchase_type` - Enum: purchase, lease
-   `status` - Enum: pending, confirmed, processing, completed, cancelled, refunded
-   `subtotal` - Order subtotal
-   `tax` - Tax amount
-   `delivery_fee` - Delivery/shipping fee
-   `total` - Total amount
-   `currency` - Currency code (EUR, USD, etc.)
-   `payment_method` - Payment method used
-   `payment_status` - Enum: pending, paid, failed, refunded
-   `billing_address_id` - Foreign key to addresses
-   `shipping_address_id` - Foreign key to addresses
-   `notes` - Customer notes
-   `admin_notes` - Internal admin notes
-   `timestamps`
-   `completed_at` - Order completion timestamp
-   `cancelled_at` - Order cancellation timestamp

#### `order_items`

Cars in each order

-   `id` - Primary key
-   `order_id` - Foreign key to orders
-   `car_id` - Foreign key to cars
-   `quantity` - Quantity ordered
-   `price` - Price per unit at time of order
-   `subtotal` - Line item subtotal
-   `timestamps`

#### `payments`

Payment transaction records

-   `id` - Primary key
-   `order_id` - Foreign key to orders
-   `payment_method` - Payment method (credit_card, bank_transfer, financing, etc.)
-   `transaction_id` - External transaction ID
-   `amount` - Payment amount
-   `currency` - Currency code
-   `status` - Enum: pending, completed, failed, refunded
-   `payment_gateway` - Gateway used (Stripe, PayPal, etc.)
-   `gateway_response` - JSON response from gateway
-   `paid_at` - Payment timestamp
-   `timestamps`

#### `lease_agreements`

Lease-specific terms and agreements

-   `id` - Primary key
-   `order_id` - Foreign key to orders
-   `car_id` - Foreign key to cars
-   `user_id` - Foreign key to users
-   `lease_duration` - Duration in months
-   `monthly_payment` - Monthly payment amount
-   `down_payment` - Initial down payment
-   `annual_mileage_limit` - Allowed mileage per year
-   `excess_mileage_charge` - Charge per km over limit
-   `start_date` - Lease start date
-   `end_date` - Lease end date
-   `status` - Enum: pending, active, completed, terminated
-   `contract_file` - Path to signed contract PDF
-   `timestamps`

---

### 3.4 Customer Interaction

#### `reviews`

Customer reviews and ratings

-   `id` - Primary key
-   `user_id` - Foreign key to users
-   `car_id` - Foreign key to cars
-   `order_id` - Foreign key to orders (nullable)
-   `rating` - Rating (1-5 stars)
-   `title` - Review title
-   `comment` - Review text
-   `is_verified_purchase` - Verified purchase flag
-   `is_approved` - Admin approval flag
-   `helpful_count` - Helpfulness counter
-   `timestamps`

#### `inquiries`

Contact form submissions and inquiries

-   `id` - Primary key
-   `user_id` - Foreign key to users (nullable)
-   `car_id` - Foreign key to cars (nullable)
-   `name` - Inquirer name
-   `email` - Inquirer email
-   `phone` - Inquirer phone
-   `subject` - Inquiry subject
-   `message` - Inquiry message
-   `status` - Enum: new, in_progress, resolved, closed
-   `admin_notes` - Internal notes
-   `timestamps`
-   `responded_at` - Response timestamp

#### `test_drives`

Test drive booking requests

-   `id` - Primary key
-   `user_id` - Foreign key to users (nullable)
-   `car_id` - Foreign key to cars
-   `name` - Customer name
-   `email` - Customer email
-   `phone` - Customer phone
-   `preferred_date` - Preferred test drive date
-   `preferred_time` - Preferred time slot
-   `message` - Additional message
-   `status` - Enum: pending, confirmed, completed, cancelled
-   `confirmed_date` - Confirmed date and time
-   `admin_notes` - Internal notes
-   `timestamps`

#### `wishlists`

User's saved/favorited cars

-   `id` - Primary key
-   `user_id` - Foreign key to users
-   `car_id` - Foreign key to cars
-   `timestamps`

---

### 3.5 User Management & Addresses

#### `users` (update existing table)

Add additional fields to existing users table:

-   `role` - Enum: customer, dealer, admin (default: customer)
-   `phone` - Phone number (nullable)
-   `avatar` - Profile picture path (nullable)
-   `is_active` - Account active status (default: true)
-   `last_login_at` - Last login timestamp

#### `addresses`

User addresses for billing and shipping

-   `id` - Primary key
-   `user_id` - Foreign key to users
-   `type` - Enum: billing, shipping, both
-   `first_name` - First name
-   `last_name` - Last name
-   `company` - Company name (nullable)
-   `address_line1` - Street address
-   `address_line2` - Apartment, suite, etc. (nullable)
-   `city` - City
-   `state` - State/Province
-   `postal_code` - ZIP/Postal code
-   `country_id` - Foreign key to countries
-   `phone` - Contact phone
-   `is_default` - Default address flag
-   `timestamps`

---

### 3.6 Multi-Currency & Regional Features

#### `currencies`

Supported currencies

-   `id` - Primary key
-   `code` - Currency code (EUR, USD, GBP) - unique
-   `name` - Currency name
-   `symbol` - Currency symbol (€, $, £)
-   `exchange_rate` - Exchange rate to base currency
-   `is_default` - Default currency flag
-   `is_active` - Active status
-   `decimals` - Decimal places (default: 2)
-   `timestamps`

#### `countries`

Countries for regional features

-   `id` - Primary key
-   `code` - ISO country code (NL, DE, BE) - unique
-   `name` - Country name
-   `currency_id` - Foreign key to currencies (default currency)
-   `tax_rate` - Default tax rate percentage
-   `phone_code` - International phone code
-   `is_active` - Active for delivery
-   `sort_order` - Display order
-   `timestamps`

#### `delivery_zones`

Delivery zones and shipping fees

-   `id` - Primary key
-   `name` - Zone name
-   `countries` - JSON array of country IDs
-   `delivery_fee` - Base delivery fee
-   `free_delivery_threshold` - Order amount for free delivery (nullable)
-   `estimated_days_min` - Minimum delivery days
-   `estimated_days_max` - Maximum delivery days
-   `is_active` - Active status
-   `timestamps`

#### `tax_rates`

Regional tax rates

-   `id` - Primary key
-   `country_id` - Foreign key to countries (nullable for global)
-   `state` - State/Province (nullable)
-   `rate` - Tax rate percentage
-   `name` - Tax name (VAT, Sales Tax, etc.)
-   `is_active` - Active status
-   `timestamps`

---

### 3.7 Trade-In System

#### `trade_ins`

Customer trade-in submissions

-   `id` - Primary key
-   `user_id` - Foreign key to users
-   `order_id` - Foreign key to orders (nullable, linked when used)
-   `brand_id` - Foreign key to brands
-   `car_model_id` - Foreign key to car_models (nullable)
-   `year` - Manufacturing year
-   `mileage` - Current mileage
-   `condition` - Enum: excellent, good, fair, poor
-   `vin` - VIN number (nullable)
-   `exterior_color` - Color
-   `transmission` - Transmission type
-   `fuel_type` - Fuel type
-   `description` - Customer description
-   `estimated_value` - Customer's estimated value (nullable)
-   `offered_value` - Dealer's offered value (nullable)
-   `final_value` - Final agreed value (nullable)
-   `status` - Enum: pending, under_review, offer_made, accepted, rejected, completed
-   `admin_notes` - Internal notes
-   `reviewed_by` - Foreign key to users (admin/dealer who reviewed)
-   `reviewed_at` - Review timestamp
-   `expires_at` - Offer expiration date
-   `timestamps`

#### `trade_in_images`

Images for trade-in vehicles

-   `id` - Primary key
-   `trade_in_id` - Foreign key to trade_ins
-   `image_path` - Image file path
-   `image_type` - Type: exterior, interior, damage, documents
-   `sort_order` - Display order
-   `timestamps`

---

### 3.8 Dealer/Vendor Management

#### `dealer_profiles`

Extended profile for dealers/vendors

-   `id` - Primary key
-   `user_id` - Foreign key to users (unique)
-   `company_name` - Business name
-   `business_registration` - Registration number
-   `tax_id` - Tax identification number
-   `logo` - Company logo path
-   `description` - Business description
-   `phone` - Business phone
-   `website` - Website URL (nullable)
-   `commission_rate` - Commission percentage (nullable)
-   `subscription_plan` - Subscription tier (nullable)
-   `status` - Enum: pending, approved, suspended, rejected
-   `approved_by` - Foreign key to users (admin)
-   `approved_at` - Approval timestamp
-   `bank_account` - Bank details (encrypted)
-   `documents` - JSON array of document paths
-   `timestamps`

#### `dealer_analytics`

Analytics and metrics for dealers

-   `id` - Primary key
-   `dealer_id` - Foreign key to dealer_profiles
-   `period` - Date period (YYYY-MM)
-   `total_listings` - Total active listings
-   `total_views` - Total car views
-   `total_inquiries` - Total inquiries received
-   `total_sales` - Total cars sold
-   `total_revenue` - Total revenue
-   `commission_owed` - Commission to be paid
-   `timestamps`

#### `commissions`

Commission tracking for dealer sales

-   `id` - Primary key
-   `dealer_id` - Foreign key to dealer_profiles
-   `order_id` - Foreign key to orders
-   `car_id` - Foreign key to cars
-   `sale_amount` - Sale amount
-   `commission_rate` - Commission percentage
-   `commission_amount` - Calculated commission
-   `status` - Enum: pending, approved, paid
-   `paid_at` - Payment timestamp
-   `payment_method` - Payment method
-   `payment_reference` - Payment reference number
-   `timestamps`

---

## 4. Migration Implementation Phases

### Phase 1: Core Car Structure (Priority: HIGH)

**Migrations to create:**

1. `create_brands_table`
2. `create_car_models_table`
3. `create_categories_table`
4. `create_conditions_table`
5. `create_cars_table`
6. `create_car_images_table`
7. `create_features_table`
8. `create_car_feature_table` (pivot)

**Seeders:**

-   Brand seeder (Audi, BMW, Mercedes, etc.)
-   Category seeder (SUV, Sedan, Coupe, etc.)
-   Condition seeder (New, Used, Certified)
-   Basic feature seeder (common features)

---

### Phase 2: Ecommerce Features (Priority: HIGH)

**Migrations to create:**

1. `create_carts_table`
2. `create_cart_items_table`
3. `create_orders_table`
4. `create_order_items_table`
5. `create_payments_table`
6. `create_lease_agreements_table`

---

### Phase 3: Customer Features (Priority: MEDIUM)

**Migrations to create:**

1. `create_reviews_table`
2. `create_wishlists_table`
3. `create_inquiries_table`
4. `create_test_drives_table`

---

### Phase 4: User & Address Management (Priority: MEDIUM)

**Migrations to create:**

1. `add_additional_fields_to_users_table`
2. `create_addresses_table`

---

### Phase 5: Multi-Currency & Regional Features (Priority: HIGH)

**Migrations to create:**

1. `create_currencies_table`
2. `create_countries_table`
3. `create_delivery_zones_table`
4. `create_tax_rates_table`

**Seeders:**

-   Currency seeder (EUR, USD, GBP, etc.)
-   Country seeder (EU countries + major markets)
-   Tax rate seeder (country-specific VAT/sales tax)
-   Delivery zone seeder (regional zones)

---

### Phase 6: Trade-In System (Priority: MEDIUM)

**Migrations to create:**

1. `create_trade_ins_table`
2. `create_trade_in_images_table`

---

### Phase 7: Dealer/Vendor Management (Priority: HIGH)

**Migrations to create:**

1. `create_dealer_profiles_table`
2. `create_dealer_analytics_table`
3. `create_commissions_table`

---

## 5. Key Design Decisions

### Inventory Management

-   **Approach:** Each car listing is unique with VIN tracking
-   **Stock quantity:** Supports multiple units of same model
-   **Status tracking:** Available → Reserved → Sold workflow

### Pricing Strategy

-   **Dual pricing:** Both purchase and lease prices stored
-   **Multi-currency:** Support multiple currencies with exchange rates
-   **Regional pricing:** Can set different prices per region
-   **Price snapshot:** Prices frozen at cart/order time with currency
-   **Dynamic conversion:** Real-time currency conversion in frontend

### Lease Implementation

-   **Complexity:** Full lease terms with duration, mileage limits, payments
-   **Contract storage:** Digital contract PDF storage
-   **Status tracking:** Separate lease agreement lifecycle

### Multi-vendor Support

-   **Implementation:** Dealer role can manage own listings from Phase 1
-   **Commission tracking:** Track sales and commissions automatically
-   **Approval workflow:** Admin approves dealer accounts and listings
-   **Tracking:** `user_id` on cars table for ownership
-   **Analytics:** Dedicated dashboard for dealer performance metrics

### Image Management

-   **Multiple images:** Unlimited images per car
-   **Primary image:** Flagged for thumbnails
-   **Sort order:** Manual ordering capability
-   **SEO:** Alt text for accessibility and SEO

### Search & Filtering

**Indexed fields:**

-   Brand, model, category, condition
-   Price range, year, mileage
-   Fuel type, transmission
-   Features (many-to-many)

---

## 6. Confirmed Professional Features ✅

### Implemented Features:

1. **✅ Multi-Currency Support:**

    - Support multiple currencies (EUR, USD, GBP, etc.)
    - Real-time exchange rates
    - User can select preferred currency
    - Prices displayed in selected currency

2. **✅ Trade-in System:**

    - Customers can submit their cars for trade-in valuation
    - Trade-in value can be applied as partial payment
    - Admin can review and approve trade-in offers
    - Track trade-in vehicles separately

3. **✅ Regional Features:**

    - Multiple tax rates based on region/country
    - Delivery zones with different fees
    - Regional pricing support
    - Country-specific regulations

4. **✅ Dealer/Vendor Access:**
    - Dealers can register and manage their own listings
    - Commission-based or subscription model
    - Analytics dashboard for dealers
    - Admin approval workflow for dealer listings

### Additional Features to Consider:

1. **Financing Options:**

    - Integration with financing providers?
    - Pre-approval process?

2. **Warranty & Insurance:**

    - Track warranty information?
    - Insurance offerings?

3. **Service History:**
    - For used cars, track service records?
    - Maintenance history?

### Future Enhancements:

-   **Comparison Tool:** Compare multiple cars side-by-side
-   **Price Alerts:** Notify users of price drops
-   **Recent Views:** Track user browsing history
-   **Related Cars:** Recommendation engine
-   **Advanced Analytics:** Sales metrics, inventory turnover
-   **API Integration:** VIN decoder, market value APIs
-   **Mobile App:** Future mobile application support
-   **Live Chat:** Real-time customer support
-   **Virtual Tours:** 360° interior views
-   **Appointment Scheduling:** In-person viewing appointments

---

## 7. Naming Conventions & Standards

### Table Names

-   Plural, lowercase, snake_case
-   Example: `cars`, `car_images`, `order_items`

### Foreign Keys

-   Pattern: `{table_singular}_id`
-   Example: `car_id`, `user_id`, `brand_id`

### Pivot Tables

-   Alphabetical order of table names (singular)
-   Example: `car_feature` (not `feature_car`)

### Timestamps

-   Always include `timestamps()` (created_at, updated_at)
-   Use `softDeletes()` for recoverable records
-   Add custom timestamps as needed (completed_at, etc.)

### Indexes

-   Primary keys: automatic
-   Foreign keys: always indexed
-   Unique constraints: VIN, email, order_number, slug fields
-   Search fields: brand_id, category_id, status, price, year

---

## 8. Testing Strategy

### Migration Testing

-   Run migrations on fresh database
-   Test rollback functionality
-   Verify all foreign key constraints
-   Check default values

### Data Integrity

-   Unique constraints working
-   Cascading deletes configured correctly
-   Nullable fields behaving properly
-   Enum values validated

### Factory Testing

-   Create factories for all models
-   Test relationships between models
-   Verify data generation logic

---

## 9. Implementation Checklist

### Phase 1: Core Structure ✅ Ready to Start

-   [ ] Core car structure migrations
-   [ ] Seeders for brands, categories, conditions
-   [ ] Factory files for testing
-   [ ] Model files with relationships

### Phase 2: Ecommerce ⏳ Pending

-   [ ] Ecommerce functionality migrations
-   [ ] Cart and order workflow testing
-   [ ] Payment integration setup

### Phase 3: Customer Features ⏳ Pending

-   [ ] Customer interaction migrations
-   [ ] Review and inquiry system testing

### Phase 4: User & Address ⏳ Pending

-   [ ] User and address migrations
-   [ ] Role-based access setup

### Phase 5: Multi-Currency & Regional ⏳ Pending

-   [ ] Currency and country migrations
-   [ ] Tax rate and delivery zone setup
-   [ ] Exchange rate API integration
-   [ ] Regional pricing logic

### Phase 6: Trade-In System ⏳ Pending

-   [ ] Trade-in table migrations
-   [ ] Trade-in workflow implementation
-   [ ] Valuation system setup

### Phase 7: Dealer/Vendor System ⏳ Pending

-   [ ] Dealer profile migrations
-   [ ] Commission tracking system
-   [ ] Dealer dashboard implementation
-   [ ] Approval workflow

### Final Steps

-   [ ] Update User model with new fields
-   [ ] Run `composer test` to verify all migrations
-   [ ] Create comprehensive API documentation
-   [ ] Update README with complete database schema
-   [ ] Performance testing with large datasets
-   [ ] Security audit of all tables and relationships

---

## 10. Next Steps

1. **Review and approve this plan**
2. **Clarify outstanding questions** (sections 6)
3. **Begin Phase 1 implementation** (core car structure)
4. **Create corresponding model files** with relationships
5. **Write factories** for test data generation
6. **Create seeders** for initial data
7. **Build controllers** for each resource
8. **Implement frontend views** using existing Blade components

---

**Document Version:** 1.0  
**Last Updated:** November 1, 2025  
**Status:** Pending Review
