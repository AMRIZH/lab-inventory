# 📋 Inventaris Lab - Sistem Inventaris Laboratorium

Sistem manajemen inventaris untuk Laboratorium Teknik Informatika yang dibangun dengan Laravel dan Alpine.js.

## 🚀 Quick Start - Menjalankan Aplikasi

### Cara Termudah (Recommended)
**Double-click salah satu file batch berikut:**

#### 1. `start-and-open.bat` ⭐ **PALING MUDAH**
- ✅ Otomatis start server
- ✅ Otomatis buka browser
- ✅ Langsung ke dashboard
- 🎯 **Pilihan terbaik untuk penggunaan sehari-hari**

#### 2. `start-inventaris-lab.bat` 🔧 **UNTUK TROUBLESHOOTING**
- ✅ Cek sistem (PHP, Composer, dll)
- ✅ Informasi detail project
- ✅ Tips dan URL yang berguna
- 🎯 **Pilihan terbaik jika ada masalah**

#### 3. `start-server.bat` 🎯 **SIMPLE & CLEAN**
- ✅ Start server sederhana
- ✅ Minimal dan bersih
- ✅ Manual buka browser
- 🎯 **Pilihan untuk yang suka kontrol manual**

### Cara Manual (Command Line)
```bash
# Masuk ke direktori project
cd D:\magang\inventaris-lab

# Start Laravel development server
php artisan serve

# Buka browser ke: http://127.0.0.1:8000
```

## 📱 Akses Aplikasi

Setelah server berjalan, buka browser ke:

- **Dashboard**: http://127.0.0.1:8000/dashboard
- **Lab FKI**: http://127.0.0.1:8000/labs/lab-fki
- **Export Data**: http://127.0.0.1:8000/export

## 🛠️ Fitur Utama

### ✅ Manajemen Items
- **Tambah/Edit/Hapus** item inventaris
- **Kelola unit** untuk setiap item
- **Kode inventaris** untuk tracking
- **Status kondisi** (Baik/Rusak)

### ✅ Manajemen Products
- **Tambah/Edit/Hapus** produk
- **Deskripsi lengkap** produk
- **Kategori per laboratorium**

### ✅ Pencarian & Filter
- **Cari berdasarkan nama** item/produk
- **Filter berdasarkan kode** inventaris
- **Real-time search** dengan Alpine.js

### ✅ Export Data
- **Export ke Excel** semua data inventaris
- **Format terstruktur** untuk reporting

## ⚙️ Persyaratan Sistem

### Software yang Diperlukan:
- **PHP 8.1+** (dengan extensions: mbstring, openssl, pdo, tokenizer, xml)
- **Composer** (untuk dependency management)
- **Web Browser** (Chrome, Firefox, Edge, dll)

### Cek Instalasi:
```bash
# Cek PHP
php --version

# Cek Composer
composer --version
```

## 🔧 Setup Project (Jika Belum Setup)

### 1. Clone/Download Project
```bash
git clone [repository-url]
cd inventaris-lab
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
- Konfigurasi database di file `.env`
- Jalankan migrasi jika diperlukan

## 🐛 Troubleshooting

### Server Tidak Bisa Start
1. **Cek PHP**: Pastikan PHP terinstall dan ada di PATH
2. **Cek Port**: Pastikan port 8000 tidak digunakan aplikasi lain
3. **Cek Directory**: Pastikan berada di direktori project yang benar

### Browser Tidak Terbuka Otomatis
1. **Manual**: Buka http://127.0.0.1:8000 di browser
2. **Firewall**: Cek apakah firewall memblokir koneksi
3. **Antivirus**: Cek apakah antivirus memblokir aplikasi

### Error "Composer Not Found"
1. **Install Composer**: Download dari https://getcomposer.org
2. **Add to PATH**: Pastikan Composer ada di system PATH
3. **Restart**: Restart command prompt setelah install

### Error Database Connection
1. **Cek .env**: Pastikan konfigurasi database benar
2. **Cek Service**: Pastikan database service berjalan
3. **Cek Credentials**: Pastikan username/password benar

## 📁 Struktur Project

```
inventaris-lab/
├── app/                    # Application logic
├── resources/
│   ├── views/             # Blade templates
│   └── css/               # Stylesheets
├── routes/                # Route definitions
├── public/                # Public assets
├── storage/               # Storage & logs
├── start-server.bat       # Simple server start
├── start-inventaris-lab.bat # Enhanced server start
├── start-and-open.bat     # Auto start & open browser
└── README.md              # This file
```

## 🎯 Tips Penggunaan

### Untuk Development:
- Gunakan `start-inventaris-lab.bat` untuk melihat informasi detail
- Monitor console untuk error messages
- Cek `storage/logs/laravel.log` jika ada masalah

### Untuk Demo/Presentasi:
- Gunakan `start-and-open.bat` untuk start cepat
- Siapkan data sample sebelumnya
- Test semua fitur sebelum demo

### Untuk Production:
- Jangan gunakan `php artisan serve` untuk production
- Setup proper web server (Apache/Nginx)
- Konfigurasi environment production

## 📞 Support

Jika mengalami masalah:

1. **Cek README** ini terlebih dahulu
2. **Jalankan** `start-inventaris-lab.bat` untuk diagnostic
3. **Cek logs** di `storage/logs/laravel.log`
4. **Screenshot error** jika perlu bantuan

## 🎉 Selamat Menggunakan!

Aplikasi Inventaris Lab siap digunakan!
Double-click `start-and-open.bat` dan mulai kelola inventaris lab Anda! 🚀

---

## About Laravel

This project is built with Laravel - a web application framework with expressive, elegant syntax. Laravel is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
