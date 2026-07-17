# Changes Log — E-Commerce Final System

This file documents every change made to connect the CRUZAT shop frontend with the existing backend (cart, checkout, payment, success, order tracking) and restore/restyle AGNER auth pages.

---

## 1. Auth Pages — Restored from AGNER (`_new_auth`)

Copied from `_new_auth/` into main project:

| File | Source |
|------|--------|
| `resources/views/auth/login.blade.php` | `_new_auth/resources/views/auth/login.blade.php` |
| `resources/views/auth/register.blade.php` | `_new_auth/resources/views/auth/register.blade.php` |
| `resources/views/auth/forgot-password.blade.php` | `_new_auth/resources/views/auth/forgot-password.blade.php` |
| `resources/views/layouts/app.blade.php` | `_new_auth/resources/views/layouts/app.blade.php` |
| `resources/views/layouts/guest.blade.php` | `_new_auth/resources/views/layouts/guest.blade.php` |
| `resources/views/layouts/customer.blade.php` | `_new_auth/resources/views/layouts/customer.blade.php` |
| `resources/views/layouts/store.blade.php` | `_new_auth/resources/views/layouts/store.blade.php` |
| `resources/views/components/sidebar.blade.php` | `_new_auth/resources/views/components/sidebar.blade.php` |
| `resources/views/components/topbar.blade.php` | `_new_auth/resources/views/components/topbar.blade.php` |
| `resources/views/components/toast.blade.php` | `_new_auth/resources/views/components/toast.blade.php` |
| `resources/views/components/auth-card.blade.php` | `_new_auth/resources/views/components/auth-card.blade.php` |
| `resources/views/components/stat-card.blade.php` | `_new_auth/resources/views/components/stat-card.blade.php` |
| `app/Http/Controllers/Auth/LoginController.php` | `_new_auth/app/Http/Controllers/Auth/LoginController.php` |
| `app/Http/Controllers/Auth/RegisterController.php` | `_new_auth/app/Http/Controllers/Auth/RegisterController.php` |
| `app/Http/Controllers/Auth/GoogleController.php` | `_new_auth/app/Http/Controllers/Auth/GoogleController.php` |
| `app/Http/Controllers/Auth/DevLoginController.php` | `_new_auth/app/Http/Controllers/Auth/DevLoginController.php` |
| `app/Models/Customer.php` | `_new_auth/app/Models/Customer.php` |

Auth pages restyled to CRUZAT design:
- Outfit font
- `#00BBFF` accent color
- `max-w-4xl` grid layout
- Compact inputs (`px-4 py-2.5 text-sm`)
- `rounded-2xl` cards with blue glow shadow

---

## 2. Old Auth & Store Files — Deleted/Replaced

- Old auth views, layouts, controllers deleted (replaced by AGNER versions)
- AGNER store views deleted:
  - `resources/views/store/home.blade.php`
  - `resources/views/store/products.blade.php`
  - `resources/views/store/product.blade.php`
  - `resources/views/store/cart.blade.php`
  - `resources/views/store/help.blade.php`

---

## 3. Google OAuth — Set Up

