# Esteban Part V2 — Changelog

## Data Flow — How Esteban's Code Connects to Our Database

### Product List (Table)
| Esteban's JS expects | Maps to our DB column | API returns |
|---|---|---|
| `p.id` | `products.id` | `p.id` |
| `p.name` | `products.name` | `p.name` |
| `p.brand` | `products.brand` | `p.brand` |
| `p.sku` | `products.sku` | `p.sku` |
| `p.category` | `products.category` (string) | `p.category` |
| `p.price` | `products.price` | `p.price` |
| `p.stock` | `products.stock` | `p.stock` |
| `p.is_featured` | `products.is_featured` (added) | `p.is_featured` |
| `p.featured_image` | `products.featured_image` | `p.featured_image` |
| `p.image_url` | Accessor: `asset('storage/' . $this->featured_image)` | `p.image_url` |

### Category
- **Form**: `<input type="text">` with `<datalist>` — user types any category
- **Filter**: Dynamically populated from `SELECT DISTINCT category FROM products`
- **Source**: `GET /api/admin/categories` → returns distinct `products.category` values
- **Storage**: Saved to `products.category` string column
- **Backfill**: Existing products' `category` copied from `categories.name` via `category_id`

### Brand
- **Filter**: Dynamically populated from loaded products' unique brand values
- **Source**: Extracted client-side from API response (`[...new Set(products.map(p => p.brand))]`)
- **Storage**: `products.brand` column

### Image Upload
- **Storage**: `$request->file('featured_image')->store('products', 'public')` → saves as `products/filename.jpg`
- **URL**: Accessor `getImageUrlAttribute()` returns `asset('storage/' . $this->featured_image)` → `/storage/products/filename.jpg`
- **JS mapping**: `p.image_url` from API used directly in `<img src>`

### API Endpoints
| Method | Route | Controller | DB Table |
|---|---|---|---|
| GET | `/api/admin/products` | `ProductController@index` | `products` |
| POST | `/api/admin/products` | `ProductController@store` | `products` + `warehouse_stock` |
| PUT | `/api/admin/products/{id}` | `ProductController@update` | `products` + `warehouse_stock` |
| DELETE | `/api/admin/products/{id}` | `ProductController@destroy` | `products` |
| PATCH | `/api/admin/products/{id}/featured` | `ProductController@toggleFeatured` | `products.is_featured` |
| GET | `/api/admin/promos` | `PromoBannerController@index` | `promo_banners` |
| POST | `/api/admin/promos` | `PromoBannerController@store` | `promo_banners` |
| DELETE | `/api/admin/promos/{id}` | `PromoBannerController@destroy` | `promo_banners` |
| GET | `/api/admin/categories` | Closure | `products.category` (DISTINCT) |

### Key Differences from Esteban's Original
| Aspect | Esteban's Original | Our Adaptation |
|---|---|---|
| Table name | `product_table` | `products` |
| Primary key | `product_id` | `id` |
| Product name col | `product_name` | `name` |
| Image col | `product_image` | `featured_image` |
| Image URL | `/storage/products/` + filename | `/storage/` + filename (via accessor) |
| Category form | `<select>` hardcoded | `<input type="text">` + datalist from DB |
| Category filter | `<select>` hardcoded | Dynamically from `products.category` |
| Brand filter | `<select>` hardcoded | Dynamically from products data |
| Layout | Standalone `layouts.admin` (sidebar+topbar+JS inline) | Uses project's `layouts.admin` + separate sidebar/topbar |
| API prefix | `/api/` | `/api/admin/` |
| Auth middleware | None | `auth` + `role:super_admin,admin` |

## V2.1 — Product Page Setup

