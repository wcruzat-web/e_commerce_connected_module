# Database Planning — ECommerce Module

## Notes
- This file tracks all database planning, table schemas, and decisions made during development.
- Tables prefixed with `[OTHER MODULE]` are placeholders — not our responsibility to build.

---

## 2026-07-09 — Products Module

### Tables Created

#### categories — [OTHER MODULE] Procurement/Product Master | Migration: `database/migrations/2026_07_09_112153_create_categories_table.php`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| name | string | |
| slug | string | Unique |
| description | text | Nullable |
| timestamps | | created_at, updated_at |

#### products — [OTHER MODULE] Procurement/Product Master | Migration: `database/migrations/2026_07_09_112154_create_products_table.php`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| brand | string | |
| name | string | |
| slug | string | Unique |
| description | text | Nullable |
| price | decimal(10,2) | |
| sale_price | decimal(10,2) | Nullable |
| category_id | bigint | FK → categories |
| featured_image | string | Nullable |
| stock | integer | Default 0 |
| sku | string | Unique |
| badge | string | Nullable ("Only 4 Left", "New Arrival", "Best Seller") |
| rating | decimal(3,2) | Default 0 |
| review_count | integer | Default 0 |
| is_active | boolean | Default true |
| timestamps | | |

#### product_images — [OTHER MODULE] Procurement/Product Master | Migration: `database/migrations/2026_07_09_112155_create_product_images_table.php`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| product_id | bigint | FK → products |
| url | string | Image path |
| sort_order | integer | Default 0 |
| timestamps | | |

#### product_specifications — [OTHER MODULE] Procurement/Product Master | Migration: `database/migrations/2026_07_09_112156_create_product_specifications_table.php`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| product_id | bigint | FK → products |
| group_name | string | "GPU Architecture", "Memory", etc. |
| label | string | "CUDA Cores" |
| value | string | "16,384" |
| is_highlight | boolean | For "At a Glance" cards |
| sort_order | integer | Default 0 |
| timestamps | | |

### Notes
- `migrate:fresh` was run; dropped pre-existing `carts`, `cart_items`, `wishlists` tables (no migration files). Recreate when Cart page is tackled.
- All product tables have proper foreign keys with cascade delete.
- For shop page UI reference, see `reference_ui.png` and layout description in changelog.

---

## 2026-07-09 — Cart Module

### Tables Created

#### customers — ECommerce Cart | Migration: `database/migrations/2026_07_09_115225_create_customers_table.php`
| Column | Type | Notes |
|---|---|---|
| customer_id | bigint | PK |
| name | string | |
| email | string | Unique |
| phone | string(20) | Nullable |
| address | text | Nullable |
| timestamps | | |

#### carts — ECommerce Cart | Migration: `database/migrations/2026_07_09_115226_create_carts_table.php`
| Column | Type | Notes |
|---|---|---|
| cart_id | bigint | PK |
| customer_id | bigint | FK → customers |
| timestamps | | |

#### cart_items — ECommerce Cart | Migration: `database/migrations/2026_07_09_115227_create_cart_items_table.php`
| Column | Type | Notes |
|---|---|---|
| cart_item_id | bigint | PK |
| cart_id | bigint | FK → carts (cascade) |
| product_id | bigint | FK → products (cascade) |
| quantity | integer | |
| unit_price | decimal(10,2) | Price snapshot at add time |
| subtotal | decimal(10,2) | quantity × unit_price |
| timestamps | | |

### Models
- `App\Models\Customer` — hasMany(Cart)
- `App\Models\Cart` — belongsTo(Customer), hasMany(CartItem)
- `App\Models\CartItem` — belongsTo(Cart), belongsTo(Product)

### Architecture
- `App\Services\CartService` — OOP service class handling cart logic (getOrCreate, addItem, updateQuantity, removeItem, getSummary)
- `App\Http\Controllers\CartController` — HTTP layer using dependency-injected CartService
- Guest users get an auto-created Customer record stored in session (`guest_customer_id`)
- Routes: GET /cart, PATCH /cart/{cartItem}, DELETE /cart/{cartItem}
- ToDo: POST /cart/voucher when coupon system is built

---

## 2026-07-10 — Checkout Module

### Tables Created

