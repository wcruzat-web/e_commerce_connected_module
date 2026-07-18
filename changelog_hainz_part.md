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
- [x] `ProductSpecification.php` — updated fillable to match new schema: `category_name`, `attribute_name`, `attribute_value`; added `$primaryKey = 'spec_id'`
- [x] `ProductCompatibility.php` — new model, table `product_compabilities`
- [x] `ProductReview.php` — new model, primaryKey `review_id`, no timestamps
- [x] `Category.php` — unchanged (already matches)
- [x] `ProductImage.php` — unchanged (already matches)

### Routes Added [HAINZ]
- [x] `GET /shop` — loads active products from DB with category, specs, compat, reviews → renders `pages.customer.shop.shop`
- [x] `POST /shop/review` — creates review + recalculates product rating
- [x] `POST /shop/decrement-stock` — stock deduction on order
- [x] `POST /shop/restore-stock` — stock restoration on cancel
- [x] `/home`, `/products`, `/products/{product}` now redirect to `/shop`

### Controller Refactor
- [x] Created `ShopController` with 4 methods
- [x] `ShopController@index` — product listing (moved from inline route closure)
- [x] `ShopController@review` — review submission
- [x] `ShopController@decrementStock` — stock deduction
- [x] `ShopController@restoreStock` — stock restoration

### Product CRUD (dummy — deferred)
- [ ] `GET/POST /dummy/add-product`
- [ ] `GET/POST /dummy/add-specs/{product}`
- [ ] `GET/POST /dummy/edit-product/{product}`
- [ ] `GET/POST /dummy/edit-specs/{product}`
- [ ] `GET /dummy/products` — product listing
- [ ] `DELETE /dummy/products/{product}`
- [ ] `GET/POST /dummy/add-image`
- [ ] `POST /dummy/upload-images`

### Assets
- [ ] `public/images/products/` — SVG product images
- [ ] `public/storage/products/` — uploaded product photos

### Views
- [ ] `pages/dummy/shop/index.blade.php` — product catalog
- [ ] `pages/dummy/shop/show.blade.php` — product detail page
- [ ] `pages/dummy/shop/components/search.blade.php` — search component

### Connections to Verify
- [ ] Cart system uses `Product` model (product_id FK)
- [ ] Cart items display real product data (name, image, price)
- [ ] Checkout creates orders referencing real products
- [ ] Payment processes with real product data
- [ ] Success page shows real product info
- [ ] Tracking shows real product info
- [ ] Profile/History shows real purchased products
- [ ] Product stock decremented on order placement
- [ ] Product stock restored on order cancellation

---

*End of plan — ERPV0.5*
