# MyStore — Multi-Tenant eCommerce Platform

A multi-tenant eCommerce SaaS platform where each merchant operates in a fully isolated environment with a dedicated MySQL database. Built on Laravel 12, Vue 3, Inertia.js, and Stancl Tenancy v3.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12.x |
| Frontend | Vue.js 3.x + Inertia.js |
| Multi-Tenancy | Stancl Tenancy 3.x (multi-database) |
| Database | MySQL 8.x |
| CSS | Tailwind CSS 3.x |
| Routing | Ziggy |
| Build Tool | Vite |

## Architecture

Each store (tenant) gets its own dedicated MySQL database. Tenant identification is subdomain-based.

- Central DB (`multi_tenant_ecommerce`): users, tenants, domains, carts
- Tenant DB (`tenant{id}`): products, tenant users

**Request flow:** Browser → `shopname.localhost:8000` → `InitializeTenancyByDomain` middleware → tenant lookup → DB switch → serve request → `RevertToCentralContext`

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8

## Installation
```bash
git clone https://github.com/zhikov4/multi-tenant-ecommerce.git
cd multi-tenant-ecommerce

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Configure `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=multi_tenant_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and build assets:
```bash
php artisan migrate
php artisan tenants:migrate --force
php artisan ziggy:generate
npm run build
```

## Creating a Tenant

**Via the UI:**

1. Go to `http://localhost:8000`
2. Register an account
3. Dashboard → Register New Store (3-step wizard)

**Via Tinker:**
```bash
php artisan tinker
```
```php
$tenant = App\Models\Tenant::create(['id' => 'mystore']);
$tenant->domains()->create(['domain' => 'mystore.localhost']);
exit
```

Add to `/etc/hosts`:
```bash
sudo sh -c 'echo "127.0.0.1 mystore.localhost" >> /etc/hosts'
```

Start the server:
```bash
php artisan serve
```

## Key URLs

| URL | Description |
|---|---|
| `http://localhost:8000` | Central marketplace |
| `http://localhost:8000/dashboard` | User personal hub |
| `http://localhost:8000/cart` | Shopping cart |
| `http://{tenant}.localhost:8000/products` | Tenant product management |
| `http://{tenant}.localhost:8000/products/create` | Add new product |
| `http://{tenant}.localhost:8000/products/{id}/edit` | Edit product |

## Running Tests
```bash
php artisan test
```

## Project Structure
```
app/
├── Http/Controllers/
│   ├── Central/
│   │   ├── DashboardController.php
│   │   ├── WelcomeController.php
│   │   ├── CartController.php
│   │   └── TenantRegisterController.php
│   └── Tenant/
│       └── ProductController.php
├── Models/
│   ├── Tenant.php
│   ├── Product.php
│   ├── Cart.php
│   └── User.php
└── Providers/
    └── TenancyServiceProvider.php

resources/js/Pages/
├── Welcome.vue
├── Dashboard.vue
├── Cart.vue
└── Tenant/Products/
    ├── Index.vue
    ├── Create.vue
    └── Edit.vue

routes/
├── web.php       (central routes)
└── tenant.php    (subdomain routes)
```

## License

MIT