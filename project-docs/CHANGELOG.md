# Changelog

All changes are timestamped and grouped by version/session.

---

## 2026-07-19

### Customer Header, Category Filter, and Voucher UI Updates
- Connected the customer top header announcement to the admin promo banner table so banner text updates in admin appear on customer pages automatically
- Enforced a single active promo banner in admin by upserting the latest banner and removing older duplicates
- Updated the shop category filter so clicking a category in the header now returns the matching products correctly from the database
- Fixed voucher apply/remove behavior on cart, checkout, payment, and success pages so the voucher state updates in place without page refresh
- Hardened voucher UI helpers to avoid `classList` null errors when the applied or form container is missing

## 2026-07-08

### 10:00 AM — Project Setup & Customer Pages
- Created Laravel project and configured Tailwind CSS with Vite
- Built customer-facing pages under `pages/customer/`:
  - **Cart** — `pages/customer/cart/cart.blade.php` with cart-items-list, order-summary, voucher-card, checkout-stepper, cart-scripts
  - **Checkout** — `pages/customer/checkout/checkout.blade.php` with checkout-details, order-summary, checkout-scripts
  - **Payment** — `pages/customer/payment/payment.blade.php` with payment-details, order-summary, payment-scripts
  - **Success** — `pages/customer/success/success.blade.php` with order-confirmed, order-summary, success-scripts
  - **Order Tracking** — `pages/customer/order-tracking/tracking.blade.php` with order-status-banner, timeline, shipment-items, shipment-meta, track-another-order, support-shortcuts, chat-button, tracking-scripts
- Created shared `checkout-stepper` component (used across cart, checkout, payment, success)
- Created `layouts/app.blade.php` (customer layout with header)

### 2:00 PM — Header Component
- Built `components/header/header.blade.php` with announcement bar, mega menu, search, cart icon, account icon
- Built `components/header/announcement-bar.blade.php` with promo message and Admin Portal link
- Built `components/header/search-card.blade.php` with search suggestions

### 4:00 PM — Admin Dashboard (Initial)
- Created `layouts/admin.blade.php` (admin layout, no customer header)
- Built initial dashboard with sidebar, topbar, stat cards, revenue chart, recent orders, low stocks
- Registered admin routes under `/admin/*` prefix
- Set up route names for all customer pages (cart, checkout, payment, success, tracking)
- Fixed breadcrumbs across checkout, payment, success pages
- Wired button navigation: Cart → Checkout → Payment → Success → Tracking

## 2026-07-09

### 11:00 AM — Admin Dashboard Refactoring
- Extracted dashboard into 8 reusable components under `pages/admin/dashboard/components/`
- Dashboard page reduced from 504 to 63 lines
- Components: sidebar, topbar, export-toast, stat-cards, revenue-section, recent-orders, low-stocks, dashboard-scripts

### 11:15 AM — Orders Page Created
- Created `pages/admin/orders/` with full orders list
- Extracted components: orders-table, orders-toolbar, orders-pagination, order-details-modal, orders-scripts
- Added client-side filtering (search by name, filter by status, filter by date range)
- Added print button with print CSS (hides sidebar/topbar, prints only table)
- Added order details slide-over modal (click any row to view)
- Clicking row opens modal with customer info, order items, totals

### 11:30 AM — Sidebar Enhanced
- Made collapsible via toggle button on desktop (persists in localStorage)
- Made responsive — off-canvas overlay on mobile with hamburger toggle
- Moved from `pages/admin/dashboard/components/` to `components/admin/sidebar/`
- Nav links: Dashboard, Product, Inventory (Orders removed — only via "View all >")
- Added `signOut()` function directly in sidebar component (self-contained)

### 11:45 AM — Topbar & Notifications
- Notification bell shows only new/unread notifications in dropdown
- "View all notifications" opens a full slide-over panel with New + Earlier sections
- Notification data reflects dashboard info (low stock, new orders, sync status, revenue milestones)
- Notification toggle JS moved into topbar itself (self-contained)
- Created notification-icon component for reusable SVG icons
- Created notifications-panel component for the "View all" slide-over
- 10 sample notifications (3 new, 7 earlier)

### 12:00 PM — Responsive Fixes
- Sidebar: off-canvas overlay on mobile, collapsible on desktop (`lg+`)
- Tables: wrapped in `overflow-x-auto` for horizontal scroll
- Order details modal: full width on mobile (`max-lg:max-w-full`)
- Grid breakpoints adjusted (`lg` → `xl` for 3-column layouts)
- Content padding: `p-4` mobile, `p-6` desktop
- Stat cards: `lg:grid-cols-4` → `xl:grid-cols-4`
- Topbar: reduced gap on mobile

