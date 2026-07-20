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