#### orders — ECommerce Checkout | Migration: `database/migrations/2026_07_10_132723_create_orders_table.php`
| Column | Type | Notes |
|---|---|---|
| order_id | bigint | PK |
| customer_id | bigint | FK → customers |
| order_number | string | Unique (ORD-XXXXX) |
| status | string | Default "pending" |
| subtotal | decimal(10,2) | |
| tax | decimal(10,2) | |
| grand_total | decimal(10,2) | |
| shipping_name | string | |
| shipping_email | string | |
| shipping_phone | string(20) | Nullable |
| shipping_address | text | |
| notes | text | Nullable |
| timestamps | | |

#### order_items — ECommerce Checkout | Migration: `database/migrations/2026_07_10_132724_create_order_items_table.php`
| Column | Type | Notes |
|---|---|---|
| order_item_id | bigint | PK |
| order_id | bigint | FK → orders (cascade) |
| product_id | bigint | FK → products (cascade) |
| quantity | integer | |
| unit_price | decimal(10,2) | |
| subtotal | decimal(10,2) | |
| timestamps | | |

### Models
- `App\Models\Order` — belongsTo(Customer), hasMany(OrderItem)
- `App\Models\OrderItem` — belongsTo(Order), belongsTo(Product)

### CustomerAddresses
`customer_addresses` — ECommerce Checkout | Migration: `database/migrations/2026_07_10_134110_create_customer_addresses_table.php`

| Column | Type | Notes |
|---|---|---|
| address_id | bigint | PK |
| customer_id | bigint | FK → customers (cascade) |
| address_type | string(20) | "Home", "Work", "Other" |
| street | string(150) | |
| barangay | string(100) | |
| city | string(100) | |
| province | string(100) | |
| postal_code | string(10) | |
| country | string(100) | |
| is_default | boolean | Only one default per customer |
| timestamps | | |

**Model:** `App\Models\CustomerAddress` — `belongsTo(Customer)`
**Seeder:** `CustomerAddressSeeder` — 3 sample addresses (Home, Work, Other)

### Architecture
- `App\DTOs\CheckoutDataDTO` — typed DTO for checkout form data (shippingName, shippingEmail, ...)
- `App\Repositories\OrderRepository` — DB queries: create order, add item, load items
- `App\Services\CheckoutService` — creates order from cart, clears cart items
- `App\Services\CustomerService` — manages guest customer session (find or create)
- `App\Http\Controllers\CheckoutController` — GET /checkout (form with address selection), POST /checkout (creates order + saves address)