### Migrations
- `add_is_featured_and_category_to_products` — Added `is_featured` (boolean, default false) and `category` (string, nullable) to `products` table; made `category_id` nullable
- `create_warehouses_table` — New table: `warehouse_id` PK, `warehouse_name`, `location`, `sync_status`, `last_sync_at`, timestamps
- `create_warehouse_stock_table` — New table: `warehouse_stock_id` PK, FK `warehouse_id` → `warehouses.warehouse_id`, FK `product_id` → `products.id`, `quantity`, timestamps
- `create_promo_banners_table` — New table: `banner_id` PK, `title`, `subtitle`, `is_active`, timestamps

### Models
- `App\Models\Product` — Added `is_featured`, `category` to `$fillable`; added `warehouseStock()` HasMany relationship; added `getImageUrlAttribute()` accessor (returns `asset('storage/' . $this->featured_image)`); added `$appends = ['image_url']`
- `App\Models\PromoBanner` — Created with `$table='promo_banners'`, `$primaryKey='banner_id'`, fillable: `title`, `subtitle`, `is_active`

### Controllers (App\Http\Controllers\Admin\Api)
- `ProductController` — Esteban's exact CRUD logic adapted to our schema:
  - `index()` — search/filter by search, category, brand, stock
  - `store()` — create product + insert warehouse_stock for primary warehouse
  - `update()` — update product + sync warehouse_stock quantity
  - `destroy()` — delete product + cleanup storage image
  - `toggleFeatured()` — toggle `is_featured` boolean
  - Columns adapted: `name` (was `product_name`), `featured_image` (was `product_image`), `id` (was `product_id`)
  - `category_id` FK kept nullable; `category` string used instead
- `PromoBannerController` — Esteban's exact CRUD: `index()`, `store()`, `destroy()`

### Routes
- Added under `prefix('api/admin')` with `['auth', 'role:super_admin,admin']` middleware:
  - `GET/POST /products` — list/create
  - `PUT/DELETE /products/{id}` — update/delete
  - `PATCH /products/{id}/featured` — toggle featured
  - `GET/POST /promos` — list/create
  - `DELETE /promos/{id}` — delete

### Views
- `resources/views/components/admin/product.blade.php` — Copied exactly from Esteban's ref (unchanged)
- `resources/views/pages/admin/products/index.blade.php` — New page:
  - Extends `layouts.admin`
  - Includes project sidebar (`components.admin.sidebar`) + topbar (`pages.admin.dashboard.components.topbar`)
  - Embeds `<x-admin.product />`
  - Includes toast, product modal, delete modal
  - Adapted JS:
    - `API_BASE = '/api/admin'`
    - Column mapping: `p.id` (was `p.product_id`), `p.name` (was `p.product_name`), `p.featured_image` (was `p.product_image`), `p.image_url` from API
    - Image URL: uses `p.image_url` from API response
    - Form fields use `name` (was `product_name`) and `featured_image` (was `product_image`)
    - No navigation handlers (single page)
    - No notifBtn/signOutBtn handlers (in project layout)

### Key Design Decisions
- Our existing `products` table column names kept intact; Esteban's JS adapted to map from our columns
- `category` string column used for Esteban's category selector (not the FK `category_id`)
- `warehouse_stock` FK references `products.id` (not `product_table.product_id`)
- Image path: `store('products', 'public')` returns `products/filename.jpg`; JS builds URL as `/storage/products/filename.jpg` via `p.image_url`

## V2.2 — Layout Fixes