| What | Details |
|------|---------|
| Controller | `app/Http/Controllers/Auth/GoogleController.php` from `_new_auth` |
| Routes | Added Google OAuth routes (login, callback) to `routes/web.php` |
| Config | Added Google block to `config/services.php` from `_new_auth/services.php` |
| `.env` | Added `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` |
| Package | Installed `laravel/socialite` v5.28.0 |
| SSL Fix | Downloaded `cacert.pem` to `C:\php8.5\`, updated `php.ini` |

> Note: Laragon services need restart after `.env` changes.

---

## 4. CRUZAT Shop — Restored & Connected to Cart

Restored CRUZAT shop views under `resources/views/pages/customer/shop/`:
- `nexa-layout.blade.php`
- `nexa-hero.blade.php`
- `nexa-main-content.blade.php`
- `nexa-product-card.blade.php`
- `nexa-product-detail.blade.php`
- `nexa-filters.blade.php`
- `nexa-footer.blade.php`

### Changes in `nexa-layout.blade.php`
- Added `<meta name="csrf-token">` for AJAX requests
- Added `cartLoading` state to prevent double-clicks
- `addToCart()` rewritten:
  - **Before:** Fake `cartMsg = 'Added ' + product.id` simulation
  - **After:** Real `fetch(POST /cart/add, {product_id, quantity})` to backend
  - Updates cart badge (`.js-cart-badge`) from JSON response

### Changes in `header.blade.php`
- Added `.js-cart-badge` span on cart icon (hidden by default)
- Badge count updated live by shop AJAX response

### Routes
- Shop route: `home -> pages.customer.shop.shop`
- Added `products.index` (redirects to `/home`) to fix broken links

---

## 5. CartController — Added AJAX Support

**File:** `app/Http/Controllers/CartController.php`

- `add()` method: added `if ($request->ajax())` JSON return
  - Returns `{ok, message, cartCount}` for AJAX requests
  - Non-AJAX requests still hit original `redirect()->back()`
- Original logic (validation, service calls, redirect) is unchanged

---

## 6. Products Seeded

Created 1 category (`Components`) + 6 products (IDs 1–6 matching `ProductSource`):

| ID | Name | Category | Price |
|----|------|----------|-------|
| 1 | Intel Core i5-12400F | Processor | ₱8,990 |
| 2 | GeForce RTX 4060 Dual 8GB | GPU | ₱18,500 |
| 3 | Corsair Vengeance 16GB DDR5 | Memory | ₱5,200 |
| 4 | Samsung 980 Pro 1TB NVMe | Storage | ₱6,900 |
| 5 | ASUS B660M Motherboard | Motherboard | ₱7,400 |
| 6 | Cooler Master 650W PSU | Power | ₱3,500 |

Required for `CartService::findOrFail()` and `exists:products,id` validation.

---

## 7. Header Swap — Cart/Checkout/Payment/Success/Tracking

### `layouts/store.blade.php`
- Replaced inline white "ShopEase" header with `@include('components.header.header')`
- Affects: cart, checkout, success, order-tracking pages

### `layouts/app.blade.php`
- Added `@include('components.header.header')` before `@yield('content')`
- Affects: payment page

---

## 8. Other Changes

- Removed "Cart" link from customer portal sidebar
- Added "My Profile" link to CRUZAT header profile dropdown
- Added `Route::fallback()` for `products.index -> /home` redirect
- Installed `laravel/socialite` for Google OAuth

---

---

## ERPV0.2 — Bug Fixes & Connection Improvements

### Checkout Address Save
- **File:** `app/Services/AddressService.php`
  - `saveOrUpdate()` — added `recipient_name` and `phone_number` to `create()` array (were missing, causing SQL NOT NULL failure)
  - `saveFromOrder()` — same fix: added `recipient_name` and `phone_number` to `create()` array
- **File:** `resources/views/components/toast.blade.php`
  - Renamed `toast-container` to `toastContainer` (mismatch with JS `getElementById('toastContainer')`)

### Register Page — Phone & Last Login
- **File:** `app/Http/Controllers/Auth/RegisterController.php`
  - Added `phone` to validation rules (`nullable|string|max:20`)
  - Added `phone_number` to `create()` array (maps from `phone` input)
  - Added `last_login` to `create()` array

### Login — Last Login
- **File:** `app/Http/Controllers/Auth/LoginController.php`
  - Added `Auth::user()->update(['last_login' => now()])` on successful login

### Checkout — Pre-fill Contact Fields
- **File:** `resources/views/pages/customer/checkout/components/contact-fields.blade.php`
  - Added `value="{{ old('field', auth()->user()->field) }}"` to:
    - `first_name` input (from `auth()->user()->first_name`)
    - `last_name` input (from `auth()->user()->last_name`)
    - `shipping_email` input (from `auth()->user()->email`)
    - `shipping_phone` input (from `auth()->user()->phone_number`)

### Index Page
- **File:** `routes/web.php`
  - Changed `/` route from `redirect(auth()->check() ? route('home') : route('login'))` to `view('auth.login')`

---

## ERPV0.2.1 — Dead Code Cleanup & Typo Fix

### Removed (unreferenced in current flow)
- `resources/views/components/auth-card.blade.php` — 3-line component, never included by any view
- `resources/views/pages/customer/shop/Components/nexa-header.blade.php` — exists on disk but shop uses `components.header.header` instead
- `app/Providers/AppServiceProvider.php` lines 22–31 — View composer computing `$cartCount` and `$wishlistCount` that no view references anymore (header was swapped to CRUZAT)

### Fixed
- `resources/views/pages/customer/cart/components/cart-items-list.blade.php` line 97 — `hover:zbg-gray-50` → `hover:bg-gray-50` (CSS typo)

### Note
- `console.log` statements (3 total) kept as placeholders for future voucher feature

---

## ERPV0.2.2 — Live Timeline Polling & Profile Addresses

### Tracking — Real-Time Status Updates [CRUZAT → AGNER]
- **File:** `app/Http/Controllers/TrackingController.php` [CRUZAT]
  - Added `poll(int $orderId)` method — returns JSON with rendered HTML for timeline, status banner, and shipment meta partials. Validates order ownership.
- **File:** `routes/web.php` [CRUZAT]
  - Added `GET /tracking/{order}/poll` route for AJAX polling
- **File:** `resources/views/pages/customer/order-tracking/tracking.blade.php` [CRUZAT]
  - Wrapped timeline, status banner, and shipment meta in identifiable `#timelineContainer`, `#statusBannerContainer`, `#shipmentMetaContainer` divs
