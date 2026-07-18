# Changes Log — Hainz Shop Integration

This file documents the integration of Hainz's real product/shop system into the existing e-commerce backend (cart, checkout, payment, success, tracking, profile).

---

### ERPV1.1 — Hainz shop: migrations, models, ShopController

```
- Copied Hainz's migrations (specs, compat, reviews tables), models
  (ProductSpecification, ProductCompatibility, ProductReview), and
  ShopController with mapProduct()
- Converted Alpine.js shop views to server-rendered Blade (index, show)
- Rebuilt header with mega menu, search toggle, mobile nav — all plain JS
- Added product CRUD routes and views at /dummy/products
- Renamed attribute_name/attribute_value to label/value
```

#### Migrations Copied
- [x] `create_specs_and_compat_tables.php` — drops old product_specifications, creates new one + product_compabilities
- [x] `create_product_reviews_table.php` — creates product_reviews (review_id, product_id, user_id, comment, created_at)
- [x] `add_rating_to_product_reviews.php` — adds rating column to product_reviews

#### Models Copied/Updated
- [x] `Product.php` — added `compatibilities()` and `reviews()` relations
- [x] `ProductSpecification.php` — updated fillable: `category_name`, `label`, `value`; `$primaryKey = 'spec_id'`
- [x] `ProductCompatibility.php` — new model, table `product_compabilities`
- [x] `ProductReview.php` — new model, primaryKey `review_id`, no timestamps

#### Schema Renames
- [x] `attribute_name` → `label`, `attribute_value` → `value` (migration 2026_07_18_163646)
- **Why**: Hainz's show.blade.php used `label`/`value` — cleaner naming.

#### Routes [HAINZ]
- [x] `GET /shop`, `GET /products`, `GET /products/{product}`, `GET /home` → redirects to shop
- [x] `POST /shop/review`, `/shop/decrement-stock`, `/shop/restore-stock` — auth-only
- [x] All wrapped in `auth` + `customer` middleware

#### Controller — `app/Http/Controllers/ShopController.php`
- [x] `index()` — loads products, maps to array, filters by GET params
- [x] `show($id)` — loads single product, maps to same array format
- [x] Private `mapProduct($p)` — shared mapping logic
- [x] Fixed bug in `review()`: `pluck('rating')` returns integers, changed `$r->rating` → `$r`

#### Views — Shop Pages
- [x] `index.blade.php` — Alpine → Blade conversion (`x-for` → `@foreach`, `x-show` → `@if`, etc.)
- [x] `show.blade.php` — tabbed layout (Specs/Compatibility/Reviews), star rating, review form
- **Why**: Removed Alpine.js CDN dependency.

#### Header — `components/header/header.blade.php`
- [x] Rebuilt: mega menu, search toggle, mobile nav drawer — all plain JS
- [x] Nav links from `$headerCategories` via `AppServiceProvider::boot()`

#### Product CRUD (dummy product management)
- [x] Views: `pages/dummy/{products,add-product,add-specs,edit-product,edit-specs}`
- [x] Routes: GET/POST for all CRUD, DELETE with cascading cleanup

#### Removed
- [x] Alpine.js CDN from shop pages
- [x] Old nexa-layout SPA components

#### Known Issues
- Add/Edit specs forms still use Alpine.js — dynamic add/remove won't work
- Filter checkboxes only update visual state on page load

### ERPV1.2 — Hainz shop integration: dynamic mega menu, product pages, CRUD, filter reset

```
- Replaced hardcoded mega menu with dynamic DB products (4 per category,
  click-toggle)
- Added trending section (top seller from order_items SUM)
- Fixed nav visibility with inline CSS (nav-cat-link/nav-all-hw)
- Added Reset button on filters, storage:link for images
- Fixed review profile pictures, route('products') → route('products.index')
- Wrapped featured_image with asset() for correct URL resolution
```

#### Mega Menu — Dynamic from DB (click-toggle)
- [x] Replaced hardcoded 8-column mega menu with dynamic products per category (4 newest)
- [x] Changed hover-triggered to click-triggered (`toggleMegaMenu()` JS)
- [x] Trending section shows highest-selling product from `order_items` (SUM quantity)
- [x] Categories seeded via tinker then reverted — only user's existing DB categories

#### CSS Fix — Inline nav visibility
- [x] Custom CSS classes `nav-cat-link` / `nav-all-hw` with inline `<style>` block
- **Why**: Tailwind v4 build didn't include `lg:inline`/`lg:block`.

#### Filter Reset Button
- [x] "Reset" link next to Filters heading — clears all query params

#### Product Images
- [x] `php artisan storage:link` — public/storage/ → storage/app/public/
- [x] Products #1-6 fall back to placehold.co when `featured_image` is null
- [x] Images wrapped with `asset()` for correct URL resolution

#### Review Profile Pictures
- [x] `mapProduct()` includes `profile_picture` in review data with `asset()` for local paths
- [x] View shows `<img>` if available, initials fallback if not