### View Structure
- Fixed page layout to match admin dashboard pattern: `flex min-h-screen` wrapper around sidebar + `flex-1 min-w-0` content area
- Added missing `#toast`, `#productModal`, `#deleteModal` elements (were in Esteban's layout, not ours)
- Added inline CSS for grid layout: Tailwind v4 doesn't detect `xl:grid-cols-[1fr_300px]` arbitrary value from component files
- Explicit `@media (min-width: 1280px) { #page-product }` rule for `display: grid` + `grid-template-columns: 1fr 300px`

## V2.3 — Image Storage Consistent with Customer-Facing Views

### Files Changed
- `app/Http/Controllers/Admin/Api/ProductController.php`:
  - Store: prepends `storage/` to `$request->file('featured_image')->store('products', 'public')`
  - Update: same for new image; strips `storage/` before `Storage::disk('public')->delete($oldPath)`
  - Destroy: strips `storage/` before `Storage::disk('public')->delete($oldPath)`
- `app/Models/Product.php`:
  - `getImageUrlAttribute()`: `asset($this->featured_image)` (was `asset('storage/' . $this->featured_image)`)

### DB Backfill
- Ran `UPDATE products SET featured_image = CONCAT('storage/', featured_image) WHERE featured_image NOT LIKE 'storage/%'` to fix existing product's image path

### Result
- Admin-stored images now visible on shop listing, product detail, and cart pages
- Storage format: `storage/products/filename.jpg` (matches existing dummy route convention)
- Customer views: ShopController `asset($p->featured_image)`, cart raw `featured_image` as `src` — both work correctly with `storage/` prefix

## V2.4 — Category & Brand Filters Connected to Database

### Migrations
- `backfill_product_category_from_categories` — backfills `products.category` from `categories.name` via `category_id` for existing products

### Routes
- Added `GET /api/admin/categories` — returns distinct category values from `products.category` column

### Views
- Changed add/edit modal's category from `<select>` (hardcoded) to `<input type="text">` with `<datalist>` populated from `products.category` distinct values
- Category filter dropdown now dynamically populated from `products.category` (via JS `loadCategorySuggestions()`)
- Brand filter dropdown now dynamically populated from loaded products' unique brands (via JS `populateBrandFilter()`)

## V2.5 — Featured Product Toggle (Single Featured)

### Backend
- `app/Http/Controllers/Admin/Api/ProductController.php::toggleFeatured()` now enforces **single featured product** — when a product is set as featured, all others are unfeatured first

### UI
- Added star (★) button to each table row to toggle featured status
- Filled star = featured (amber), outline star = not featured
- Clicking triggers `PATCH /api/admin/products/{id}/featured`, updates row + featured sidebar immediately
- Featured sidebar list (`renderFeatured()`) updates on toggle

## V2.6 — Specifications & Compatibility in Product Form

### Backend
- `app/Http/Controllers/Admin/Api/ProductController.php`:
  - `index()` — added `->with(['specifications', 'compatibilities'])` so API response includes specs/compat
  - `store()` / `update()` — decodes JSON strings from FormData (`specs`, `compat`) and saves via wipe-and-replace pattern
  - Added `saveSpecs()` and `saveCompat()` private helpers

### UI (Product Modal)
- Modal widened from `max-w-md` to `max-w-2xl` with scroll
- Basic fields rearranged into 2-column grid
- **Specifications**: dynamic rows with text inputs for Section, Label, Value
- **Compatibility**: dynamic rows with text inputs for Type, Item Name
- Section/Type are plain `<input>` (free text), not dropdowns

### JS
- `formSpecs` / `formCompat` arrays manage dynamic rows
- `renderSpecRows()` / `renderCompatRows()` build the row DOM
- `collectSpecData()` / `collectCompatData()` read values from DOM on submit
- `openEditModal()` pre-populates from `p.specifications` / `p.compatibilities`
- `openAddModal()` resets specs/compat to empty
- Form submit appends `specs` and `compat` as JSON strings to FormData

## V2.7 — Header categories from products.category; category_id synced

### Fix
- Header (`header.blade.php`) now reads categories from **distinct `products.category`** values instead of the `categories` table (which only had "Components")
- Mega menu groups products by `products.category` (free-text) instead of `Category` relationship
- `ProductController` — added `syncCategoryId()` helper that `firstOrCreate`s a `Category` record matching the free-text `category` and sets `category_id`, called on both store and update

## V2.8 — Featured Product Hero Section (Customer Shop)

> Not in scope — moved to Hainz's changelog (`changelog_hainz_part.md` ERPV1.6)
