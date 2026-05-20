# 💰 Finance Tracker

Aplikasi manajemen keuangan pribadi berbasis **Laravel 11 + Livewire 3 + MySQL** dengan UI modern berbahasa Indonesia.

---

## 🚀 Quick Start (3 Langkah)

```bash
# 1. Clone & masuk ke folder
cd finance-tracker

# 2. Jalankan installer otomatis
chmod +x install.sh && ./install.sh

# 3. Jalankan server
php artisan serve
```

Buka **http://localhost:8000** — selesai! ✅

---

## 📦 Tech Stack

| Layer       | Teknologi                           |
|-------------|-------------------------------------|
| Backend     | Laravel 11 (PHP 8.2+)               |
| Frontend    | Livewire 3 + Tailwind CSS (CDN)     |
| Database    | MySQL 8.0+                          |
| Charts      | Chart.js 4                          |
| Export XLSX | Maatwebsite Excel 3.1               |
| Export PDF  | barryvdh/laravel-dompdf 2.2         |
| JS          | Alpine.js 3                         |

---

## ✨ Fitur

### 📊 Dashboard
- Ringkasan pemasukan, pengeluaran, saldo, & total aset
- Line chart tren keuangan 12 bulan (Chart.js)
- Donut chart pengeluaran per kategori
- 5 transaksi terbaru + progress tujuan keuangan aktif
- Loading bar + spinner saat Livewire request

### 💳 Transaksi (Income & Expense)
- CRUD lengkap dengan validasi (Bahasa Indonesia)
- Filter: tipe, kategori, bulan, tahun, pencarian teks
- Sorting kolom (tanggal, jumlah) ascending/descending
- Pagination 15 per halaman
- Export ke Excel (.xlsx) dan PDF

### 🎯 Tujuan Keuangan
- CRUD dengan ikon emoji pilihan (10 pilihan)
- Progress bar visual real-time
- Modal "Tambah Tabungan" — input nominal langsung
- Auto-complete + notifikasi ketika target tercapai
- Filter status: Aktif / Selesai / Dibatalkan

### 🏦 Aset
- CRUD: properti, kendaraan, investasi, tabungan, lainnya
- Kalkulasi otomatis untung/rugi (nilai & persentase)
- Ringkasan total nilai per tipe
- Export ke Excel (.xlsx) dan PDF

---

## 📁 Struktur Proyek

```
finance-tracker/
├── app/
│   ├── Exports/                     ← Excel exports
│   │   ├── TransactionsExport.php
│   │   └── AssetsExport.php
│   ├── Http/Livewire/               ← Livewire components
│   │   ├── Dashboard.php
│   │   ├── Transactions.php
│   │   ├── Goals.php
│   │   └── Assets.php
│   ├── Models/
│   │   ├── Transaction.php
│   │   ├── FinancialGoal.php
│   │   ├── Asset.php
│   │   └── User.php
│   └── Providers/
│       └── AppServiceProvider.php   ← Carbon locale ID
├── bootstrap/
│   ├── app.php                      ← Laravel 11 bootstrap
│   └── providers.php
├── config/                          ← app, database, cache, session, dll
├── database/
│   ├── factories/UserFactory.php
│   ├── migrations/                  ← 6 migration files
│   └── seeders/                     ← Data contoh Indonesia
├── public/
│   ├── index.php                    ← Front controller
│   └── .htaccess
├── resources/
│   ├── css/app.css
│   ├── js/{app,bootstrap}.js
│   ├── lang/id/validation.php       ← Pesan validasi ID
│   └── views/
│       ├── components/              ← Blade components
│       ├── errors/                  ← 404, 500, 503
│       ├── exports/                 ← Template PDF
│       ├── layouts/app.blade.php    ← Layout utama
│       ├── livewire/                ← 4 halaman
│       └── vendor/livewire/         ← Custom pagination
├── routes/
│   ├── web.php
│   └── console.php
├── storage/                         ← Cache, sessions, logs
├── tests/
│   ├── Feature/DashboardTest.php
│   └── Unit/TransactionTest.php
├── .env.example
├── artisan
├── composer.json
├── install.sh                       ← Installer otomatis
├── package.json
├── phpunit.xml
├── tailwind.config.js
└── vite.config.js
```

---

## 🗄️ Database Schema

### `transactions`
| Kolom    | Tipe            | Keterangan          |
|----------|-----------------|---------------------|
| id       | bigint PK       |                     |
| type     | enum            | `income` / `expense`|
| title    | varchar(255)    | Judul transaksi     |
| amount   | decimal(15,2)   | Jumlah (Rupiah)     |
| category | varchar(100)    | Kategori            |
| date     | date            | Tanggal             |
| notes    | text nullable   | Catatan             |

### `financial_goals`
| Kolom          | Tipe            | Keterangan              |
|----------------|-----------------|-------------------------|
| id             | bigint PK       |                         |
| name           | varchar(255)    | Nama tujuan             |
| target_amount  | decimal(15,2)   | Target tabungan         |
| current_amount | decimal(15,2)   | Dana terkumpul          |
| deadline       | date nullable   | Tenggat waktu           |
| status         | enum            | active/completed/cancelled |
| icon           | varchar         | Emoji ikon              |

### `assets`
| Kolom          | Tipe            | Keterangan              |
|----------------|-----------------|-------------------------|
| id             | bigint PK       |                         |
| name           | varchar(255)    | Nama aset               |
| type           | enum            | property/vehicle/investment/cash/other |
| purchase_price | decimal(15,2)   | Harga beli              |
| current_value  | decimal(15,2)   | Nilai sekarang          |
| purchase_date  | date            | Tanggal pembelian       |

---

## 🛠️ Instalasi Manual

### Prasyarat
- PHP >= 8.2 dengan ekstensi: `pdo_mysql`, `mbstring`, `xml`, `zip`, `gd`
- Composer >= 2.0
- MySQL 8.0+

### Langkah

```bash
# 1. Install Composer dependencies
composer install

# 2. Salin .env
cp .env.example .env
php artisan key:generate

# 3. Konfigurasi database di .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=finance_tracker
DB_USERNAME=root
DB_PASSWORD=your_password

# 4. Buat database
mysql -u root -p -e "CREATE DATABASE finance_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Jalankan migrasi + seeder
php artisan migrate
php artisan db:seed

# 6. (Opsional) Optimasi untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Jalankan server
php artisan serve
```

---

## 🧪 Menjalankan Tests

```bash
php artisan test
# atau
./vendor/bin/phpunit
```

---

## 🌐 URL Halaman

| Halaman          | URL           |
|-----------------|----------------|
| Dashboard        | `/dashboard`  |
| Transaksi        | `/transaksi`  |
| Tujuan Keuangan  | `/tujuan`     |
| Aset             | `/aset`       |

---

## 📄 Lisensi

MIT License — bebas digunakan dan dimodifikasi untuk keperluan pribadi maupun komersial.