### 12:15 PM — Dummy Pages Created
Placeholder pages so navigation works end-to-end:

| Route | Name | View Path | Purpose |
|---|---|---|---|
| `/shop` | `products.index` | `pages/customer/shop/index` | "Continue Shopping" button |
| `/account` | `account` | `pages/customer/account/index` | Header account icon |
| `/admin/products` | `admin.products` | `pages/admin/products/index` | Sidebar "Product" link |
| `/admin/inventory` | `admin.inventory` | `pages/admin/inventory/index` | Sidebar "Inventory" link |

### 12:20 PM — Route Changes

---

## 2026-07-09 (Session 2)

### 7:20 PM — Products Database
- Created 4 migrations: categories, products, product_images, product_specifications
- Ran `migrate:fresh` (dropped pre-existing carts, cart_items, wishlists tables)
- All tables have proper FKs with cascade delete
- Updated DATABASE.md with full schema docs
- Read shop UI reference and designed schema around it (brand, badge, rating, highlight specs, etc.)

### 7:40 PM — Product Models & Seeders
- Created models: Category, Product, ProductImage, ProductSpecification (with relationships)
- Created seeders: CategorySeeder (4 categories), ProductSeeder (4 products with 52 specifications)
- Ran `db:seed` successfully — database has 4 categories, 4 products, 52 specs

### 8:00 PM — Shop Page Live
- Updated `/shop` route to fetch products from DB and pass to view
- Built responsive product grid (brand, name, rating, price, sale price, badge)
- Product cards link to detail page at `/shop/{slug}`
- Created product detail page with:
  - Left: Product image with badge overlay
  - Right: Brand, name, rating, price, "At a Glance" spec cards (2×3 grid), quantity selector, add to cart, wishlist, features
  - Bottom: Full Specifications tab with grouped spec tables in 2-column grid
  - Placeholder tabs for Compatibility and Reviews

### 8:30 PM — Cleanup & Cart Module
- Removed 9 non-ecommerce files (users/cache/jobs migrations, User model, UserFactory, console routes, placeholder tests)
- Updated bootstrap/app.php, config/auth.php, DatabaseSeeder.php to remove dead references
- Created Customer, Cart, CartItem models + migrations
- Created CartService with methods: getOrCreateCart, addItem, updateQuantity, removeItem, getSummary
- Created CartController with dependency-injected CartService
- Guest customers auto-created and tracked via session (guest_customer_id)
- Cart views converted from static data to DB-driven (cart items, order summary from $cart/$summary)
- Quantity stepper debounces PATCH form submit; remove button submits DELETE
- Header cart link now uses route('cart')
- DATABASE.md updated with all new tables and removed tables

### 9:00 PM — Cart Refactored to Single-Responsibility OOP
- Created `App\DTOs\CartSummaryDTO` — typed immutable object replacing loose array
- Created `App\Repositories\CartRepository` — all cart DB queries moved out of service
- Created `App\Repositories\CustomerRepository` — all customer DB queries moved out of service
- Refactored `CartService` to depend on repositories + return DTO instead of array
- Updated views to use DTO property access (`$summary->subtotal`)

### 9:30 PM — Checkout Page with Order Creation
- Created `orders` migration (order_id PK, customer_id FK, order_number, status, subtotal, tax, grand_total, shipping fields, timestamps)
- Created `order_items` migration (order_item_id PK, order_id FK, product_id FK, quantity, unit_price, subtotal)
- Created `Order`, `OrderItem` models with relationships
- Created `App\DTOs\CheckoutDataDTO` — typed DTO for checkout form data
- Created `App\Repositories\OrderRepository` — DB queries for orders + items
- Created `App\Services\CheckoutService` — creates an order from cart items, then clears the cart
- Created `App\Http\Controllers\CheckoutController` — GET (form) + POST (store)
- Updated checkout views: form with validation, order summary uses $summary DTO
- Checkout stepper now highlights "Checkout" as active
- Added `orders.track` alias for `/track` (reuses tracking page)
- Removed duplicate root route — `/` now redirects to `/tracking`
- All hardcoded JS redirects (`/checkout`, `/payment`, `/success`) replaced with `{{ route('...') }}`

---

## 2026-07-10

