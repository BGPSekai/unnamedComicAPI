# unnamedComicAPI
暫未命名的漫畫API

基於Larvel 5.2

# 如何建置

```
composer install
建立你的資料庫
copy .env.example .env
修改.env, 在DB_DATABASE、DB_USERNAME...等
php artisan migrate 資料庫遷移
php artisan key:generate 產生Laravel金鑰
php artisan jwt:generate 產生JWT金鑰 (非必要)
```

# 修改php.ini (如果有必要)

```
post_max_size = 總檔容量上限
upload_max_filesize = 單檔容量上限
max_file_uploads = 上傳數量上限
```