#### Route Name Fix
- [x] `route('products')` → `route('products.index')` in sidebar, orders-list, orders-header, history-list

### ERPV1.3 — Outfit font, AJAX wishlist/cart, route name fix, image asset paths

```
- Changed font from Instrument Sans to Outfit
- AJAX wishlist toggle and add-to-cart (no page refresh)
- WishlistController uses Product model instead of ProductSource
- Order items store product_name and product_image
- Fixed review HTML (extra div) and profile pictures
```

#### Font Change
- [x] Instrument Sans → Outfit in `app.css` (`--font-sans`)
- [x] Google Fonts link in `layouts/app.blade.php` `<head>`

#### AJAX — Wishlist & Cart (no page refresh)
- [x] Wishlist heart toggle via `fetch()` — updates fill/color instantly
- [x] Add to Cart via `fetch()` — updates cart badge count silently
- [x] Prevents default form submit (no scroll to top)

#### Wishlist Button in Catalog
- [x] Heart icon beside "Add to Cart" in shop index
- [x] POSTs to `wishlist.toggle` route

#### WishlistController — Use Product Model
- [x] `toggle()` changed from `ProductSource::find()` to `Product::find()`
- [x] `product_image` from `$product->featured_image` (with `asset()`)
- [x] `unit_price` from `$product->sale_price ?? $product->price`
- [x] `in_stock` from `$product->stock > 0`

#### Order Images
- [x] `PaymentService::processPayment()` stores `product_name` and `product_image`

#### Review HTML Bug Fix
- [x] Removed extra `</div>` that pushed comment text outside review container

### ERPV1.4 — Shop page search & sort implementation

```
- Search: header search bar now functional — Enter key or icon click navigates
  to products.index?search= with current query persisted in input
- Controller: filters products by name/brand via stripos when ?search= is present
- Sort: dropdown now has name="sort" with URLSearchParams-based onchange that
  preserves existing filter params during navigation
- Controller: usort on price (spaceship <=>) after all filtering for asc/desc
- Featured (default) orders by created_at desc; option correctly pre-selected
```

#### File: `app/Http/Controllers/ShopController.php`
- Added `$sort = $request->input('sort', 'featured')` — defaults to Featured
- Added `$search = $request->input('search')`
- Added search filter: `$products->filter(fn ($p) => stripos(...))` on name/brand
- Added price sort: `usort()` on `price` with spaceship `<=>` after all filtering
- **Why**: Search bar had no listener and sort select had no name/onchange — both were non-functional.

#### File: `resources/views/components/header/header.blade.php`
- Added `value="{{ request('search') }}"` to search input for query persistence
- Rewrote `toggleSearch()` — clicking search icon while input has text submits search
- Added `doSearch(q)` function — navigates to `products.index?search=...`
- Added `DOMContentLoaded` listener — Enter key on search input triggers search
- Changed header promo from `$99` to `₱3,000`
- **Why**: Search was completely non-functional; header price needed local currency.

#### File: `resources/views/pages/customer/shop/index.blade.php`
- Replaced static `<select>` with `name="sort"` + `URLSearchParams` onchange
- Added `value` attributes to all options with correct selected logic
- **Why**: Sort select had no name, no onchange, no values — clicking it did nothing.

### ERPV1.5 — Refactor shop pages into components, fix header price

```
- index.blade.php: extracted into 6 components (hero-banner, filters, toolbar,
  product-card, index-footer, index-scripts) — parent now 8 lines of @includes
- show.blade.php: extracted into 7 components (product-breadcrumb, product-hero,
  tab-navigation, specs-tab, compatibility-tab, reviews-section, show-scripts)
- Removed 10 legacy Nexa component files from Components/
- Removed dead shop.blade.php (referenced deleted nexa-layout)
- Lowercased components/ directory for Linux compatibility
- Header: changed "$99" to "₱3,000"
```

#### Files Created
- `resources/views/pages/customer/shop/components/` (13 new component files)
- `hero-banner.blade.php`, `filters.blade.php`, `toolbar.blade.php`, `product-card.blade.php`
- `index-footer.blade.php`, `index-scripts.blade.php`
- `product-breadcrumb.blade.php`, `product-hero.blade.php`, `tab-navigation.blade.php`
- `specs-tab.blade.php`, `compatibility-tab.blade.php`, `reviews-section.blade.php`
- `show-scripts.blade.php`

#### Files Deleted
- `resources/views/pages/customer/shop/Components/` (10 legacy Nexa files)
- `resources/views/pages/customer/shop/shop.blade.php` (dead — referenced deleted nexa-layout)

#### Files Modified
- `resources/views/pages/customer/shop/index.blade.php` — reduced from 265 to 8 lines (pure @includes)
- `resources/views/pages/customer/shop/show.blade.php` — reduced from 217 to 14 lines (pure @includes)
- `resources/views/components/header/header.blade.php` — `$99` → `₱3,000`
- **Why**: Consistent with the component-split pattern used by orders, wishlist, history, etc.

---