### 9:45 PM — Customer Addresses Table & Seeder
- Created `customer_addresses` migration (`address_id` PK, `customer_id` FK, `address_type` (Home/Work/Other), `recipient_name`, `phone_number`, `street`, `barangay`, `city`, `province`, `postal_code`, `country`, `is_default`)
- Created `CustomerAddress` model with `belongsTo(Customer)` relationship
- Created `CustomerAddressSeeder` with 3 sample addresses (Home/Work/Other)
- Created dummy preview page at `/dummy/addresses` for testing address cards

### 10:00 PM — Address UX Redesign (Checkout Modal Flow)
- Replaced always-visible address fields with modal-based flow
- New flow: "Add Address" button (dashed) → modal overlay with all fields → "Use This Address" → modal closes, fields populate in editable form
- Saved address cards auto-fill fields + phone on click
- Default address auto-selects on page load (pre-fills all fields including phone)
- Added toast notifications via `toastContainer` in layout + `toastNotify()` function
- Added `#toastContainer` to `layouts/app.blade.php`

### 10:15 PM — Empty-Cart Guards
- Added empty cart check to `CheckoutController@index` (redirects to cart with error)
- Hid "Proceed to Checkout" button in cart order-summary when cart is empty
- Existing guard in `CheckoutController@store` was already in place

### 10:30 PM — OOP Refactoring: Extract CustomerService
- Moved `getGuestCustomer()` from `CartService` to new `App\Services\CustomerService`
- `CartService` now only depends on `CartRepository` (single responsibility)
- Updated `CartController`, `CheckoutController`, `web.php` dummy route to use `CustomerService`
- Added missing relationships to `Customer` model: `addresses()`, `orders()`
- Removed unused `CustomerAddress` import from `CheckoutController`

### 10:45 PM — Address Auto-Save on Order
- After order creation in `CheckoutController@store`, address is saved to `customer_addresses`
- Duplicate check: if exact same address exists, skips insert
- First saved address becomes default (`is_default = true`)
- Fixed `address_type` column: changed from `enum('shipping', 'billing')` to `string(20)` to support "Home", "Work", "Other"
- Updated `CustomerAddressSeeder` to use matching type values

---

## 2026-07-11

### 6:30 AM — Login/Register Pages & Auth System
- Added login, register, forgot-password Blade files to `pages/customer/auth/`
- Refactored auth pages into components (cards/, shared/ subfolders)
- Created `layouts/guest.blade.php` — minimal layout for auth pages with ShopEase branding header
- Created `App\Http\Controllers\Auth\LoginController` — showLoginForm(), login(), logout()
- Created `App\Http\Controllers\Auth\RegisterController` — showRegistrationForm(), register()
- Updated `Customer` model: implements `Authenticatable`, uses Laravel auth, hashed password casting
- Updated `customers` table schema: added `first_name`, `last_name`, `password`, `phone_number`, `profile_picture`, `status` (Active/Inactive), `email_verified_at`, `last_login`; removed `name`, `phone`, `address`
- Updated `App\Repositories\CustomerRepository` — added findByEmail(), createRegistered(), updateLastLogin()
- Updated `App\Services\CustomerService` — added authenticate(), register(), logout() using Laravel Auth
- Recreated `sessions` migration (was deleted in earlier cleanup)
- Configured `config/auth.php` to use `Customer` model instead of non-existent `User`
- All customer pages (cart, checkout, payment, success, tracking) now require login via `auth` middleware
- Updated `CartController` and `CheckoutController` to use `Auth::user()` instead of guest session
- Guest customer flow (`getGuestCustomer()`) replaced with authenticated user for protected pages

### 7:15 AM — Removed Guest Customer Flow
- Deleted `getGuestCustomer()` from `CustomerService`
- Deleted `createGuest()` from `CustomerRepository`
- Removed all existing guest customer records from `customers` table
- All users must now register and login before accessing cart, checkout, etc.
- `CartController` and `CheckoutController` use `Auth::user()` exclusively

### 11:00 PM — Organized Dummy Pages
- Moved shop pages (`/shop`, `/shop/{slug}`) from `pages/customer/shop/` to `pages/dummy/shop/`
- Moved account page from `pages/customer/account/` to `pages/dummy/account.blade.php`
- Updated routes: `/shop` → `/dummy/shop`, `/account` → `/dummy/account`, `/` redirects to `/dummy/shop`
- Route names unchanged (`products.index`, `products.show`, `account`) so all `route()` calls still work
- Empty `pages/customer/shop/` and `pages/customer/account/` directories removed

