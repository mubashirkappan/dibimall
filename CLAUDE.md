# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Laravel 10 + Filament 3 backend for the **Fooddly / Dibimall** platform (git remote: `mubashirkappan/dibimall`). It serves two things: a JSON API under `/api` consumed by the Nuxt storefront at `/home/developer2/Projects/assakin-web-vue` (see that repo's `CLAUDE.md`), and a Filament admin panel. PHP ^8.1.

## Commands

```bash
php artisan serve                 # http://127.0.0.1:8000 — the Nuxt app expects .../api
php artisan migrate
php artisan storage:link          # required, uploads are served from storage/app/public
php artisan optimize:clear
php artisan route:list --path=api

./vendor/bin/pint                 # code style (Laravel Pint)
php artisan test                  # full suite
php artisan test tests/Feature/Api
php artisan test --filter=test_it_decrements_stock_by_matching_item_name_within_the_shop
```

`vite`/`package.json` exist but only serve the Filament/Blade side; the customer UI is the separate Nuxt repo.

## Testing

`tests/Feature/Api/` covers the API surface the storefront depends on, and each test is written to pin a behavior that is surprising rather than to restate the framework. Read them alongside this file — several document sharp edges more precisely than prose can.

`phpunit.xml` sets `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:`. **Leave those enabled.** They were commented out originally, which meant `RefreshDatabase` would have run against whatever database `.env` points at — i.e. wiping the development database.

Factories exist for `Customer`, `Place`, `Type`, `Shop`, `Category`, `Item`, with named states that mirror the domain rules: `Customer::factory()->owner()` / `->pendingOwner()`, `Item::factory()->outOfStock()` / `->inactive()`, `Shop::factory()->inactive()`, `Place::factory()->inactive()`. Prefer these over hand-built arrays — they already satisfy the non-null columns and the `place -> type -> customer` chain a shop needs.

Authenticate a test request with `Sanctum::actingAs($customer)`, never `actingAs($user)` — see the two-identities section below.

The 2 reported deprecations are vendor-level (PHP 8.4 against PHPUnit 10 / Collision), not repository code.

## API reference

A Postman collection covering all 46 routes lives at `postman/Dibimall.postman_collection.json`. Import it, set `baseUrl`, and run **Auth > Customer Login** — a test script captures the bearer token into a collection variable for every other request. Each request carries a description explaining the payload and any trap; that collection and `routes/api.php` are the two sources of truth, so update the collection when you add a route.

Cross-cutting rules for every endpoint:

- Responses are `{ success, data, message }` from `BaseController`.
- **Business failures return HTTP 404**, because Actions catch their own exceptions and `sendError()` defaults to 404. Branch on `success`, not on the status code. `ItemsController` is the exception — it returns 500 on failure.
- FormRequest validation failures do return a normal 422.
- Destructive/update routes take an **encrypted id** in the path — with three inconsistencies: `categories/update` takes it in the *body*, `items/update` takes the *raw numeric* `id`, and `offer/delete/{id}` takes the *raw numeric* id.
- Several destructive operations are exposed over `GET` (`shop/delete`, `items/delete`, `categories/delete`, `offer/delete`, `items/status-change`).

## Architecture

### Controller → Action pattern

Controllers are thin, extend `BaseController`, and never contain business logic. Each endpoint delegates to a single-purpose class in `app/Actions/<Domain>/`, then branches on the returned array:

```php
$response = $action->execute($request);
return $response['success']
    ? $this->sendSuccess($response['data'], $response['message'])
    : $this->sendError($response['message']);
```

Actions catch their own `\Throwable` and return `['success' => false, 'message' => ...]`, so exceptions never bubble to Laravel's handler.

This is a convention, not an interface — there is no base class and no shared signature. Most Actions expose `execute()`, but `CustomerLoginAction` is invokable (`$action($request->validated())`), and argument shapes vary: some take the Request, others take scalars (`execute($encrypted_id)`, `execute($userId)`, `execute($city, $shop, $from)`). Check the class before calling it.

Validation lives in `app/Http/Requests/*`; output shaping in `app/Http/Resources/*`. Some controllers still call `request()->validate()` inline for one- or two-field payloads.

### Two identities, two auth systems

