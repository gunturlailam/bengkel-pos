# 🔧 Bengkel POS

Aplikasi **Point of Sale (POS) & Manajemen Bengkel** berbasis web, dibangun dengan Laravel dan Filament. Dirancang untuk bengkel motor/mobil untuk mengelola transaksi jasa + sparepart, stok gudang, work order mekanik, hingga laporan omzet pemilik.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-4-FFC108?style=flat-square)
![Livewire](https://img.shields.io/badge/Livewire-3-FB70A9?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?style=flat-square&logo=mysql)

## ✨ Fitur Utama

### 💰 Kasir (POS)

- Transaksi gabungan **jasa servis + sparepart** dalam satu nota
- Keranjang interaktif dengan validasi stok real-time
- Diskon, pembayaran, dan perhitungan kembalian otomatis
- Nomor invoice otomatis dengan format `WO-YYYYMMDD-0001`
- Pemotongan stok sparepart otomatis saat checkout

### 📦 Master Data

- Pelanggan & kendaraan (nomor polisi, merek, tipe, tahun)
- Jasa servis dengan tarif dan estimasi waktu pengerjaan
- Sparepart dengan kategori, SKU, harga beli/jual, dan stok minimum

### 🔧 Work Order

- Riwayat transaksi lengkap dengan detail item
- Manajemen status pengerjaan: `Menunggu` → `Dikerjakan` → `Selesai`
- Cetak nota PDF (ukuran A5) siap print untuk pelanggan

### 📊 Dashboard Pemilik

- Kartu statistik: total omzet, jumlah transaksi, peringatan stok menipis
- Grafik batang omzet 7 hari terakhir
- Filter rentang tanggal

### 🔐 Multi-Role Access Control

| Role        | Akses                                             |
| ----------- | ------------------------------------------------- |
| **Admin**   | Semua menu + dashboard omzet                      |
| **Kasir**   | Kasir (POS), riwayat transaksi, cetak nota        |
| **Mekanik** | Riwayat transaksi (update status pengerjaan saja) |

## 🛠️ Tech Stack

- **Framework:** Laravel 11
- **Admin Panel:** Filament PHP 4
- **Reactivity:** Livewire 3
- **Database:** MySQL
- **PDF:** barryvdh/laravel-dompdf
- **Styling:** Tailwind CSS + Custom CSS

## 🚀 Cara Instalasi

```bash
# 1. Clone repository
git clone https://github.com/USERNAME_KAMU/bengkel-pos.git
cd bengkel-pos

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env, lalu migrate + seed
php artisan migrate --seed

# 5. Jalankan aplikasi
php artisan serve
```

Akses admin panel di: `http://localhost:8000/admin`

## 👤 Akun Default

| Role    | Email               | Password |
| ------- | ------------------- | -------- |
| Admin   | admin@bengkel.com   | password |
| Kasir   | kasir@bengkel.com   | password |
| Mekanik | mekanik@bengkel.com | password |

## 📮 API Data Entry (Bulk)

Tersedia endpoint API untuk input data massal (berguna untuk seeding via Postman):

| Method | Endpoint          | Fungsi                   |
| ------ | ----------------- | ------------------------ |
| POST   | `/api/categories` | Input kategori sparepart |
| POST   | `/api/customers`  | Input pelanggan          |
| POST   | `/api/services`   | Input jasa servis        |
| POST   | `/api/parts`      | Input sparepart          |
| POST   | `/api/vehicles`   | Input kendaraan          |

> ⚠️ Endpoint ini terbuka tanpa autentikasi dan ditujukan hanya untuk pengembangan lokal.

## 🗺️ Roadmap

- [ ] Riwayat stok masuk/keluar (stok opname)
- [ ] Reminder servis berkala via WhatsApp
- [ ] Export laporan bulanan ke Excel
- [ ] Dukungan barcode scanner

## 📄 Lisensi

Project ini bersifat open-source untuk keperluan pembelajaran. Silakan gunakan dan modifikasi dengan bijak.

---

Dibuat dengan ☕ dan 🔥 oleh **[Nama Kamu](https://github.com/USERNAME_KAMU)**
