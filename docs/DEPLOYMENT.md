# Production Deployment

## Document root

Set document root domain ke folder `public`, bukan root project Laravel.

Contoh:

```text
/home/account/kasirakun/public
```

## Public media

Jalankan dari root project setelah deploy:

```bash
php artisan storage:unlink
php artisan storage:link
```

Pastikan `public/storage` mengarah ke `storage/app/public`. Jika hosting tidak mengizinkan symbolic link, buat link tersebut melalui File Manager atau minta hosting mengaktifkan symlink. Jangan membuat folder kosong `public/storage` karena file upload tetap tersimpan di `storage/app/public`.

## Environment

Gunakan konfigurasi production:

```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=warning
APP_URL=https://domain-anda.example
SESSION_DRIVER=file
CACHE_STORE=file
MEDIA_DISK=public
```

Jika aplikasi dipasang di subfolder, masukkan subfolder tersebut ke `APP_URL`. URL media production akan mengikuti konfigurasi disk Laravel.

## Permission

Pastikan web server dapat membaca file media dan menulis cache Laravel:

```bash
chmod -R 755 storage bootstrap/cache
find storage/app/public -type f -exec chmod 644 {} \;
```

Sesuaikan owner/group sesuai user web server pada hosting.

## Final commands

```bash
php artisan migrate --force
php artisan optimize
php artisan storage:link
```

Setelah deploy, uji satu URL original dan satu URL conversion dari folder `storage/app/public`. Keduanya harus merespons `200`, bukan `403`.
