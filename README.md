# Installation

```
composer install
```

Build your database

```
cp .env.example .env
vi .env
```

Edit DB_DATABASE, DB_USERNAME, and DB_PASSWORD.

```
php artisan migrate
php artisan key:generate
php artisan jwt:generate
```

Create test data with seed if you need.
```
php artisan db:seed
```

Edit php.ini if you need.

```
post_max_size = 0
upload_max_filesize = 0
max_file_uploads = 0
```

#### If you cannot store any file, remember to chmod.
#### You can also edit .htaccess to do what you want.

# Usage
## JWT Auth

```
{URL}?token={token}
- or -
Headers
  Authorization: Bearer {token}
```