- **`User`** (`users` table, `web` guard) — Filament admins only. `User::canAccessPanel()` requires the email to end in `@localhost` **and** the email to be verified.
- **`Customer`** (`customers` table, `customer` guard, soft-deleted) — every API consumer: buyers and shop owners alike. Issues Sanctum tokens via `createToken('MyApp')`. `auth()->user()` inside API routes is always a `Customer`.

`config/sanctum.php` lists `'guard' => ['web']`, which looks contradictory but is not: that array is only the *stateful session* fallback. Token requests resolve through the `personal_access_tokens` morph straight to `Customer`.

`Customer::setPasswordAttribute` hashes on assignment, so never pass an already-hashed value. `UserController::resetPassword` also accepts legacy `md5` passwords for migration.

For `method: normal`, login matches `identifier` against the **`username` column only** — the email and phone lookups are commented out in `CustomerLoginAction`, despite the field's generic name and the request rules still requiring one of email/phonenumber/identifier.

### The ownership lifecycle

`Customer::user_type` drives authorization: `1` = buyer, `2` = approved shop owner, `3` = **pending owner approval**. The `is.owner` middleware (`app/Http/Middleware/IsOwner.php`, aliased in `app/Http/Kernel.php`) gates the owner half of `routes/api.php` on `user_type == 2`.

The transitions matter, because there is no self-service path to owner:

