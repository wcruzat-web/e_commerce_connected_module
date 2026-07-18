# Changes Log — Hainz Shop Integration

This file documents the integration of Hainz's real product/shop system into the existing e-commerce backend (cart, checkout, payment, success, tracking, profile).

---

## Connection Plan — ERPV0.5

### Migrations Copied
- [x] `create_specs_and_compat_tables.php` — drops old product_specifications, creates new one + product_compabilities
- [x] `create_product_reviews_table.php` — creates product_reviews (review_id, product_id, user_id, comment, created_at)
- [x] `add_rating_to_product_reviews.php` — adds rating column to product_reviews

### Models Copied/Updated
- [x] `Product.php` — added `compatibilities()` and `reviews()` relations (identical schema otherwise)
- [x] `ProductSpecification.php` — updated fillable to match new schema: `category_name`, `label`, `value`; added `$primaryKey = 'spec_id'`
- [x] `ProductCompatibility.php` — new model, table `product_compabilities`
- [x] `ProductReview.php` — new model, primaryKey `review_id`, no timestamps
- [x] `Category.php` — unchanged (already matches)
- [x] `ProductImage.php` — unchanged (already matches)

### Schema Renames
- [x] `attribute_name` → `label` (migration 2026_07_18_163646_rename_spec_columns)
- [x] `attribute_value` → `value` (same migration)
- [x] Original migration updated for fresh installs
- **Why**: Hainz's show.blade.php used `label`/`value` — cleaner naming than
  `attribute_name`/`attribute_value`. Also matches how the views reference them.

### Routes [HAINZ]
- [x] `GET /shop` — public shop listing (named `shop`)
- [x] `GET /products` — public product listing (named `products.index`)
- [x] `GET /products/{product}` — public product detail (named `products.show`)
- [x] `GET /home` — redirects to shop
- [x] `POST /shop/review` — auth-only
- [x] `POST /shop/decrement-stock` — auth-only
- [x] `POST /shop/restore-stock` — auth-only
- [x] All shop routes wrapped in `auth` + `customer` middleware
- **Why**: Guest → login prompt. Customer → passes. Admin/super_admin →
  redirected to `/admin/dashboard`.

### Controller
- [x] `ShopController@index` — loads products, maps to array, filters by GET
  params (brands, chipsets, sockets, vram)
- [x] `ShopController@show($id)` — loads single product, maps to same array
  format
- [x] Private `mapProduct($p)` — shared mapping logic for both methods
- [x] Fixed bug in `review()`: `$allRatings` is from `pluck('rating')` which
  returns integers, not models. Changed `$r->rating` → `$r`.

### Views — Shop Pages
- [x] `pages/customer/shop/index.blade.php` — nexa catalog design, extends
  `layouts.app`, no Alpine
  - `x-for` → `@foreach`
  - `x-show` → `@if`
  - `x-text` → `{{ }}`
  - `:class` → inline ternary
  - Filters submit via GET, auto-submit on checkbox change
  - "Add to Cart" is form POST (not AJAX)
  - Sort dropdown is visual only
- [x] `pages/customer/shop/show.blade.php` — nexa product detail, tabbed layout
  - Three tabs: Full Specifications / Compatibility / Reviews
  - Tab switching via plain JS (no Alpine)
  - Star rating selector (1-5) for review form
  - Review form POSTs to `shop.review` route
  - Shows rating distribution bars, user comments, submit form
- **Why**: Removed Alpine.js CDN dependency. Server-rendered Blade is simpler,
  gives proper URLs, works without JS for basic navigation.

### Header
- [x] Rebuilt `components/header/header.blade.php`
  - Merged Hainz's features: mega menu, search toggle, mobile nav drawer
  - All converted to plain JS (no Alpine `x-data`/`x-show`/`@click`)
  - Nav links pull from `$headerCategories` via ViewComposer
    (`AppServiceProvider::boot()`)
  - Profile dropdown + Sign In/Up retained from original
- **Why**: Hainz's header used Alpine.js for search toggle, mobile hamburger.
  Since Alpine was removed, these needed plain JS equivalents.

### Product CRUD (dummy product management)
- [x] Copied from `_hainz_shop/` views + routes
- [x] Views: `pages/dummy/{products,add-product,add-specs,edit-product,edit-specs}`
- [x] Routes: GET/POST for all CRUD, DELETE with cascading cleanup
- [x] Updated validation rules: `attribute_name`→`label`, `attribute_value`→`value`
- [x] Updated form field names in Alpine-driven spec editors
- **Why**: These are standalone admin-like pages (standalone HTML, Tailwind CDN)
  to manage products, specs, and compatibility until proper admin panel is built.
  Flow: add-product → add-specs → shop.

### Removed
- [x] Alpine.js CDN dependency removed from shop pages
- [x] Old nexa-layout SPA components no longer referenced

### Known Issues
- Add/Edit specs forms still use Alpine.js (`x-data="specsForm()"`) — the dynamic
  add/remove row functionality won't work. Form submission still works.
- Filter checkboxes show/hide products but checkbox visual state (filled circle)
  only updates on page load, not instantly.

### Pending
- [ ] `public/images/products/` — copy from Hainz
- [ ] `public/storage/products/` — copy from Hainz
- [ ] Cart → checkout → payment → success → tracking integration testing

---

### Mega Menu — Dynamic from DB (click-toggle)
- [x] Replaced hardcoded 8-column mega menu with dynamic products per category (4 newest per category)
- [x] Changed hover-triggered to click-triggered (`toggleMegaMenu()` JS)
- [x] Trending section shows highest-selling product from `order_items` (SUM quantity)
- [x] Categories seeded via tinker then reverted — only user's existing DB categories remain
- **Why**: Shows real products grouped by real categories; click works on mobile/tablet; top-seller badge incentivizes browsing.

### CSS Fix — Inline nav visibility
- [x] Removed `hidden lg:inline` Tailwind classes (not in production build)
- [x] Custom CSS classes `nav-cat-link` / `nav-all-hw` with inline `<style>` block
- **Why**: Tailwind v4 build didn't scan the header file — `lg:inline`/`lg:block` were missing from compiled CSS. Inline `<style>` guarantees visibility regardless of build state.

### Filter Reset Button
- [x] Added "Reset" link next to Filters heading — clears all query params via plain `route('products.index')`
- **Why**: Quick way to reset filter selections without manual unchecking.

---
