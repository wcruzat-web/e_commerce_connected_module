# Esteban Part — Changelog

## V1.0 — Initial Integration (Admin Product & Inventory Management)

### Migration — Table Renames
- `products` → `product_table` (PK: `id` → `product_id`, `name` → `product_name`, `featured_image` → `product_image`)
- Added `is_featured` boolean, `category` string column
- `product_specifications` → `product_specification`
- `product_compabilities` → `product_compatibilities`
- All FKs re-pointed to `product_table(product_id)`

### Migration — New Tables
- `warehouses`, `warehouse_stock`, `promo_banners`, `revenue_overview`

### Migration — Relax Constraints
- Made `slug` + `category_id` nullable (required by Esteban's Product schema)

### Models — Updated (match new table/PK)
- `Product` — `$table='product_table'`, `$primaryKey='product_id'`, added `is_featured`/`category`/`warehouseStock()`/`getImageUrlAttribute()`
- `ProductSpecification` — `$table='product_specification'`
- `ProductCompatibility` — `$table='product_compatibilities'`
- `ProductImage`, `ProductReview`, `CartItem`, `OrderItem` — FK local key `'id'` → `'product_id'`

### Models — Created (copied from Esteban's originals)
- `Warehouse`, `WarehouseStock`, `PromoBanner`, `RevenueOverview` — exact copies

### REST API Controllers (Admin\Api) — Esteban's logic, unchanged
- `ProductController` — index, store, update, destroy, toggleFeatured
- `InventoryController` — stats, warehouses, forceSync, revenue
- `PromoBannerController` — index, store, destroy

### SPA Views (Blade shell + JS fetch, Esteban's architecture)
- `pages/admin/products/index.blade.php` — product CRUD, search/filter, featured toggle via JS
- `pages/admin/inventory/index.blade.php` — stat cards, stock alerts, warehouses, revenue via JS

### Routes
- `GET /admin/products` → SPA view (named `admin.products`)
- `GET /admin/inventory` → SPA view (named `admin.inventory`)
- `GET/POST/PUT/DELETE/PATCH /api/admin/*` → API controllers (auth + role middleware)

### Codebase Updated for Renamed Columns
- `ShopController::mapProduct` — `$p->id/name/featured_image` → `$p->product_id/product_name/product_image`
- `WishlistController::toggle` — same renames + image URL fix
- `PaymentService` — same renames + image URL fix
- `CartController` — validation `exists:product_table,product_id`
- `DashboardService` — `$product->name` → `$product->product_name`
- `CartItemsList` Blade — `featured_image` → `product_image`, `name` → `product_name`
- `ProductSeeder` — all column names + `$product->id` → `$product->product_id`
- All dummy routes/views — column names updated

### V1.1 — Inventory Product Stock Table
- **New API endpoint** `GET /api/admin/inventory/products` → `InventoryController::products()` — returns all products with stock count + computed status (available/low/out). *Custom method, not in Esteban's original.*
- **SPA update** — added "Product Stock" section with sortable/filterable table listing every product, its category, stock, and status badge (green/amber/red)
- **Status filter** dropdown (All / Available / Low Stock / Out of Stock) + **search box** by product name
- **Category fix** — `category` is both a DB column (string) and a relationship name (`belongsTo`), so Eloquent returns the column (null for existing products) instead of the related model. Fixed with `leftJoin('categories', ...)` to read `categories.name` as fallback.
- **Changelog note** — all new code marked `[NEW]` in source comments

### V1.2 — Category Backfill & Free-Text Fix
- **Migration `backfill_product_category_column`** — copies `categories.name` into `product_table.category` for existing products where `category` is null (resolves empty edit form)
- **ProductController** — reverted back to free-text `category` string input (admin types any category)
- **Product SPA** — reverted dropdown → text input, `p.category` display
- **Inventory `products()` endpoint** — simplified, uses `category` column directly (no JOIN needed)
- **Username comments** — `[AGNER]`, `[CRUZAT]`, `[ESTEBAN]`, `[HAINZ]`, `[NEW]` annotations added to all modified files

### V1.3 — Bug Fix: Header Trending Query
- `header.blade.php` — fixed `products.*` → `product_table.*`, `products.id` → `product_table.product_id`, `$topProduct->name` → `$topProduct->product_name` (caused SQL error on customer pages)

### Notes
- `categories` table still active globally (header nav, dashboard chart, dummy pages). Only Esteban's SPA uses the string `category` column.

### Pending
- Admin login page (`users` table auth)
- Promo banners UI in SPA
- Seeders for warehouses, warehouse_stock, revenue_overview, promo_banners
- Product image upload testing in SPA
