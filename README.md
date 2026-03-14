# Multi-Tenant eCommerce

A multi-tenant eCommerce platform built with Laravel 12 and Vue.js. Each store gets its own isolated MySQL database, so data from one store never bleeds into another.

## What it does

Store owners register an account, create a store, and get a subdomain like `mystore.localhost`. From there they manage their own product catalog. Customers browse the central marketplace at `localhost` and can add items from any store to their cart.

## Stack

- Laravel 12
- Vue 3 + Inertia.js
- Stancl Tenancy v3 (multi-database)
- MySQL
- Tailwind CSS

## Architecture

Central Database (multi_tenant_ecommerce)
├── users         — registered central accounts
├── tenants       — store records & metadata
├── domains       — subdomain mapping
└── sessions      — central session management

Tenant Database (Dedicated per store)
├── users         — tenant-specific user records
├── products      — isolated product catalog
└── carts         — isolated shopping cart data


When a tenant is created, Stancl automatically provisions a new MySQL database and runs migrations into it. All product and order data lives inside that tenant's own database. The central database only knows about users, store metadata, and domains.

## Local Setup
```bash
git clone https://github.com/zhikov4/multi-tenant-ecommerce.git
cd multi-tenant-ecommerce
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=multi_tenant_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

Then run:
```bash
php artisan migrate
php artisan serve
```

Add tenant subdomains to `/etc/hosts`:
```
127.0.0.1 mystore.localhost
127.0.0.1 topstore.localhost
```

## Vendor Patch

This project includes a fix to `vendor/stancl/tenancy/src/DatabaseConfig.php` to resolve a driver resolution bug in Stancl Tenancy v3.9. After running `composer install`, reapply the patch by copying the fixed file:
```bash
cp patches/DatabaseConfig.php vendor/stancl/tenancy/src/DatabaseConfig.php
```

## Creating a Store

Register an account at `http://localhost:8000`, then go to Dashboard and create a store. A new database is provisioned automatically.

To manually provision an existing tenant:
```bash
php artisan tenants:migrate --tenants=storename
```

## Running Tests
```bash
php artisan test
```

Tests cover tenant isolation, product CRUD per tenant, and the full auth flow.