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

### ERPV3.3.4.2: Google OAuth hardening
- **Production login fixed:** Removed the `redirectUriIsAllowed()` / `loopbackBaseUrl()` bounce that redirected any non-localhost host to `127.0.0.1`, which broke Google login on a real domain. Replaced with `syncRedirectUri()` — honors an explicit `GOOGLE_REDIRECT_URI` (production) and otherwise falls back to the current host (local dev). Only `app/Http/Controllers/Auth/GoogleController.php` changed.
- **Null/empty-email guard:** `callback()` now rejects Google accounts with no email (phone-only) or an unverified email before `updateOrCreate`, instead of creating a broken null-email record that collided every such login onto one row. `email_verified` is read from the raw Socialite response (`getRaw()['email_verified']`), since it isn't a mapped User property.
- **Local profile edits preserved:** On subsequent logins the callback no longer overwrites `first_name`/`last_name` from Google; it only refreshes `profile_picture`, `email_verified_at`, `last_login`, and reactivates `Inactive` accounts. First-time creation still seeds the name from Google.
- **Not touched:** `config/services.php` (redirect still env-driven), the `google.redirect`/`google.callback` routes, `ChangePasswordController`/`ProfileController` `auth_via` gating, and all views.
- **Known follow-ups (not done, out of scope):** persist an `auth_provider` column instead of the session-only `auth_via` flag; add a "set a password" flow so Google users aren't locked out of password login.

### ERPV3.3.4.3: Google login silent-reload diagnosed + error now visible
- **Root cause:** `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET` were absent from `.env`, so `GoogleController::redirect()` hit the `hasGoogleCredentials()` guard and bounced back to `login` — which looked like a silent page reload (no error was shown).
- **`.env` scaffolding:** Added `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, and `GOOGLE_REDIRECT_URI` (empty, with a guidance comment). `GOOGLE_REDIRECT_URI` can stay blank in dev — `syncRedirectUri()` auto-detects the current host.
- **Visible error:** Added a generic `session('error')` red banner to `resources/views/auth/login.blade.php` (your `[AGNER]` auth view) so "Google login is not configured" and other flash errors now display instead of reloading silently.
- **Not touched:** `GoogleController` logic, routes, `config/services.php`.
- **Still required (user action):** a real OAuth client must be created in Google Cloud Console and the correct redirect URI authorized — see chat notes. Claude cannot generate the client ID/secret.

### ERPV3.3.4.4: Google OAuth redirect-URI empty-string bug fixed
- **Bug:** `syncRedirectUri()` returned early whenever `config('services.google.redirect')` differed from the `app.url` default. Because an empty `GOOGLE_REDIRECT_URI` env var resolves to `""` (not `null`), the `services.php` fallback never applied, so the configured value was `""` and the method bounced out without ever setting the real callback URI — OAuth would have sent an empty redirect and failed.
- **Fix:** Guard now uses `filled($configured) && $configured !== $default` so an empty/missing value correctly falls back to `request()->getSchemeAndHttpHost().'/auth/google/callback'`. A real explicit `GOOGLE_REDIRECT_URI` is still trusted as-is.
- **Verified:** `php artisan config:show services.google` confirms the client ID/secret load from `.env`; controller passes `php -l`.
- **Note:** Real client credentials were added to `.env` (values omitted here on purpose — secrets must not be committed). Default dev host is `http://localhost:8000`, so the authorized redirect URI is `http://localhost:8000/auth/google/callback`.

### ERPV3.3.6: GCash number input formatting + validation
- **Frontend (`add-payment-gcash-fields.blade.php`):** GCash Number input now `inputmode="numeric"`, `id="gcashNumber"`, `maxlength="12"`, placeholder `912 345 6789`, plus hint "10-digit Philippine mobile number, must start with 9." (was a plain `type="text"`, `maxlength="10"`, placeholder `9123456789` — you could type letters/anything).
- **Frontend (`add-payment-scripts.blade.php`):** New handler on `#gcashNumber` strips non-digits, caps at 10 digits, and auto-formats as `912 345 6789` (3-3-4) as you type. On submit it strips the spaces so the backend gets clean digits.
- **Backend (`PaymentMethodController`):** `gcash_number` rule max raised `10` → `12`. Validation tightened from "exactly 10 digits" to a valid PH mobile number `^9\d{9}$` (10 digits starting with 9) → "Enter a valid GCash number (10 digits starting with 9)." `store`/`update` now strip non-digits before building `masked_account_number` (`+63…`).
- **Why:** You could previously type `123567890` (9 digits, wrong leading digit) and the weak check only counted digits. Now the field formats itself and rejects anything that isn't a real PH GCash number.
- **Not touched:** card fields/Visa-Mastercard validation, routes, `SavedPaymentMethod` model, `payment-methods` list/other views, the other changelog files. Controller passes `php -l`.

### ERPV3.3.7: Pagination for Wishlist, My Orders, Order History
- **Limits (by card height):** Wishlist `paginate(10)` (compact rows), My Orders `paginate(7)`, Order History `paginate(7)` (tall cards).
- **Why these limits:** user asked me to pick per the box/div size — wishlist rows are short so 10 fits ~one screen; order/history cards are tall (~300px) so 7 keeps it to ~2 screenfuls.
- **Auto-hide + incremental:** All three render `{{ $xxx->withQueryString()->links() }}`. Laravel's default Tailwind pager only shows when `hasPages()` is true, so it appears only once the list exceeds the limit (e.g. 20 wishlist items @10 → 2 pages, never one giant list). `withQueryString()` preserves `sort`/`status`/`search` across page links.
- **Wishlist (`WishlistController@index` + `wishlist-items`/`wishlist-toolbar`):** `->get()` → `->paginate(10)`; toolbar count changed `$items->count()` → `$items->total()` so it shows full size, not just the page.
- **My Orders (`OrderController@index` + `orders-tabs`):** status filter moved server-side (`?status=all|processing|delivered`, applied via `when()`); tabs are now `<a>` links with active state from `request('status')`; `->get()` → `->paginate(7)`. Deleted `orders-scripts.blade.php` (its `filterOrders()` JS is obsolete) and removed its `@include`.
- **Order History (`OrderController@history` + `history-header`/`history-list`/`history-scripts`):** search moved server-side (`?search=`, scoped `(order_number OR order_id OR item product_name)` inside a closure so the `delivered` filter isn't broken by OR precedence); `->get()` → `->paginate(7)`; search input keeps its value via `request('search')`; `history-scripts` now does a 400ms-debounced redirect to `?search=…` (drops `page`) instead of client-side filtering.
- **Why server-side filters:** orders (status tabs) and history (search) originally filtered client-side, which would only act on the current page under pagination — so those filters had to move server-side to stay correct.
- **Not touched:** card markup, routes, `Order` model/scopes, admin pages, other customer pages, the admin `orders-scripts` (different file). Both controllers pass `php -l`.