- Registration with `is_owner` truthy → `3`; otherwise `1` (`CustomerRegisterAction`).
- `GET /api/update-to-owner` moves `1 → 3` only, and rejects any other starting type.
- **Only an admin grants `2`**, via the Approve/Decline row actions on `app/Filament/Admin/Resources/CustomerResource.php` (visible only for `user_type == 3`; the table is `orderByRaw`'d so pending requests sort to the top). Decline sends them back to `1`.
- `CreateShopAction:51` writes `user_type => 2`, but `create-shop` already sits behind `is.owner`, so that line is a redundant re-write of an existing owner — never a promotion path.

`IsOwner` denies with `redirect()->back()`, so an API client gets a **302 to HTML rather than a 403/JSON**. Expect confusing frontend errors from a non-owner hitting an owner route.

### Filament panels

- `AdminPanelProvider` — `/admin`, the default panel, resources auto-discovered from `app/Filament/Admin/Resources`.
- `OwnerPanelProvider` — `/owner`, with login + registration, but `app/Filament/Owner/` **does not exist**, so it has no resources. Shop owners actually self-serve through the Nuxt shop-management pages against the API, not this panel.
- `app/Filament/Widgets/CustomerOverview.php` is orphaned for the same reason: the panels discover widgets in `Filament/Admin/Widgets` and `Filament/Owner/Widgets`, neither of which exists.

`routes/web.php` redirects `/` to `/admin/login`; there is no public web UI.

### Domain model

`Shop` is the central entity. `slug` is what the API and the storefront call `user_name`, and it is the public storefront URL segment. `shops.from` is a **tenant discriminator** (defaults to `'thasweel'`); shop-listing queries filter on it, so omitting it leaks other tenants.

Ordering has two independent lineages — both are live, do not consolidate them casually:

- **`TasOrder` / `TasOrderItem`** — the current storefront path. `POST /api/order` (`TasOrderController::orderFromTas`, unauthenticated) writes the order and its items in one shot, then the frontend hands off to WhatsApp. Carries `item_note`, `preparation_preference`, `unit`. Owners read them via `/order-list` and mark `/deliverd`. `TasOrder::booted()` cascades item deletes.
- **`Cart` / `Order` / `OrderItem`** — the older authenticated marketplace cart (`/add-to-cart`, `/confirm-order`, `/accept-order`, `/complete-order`). `Cart.purchased` distinguishes an open cart row from a historical one.

Supporting models: `Category` and `Item` (both per-shop), `Place` + `Type` (shop taxonomy, `Place` has an `active` scope), `OfferImage` (in-shop banner carousel), `ContactUs`, `TrackPhonenumberClickedUser` (logs who revealed a shop's phone number).

### The Item vs Items split

Two near-identical names one letter apart, easy to edit the wrong one:

| | Public read path | Owner CRUD |
|---|---|---|
| Controller | `ItemController` | `ItemsController` |
| Actions | `app/Actions/Item/` | `app/Actions/Items/` |
| Route | `POST /api/items` | `POST /api/items/list`, `/create`, `/update`, … |
| Error shape | 404 via `BaseController` | 500 via raw `response()->json` |

### Conventions worth knowing before editing

- **Encrypted IDs.** `Shop`, `Item`, `Cart`, `Customer` append an `encrypted_id` attribute using Laravel's `encrypt()`. `ShopResource` names it `encrypt_id`, without the `ed`.
- **`dibi_price` is the real selling price.** `ItemResource` exposes it as `db_price`; `price` is the higher struck-through MRP. Don't swap them.
- **Stock decrement matches on name.** `orderFromTas` does `Item::where('name', $value['name'])->where('shop_id', ...)->decrement('count', ...)`, so duplicate item names within one shop double-decrement, and a rename between add-to-cart and checkout silently no-ops. Both behaviors are pinned in `StorefrontOrderTest`.
- **`ListItemAction` hides zero-stock items** (`where('count', '>', 0)` + `active` scope), so an item can vanish from the storefront without being deactivated.
- **An inactive `Place` hides every shop attached to it**, even active ones, because `ListShopAction` resolves place ids through `Place::active()`.
- **Uploads.** Actions write to `Storage::disk('public')` with `time().'.'.$ext` as the filename — two uploads in the same second collide. Models expose `image_url` via `asset('storage/'.$name)`.
- **Quotas.** `Customer.shop_count` caps shops per owner (`CreateShopAction`); `Shop.item_count` caps items per shop (`SaveItemAction`). Both fail with "contact admin" messages.
- **Mass assignment is wide open.** Every model except `User` and `ContactUs` sets `protected $guarded = []`, so `create()`/`update()` payloads must be constructed explicitly rather than passed straight from a request.
- **`deliverd` is the real spelling** of both the route path and the `tas_orders.status` enum value. Don't "fix" it without a migration.
- `config/cors.php` allows all origins on `api/*`.

### Known-broken

- **`GET /api/shop/show/{user_name}`** is registered against `ShopController@showShopDetails`, but no such method exists — the route 500s. Use `GET /api/shops?shop=<slug>` instead.
- **The migration set could not run from scratch** until recently: `create_tas_orders_table` was retroactively edited to declare `unit`, `preparation_preference` and `item_note` on `tas_order_items`, which the later `2026_08_12_180410_add_new_columns_in_tas_order_items_table` also adds. Production survived because it migrated incrementally; a fresh `migrate` hit a duplicate-column error. That later migration is now guarded with `Schema::hasColumn`. **Before editing any historical migration, check whether a later one already adds the same column** — the same trap is easy to re-introduce.

## CI

`.github/workflows/tests.yml` runs the suite on every PR targeting `main`, on pushes to `main`, and on manual dispatch, across PHP 8.2 and 8.3.

**Do not add 8.4 to that matrix.** `nette/schema` and `nette/utils` (pulled in transitively via Filament) cap at `< 8.4`, so a clean `composer install` fails outright on 8.4 — even though an already-installed `vendor/` happens to run there, which is why local dev on 8.4 works and reports two vendor-level deprecations. On 8.2 the suite is clean.

The workflow copies `.env.example` to `.env` *before* `composer install` (the post-autoload-dump hook runs `artisan package:discover` and `artisan filament:upgrade`, which need an env to boot against), then runs `key:generate` because the tests exercise `encrypted_id` attributes.

## Deploy

`.github/workflows/deploy.yml` fires on push/merge to `main` (or manual dispatch): SSHes to Hostinger, `git pull`, `composer install`, `artisan migrate --force`, `optimize:clear`, `optimize`. Merging to `main` deploys to production immediately, migrations included.

Two things to know about that trigger set: it also listens for `pull_request` `types: [closed]`, which fires when a PR is closed **without** being merged; and merging a PR fires both `push` and `pull_request.closed`, so a merge deploys twice. Deploying is also independent of the test workflow — nothing blocks a red build from shipping unless `main` has branch protection with the `PHP 8.2` / `PHP 8.3` checks marked required.
