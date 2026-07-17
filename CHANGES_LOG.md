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

*End of changes log*
