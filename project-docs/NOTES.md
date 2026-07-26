# Project Notes — ECommerce Module

## Overview
These notes document every file, function, class, and decision related to the ECommerce module for defense/review purposes. Organized by layer: **Migrations → Models → Architecture (DTOs, Repositories, Services) → Controllers → Views → Routes → Flows**

---

## Migrations

### `2026_07_09_115225_create_customers_table.php`
Creates the `customers` table. Supports full auth (Authenticatable contract). Originally had guest-only columns (name, phone, address) — later consolidated to final schema directly in this migration.

| Column | Type | Notes |
|---|---|---|
| customer_id | bigint | PK (not default `id`) |
| first_name | string(50) | |
| last_name | string(50) | |
| email | string(100) | Unique |
| password | string(255) | Hashed |
| phone_number | string(20) | Nullable |
| profile_picture | string(255) | Nullable |
| status | enum(Active,Inactive) | Default Inactive |
| email_verified_at | timestamp | Nullable |
| last_login | datetime | Nullable |
| timestamps | | |

### `2026_07_09_115226_create_carts_table.php`
One cart per customer. The cart is the container for cart items before checkout.

| Column | Type | Notes |
|---|---|---|
| cart_id | bigint | PK |
| customer_id | bigint | FK → customers (cascade delete) |
| timestamps | | |

### `2026_07_09_115227_create_cart_items_table.php`
Each row is one product line in a cart. Stores a snapshot of the unit price at add time so price changes in the Product table don't affect existing cart items.

| Column | Type | Notes |
|---|---|---|
| cart_item_id | bigint | PK |
| cart_id | bigint | FK → carts (cascade) |
| product_id | bigint | FK → products (cascade) |
| quantity | integer | |
| unit_price | decimal(10,2) | Price snapshot at add time |
| subtotal | decimal(10,2) | quantity × unit_price |
| timestamps | | |

### `2026_07_09_112153_create_categories_table.php` (Other module)
### `2026_07_09_112154_create_products_table.php` (Other module)
### `2026_07_09_112155_create_product_images_table.php` (Other module)
### `2026_07_09_112156_create_product_specifications_table.php` (Other module)
These belong to Procurement/Product Master (OTHER MODULE) — we only read from them. Products, categories, images, and specifications are managed upstream.

### `2026_07_10_132723_create_orders_table.php`
Created when user completes checkout. Stores the full order with a snapshot of shipping info, payment status, and fulfillment tracking.

