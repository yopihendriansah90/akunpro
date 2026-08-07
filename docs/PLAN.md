# Rencana Implementasi — KasirAkun (dinamis)

Stack: Laravel 12, Filament 5, MySQL 8, Tailwind 4 + Vite, custom Blade, vanilla JS.
Konversi dari situs statis di `reference-static/` (desain kuning amber, dipertahankan).

## Lingkungan (terverifikasi)

- PHP 8.4, Composer 2.9, Node 24, npm 11.
- MySQL 8 berjalan; DB `dbaksesindigital`, user `yopi`, password `yopi`.
- Root Laravel = `kasirakun/` (project sendiri, terpisah dari ProKita di parent).

## Keputusan pemilik

1. Desain port persis dari `reference-static/` (kuning amber + violet + emerald, Plus Jakarta Sans, Material Symbols).
2. Cart + qty + pesan checkout WA dibangun client-side (localStorage), stepper +/-, badge total qty, pesan compact `Rp 30.000 × 2 = Rp 60.000`, tanpa baris "Bagaimana cara beli nya?".
3. Detail produk: modal.
4. Kategori & testimoni: tabel DB + CRUD Filament.
5. Brand & nomor WA admin: tabel `settings` + halaman Filament `ManageSettings` (bisa diubah dari `/admin`, tanpa edit kode).
6. Nomor WA & nama brand sumber utama = tabel `settings`; fallback `.env` saat belum di-seed.

## Skema DB (MySQL `dbaksesindigital`)

- `categories`: id, name, icon (Material Symbol), sort, timestamps
- `products`: id, category_id (FK → categories, cascade), name, description (text),
  price (uint), original_price (uint nullable), duration (masa aktif), warranty (garansi),
  icon, rating (tinyint, default 5), badge (nullable), available (bool), sort (uint), timestamps
- `testimonials`: id, name, role (nullable), rating (tinyint), text, available (bool), sort, timestamps
- `settings`: id, store_name, whatsapp_number, timestamps (singleton 1 baris)

## Tahap

### Phase 1 — Persiapan
- [x] Pindah statis → `reference-static/`
- [ ] Tulis `docs/PLAN.md` (file ini)
- [ ] `composer create-project laravel/laravel .`
- [ ] `.env`: APP_NAME=KasirAkun, APP_LOCALE=id, DB dbaksesindigital/yopi/yopi, WHATSAPP_NUMBER=6283116545674 (fallback)
- [ ] Buat DB `dbaksesindigital`

### Phase 2 — Data layer
- [ ] Migrasi: categories, products, testimonials, settings
- [ ] Model: Category, Product (casts, scope active/ordered), Testimonial, Setting (singleton + helper `settings('key')`)
- [ ] Seeder: CategorySeeder (AI Chat, Video Editor, Desain, Musik, Streaming), ProductSeeder (6 produk dari reference-static/products.js), TestimonialSeeder (3), AdminUserSeeder (admin@kasirakun.test), SettingsSeeder (KasirAkun + 6283116545674)

### Phase 3 — Admin Filament
- [ ] `php artisan filament:install --panels`
- [ ] ProductResource: table name/category/price(Rp)/original_price/duration/warranty/badge/available/sort, search + filter kategori & status, form lengkap (category select, icon + hint, rating)
- [ ] CategoryResource: name, icon, sort
- [ ] TestimonialResource: name, role, rating, text, available, sort
- [ ] Page `ManageSettings`: form store_name + whatsapp_number + simpan

### Phase 4 — Frontend Blade
- [ ] `app/Support/WhatsApp.php`: `number()`, `formatRupiah()`, `directMessage(Product)`, `waLink($text)`
- [ ] `layouts/app.blade.php`: head SEO + fonts (Plus Jakarta Sans, Material Symbols), header sticky (logo storefront, nav, WA admin, cart + badge, hamburger), footer, floating WA, cart drawer, modal overlay, toast, Vite css/js
- [ ] `index.blade.php`: hero + search, tab kategori (dari DB, icon per kategori), grid kartu, cara belanja, testimoni (DB), tentang
- [ ] `resources/css/app.css`: Tailwind 4 entry + custom (line-clamp, scrollbar-hide, font-variation)
- [ ] `resources/js/app.js`: data via @json, search live, filter kategori, cart (qty/stepper/localStorage/badge), modal detail, checkout message
- [ ] `resources/js/wa-message.mjs`: buildCartMessage murni (ESM, bisa di-test node)

### Phase 5 — Build & seed
- [ ] `php artisan migrate --seed`
- [ ] `npm install`, `npm run build`
- [ ] `php artisan config:clear`

### Phase 6 — QA
- [ ] PHPUnit: GET / 200, /admin redirect login, formatRupiah, directMessage
- [ ] Node test: buildCartMessage (qty, total, format)
- [ ] Manual: cart + qty → pesan WA benar; search; filter; modal; responsive 3/2/1; ganti settings → nama & nomor WA update semua link

## Pesan WhatsApp

Checkout cart (JS):
```
Halo, aku mau beli akun pro dengan rincian sebagai berikut:

1. *Gemini Pro* ×2
   Masa aktif 3 bulan · Garansi 1 bulan
   - Rp 30.000 × 2 = Rp 60.000

*Total Rp 85.000*
```

Direct order (PHP, tombol Beli):
```
Halo, aku mau Beli akun pro
*Gemini Pro*
- Masa aktif 3 bulan
- garansi 1 bulan
- Rp 30.000

Apakah masih tersedia?
```