- **File:** `resources/views/pages/customer/order-tracking/components/tracking-scripts.blade.php` [CRUZAT]
  - Added polling JS: `setInterval` every 3 seconds fetches `/tracking/{order}/poll` and silently swaps DOM in-place. Preserves timeline collapsed/expanded toggle state across updates. No page refresh, no scroll jump.

### Profile — Saved Addresses [AGNER → AGNER]
- **File:** `app/Http/Controllers/Customer/ProfileController.php` [AGNER]
  - `index()` now also loads `$addresses` from `customer_addresses` table (ordered by default first, then by ID)
- **File:** `resources/views/customer/profile.blade.php` [AGNER]
  - Added "Saved Addresses" section between Account Information and Help, matching the same card style and data source as the dedicated pages. Includes Add/Edit/Delete actions.

---

## ERPV0.2.3 — Order Page Unified [AGNER → CRUZAT]

### My Orders — Single unified view
- **File:** `app/Http/Controllers/Customer/OrderController.php` [AGNER]
  - `index()` removed `->active()` scope — now loads **all** orders regardless of status
  - Added `orderByRaw("FIELD(status, ...)")` to sort active orders before delivered
- **File:** `resources/views/customer/orders.blade.php` [AGNER]
  - Tabs changed from All / Processing / Shipped to **All / Processing / Delivered**
  - Processing tab includes: pending, processing, shipped, in_transit, out_for_delivery
  - Delivered tab includes: delivered
  - Added color-coded status badges matching admin table style
  - "Mark as Received" button only shows on shipped/in_transit/out_for_delivery
  - Total amount shown on every order card

---

## ERPV0.2.4 — Customer Received Flag, Admin Modal, Track Button [CRUZAT → AGNER]

### Migration
- Added `customer_received` boolean (default false) to `orders` table — separates customer confirmation from admin fulfillment status

### Customer OrderController
- `receive()` now sets `customer_received = true` instead of overwriting `status = 'Delivered'`
- `Order` model: added `customer_received` to `$fillable`

### My Orders page (`orders.blade.php`)
- Button logic:
  - `customer_received = true` → "Received" green badge (static)
  - `status = 'delivered'` (admin) → **"Mark as Received"** green button (clickable)
  - `cancelled` → "Cancelled" badge
  - All other statuses → status name badge (static, not clickable)