| Column | Type | Notes |
|---|---|---|
| order_id | bigint | PK |
| customer_id | bigint | FK → customers |
| order_number | string | Unique (OID-####-#### format) |
| status | string | Fulfillment: pending/processing/shipped/in_transit/out_for_delivery/delivered/cancelled |
| payment_status | string | Default "pending", set to "paid" by admin |
| payment_method | string | visa, mastercard, or gcash |
| paid_at | timestamp | Set at order creation (payment processed time) |
| subtotal | decimal(10,2) | Sum of order_items subtotals |
| grand_total | decimal(10,2) | subtotal + shipping - discount |
| shipping_name | string | Recipient name from checkout |
| shipping_email | string | Recipient email |
| shipping_phone | string(20) | Nullable |
| shipping_address | text | Full address string (street, barangay, city, etc.) |
| notes | text | Nullable — order notes |
| timestamps | | |

### `2026_07_10_132724_create_order_items_table.php`
Copies of cart items frozen at order time. Even if products/prices change later, the order preserves what was purchased.

| Column | Type | Notes |
|---|---|---|
| order_item_id | bigint | PK |
| order_id | bigint | FK → orders (cascade) |
| product_id | bigint | FK → products (cascade) |
| quantity | integer | |
| unit_price | decimal(10,2) | Price snapshot at order time |
| subtotal | decimal(10,2) | quantity × unit_price |
| timestamps | | |

### `2026_07_10_134110_create_customer_addresses_table.php`
Saved address cards shown on checkout. A customer can have multiple addresses (Home, Work, Other). Only one can be marked `is_default`. Originally had `recipient_name` and `phone_number` columns, but those were dropped later since contact info is entered in the checkout form, making them redundant here.

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

### `2026_07_11_065100_create_sessions_table.php`
Standard Laravel sessions table. Required because we switched session driver to `database` so sessions persist even without file-based storage.

### `2026_07_11_191706_create_order_tracking_table.php`
Creates the `order_tracking` table. Stores shipment tracking records per order. Created when Admin updates payment to received. Uses `order_id` FK → orders.

| Column | Type | Notes |
|---|---|---|
| tracking_id | bigint | PK |
| order_id | bigint unsigned | FK → orders (cascade) |
| tracking_number | varchar(50) | UNIQUE |
| order_status | string | ENUM-like: Order Placed / Processing / Shipped / In Transit / Out for Delivery / Delivered |
| courier_name | varchar(100) | Default: J&T Express |
| shipped_from | varchar(150) | |
| estimated_delivery_date | date | |
| last_updated | datetime | |
| sync_status | string | ENUM-like: Pending / Synced / Failed |
| timestamps | | created_at, updated_at |

---

## Models

### `App\Models\Customer`
- **Primary key:** `customer_id` (not the default `id`)
- **Implements:** `Authenticatable` (Laravel's auth contract) — enables `Auth::user()`, `Auth::login()`, etc.
- **Hidden:** `password` (never exposed in JSON)
- **Casts:** `password` → `hashed` (auto-hashes on set)
- **Fillable:** `first_name`, `last_name`, `email`, `phone_number`, `password`, `profile_picture`, `status`
- **Relationships:**
  - `hasMany(Cart)` — one customer can have multiple carts (though we only use one active cart)
  - `hasMany(Order)` — one customer can have many orders
  - `hasMany(CustomerAddress)` — one customer can have many saved addresses

### `App\Models\Cart`
- **Primary key:** `cart_id`
- **Fillable:** `customer_id`
- **Relationships:**
  - `belongsTo(Customer)` — each cart belongs to one customer
  - `hasMany(CartItem)` — a cart holds multiple items

### `App\Models\CartItem`
- **Primary key:** `cart_item_id`
- **Route key name:** `cart_item_id` (route-model binding uses `cart_item_id` instead of default `id`)
- **Fillable:** `cart_id`, `product_id`, `quantity`, `unit_price`, `subtotal`
- **Relationships:**
  - `belongsTo(Cart)` — each item belongs to one cart
  - `belongsTo(Product)` — each item references one product from Procurement module

### `App\Models\Order`
- **Primary key:** `order_id`
- **Fillable:** all order columns
- **Relationships:**
  - `belongsTo(Customer)` — each order belongs to one customer
  - `hasMany(OrderItem)` — an order has multiple line items

### `App\Models\OrderItem`
- **Primary key:** `order_item_id`
- **Fillable:** all order_item columns
- **Relationships:**
  - `belongsTo(Order)` — each item belongs to one order
  - `belongsTo(Product)` — references the product snapshot

**Order model additional relationship:**
- `hasOne(OrderTracking)` — an order has one tracking record

### `App\Models\CustomerAddress`
- **Primary key:** `address_id`
- **Fillable:** `customer_id`, `address_type`, `street`, `barangay`, `city`, `province`, `postal_code`, `country`, `is_default`
- **Relationships:**
  - `belongsTo(Customer)` — each address belongs to one customer

### `App\Models\OrderTracking`
- **Table:** `order_tracking`
- **Primary key:** `tracking_id`
- **Status values (inline strings):** `Order Placed`, `Processing`, `Shipped`, `In Transit`, `Out for Delivery`, `Delivered`; sync: `Pending`, `Synced`, `Failed`
- **Casts:** `estimated_delivery_date` → date, `last_updated` → datetime
- **Fillable:** all columns
- **Relationships:**
  - `belongsTo(Order)` — each tracking record belongs to one order

---

## Architecture (OOP — Single Responsibility)

Each layer has one job. The flow: **Controller → Service → Repository → Model**

DTOs sit between Controller and Service — typed immutable objects replacing loose arrays.

### DTOs

#### `App\DTOs\CartSummaryDTO`
A Data Transfer Object — a plain PHP class with typed `readonly` properties. Replaces the old associative array so you always know exactly what type each value is.

| Property | Type | Description |
|---|---|---|
| `itemsCount` | int | Total quantity of all items |
| `subtotal` | float | Sum of all item subtotals |
| `grandTotal` | float | subtotal + shipping - discount |

Usage: `$summary->subtotal` instead of `$summary['subtotal']`.

#### `App\DTOs\PaymentDataDTO`
Typed DTO holding validated payment and shipping data. Created in PaymentController after validation, passed to PaymentService.

| Property | Type | Description |
|---|---|---|
| `paymentMethod` | string | `visa`, `mastercard`, or `gcash` |
| `shippingName` | string | first_name + last_name combined |
| `shippingEmail` | string | Customer email for this order |
| `shippingPhone` | string | Phone number, defaults to empty string |
| `shippingAddress` | string | Full address as single string |
| `notes` | string | Order notes, defaults to empty |

### Repositories

Repositories only talk to the database. No business logic, no calculations.

#### `App\Repositories\CartRepository`
| Method | What it does |
|---|---|
| `findOrCreateByCustomer(customerId)` | Gets existing cart or creates a new one for this customer |
| `loadItems(cart)` | Eager-loads all cart items with their product data (prevents N+1 queries) |
| `findExistingItem(cartId, productId)` | Checks if a product is already in the cart |
| `addItem(cartId, productId, qty, price)` | Inserts a new row into cart_items |
| `updateItem(cartItemId, qty, subtotal)` | Updates quantity and subtotal on an existing cart item |
| `deleteItem(cartItemId)` | Removes a cart item row |

#### `App\Repositories\CustomerRepository`
| Method | What it does |
|---|---|
| `createRegistered(data)` | Creates a new registered customer with name, email, hashed password |
| `updateLastLogin(customer)` | Sets `last_login` to now and saves |

#### `App\Repositories\OrderRepository`
| Method | What it does |
|---|---|
| `create(data)` | Inserts a new order row |
| `addItem(order, data)` | Inserts an order_items row linked to an order |
| `loadItems(order)` | Eager-loads items with product data |
| `findWithItems(orderId)` | Finds an order by ID with items + product relations loaded |
| `findByOrderNumberAndCustomer(orderNumber, customerId)` | Finds an order by order_number scoped to a customer |
| `find(orderId)` | Finds an order by ID with items + tracking loaded |
| `findAllPaginated(filters, perPage)` | Returns paginated orders with customer, items, tracking; supports search (order_number, shipping_name, shipping_email, customer name), status, payment_status, date range filters |
| `update(orderId, data)` | Updates an order and returns fresh with items + tracking |

#### `App\Repositories\CustomerAddressRepository`
| Method | What it does |
|---|---|
| `findExact(customerId, street, barangay, city, province, postal, country)` | Checks if an address with exact same fields already exists for this customer (duplicate detection) |
| `findByCustomerAndId(customerId, addressId)` | Finds a specific address by ID, scoped to the customer (prevents accessing another customer's address) |
| `create(data)` | Inserts a new address row; first address for customer gets `is_default: true` |
| `update(addressId, data)` | Updates an existing address's columns |
| `countByCustomer(customerId)` | Returns count of addresses for this customer |

### Services

Services contain business logic. They never call Eloquent directly — all DB work goes through repositories.

#### `App\Services\CustomerService`
| Method | What it does |
|---|---|
| `authenticate(credentials)` | Calls `Auth::attempt()` with email + password |
| `register(data)` | Calls `CustomerRepository::createRegistered()` then logs the user in |
| `logout()` | Calls `Auth::logout()` and invalidates session |

**Guest methods removed (2026-07-11):** `getGuestCustomer()` and `createGuest()` were deleted. All cart/checkout users must now be authenticated.

#### `App\Services\CartService`
Business logic only. Uses `CartRepository` for DB and returns `CartSummaryDTO` instead of a loose array.

| Method | What it does |
|---|---|
| `getOrCreateCart(customerId)` | Uses CartRepository to find or create a cart, then load its items |
| `addItem(cart, productId, qty)` | Gets product price, checks if item already in cart via repository — if yes increments qty; if no creates new item |
| `updateQuantity(cartItem, qty)` | Ensures min quantity of 1, delegates update to repository |
| `removeItem(cartItem)` | Delegates delete to repository |
| `getSummary(cart)` | Loops items, calculates counts + totals, returns a CartSummaryDTO |

#### `App\Services\AddressService`
| Method | What it does |
|---|---|
| `saveOrUpdate(data)` | If `address_id` present → updates existing via CustomerAddressRepository::update(). If exact match exists → returns existing unchanged. If new → creates via CustomerAddressRepository::create() (first = default). Returns the address with its address_id |
| `saveFromOrder(customerId, dto)` | Called automatically after order creation — builds address data from checkout DTO + customer ID, then delegates to saveOrUpdate() |
| `getAddresses(customerId)` | Returns all addresses for a customer, ordered by `is_default` DESC then `created_at` DESC |

#### `App\Services\TrackingService`
| Method | What it does |
|---|---|
| `findByOrderNumberForCustomer(orderNumber, customerId)` | Finds an order by number scoped to customer |
| `buildTimeline(order)` | Derives timeline steps from `order_tracking` if exists, otherwise from `order.created_at` (Order Placed), `order.paid_at` (Payment Confirmed), and `order.status` (remaining steps) |

#### `App\Services\PaymentService`
| Method | What it does |
|---|---|
| `processPayment(cart, dto)` | Creates Order with `payment_status = pending` + `paid_at = now()` via OrderRepository → copies cart items into order_items → clears cart items → creates OrderTracking record (TRS-###-###, ShopEase Express, Bulacan, 5-10 day delivery) → returns loaded order |

#### `App\Services\Admin\DashboardService`
| Method | What it does |
|---|---|
| `getStats()` | Returns total revenue (paid orders), orders this month count, total orders, low stock count (stock ≤ 5) |
| `getRecentOrders(limit)` | Latest N orders with customer name, item names, total, status |
| `getRevenueOverview()` | Last 6 months monthly revenue sums |
| `getRevenueByCategory()` | Revenue grouped by product category, sorted highest first |
| `getLowStockProducts()` | Products with stock ≤ 5, sorted by stock ascending |

### Controllers

The HTTP layer. Only job is receiving requests and returning responses. Services are injected via constructor.

#### `App\Http\Controllers\CartController`
| Method | Route | What it does |
|---|---|---|
| `index` | `GET /cart` | Gets authenticated customer → gets cart → gets summary DTO → renders view. If customer has no cart, it creates one (empty cart view) |
| `add` | `POST /cart/add` | Validates product_id + quantity → delegates to service → redirects back |
| `updateQuantity` | `PATCH /cart/{cartItem}` | Validates quantity → delegates to service → redirects to cart |
| `remove` | `DELETE /cart/{cartItem}` | Delegates to service → redirects to cart |

#### `App\Http\Controllers\CheckoutController`
| Method | Route | What it does |
|---|---|---|
| `index` | `GET /checkout` | Gets customer → gets cart + summary → loads saved addresses → renders checkout form. Redirects to cart if cart is empty |
| `store` | `POST /checkout` | Validates form (first/last name, email, phone, full address, notes) → saves address via AddressService → stores validated data as `checkout_data` in session → redirects to /payment |
| `saveAddress` | `POST /checkout/address` | JSON-only endpoint. If `address_id` provided → updates existing address. If exact match exists → returns existing. Otherwise → creates new address. Returns `{ address: {...} }`. First saved address becomes default |

#### `App\Http\Controllers\PaymentController`
| Method | Route | What it does |
|---|---|---|
| `index` | `GET /payment` | Checks `checkout_data` session exists → gets cart + summary → renders payment form. Redirects to checkout if no data, to cart if empty |
| `process` | `POST /payment` | Validates only the selected payment method's fields → creates PaymentDataDTO → calls PaymentService::processPayment → forgets checkout_data + stores order_id in session → redirects to /success |

**Note:** Validation rules are built conditionally per payment method (no `required_if` — only the selected method's rules are added to `$rules`).

#### `App\Http\Controllers\TrackingController`
| Method | Route | What it does |
|---|---|---|
| `index` | `GET /tracking` | If `order_id` in session → loads order + builds timeline → renders tracking page with data. Otherwise renders blank search form |
| `track` | `POST /track` | Validates `order_number` → looks up via TrackingService → if found, renders page with order + timeline; if not found, redirects back with error |

#### `App\Http\Controllers\Admin\DashboardController`
| Method | Route | What it does |
|---|---|---|
| `index` | `GET /admin/dashboard` | Aggregates stats (total revenue, orders this month, low stock count), recent 2 orders, 6-month revenue chart data, revenue by category, low stock products via DashboardService → renders dashboard view |
| `print` | `GET /admin/dashboard/print` | Same data in print-friendly layout with chart, stat cards, tables — open via Export Report button |

#### `App\Http\Controllers\SuccessController`
| Method | Route | What it does |
|---|---|---|
| `index` | `GET /success` | Gets `order_id` from session → calls OrderRepository::findWithItems → renders success view. Redirects to cart if no order_id in session |

#### `App\Http\Controllers\Auth\LoginController`
| Method | Route | What it does |
|---|---|---|
| `showLoginForm` | `GET /login` | If already authenticated → redirects to /cart. Otherwise renders login page with `guest` layout |
| `login` | `POST /login` | Validates email + password → calls `Auth::attempt()` → if fails, back with error. If succeeds, regenerates session + redirects to intended page (default /cart) |
| `logout` | `POST /logout` | Calls `Auth::logout()`, invalidates session, redirects to /login |

#### `App\Http\Controllers\Auth\RegisterController`
| Method | Route | What it does |
|---|---|---|
| `showRegistrationForm` | `GET /register` | If already authenticated → redirects to /cart. Otherwise renders register page |
| `register` | `POST /register` | Validates first_name, last_name, email, password (confirmed) → calls CustomerService::register (creates + logs in) → redirects to /cart |

---

## Views

### Layouts

`layouts/app.blade.php` — Main customer layout. Extends a base shell. Includes the header (with mega menu, search, cart icon, account icon), a `toastContainer` div for toast notifications, and a `@yield('content')` section. Used by cart, checkout, payment, success, tracking pages.

`layouts/guest.blade.php` — Minimal layout for auth pages (login, register, forgot-password). Has ShopEase branding header + footer. No navigation, no mega menu.

`layouts/admin.blade.php` — Admin layout with sidebar, topbar, and content area.

### Auth Pages

#### `login.blade.php` (pages/customer/auth/)
Uses `guest` layout. Includes `login-card` component. Standard form (`method="POST"` with `@csrf`) — no JS fetch. Uses `@error` directives for per-field validation errors. Has link to register page and forgot-password.

**Included components:**
- `components/cards/login-card.blade.php` — The actual form card: email input, password input, remember me checkbox, submit button, links to register + forgot password
- `components/shared/auth-branding.blade.php` — ShopEase logo and tagline
- `components/shared/field-error.blade.php` — Reusable `@error` wrapper with red border + error text
- `components/shared/social-divider.blade.php` — "OR" divider for social logins
- `components/shared/google-icon.blade.php` — SVG Google icon (placeholder)

#### `register.blade.php` (pages/customer/auth/)
Same structure as login. Includes `register-card` component with: first_name, last_name, email, password, password_confirmation fields.

#### `forgot-password.blade.php` (pages/customer/auth/)
Static forgot-password form. POST route exists but returns a "not implemented" message.

### Cart Pages

#### `cart.blade.php` (pages/customer/cart/)
Main cart page. Extends `layouts.app`. Two-column layout: left has checkout stepper + cart items list, right has voucher card + order summary.

**Included components:**
- `components/checkout-stepper.blade.php` — 4-step progress bar: Cart (active/green) → Checkout → Payment → Success. Each step is "done" (green check), "active" (blue), or "upcoming" (gray) based on `$activeStep`
- `components/cart-items-list.blade.php` — Loops `$cart->items` and renders each row: product image, brand/category tags, name, SKU, stock status badge (`In Stock` = green, `Low Stock` = yellow, `Out of Stock` = red), quantity stepper with +/- buttons and hidden input, remove button (submits DELETE form), line total. Has empty state with "Continue Shopping" button
- `components/order-summary.blade.php` — Sidebar card: items count, subtotal, shipping, grand total. "Proceed to Checkout" button links to route('checkout'). Hidden when cart is empty
- `components/voucher-card.blade.php` — Input + Apply button for coupon codes. Placeholder only — not wired to backend
- `components/cart-scripts.blade.php` — JS for quantity stepper. On +/- click: updates hidden input + display, debounces 600ms, submits PATCH form via HTMX-style form submit. Also `applyVoucher()` stub

### Checkout Pages

#### `checkout.blade.php` (pages/customer/checkout/)
Extends `layouts.app`. Two-column layout: left has checkout stepper (step 2 active) + checkout details form, right has order summary.

**Included components:**
- `components/checkout-details.blade.php` — The main form, reduced from ~460 to ~150 lines after extraction. Contains inline JS + @includes for sub-components
- `components/contact-fields.blade.php` — Extracted form fields: first_name, last_name, email, shipping_phone. Each has label + input with old value + @error directive
- `components/address-section.blade.php` — Saved address cards (loops `$addresses`), hidden address fields container, "Use Another Address" dashed button card, and the full address modal overlay (fixed backdrop, form with address_type dropdown, street, barangay, city, province, postal_code, country, "Use This Address" button)
- `components/order-notes.blade.php` — Notes textarea + "Continue to Payment" submit button
- `components/order-summary.blade.php` — Same as cart sidebar but for checkout
- `components/checkout-scripts.blade.php` — Additional checkout JS if needed

### Payment Page
`payment.blade.php` (pages/customer/payment/) — Extends `layouts.app`. Two-column layout: left has checkout stepper (step 3 active) + payment form, right has order summary from cart.

**Included components:**
- `components/checkout-stepper.blade.php` — Shared from cart, step 3 (Payment) active
- `components/payment-details.blade.php` — Payment method tabs (Visa/Mastercard/GCash) with dynamic field visibility, place order button
- `components/order-summary.blade.php` — Items count, subtotal, grand total from `$summary`
- `components/payment-scripts.blade.php` — JS for payment method switching, client-side validation, form submit, `payment_error` toast display

### Success Page
`success.blade.php` (pages/customer/success/) — Extends `layouts.app`. Two-column layout: left has order confirmation card, right has order summary. Data sourced from session-stored `order_id` via `OrderRepository::findWithItems()`.

**Included components:**
- `components/order-confirmed.blade.php` — Order number, success icon, shipping details, order items list
- `components/order-summary.blade.php` — Items count, subtotal, grand total from `$order`

### Order Tracking Page
`tracking.blade.php` (pages/customer/order-tracking/) — Extends `layouts.app`. If `$order` is set in controller, shows status banner, shipment meta, timeline, and items; otherwise shows only search form.

**Included components:**
- `components/track-another-order.blade.php` — Search form, `POST /track`, validates `order_number`
- `components/order-status-banner.blade.php` — Order number + status badge with dynamic color mapping
- `components/shipment-meta.blade.php` — Carrier, tracking #, shipped from, est delivery from `$order->tracking`
- `components/timeline.blade.php` — Collapsed/expanded timeline from `$timelineSteps` (derived from `order_tracking` or fallback to `order.status`)
- `components/shipment-items.blade.php` — Loops `$order->items` with real product names/qty/prices
- `components/support-shortcuts.blade.php` — Call + email support cards
- `components/chat-button.blade.php` — Floating chat button stub
- `components/tracking-scripts.blade.php` — Timeline toggle + clipboard copy

### Dummy Pages
- `shop/index.blade.php` — Product grid: brand, name, rating, price, sale price, badge overlay. Fetches from DB.
- `shop/show.blade.php` — Product detail: image, brand, name, rating, price, spec cards, quantity selector, Add to Cart (POST /cart/add), specs tabs. "Add to Cart" redirects back to product page.
- `account.blade.php` — Placeholder account page.
- `address-preview.blade.php` — Preview of saved address cards for testing.

---

## JavaScript (checkout-details.blade.php)

All JS is inline in `checkout-details.blade.php`. No separate JS file.

### Functions

#### `fillAddressFields(street, barangay, city, province, postal, country, type)`
Sets the hidden form field values from address card data or modal response. Also sets the `address_type` select value.

**Note:** `phone_number` and `recipient_name` parameters were removed — those are captured by the checkout form (shipping_phone, first_name+last_name), not the address.

#### `openAddressModal(clear, addr)`
Three modes:
1. `clear=true, addr=object`: Pre-fills modal with address data for editing (from Edit button click)
2. `clear=true, addr=undefined/empty`: Clears all modal fields for new address entry
3. `clear=false`: Copies current hidden form field values into modal (used if user clicks "Use Another Address" after already selecting one)

#### `closeAddressModal()`
Hides modal, restores body scroll.

#### `esc(str)`
Escapes HTML entities in strings used in dynamic card creation (prevents XSS in `addAddressCard` innerHTML).

#### `updateAddressCard(card, addr)`
Called after successful AJAX save when editing an existing address. Updates the card's data attributes and visible text in-place so changes appear without a page refresh. Steps:
1. Updates all `data-*` attributes on the `<label>` element
2. Sets the radio input value to the server-returned `address_id`
3. Updates the visible type badge text
4. Updates street/barangay and city/province/postal text lines

#### `useAddressFromModal()`
Main function called when user clicks "Use This Address" in the modal. Steps:
1. Validates street + barangay are not empty (shows toast error if missing)
2. Builds payload object with: `address_id` (null for new, existing ID for edit), `address_type`, `street`, `barangay`, `city`, `province`, `postal_code`, `country`
3. Sends `POST /checkout/address` with JSON body
4. On success: calls `fillAddressFields()` to populate hidden form → closes modal → if card with matching `address_id` exists, calls `updateAddressCard()` + `.click()`; otherwise calls `addAddressCard()` with the new address
5. Shows success/error toast

#### `addAddressCard(addr)`
Creates a new address card `<label>` element dynamically and prepends it to `#savedAddressCards`. Sets all data attributes, radio input, innerHTML with escaped values. Attaches click + edit listeners. Automatically clicks the new card to select it.

### Event Listeners (setup on page load)

**Address card click:** Selects the card (visual highlight), fills hidden fields via `fillAddressFields()`, shows the address fields container.

**Edit button click (`.edit-address-btn`):** Stops propagation (so card click doesn't fire), opens modal pre-filled with that card's data.

**Modal backdrop click:** Closes modal if user clicks outside the modal card.

**DOMContentLoaded:** If a default address card exists, auto-selects it. If no cards exist at all, opens the modal for the user to add one.

### Toast Notifications

`toastNotify(type, message)` — Creates a colored toast div in `#toastContainer` (fixed to bottom-right in app layout). Types: `success` (green), `error` (red), `info` (blue). Auto-fades after 3 seconds.

---

## Routes (web.php)

### Middleware Groups

| Middleware | Routes | Purpose |
|---|---|---|
| `auth` | cart, checkout, payment, success, tracking, dummy/addresses | All customer pages require login |
| None (guest) | login, register, forgot-password | Auth pages accessible without login |
| None | dummy/shop, dummy/account, dummy/shop/{slug} | Public product browsing |
| None | admin/* | Admin dashboard routes |

### Route Table

| Method | URI | Controller / View | Name |
|---|---|---|---|
| GET | `/` | Redirect → `/dummy/shop` | — |
| GET | `/cart` | `CartController@index` | `cart` |
| POST | `/cart/add` | `CartController@add` | `cart.add` |
| PATCH | `/cart/{cartItem}` | `CartController@updateQuantity` | `cart.update` |
| DELETE | `/cart/{cartItem}` | `CartController@remove` | `cart.remove` |
| GET | `/checkout` | `CheckoutController@index` | `checkout` |
| POST | `/checkout` | `CheckoutController@store` | `checkout.store` |
| POST | `/checkout/address` | `CheckoutController@saveAddress` | `checkout.address.save` |
| GET | `/payment` | `PaymentController@index` | `payment` |
| POST | `/payment` | `PaymentController@process` | `payment.process` |
| GET | `/success` | `SuccessController@index` | `success` |
| GET | `/tracking` | `TrackingController@index` | `tracking` |
| POST | `/track` | `TrackingController@track` | `orders.track` |
| GET | `/login` | `LoginController@showLoginForm` | `login` |
| POST | `/login` | `LoginController@login` | — |
| GET | `/register` | `RegisterController@showRegistrationForm` | `register` |
| POST | `/register` | `RegisterController@register` | — |
| POST | `/logout` | `LoginController@logout` | `logout` |
| GET | `/forgot-password` | `pages.customer.auth.forgot-password` | `forgot.password` |
| POST | `/forgot-password` | Closure (not implemented) | — |
| GET | `/dummy/shop` | Closure (fetches categories + products) | `products.index` |
| GET | `/dummy/shop/{slug}` | Closure (loads product with relations) | `products.show` |
| GET | `/dummy/account` | `pages.dummy.account` | `account` |
| GET | `/dummy/addresses` | Closure (previews saved addresses) | `dummy.addresses` |

---

## Flows

### Cart Flow

1. User visits `/dummy/shop` → clicks a product → lands on product detail page
2. User selects quantity, clicks **Add to Cart** → submits `POST /cart/add`
3. `CartController@add` runs:
   - `Auth::user()` gets the authenticated customer
   - `CartService::getOrCreateCart()` finds or creates Cart for that customer
   - `CartService::addItem()` adds the product or increments qty if already in cart
   - Redirects back to product page with success message
4. User visits `/cart`:
   - `CartController@index` loads cart + summary, renders the page
   - User can change quantity (debounced PATCH submit via quantity stepper) or remove items (DELETE form submit)
   - "Proceed to Checkout" links to route('checkout')
   - If cart is empty: shows empty state with "Continue Shopping" button, order summary hidden

### Checkout Flow

1. User has items in cart → visits `/checkout`
2. `CheckoutController@index` checks cart is not empty → loads saved addresses from `$customer->addresses`
3. If saved addresses exist: cards shown with radio selection. Click a card → fills hidden form fields. Default address auto-selected on page load
4. **Edit a card:** Click the pencil icon on any card → modal opens pre-filled with that address → edit fields → "Use This Address" → AJAX save → card DOM updates instantly → card stays selected
5. **Add new address:** Click "Use Another Address" (dashed button) → modal opens empty → fill in → "Use This Address" → AJAX save → new card created + auto-selected
6. User fills contact fields (first_name, last_name, email, phone) if not already filled
7. User clicks "Continue to Payment" → `POST /checkout`
8. `CheckoutController@store`:
   - Validates all fields (name, email, phone, address, notes)
   - Auto-saves address to `customer_addresses` (skips if duplicate; first becomes default)
   - Stores validated data in session as `checkout_data`
   - Redirects to `/payment` (**no order created yet**)

### Address Save Flow (useAddressFromModal)

1. User fills modal → clicks "Use This Address"
2. JS validates street + barangay required
3. `POST /checkout/address` with JSON payload: `{ address_id, address_type, street, barangay, city, province, postal_code, country }`
4. `CheckoutController@saveAddress`:
   - If `address_id` present: Find existing by customer+id → update columns → return `{ address }`
   - If no `address_id` but exact match exists (street+barangay+city+province+postal+country): Return existing address unchanged
   - Otherwise: Create new address (first one = `is_default: true`) → return `{ address }`
5. JS: `fillAddressFields()` → close modal → if card with matching `address_id` exists: `updateAddressCard()` + click; else: `addAddressCard()` + click
6. Success toast shown

### Auth Flow

1. User visits a protected page (e.g., `/cart`) → not logged in → redirected to `/login`
2. User fills email + password → `POST /login`
3. `LoginController@login`: Validates → `Auth::attempt()` → if fails, return with `@error` → if succeeds, regenerate session → redirect to intended URL (default `/cart`)
4. **Register:** `/register` → fill first_name, last_name, email, password, confirm password → `POST /register`
5. `RegisterController@register`: Validates → `CustomerService::register()` (creates customer with hashed password + logs in) → redirect to `/cart`
6. **Logout:** `POST /logout` → `Auth::logout()` + session invalidate → redirect to `/login`

---

## Payment Flow

1. **Checkout POST** → validates form, saves address, stores `checkout_data` in session. No order created yet.
2. **Payment GET** → loads cart + `checkout_data` from session. Shows totals from cart.
3. **Payment POST** → validates only the selected payment method's fields (conditional rules, no `required_if`):
   - **Visa/Mastercard**: cardholder name, Luhn check on card number, MM/YY expiry (not expired), 3-4 digit CVV
   - **GCash**: name, number must be exactly 10 digits (`+63` prefix is fixed in the UI)
4. Creates `PaymentDataDTO` with shipping + payment info.
5. Calls `PaymentService::processPayment()` → creates Order with `payment_status = pending` + `paid_at = now()` → copies cart items into order_items → clears cart items → returns loaded Order.
6. Forgets `checkout_data` from session, stores `order_id` in session.
7. Redirects to `/success`.
8. `SuccessController@index` → reads `order_id` from session → `OrderRepository::findWithItems()` → renders success view.

---

## Order Status Fields — Responsibility Split

The `orders` table has two separate status fields with different owners:

| Field | Values | Owner | Description |
|---|---|---|---|
| `status` | pending → processing → shipped → delivered | **Admin/Order Management** (not yet built) | Tracks order fulfillment lifecycle |
| `payment_status` | pending → paid | **ECommerce (us)** | Tracks whether payment was completed |
| `paid_at` | timestamp or null | **ECommerce (us)** | Set to current time when payment succeeds |

Payment flow: Checkout validates → stores data in session → Payment Controller validates card → **only on success** creates order with `payment_status = pending` + `paid_at = now()`. The `status` field stays `pending` for admin to manage later. Admin changes `payment_status` to `paid` after verification.

---

## Pending / Not Yet Built

| Feature | Status |
|---|---|
| Payment processing | **Built (OOP)** — PaymentService + PaymentDataDTO + conditional validation. Payment stays `pending` — admin verifies later |
| Order tracking backend | **Built (OOP)** — TrackingService + TrackingController + OrderTracking model + dynamic timeline from `order_tracking` or fallback to `order.status` |
| Admin dashboard | **Built (OOP)** — DashboardController + DashboardService. Real data: revenue, orders, low stocks, revenue chart, category breakdown |
| Admin order management | **Built** — OrderController (index/show/updatePayment/updateStatus/updateTracking), real DB data, AJAX modal with payment confirm + fulfillment status dropdown + tracking sync actions |
| Admin product management | Static list |
| Account/profile settings | Placeholder page |
| Order history for customers | No route or view |
| Coupon/voucher system | Placeholder input only |
| Password reset | Returns "not implemented" |
| Google OAuth | Placeholder buttons only |
| REST API for checkout | Only `saveAddress` is JSON; `store` is form POST |
| `remember_token` missing from customers table | Skipped — remember me disabled in controller for now |

---

## 2026-07-12 — Codebase Cleanup

### Migrations Consolidation
- Merged `modify_customers_table` columns into `create_customers_table` (final schema)
- Merged `add_payment_fields_to_orders` + `add_payment_method_to_orders` into `create_orders_table` (final schema)
- Deleted `remove_recipient_phone_from_customer_addresses` (original migration already cleaned)
- **Deleted migration files (4):** `modify_customers_table`, `add_payment_fields_to_orders`, `add_payment_method_to_orders`, `remove_recipient_phone_from_customer_addresses`

### Dead PHP Code Removed
- `app/Services/CheckoutService.php` — entire file; logic duplicated in PaymentService
- `app/DTOs/CheckoutDataDTO.php` — only referenced by deleted CheckoutService
- `CustomerRepository::find()` — never called
- `CustomerRepository::findByEmail()` — never called
- `TrackingService::findByOrderNumber()` — never called externally
- `OrderRepository::findByOrderNumber()` — only called by dead service method
- `OrderTracking` constants (all 9) — defined but never referenced by name
- `use Illuminate\Support\Facades\Hash` in CustomerService — unused import

### Dead Views Removed (4 files)
- `components/header/announcement-bar.blade.php` — never included (announcement bar is inline in header)
- `components/header/search-card.blade.php` — never included
- `pages/dummy/shop/components/search.blade.php` — empty skeleton
- `pages/customer/auth/components/shared/field-error.blade.php` — never included

### Dead JS Removed
- `resources/js/tracking.js` — disconnected from templates (element IDs didn't exist)
- `resources/js/app.js` — emptied (only imported tracking.js)
- `selectShippingOption()` function in checkout-scripts — no matching HTML exists

---

## 2026-07-12 — Role-Based Access Control

### Architecture
| Role | Access |
|---|---|
| `super_admin` | Admin dashboard + user management (create admin/customer) |
| `admin` | Admin dashboard only (no user management) |
| `customer` | Storefront only — no admin access |

### Changes Made

**1. Migration — `add_role_to_customers_table`**
- Added `role` column (string, 20) with default `'customer'` after `status`.

**2. Middleware — `CheckRole`**
- `app/Http/Middleware/CheckRole.php` — accepts variadic role strings, aborts 403 if not matched.

**3. Middleware registration — `bootstrap/app.php`**
- Registered alias `role` for `CheckRole`.
- `redirectUsersTo` closure: customers → `/cart`, admins → `/admin/dashboard`.
- `redirectGuestsTo` set to `/login`.

**4. Login redirect — `LoginController`**
- After auth: admins redirect to `/admin/dashboard`, customers to intended or cart.

**5. Admin routes — `routes/web.php`**
- Admin routes wrapped in `middleware(['auth', 'role:super_admin,admin'])`.
- User management routes under separate `role:super_admin` middleware group:
  - `GET /admin/users` — list users
  - `GET /admin/users/create` — create form
  - `POST /admin/users` — store new user

**6. Sidebar — `components/admin/sidebar.blade.php`**
- Added "Users" nav link (visible only to `super_admin`).
- Sign Out now triggers the logout form instead of a console log.

**7. Header — `components/header/header.blade.php`**
- Removed "Admin Portal" link (line 37).

**8. Super Admin — seeded**
- `admin@admin.com` / `password` with `role = super_admin`.

**9. Demo Customer — updated**
- `demo@example.com` / `password` now has `role = customer`.

### New Files
| File | Purpose |
|---|---|
| `database/migrations/2026_07_12_173227_add_role_to_customers_table.php` | Add role column |
| `app/Http/Middleware/CheckRole.php` | Role-checking middleware |
| `app/Http/Controllers/Admin/UserController.php` | User CRUD (super_admin only) |
| `database/seeders/UserSeeder.php` | Seeds super_admin + updates demo customer |
| `resources/views/pages/admin/users/index.blade.php` | User list page |
| `resources/views/pages/admin/users/create.blade.php` | Create user form |
