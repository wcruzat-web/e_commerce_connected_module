# agner-dev Changelog

Log of every change made on the `agner-dev` branch, so progress can be recalled across sessions.

**Format:** `ERPV3.3.Z: Title`
- The `Z` (final digit) increments by 1 with each new entry (`.1` → `.2` → `.3` ...).
- `Title` is a short, general description of what was changed. If several changes ship together, use one general title.
- Continue from the current `ERPV3.3.x` line.

> Note: This file is updated by Claude as changes are made. Commits are done by the user — do not commit from the agent.

## Entries

### ERPV3.3.1: Remove Products button from user dashboard sidebar
- **Removed** the `Products` nav link (`route('products.index')`) from the user/customer sidebar — `resources/views/components/sidebar.blade.php` (was lines 28–31).
- **Removed** the orphaned i18n label `nav.products` (English + Filipino) from `resources/js/app.js`, since it was used only by that sidebar button.
- **Kept** the `products.index` route (`routes/web.php:160`) and `ShopController` — still required by the header (category links + search), shop breadcrumb/filters, cart "Start Shopping", orders, history, and order-confirmation pages.
- **Not touched:** admin sidebar (`components/admin/sidebar.blade.php`), all other nav items, routes, controllers, and views.

### ERPV3.3.2: Profile Picture Bug Fixed
- **Root cause:** The `public/storage` symlink was missing, so uploaded pictures (saved to `storage/app/public/profiles/`) were unreachable (404). The topbar also rendered `profile_picture` as a relative URL (`storage/...`), which 404s on any sub-route like `/profile`.
- **Fix 1 — storage symlink:** Created `public/storage → storage/app/public` via `php artisan storage:link`. Uploaded files are now served; the user's earlier upload is immediately visible without re-uploading.
- **Fix 2 — topbar:** `resources/views/components/topbar.blade.php` now renders the picture with `asset()` (plus an `http(s)` check so Google avatars stay absolute), matching the profile form. Topbar updates when the picture is changed in Profile.
- **Not changed:** `ProfileController` (upload logic was already correct — the file + DB were saved), `Customer` model, the profile form, and all other features.

### ERPV3.3.3: Wishlist Select-All & Icon Actions
- **Header:** Replaced the `Move All to Cart` button (`.js-move-all`) with a **Select All / Unselect All** toggle (`.js-select-all`) that checks/unchecks every row checkbox and flips its label.
- **Per-item:** `Move to Cart` is now an **icon-only** cart button (text removed). `Remove` was already a trash icon and was left unchanged. One-by-one move/delete still works via the existing `js-wishlist-move` / `js-wishlist-delete` handlers.
- **Footer:** `Remove Selected` + `Move Selected to Cart` (already in the lower area) now show a **trash icon + cart icon alongside text** (icon+text combo).
- **JS:** Repurposed the old move-everything handler into the select-all toggle (label also re-syncs when boxes are ticked manually). Removed the orphaned i18n key `wishlist.moveAll`.
- **Not touched:** `WishlistController`, routes (all under `[AGNER]`), and all other features — bulk `wishlist.bulkMove` / `wishlist.bulkDestroy` and the per-item routes were already present and reused.

### ERPV3.3.3.1: Wishlist button bug fixes
- **Select All icon:** `check-square.svg` was a 404 at the pinned lucide CDN version (no icon rendered) — swapped to `list-checks.svg` (verified HTTP 200).
- **Out-of-stock items:** Now show only a **"Wait for Stock"** label. They have **no checkbox** (can't be ticked manually or via Select All) and **no Move/Delete buttons**.
- **Unified button sizes:** Per-item Move (cart) and Remove (trash) are now identical icon buttons (`p-2 rounded` + matching border), so size no longer varies with item name.
- **Stock label color:** In Stock = green, Out of Stock = red (was always green).
- **Not touched:** `WishlistController`, routes, and the select-all JS — it already only acts on `.js-wish-check` boxes, which OOS rows no longer have.

### ERPV3.3.3.2: Wishlist icon buttons fixed-size + text ellipsis
- **Fixed icon buttons:** Per-item Move (cart) and Remove (trash) are now locked to `w-9 h-9` squares with a centered icon, so they never resize regardless of content.
- **Text adjusts instead:** Product name + description now truncate with an ellipsis ("…") when too long. The row's left group is `flex-1 min-w-0` (grows/shrinks) and the action group is `shrink-0` (locked), so long names no longer push or misalign the icons.
- **Not touched:** `WishlistController`, routes, JS, and the OOS "Wait for Stock" behavior from 3.3.3.1.

### ERPV3.3.3.3: Wishlist OOS checkbox + Price sort + OOS at bottom
- **OOS checkbox restored:** Out-of-stock items have a checkbox again, so they can be selected and removed (one-by-one or via "Remove Selected"). They still show only "Wait for Stock" with no Move-to-Cart button.
- **Sort by Price:** The "Sort by" dropdown is now functional — `Recently Added` (default), `Price: Low to High`, `Price: High to Low` — submitting `?sort=` to the wishlist route.
- **OOS sink to bottom:** Default (and every sort) orders out-of-stock items last, in-stock first.
- **Controller touched (AGNER):** `WishlistController@index` now reads `sort` and applies `orderByDesc('in_stock')` plus the chosen secondary sort. This is your `[AGNER]` code, so in-scope.
- **Not touched:** routes, JS, the in-stock Move/Delete buttons, and the icon-size/ellipsis fixes from 3.3.3.2.

### ERPV3.3.4: Address Add & Edit Fixed
- **Add route view fix:** `/add-address` rendered `view('customer.add-address')`, which doesn't exist → 404. Changed to the real form `view('pages.customer.addresses.add-address')` (file already present). "+ Add New Address" now opens the form.
- **`address_type` validation fix:** `AddressController` store + update required `in:Shipping,Billing`, but the forms (and the list display + icon logic) use `Home`/`Work`/`Other` — so every submission failed validation silently. Aligned the rule to `in:Home,Work,Other`.
- **Not touched:** route structure/other routes, the address views/forms, the index page, and the delete flow (all already correct). Both edited files are your `[AGNER]` code.

### ERPV3.3.4.1: Custom address delete confirmation
- Replaced the browser's native `confirm()` dialog on address delete with a custom animated modal (fade + scale-in).
- New shared component `resources/views/components/confirm-modal.blade.php` (included in `layouts.customer`); JS in `resources/js/app.js` intercepts forms with `js-confirm-delete`, opens the modal, and its Delete button submits the form (preserving CSRF + `@method('DELETE')`). Cancel / backdrop click / `Esc` all close it.
- Both delete forms — profile "Saved Addresses" (`profile-addresses`) and the addresses index (`address-list`) — now use `js-confirm-delete` instead of `onsubmit="return confirm(...)"`.
- **Not touched:** the delete route/controller, add/edit forms, and the wishlist delete (already toast/ajax-based).
