# Installation

```
composer install
```

Build your database

```
cp .env.example .env
vi .env
```

Edit DB_DATABASE and DB_USERNAME...

```
artisan migrate
artisan key:generate
artisan jwt:generate
```

Edit php.ini if needed

```
post_max_size =
upload_max_filesize =
max_file_uploads =
```