- Added "Track" button on every order card → links to tracking page with that order loaded
- Removed `$canReceive` variable (no longer needed)
- Order number now uses `$order->order_number` (OID-XXXX-XXXX format)

### Admin order modal
- Added "Customer Received" field — shows "Received" (green) or "Awaiting" (gray)
- Fulfillment dropdown disabled when `customer_received = true` (customer already confirmed)

### Tracking page
- Added "Order Receipt" section (`received-action` component) with same Mark as Received logic
- Polling updated to also swap `received_html` in real-time
- Added `TrackingController@show` — new route `GET /tracking/{order}` that sets session and redirects to tracking page

### History page
- Order number now uses `$order->order_number` instead of padded `order_id`

---

## ERPV0.2.5 — Payment Methods Aligned with Supported Types [CRUZAT]

### Views
- `add-payment.blade.php` and `edit-payment.blade.php`: payment type options changed from `Credit Card / Debit Card / GCash / Maya` to `Visa / Mastercard / GCash` (matching actual payment processing)
- Grid layout changed from `grid-cols-2 md:grid-cols-4` to `grid-cols-3`
- Default selection changed to `Visa`

### Controller
- `PaymentMethodController` validation updated from `in:Credit Card,Debit Card,GCash,Maya,Bank Transfer` to `in:Visa,Mastercard,GCash`

### _new_auth copies
- Synced same changes to `_new_auth` mirror directory

---

## ERPV0.2.6 — Payment Form Fields Aligned with Checkout [CRUZAT]

### Views (`add-payment.blade.php`, `edit-payment.blade.php`)
- Split account information into conditional sections:
  - **Visa / Mastercard**: Cardholder Name, Card Number (maxlength 19), Expiry Date (MM/YY, maxlength 5)
  - **GCash**: GCash Name, GCash Number (+63 prefix, maxlength 10)
- Removed generic "Provider" field and full date picker
- Added JS to toggle card/GCash fields based on selected payment type
- Grid layout changed from `grid-cols-3` to conditional per-type layout

### Controller (`PaymentMethodController`)
- Validation updated: `account_name` and `masked_account_number` now nullable (GCash sends different fields)
- Added `gcash_name` and `gcash_number` to validation
- On GCash submission: maps `gcash_name` → `account_name`, `+63` + `gcash_number` → `masked_account_number`
- Removed `provider` from validation

### `_new_auth` copies
- Synced same changes to controller and views in `_new_auth` mirror

---

## ERPV0.2.7 — Payment Form Validation & CVV Field [CRUZAT]

### Views (`add-payment.blade.php`, `edit-payment.blade.php`)
- Added CVV field (password, maxlength 4) to card section
- Added `id="paymentForm"` to form tag for JS binding
- Added client-side validation on submit:
  - Visa/Mastercard: cardholder name required, Luhn algorithm check, MM/YY expiry validation (format + not expired), CVV 3-4 digits
  - GCash: name required, number must be exactly 10 digits
- Added `toastNotify` for error messages
- Same changes synced to `_new_auth` copy

### Controller (`PaymentMethodController`)
- Validation rules now conditional based on `payment_type`:
  - Visa/Mastercard: `account_name`, `masked_account_number` (max 19), `expiry_date` (max 5) — all required
  - GCash: `gcash_name`, `gcash_number` (max 10) — both required
- `cvv` accepted in validation but unset before save (not stored)
- Same changes synced to `_new_auth` controller

---

## ERPV0.2.8 — Add Payment: Server-Side Error Display & Field Annotations [CRUZAT]

### Views (`add-payment.blade.php`)
- Added `$errors->any()` general error summary block at top of form
- Added per-field `@error` directives for `account_name`, `masked_account_number`, `expiry_date`, `gcash_name`, `gcash_number`
- Added `border-red-400` class on inputs when validation fails
- Added `session('success')` display block
- Same changes synced to `_new_auth` copy

---

## ERPV0.2.9 — Fix JS Crash & Add Error Handling to Edit Payment [CRUZAT]

