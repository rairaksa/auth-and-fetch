# auth-and-fetch

## Setup
Auth and Fetch menggunakan Laravel sebagai backend.

### Requirement
1. PHP 8.0^
2. MySQL
3. Exchange Rates Data API [APILayer](https://apilayer.com/marketplace/exchangerates_data-api#details-tab)

### Konfigurasi dan Installasi Auth Apps
1. Duplikasi .env.example ke .env
2. Edit .env dengan menambahkan/mengubah konfigurasi berikut :
```
DB_NAME=jds // tergantung dari nama di db mesin
JWT_SECRET=jwt-secret
```
3. Setelah menyiapkan file environment, lalu install dan jalankan aplikasi melalui perintah berikut :
```
// masuk ke dalam direktori auth_apps
$ cd auth_apps

// install paket
$ composer install

// migrasi db
$ php artisan migrate

// jalankan server pada port 8000
$ php artisan serve --port=8000
```

### Konfigurasi dan Installasi Fetch Apps
1. Duplikasi .env.example ke .env
2. Edit .env dengan menambahkan/mengubah konfigurasi berikut :
```
JWT_SECRET=jwt-secret // samakan dengan JWT_SECRET pada auth_apps
CURRENCY_KEY=currency-key // API Key yang bisa didapatkan dari Exhange Rates Data APILayer
AUTH_APP_URL=http://localhost:8000 // digunakan untuk testing, agar fetch apps dapat mengetahui base url dari auth apps
```
3. Setelah menyiapkan file environment, lalu install dan jalankan aplikasi melalui perintah berikut :
```
// masuk kedalam direktori fetch_apps
$ cd fetch_apps

// install paket
$ composer install

// jalankan server pada port 9000
$ php artisan serve --port=9000
```

### Testing 
1. Metode testing menggunakan simple http request melalui fitur bawaan laravel
2. Untuk testing kedua apps dapat dilakukan didalam masing-masing module dengan perintah berikut :
```
// masuk kedalam direktori auth_apps
$ cd auth_apps

// jalankan perintah testing
$ php artisan test

// masuk kedalam direktori fetch_apps
$ cd fetch_apps

// jalankan perintah testing
$ php artisan test
```

### Dokumentasi API
API Documentation in [OpenAPI Format](https://documenter.getpostman.com/view/4152974/Uz5NiYXL).
