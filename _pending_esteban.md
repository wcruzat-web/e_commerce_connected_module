# Esteban — Completed: REST API + SPA Integration

## ✅ Done

### Database (Migrations)
- Renamed `products` → `product_table`, `id` → `product_id`, `name` → `product_name`, `featured_image` → `product_image`
- Added `is_featured` boolean + `category` string column to `product_table`
- Renamed `product_specifications` → `product_specification`
- Renamed `product_compabilities` → `product_compatibilities`
- Created tables: `warehouses`, `warehouse_stock`, `promo_banners`, `revenue_overview`
- Updated all FKs to reference `product_table(product_id)`

### Models
- `Product` — `$table='product_table'`, `$primaryKey='product_id'`, added `is_featured`, `category`, `warehouseStock()`, `getImageUrlAttribute()`
- `ProductSpecification` — `$table='product_specification'`
- `ProductCompatibility` — `$table='product_compatibilities'`
- Created: `Warehouse`, `WarehouseStock`, `PromoBanner`, `RevenueOverview`
- Updated all FK relationships (`'id'` → `'product_id'`)

### API Controllers (return JSON — Esteban's architecture)
- `Admin\Api\ProductController` — index, store, update, destroy, toggleFeatured
- `Admin\Api\InventoryController` — stats, warehouses, forceSync, revenue
- `Admin\Api\PromoBannerController` — index, store, destroy

### SPA Views (Blade shell + embedded JS — Esteban's architecture)
- `pages/admin/products/index.blade.php` — fetches from `/api/admin/products`, CRUD modals, featured toggle, search/filter
- `pages/admin/inventory/index.blade.php` — fetches stats/warehouses/revenue, stat cards, warehouse sync, low-stock alerts

### API Routes (under `/api/admin/`)
```
GET    /api/admin/products              → ProductController@index
POST   /api/admin/products              → ProductController@store
PUT    /api/admin/products/{id}         → ProductController@update
DELETE /api/admin/products/{id}         → ProductController@destroy
PATCH  /api/admin/products/{id}/featured → ProductController@toggleFeatured
GET    /api/admin/inventory/stats       → InventoryController@stats
GET    /api/admin/inventory/warehouses  → InventoryController@warehouses
POST   /api/admin/inventory/sync        → InventoryController@forceSync
GET    /api/admin/revenue               → InventoryController@revenue
GET    /api/admin/promos                → PromoBannerController@index
POST   /api/admin/promos                → PromoBannerController@store
DELETE /api/admin/promos/{id}           → PromoBannerController@destroy
```

### Models — all copied from Esteban's originals unchanged (Warehouse, WarehouseStock, PromoBanner, RevenueOverview)

### Updated Code (Hainz / Agner / other)
- `ShopController::mapProduct` — `$p->id` → `$p->product_id`, `$p->name` → `$p->product_name`, `$p->featured_image` → `$p->product_image`
- `WishlistController` — same column renames + image URL logic
- `PaymentService` — same column renames + image URL logic
- `CartController` — validation `exists:product_table,product_id`
- `DashboardService` — `$product->name` → `$product->product_name`
- `CartItemsList` view — `featured_image` → `product_image`, `name` → `product_name`
- `ProductSeeder` — all column names updated
- Dummy routes/views — all column names updated
- `CartItem`, `OrderItem` — FK relationship local key `'id'` → `'product_id'`

## Pending Improvements
- Admin auth login page (using `users` table) — link from sidebar "Sign Out" leads to current customer logout
- Promo banners UI (API exists, SPA view not yet wired)
- Seeders for warehouses, warehouse_stock, revenue_overview, promo_banners
- Product image upload in SPA (formData with image file — currently supported in API but need to test)