### 4:00 PM — Address Modal Cleanup (Live Edit + Removed Recipient/Phone)
- Removed `recipient_name` and `phone_number` fields from the address modal (address now only asks for address data — contact info is handled in checkout details form)
- Dropped `recipient_name` and `phone_number` columns from `customer_addresses` table via new migration
- Removed recipient name and phone from address card display and data attributes
- Added `updateAddressCard()` JS function — on edit, card DOM updates in real-time without page refresh
- Changed card lookup strategy: now finds card by `address_id` instead of street+barangay, so even if street/barangay change during edit, the right card is still found
- Combined Address Type and Street into a 2-column grid row (address type was alone and looked too short)
- Updated all JS functions (`fillAddressFields`, `openAddressModal`, `useAddressFromModal`, `addAddressCard`, `updateAddressCard`, card click handlers) to remove recipient/phone params
- Updated `CheckoutController@saveAddress` validation and create/update — removed `recipient_name` and `phone_number`
- Updated `CheckoutController@store` auto-save address logic — removed those fields
- Cleaned original migration (`2026_07_10_134110_create_customer_addresses_table.php`) to match new schema for fresh installs
- Updated `CustomerAddress` model fillable and seeder accordingly
- **Note:** `POST /checkout` (store) is still a standard form POST (redirect), not a REST API endpoint. Only `POST /checkout/address` returns JSON (AJAX). REST API for checkout will be done per-page later.

### 5:00 PM — Checkout Components Extracted
- Extracted checkout into reusable Blade components: `contact-fields`, `address-section`, `order-notes`
- Created `App\Services\AddressService` + `App\Repositories\CustomerAddressRepository` — OOP separation from CheckoutController
- `CheckoutController` now injects `AddressService` and `CustomerService` instead of raw repository calls
- `checkout-details` view reduced; components render via `@include`
- Updated NOTES.md with OOP architecture docs

### 6:30 PM — Payment Refactored + Success Controller + Order Tracking
- Extracted `App\Services\PaymentService` — `processPayment()` creates order with `payment_status=pending`, `paid_at=now()`, copies cart items, clears cart, creates order_tracking record
- Created `App\DTOs\PaymentDataDTO` — typed DTO for validated payment + shipping data
- `PaymentController` now injects only `PaymentService` + `CartService` (removed `CheckoutService` dependency)
- `clearCart()` removed from `CheckoutService`, moved to `PaymentService`
- Created `App\Http\Controllers\SuccessController` — reads `order_id` from session, calls `OrderRepository::findWithItems()`, replaces route closure
- Created `order_tracking` table migration — `tracking_id` PK, `order_id` FK, `tracking_number` UNIQUE, `order_status`, `courier_name`, `shipped_from`, `estimated_delivery_date`, `last_updated`, `sync_status`
- Created `App\Models\OrderTracking` — relationships to Order, status constants
- Created `App\Services\TrackingService` — `findByOrderNumberForCustomer()` (customer-scoped), `buildTimeline()` (from order_tracking or fallback to order.status)
- Created `App\Http\Controllers\TrackingController` — `index()` pre-loads from session scoped to customer, `track()` POST search with regex `OID-####-####`
- Auto-generated tracking record at order creation: `TRS-###-###`, ShopEase Express, Bulacan, 5-10 day delivery

### 9:00 PM — Admin Dashboard (Live Data)
- Created `App\Http\Controllers\Admin\DashboardController` — `index()` + `print()`
- Created `App\Services\Admin\DashboardService` — `getStats()` (total revenue from paid orders, orders this month, low stock count), `getRecentOrders(limit=2)`, `getRevenueOverview()` (6 months), `getRevenueByCategory()` (sorted highest), `getLowStockProducts()` (stock ≤ 5, sorted ascending)
- Dashboard components updated to render live DB data instead of static
- Revenue chart: SVG circles with `data-tooltip` + JS for styled tooltip (dark bg, cyan accent, dynamically positioned)
- Print report: `print.blade.php` — full printable page with stat cards, SVG chart, category bars, low stocks table, recent orders table. Opens in new tab via "Export Report" button.
- Timezone changed to `Asia/Kuala_Lumpur` in `config/app.php`
- Products/categories joined on correct PKs (`categories.id`/`products.id`, not `category_id`)
- NOTES.md updated with all modules through admin dashboard