## Removed Tables
- `users`, `password_reset_tokens`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` — non-ecommerce Laravel scaffolding deleted from migrations

### Seeders
- **CategorySeeder** — 4 categories: Graphics Cards, Processors, Motherboards, Memory
- **ProductSeeder** — 4 products: RTX 4090, i9-14900K, ROG Maximus Z790 Hero, Trident Z5 RGB
- **CustomerAddressSeeder** — 3 addresses (Home/Work/Other) for first customer
- 52 product specifications created across all products

Seeder files:
- `database/seeders/CategorySeeder.php`
- `database/seeders/ProductSeeder.php`
- `database/seeders/CustomerAddressSeeder.php`

---

## 2026-07-11 — Auth System + Order Tracking + Payment Fields

### Schema Changes

#### customers — Modified (Auth System) | Migration: `2026_07_11_064617_modify_customers_table.php`
| Column | Type | Notes |
|---|---|---|
| customer_id | bigint | PK |
| first_name | string | Renamed from `name` |
| last_name | string | New |
| email | string | Unique |
| password | string | Hashed, new |
| phone_number | string(20) | Renamed from `phone` |
| profile_picture | string | Nullable, new |
| status | string(20) | "Active"/"Inactive", new |
| email_verified_at | timestamp | Nullable, new |
| last_login | timestamp | Nullable, new |
| remember_token | string | New |
| timestamps | | |

**Removed columns:** `name`, `address`

#### orders — Modified (Payment Fields) | Migration: `2026_07_11_092926_add_payment_fields_to_orders_table.php`
| Column | Type | Notes |
|---|---|---|
| order_id | bigint | PK |
| customer_id | bigint | FK → customers |
| order_number | string | Unique (ORD-XXXXX) |
| status | string | Fulfillment status: pending→processing→shipped→delivered (Admin-owned) |
| payment_status | string | Default "pending", "pending"→"paid" (ECommerce-owned) |
| payment_method | string | "visa", "mastercard", "gcash" |
| paid_at | timestamp | Set at order creation (payment processed time) |
| subtotal | decimal(10,2) | |
| tax | decimal(10,2) | |
| grand_total | decimal(10,2) | |
| shipping_name | string | |
| shipping_email | string | |
| shipping_phone | string(20) | Nullable |
| shipping_address | text | |
| notes | text | Nullable |
| timestamps | | |

#### customer_addresses — Modified (Removed Recipient/Phone) | Migration: `2026_07_11_080433_remove_recipient_phone_from_customer_addresses_table.php`
**Dropped columns:** `recipient_name`, `phone_number`

#### sessions — New (Auth) | Migration: `2026_07_11_065100_create_sessions_table.php`
Standard Laravel sessions table (database driver).

### New Tables

#### order_tracking — ECommerce Tracking | Migration: `2026_07_11_191706_create_order_tracking_table.php`
| Column | Type | Notes |
|---|---|---|
| tracking_id | bigint | PK |
| order_id | bigint | FK → orders (cascade) |
| tracking_number | string | UNIQUE, auto-generated TRS-###-### |
| order_status | string | Matches order status constants |
| courier_name | string | Default "ShopEase Express" |
| shipped_from | string | Default "Bulacan, Philippines" |
| estimated_delivery_date | string | "5-10 business days" |
| last_updated | timestamp | Nullable |
| sync_status | string | "synced", "pending", "failed" |
| timestamps | | |

### Models
- `App\Models\Customer` — implements `Authenticatable`, uses Laravel Auth, `BelongsToMany` removed, added `hasMany(Order)`, `hasMany(Cart)`, `hasMany(CustomerAddress)`
- `App\Models\Order` — added `payment_status`, `payment_method`, `paid_at` fillable; relationship `hasOne(OrderTracking)`
- `App\Models\OrderTracking` — `belongsTo(Order)`, status constants (PENDING, PROCESSING, SHIPPED, DELIVERED, CANCELLED)
- `App\Models\CustomerAddress` — removed `recipient_name`, `phone_number` from fillable

### Repositories
- `App\Repositories\CustomerRepository` — added `findByEmail()`, `createRegistered()`, `updateLastLogin()`
- `App\Repositories\OrderRepository` — added `findWithItems()`, `findByOrderNumber()`, `findByOrderNumberAndCustomer()`, `find()`
- `App\Repositories\CartRepository` — unchanged (getOrCreate, addItem, updateQuantity, removeItem, clearCart)

### Services
- `App\Services\CustomerService` — added `authenticate()`, `register()`, `logout()` using `Auth::facade`; removed `getGuestCustomer()`
- `App\Services\AddressService` — new: `getAddressesForCustomer()`, `findById()`
- `App\Services\PaymentService` — new: `processPayment()` creates order + tracking record
- `App\Services\TrackingService` — new: `findByOrderNumberForCustomer()`, `buildTimeline()`
- `App\Services\Admin\DashboardService` — new: `getStats()`, `getRecentOrders()`, `getRevenueOverview()`, `getRevenueByCategory()`, `getLowStockProducts()`

### Seeders
- `CustomerAddressSeeder` — updated to match new schema (removed recipient_name, phone_number)
- `DatabaseSeeder` — updated to remove `User` class reference

### Auth Configuration
- `config/auth.php` — model set to `App\Models\Customer::class`
- `bootstrap/app.php` — removed `User` class references

### Removed Migrations
- `users`, `password_reset_tokens`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` — all deleted

### Notes
- Session driver is `database` (uses `sessions` table)
- `payment_status` stays `pending` until admin manually sets to `paid` (admin UI not yet built)
- `paid_at` is set at order creation time, but "Payment Confirmed" timeline step only appears when admin sets `payment_status=paid`
- Tracking number format: `TRS-XXX-XXX` (X = random alphanumeric)
- Two separate status fields on orders: `status` (fulfillment, Admin-owned) and `payment_status` (payment, ECommerce-owned)

## Pending Decisions

