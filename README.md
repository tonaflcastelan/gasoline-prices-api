# gasoline-prices-api

This test is make with Laravel

Follow the next steps:

## Install

Clone repo

```bash
git clone git@github.com:tonaflcastelan/gasoline-prices-api.git
```

Run composer

```bash
composer install
```

Setup your credential in *.env* file

- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE={your-db}
- DB_USERNAME={you-root}
- DB_PASSWORD={you-password}

## Run migrations and populate DB

Run next command to create tables

```bash
php artisan migrate
```

Run next command to populate tables (states, municipalities, zipcodes)

```bash
php artisan import:sepomex
```

Run next comman to populate gasoline prices

```bash
php artisan produce:gasoline.service
```