### Root Cause
- Two IIFEs back-to-back (`})(); (function()`) without leading `;` caused JS to interpret `undefined(function)` as a function call, throwing `TypeError` that killed entire script — submit handler never registered, no errors ever appeared.

### Views (`add-payment.blade.php`)
- Added `;` before both IIFEs to prevent ASI (automatic semicolon insertion) crash
- Same `@error` directives, `$errors->any()` summary, `session('success')`, and `border-red-400` kept

### Views (`edit-payment.blade.php`)
- Added same error handling: `$errors->any()` summary, `serverErrors` hidden div with toast, `@error('payment_type')`, per-field `@error` directives + `border-red-400`
- Added `;` before IIFEs
- Added `serverErrors` JS toast on page load

---

## ERPV0.2.10 — Real Semantic Server Validation for Payment Methods [CRUZAT]

### Controller (`PaymentMethodController`)
- Refactored validation into private `validatePaymentMethod()` method used by both `store` and `update`
- Added custom error messages for all fields
- **Visa/Mastercard**: Luhn algorithm check on card number → "Invalid card number."
- **Visa/Mastercard**: Card number too short (< 13 digits) → "Card number too short."
- **Visa/Mastercard**: Expiry format must be MM/YY → "Invalid expiry date format. Use MM/YY."
- **Visa/Mastercard**: Month must be 1-12 → "Invalid month in expiry date."
- **Visa/Mastercard**: Expiry date in the past → "Card is expired."
- **Visa/Mastercard**: CVV must be 3-4 digits → "Invalid CVV. Must be 3-4 digits."
- **GCash**: Number must be exactly 10 digits → "GCash number must be exactly 10 digits."

---

## ERPV0.2.11 — Toast Container Self-Healing [CRUZAT]

### Views (`add-payment.blade.php`, `edit-payment.blade.php`)
- `toastNotify()` now creates `toastContainer` dynamically if it doesn't exist in the DOM
- No longer silently fails when container is missing
- Ran `php artisan view:clear` to purge cached layouts

---

## ERPV0.2.12 — Payment Page: Saved Method Cards, CVV Stored, Manual Toggle [CRUZAT]

### Controller (`PaymentController@index`)
- Now loads all saved payment methods (`$paymentMethods`) ordered by default first
- `$defaultMethod` derived from the collection instead of a separate query

### Model (`SavedPaymentMethod`)
- Added `cvv` to `$fillable` so CVV is persisted and available for pre-fill

### Controller (`PaymentMethodController` — store/update)
- Removed `cvv` from `unset()` — CVV is now saved to the database

### Views (`payment-details.blade.php`)
- Added saved payment method cards section (same pattern as checkout address-section):
  - Shows masked card number, account name, expiry, DEFAULT badge, radio selection
  - `data-type`, `data-account`, `data-number`, `data-expiry`, `data-cvv` attributes for JS fill
- "Add Payment Method" button shown only when saved methods exist
- `#manualPaymentSection` wraps tabs + form fields, hidden by default when saved methods exist, always visible when none
- CVV input now pre-filled from `$defaultCvv`
- `$defaultType`, `$defaultCardNumber`, `$defaultExpiry`, `$defaultGcashNumber`, `$defaultCvv` moved to parent `payment.blade.php` @php block

### Views (`payment-scripts.blade.php`)
- `selectPaymentMethod(method, fillData)` — second param auto-fills fields when provided
- `selectSavedPayment(card)` — highlights card, calls `selectPaymentMethod` with fill data
- `addPaymentBtn` click — shows `#manualPaymentSection`, hides button, clears all fields

---

## ERPV0.2.13 — Dashboard Stats: Real Cart/Pending/Completed Counts [CRUZAT]

### Controller (`DashboardController@index`)
- `cart` now counts from the customer's actual cart items (`$customer->carts()->first()?->items()->count()`), falls back to 0 if no cart
- `pending` counts orders where status is NOT `Delivered` or `Cancelled`
- `completed` counts orders where status is `Delivered` AND `customer_received` is true

---

*End of changes log*
