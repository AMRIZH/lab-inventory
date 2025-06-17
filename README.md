# 📋 Lab Inventory - Sistem Inventaris Laboratorium Teknik Informatika

Sistem manajemen inventaris modern untuk Laboratorium Teknik Informatika yang dibangun dengan **Laravel 10**, **Alpine.js**, **Tailwind CSS**, dan **Supabase** sebagai database.

## 🌟 Fitur Utama

- 🔐 **Autentikasi dengan Supabase** - Register, login, dan konfirmasi email
- 📦 **Manajemen Inventaris** - CRUD lengkap untuk item dan produk lab
- 🏷️ **Unit Tracking** - Kelola unit individual dengan kode inventaris
- 📊 **Dashboard Analytics** - Overview data inventaris dan statistik
- 📤 **Export Data** - Export ke Excel untuk reporting
- 🔍 **Real-time Search** - Pencarian cepat dengan Alpine.js
- 🐳 **Docker Ready** - Deployment mudah dengan Docker
- 📱 **Responsive Design** - UI modern dengan Tailwind CSS

## 🚀 Quick Start - Menjalankan Aplikasi

### 🎯 Pilihan 1: Docker (Recommended untuk Production)

**Cara termudah dengan Docker:**
```bash
# Build dan jalankan dengan Docker Compose
docker-compose up -d

# Atau gunakan script otomatis
.\docker-build.bat
```

**Akses aplikasi di:** http://localhost:8080

### 🎯 Pilihan 2: Development Server (Recommended untuk Development)

**Double-click salah satu file batch berikut:**

#### 1. `start-and-open.bat` ⭐ **PALING MUDAH**
- ✅ Otomatis start server Laravel
- ✅ Otomatis buka browser ke dashboard
- ✅ Perfect untuk demo dan penggunaan harian

#### 2. `start-inventaris-lab.bat` 🔧 **UNTUK TROUBLESHOOTING**
- ✅ Diagnostic lengkap (PHP, Composer, environment)
- ✅ Informasi detail project dan URL
- ✅ Tips troubleshooting jika ada masalah

#### 3. `start-server.bat` 🎯 **SIMPLE & CLEAN**
- ✅ Start server Laravel sederhana
- ✅ Manual control, buka browser sendiri

**Akses aplikasi di:** http://localhost:8000

### 🎯 Pilihan 3: Manual Command Line
```bash
# Masuk ke direktori project
cd D:\myLab2025\Lab-Inventory

# Start Laravel development server
php artisan serve

# Buka browser ke: http://127.0.0.1:8000
```

## 📱 URL Aplikasi

### Development Server (Port 8000)
- **Main App**: http://localhost:8000
- **Dashboard**: http://localhost:8000/dashboard
- **Lab FKI**: http://localhost:8000/labs/lab-fki
- **Export Data**: http://localhost:8000/export

### Docker Deployment (Port 8080)
- **Main App**: http://localhost:8080
- **Dashboard**: http://localhost:8080/dashboard
- **Lab FKI**: http://localhost:8080/labs/lab-fki
- **Export Data**: http://localhost:8080/export

## 🛠️ Fitur Aplikasi

### 🔐 **Autentikasi & User Management**
- **Register** dengan konfirmasi email via Supabase
- **Login/Logout** dengan session management
- **Email Confirmation** dengan dynamic redirect URLs
- **Multi-environment support** (dev, docker, production)

### 📦 **Manajemen Items**
- **CRUD lengkap** - Tambah, edit, lihat, hapus item inventaris
- **Unit Management** - Kelola unit individual untuk setiap item
- **Kode Inventaris** - System tracking dengan kode unik
- **Status Kondisi** - Monitor kondisi item (Baik/Rusak/Hilang)
- **Kategori** - Organisasi item berdasarkan kategori

### 🏷️ **Manajemen Products**
- **Product Catalog** - Database produk lengkap
- **Deskripsi Detail** - Informasi lengkap setiap produk
- **Lab Association** - Produk dikategorikan per laboratorium
- **Search & Filter** - Pencarian produk cepat

### 📊 **Dashboard & Analytics**
- **Statistics Overview** - Ringkasan data inventaris
- **Quick Actions** - Akses cepat ke fungsi utama
- **Recent Activities** - Activity feed terbaru
- **Charts & Graphs** - Visualisasi data inventaris

### 🔍 **Search & Filter System**
- **Real-time Search** - Pencarian instant dengan Alpine.js
- **Multi-criteria Filter** - Filter berdasarkan berbagai kriteria
- **Smart Search** - Pencarian berdasarkan nama, kode, atau deskripsi
- **Result Highlighting** - Highlight hasil pencarian

### 📤 **Export & Reporting**
- **Excel Export** - Export data ke format Excel (.xlsx)
- **Custom Reports** - Generate laporan berdasarkan filter
- **Formatted Output** - Data terstruktur untuk reporting
- **Bulk Operations** - Operasi massal pada data

### 🎨 **Modern UI/UX**
- **Responsive Design** - Tampilan optimal di semua device
- **Dark/Light Theme** - (Coming soon)
- **Intuitive Navigation** - Menu dan navigasi yang mudah dipahami
- **Loading States** - Feedback visual untuk user experience

## ⚙️ Persyaratan Sistem

### 🐳 **Docker Deployment (Recommended)**
- **Docker** 20.10+ dan **Docker Compose** 2.0+
- **8GB RAM** minimum untuk container
- **Port 8080** tersedia untuk aplikasi

### 💻 **Development Environment**
- **PHP 8.1+** dengan extensions: mbstring, openssl, pdo_pgsql, tokenizer, xml, gd, zip
- **Composer 2.0+** untuk dependency management
- **Node.js 16+** dan **npm/pnpm** untuk asset building
- **PostgreSQL Client** (untuk koneksi ke Supabase)

### 🌐 **Database & Services**
- **Supabase Account** dengan project yang sudah setup
- **Supabase Database** password untuk koneksi PostgreSQL
- **Email Service** (via Supabase) untuk konfirmasi registrasi

### 🔍 **Cek Instalasi**
```bash
# Cek PHP dan extensions
php --version
php -m | grep -E "(pdo_pgsql|mbstring|gd)"

# Cek Composer
composer --version

# Cek Node.js
node --version
npm --version

# Cek Docker (untuk deployment)
docker --version
docker-compose --version
```

## 🔧 Setup Project

### 🐳 **Option 1: Docker Setup (Production-Ready)**

1. **Clone Repository**
```bash
git clone [repository-url]
cd Lab-Inventory
```

2. **Environment Configuration**
```bash
# Copy environment template
cp .env.production .env

# Edit .env file dan update:
# - SUPABASE_DB_PASSWORD (dari Supabase dashboard)
# - APP_URL (sesuai domain Anda)
```

3. **Build dan Deploy**
```bash
# Menggunakan script otomatis
.\docker-build.bat

# Atau manual dengan Docker Compose
docker-compose up -d --build
```

4. **Supabase Configuration**
- Buka Supabase Dashboard → Authentication → Settings
- Tambahkan redirect URLs:
  - `http://localhost:8080/auth/confirm`
  - `https://yourdomain.com/auth/confirm` (untuk production)

### 💻 **Option 2: Development Setup**

1. **Clone Repository**
```bash
git clone [repository-url]
cd Lab-Inventory
```

2. **Install Dependencies**
```bash
# PHP dependencies
composer install

# Node.js dependencies
npm install
# atau gunakan pnpm
pnpm install
```

3. **Environment Setup**
```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Update .env dengan konfigurasi Supabase:
# - SUPABASE_URL=your_supabase_url
# - SUPABASE_KEY=your_supabase_anon_key
# - SUPABASE_DB_PASSWORD=your_db_password
```

4. **Build Assets**
```bash
# Development build
npm run dev

# Production build
npm run build
```

5. **Database Setup**
- Database sudah setup via Supabase
- Tidak perlu migrasi manual (menggunakan Supabase REST API)

### 🔗 **Supabase Project Setup**

1. **Buat Supabase Project**
   - Kunjungi https://supabase.com
   - Buat project baru
   - Catat URL dan anon key

2. **Setup Authentication**
   - Enable Email authentication
   - Configure email templates
   - Setup redirect URLs

3. **Database Schema**
   - Project menggunakan Supabase REST API
   - Tables dibuat via Supabase interface
   - Setup Row Level Security (RLS) sesuai kebutuhan

## 🐛 Troubleshooting

### 🐳 **Docker Issues**

**Container tidak bisa start:**
```bash
# Cek status container
docker ps -a

# Lihat logs container
docker logs lab-inventory-app

# Rebuild image
docker-compose down
docker-compose up -d --build --no-cache
```

**Port sudah digunakan:**
```bash
# Cek port yang digunakan
netstat -an | grep 8080

# Ganti port di docker-compose.yml jika perlu
ports:
  - "8081:80"  # Ganti 8080 ke 8081
```

**Database connection error:**
- Verifikasi `SUPABASE_DB_PASSWORD` di .env
- Cek koneksi internet ke Supabase
- Pastikan Supabase project masih aktif

### 💻 **Development Server Issues**

**Server tidak bisa start:**
1. **Cek PHP**: `php --version` (pastikan 8.1+)
2. **Cek Port**: Pastikan port 8000 tidak digunakan aplikasi lain
3. **Cek Directory**: Pastikan berada di direktori project yang benar
4. **Cek Extensions**: `php -m | grep pdo_pgsql`

**Browser tidak terbuka otomatis:**
1. **Manual**: Buka http://127.0.0.1:8000 di browser
2. **Firewall**: Cek apakah firewall memblokir koneksi
3. **Antivirus**: Cek apakah antivirus memblokir aplikasi

**Error "Composer not found":**
1. **Install Composer**: Download dari https://getcomposer.org
2. **Add to PATH**: Pastikan Composer ada di system PATH
3. **Restart**: Restart command prompt setelah install

### 🔐 **Authentication Issues**

**Email confirmation tidak work:**
1. **Cek Supabase redirect URLs** di dashboard
2. **Verifikasi environment variables**:
   ```env
   SUPABASE_REDIRECT_URL=${APP_URL}/auth/callback
   SUPABASE_EMAIL_CONFIRM_REDIRECT_URL=${APP_URL}/auth/confirm
   ```
3. **Clear browser cache** dan cookies

**Login gagal:**
1. **Cek Supabase credentials** di .env
2. **Verifikasi user sudah confirm email**
3. **Cek network connection** ke Supabase

### 🌐 **Supabase Issues**

**Database connection timeout:**
```bash
# Test koneksi ke Supabase
curl -I https://icdscorcuiucwipusohb.supabase.co

# Cek konfigurasi database di .env
DB_CONNECTION=pgsql
DB_HOST=db.icdscorcuiucwipusohb.supabase.co
```

**Authentication API error:**
- Cek SUPABASE_URL dan SUPABASE_KEY di .env
- Verifikasi project masih aktif di Supabase dashboard
- Cek quota Supabase project

### 📊 **Asset & Frontend Issues**

**Tailwind CSS tidak load:**
```bash
# Rebuild assets
npm run build

# Atau development mode
npm run dev
```

**Alpine.js tidak berfungsi:**
- Cek browser console untuk JavaScript errors
- Pastikan Alpine.js script loaded
- Clear browser cache

### 🔍 **Debug Commands**

```bash
# Laravel application info
php artisan about

# Check environment
php artisan env

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Docker debug
docker exec -it lab-inventory-app bash
docker exec lab-inventory-app php artisan about
```

### 📝 **Log Locations**

- **Laravel Logs**: `storage/logs/laravel.log`
- **Docker Logs**: `docker logs lab-inventory-app`
- **Apache Logs**: `docker exec lab-inventory-app tail -f /var/log/apache2/error.log`
- **Browser Console**: F12 → Console (untuk JavaScript errors)

## 📁 Struktur Project

```
Lab-Inventory/
├── 🐳 Docker Configuration
│   ├── Dockerfile                     # Container definition
│   ├── docker-compose.yml             # Service orchestration
│   ├── docker-build.bat              # Windows build script
│   ├── docker-build.sh               # Linux/Mac build script
│   └── docker/                       # Docker configs
│       ├── apache.conf               # Apache virtual host
│       ├── entrypoint.sh            # Container startup script
│       └── healthcheck.sh           # Health check script
│
├── 🚀 Quick Start Scripts
│   ├── start-and-open.bat            # Auto start & open browser
│   ├── start-inventaris-lab.bat      # Enhanced start with diagnostics
│   └── start-server.bat              # Simple server start
│
├── 🎯 Laravel Application
│   ├── app/                          # Application logic
│   │   ├── Http/Controllers/        # Controllers (Auth, Dashboard, etc.)
│   │   ├── Models/                  # Eloquent models
│   │   └── Services/                # Business logic (SupabaseService)
│   ├── resources/                   # Frontend resources
│   │   ├── views/                   # Blade templates
│   │   ├── css/                     # Stylesheets (Tailwind)
│   │   └── js/                      # JavaScript (Alpine.js)
│   ├── routes/                      # Route definitions
│   │   └── web.php                  # Web routes
│   ├── config/                      # Configuration files
│   ├── database/                    # Database files
│   ├── public/                      # Public assets
│   └── storage/                     # Storage & logs
│
├── 📦 Dependencies & Config
│   ├── composer.json                 # PHP dependencies
│   ├── package.json                  # Node.js dependencies
│   ├── tailwind.config.js            # Tailwind CSS config
│   ├── vite.config.js                # Vite build config
│   └── phpunit.xml                   # Testing configuration
│
├── 🔧 Environment & Config
│   ├── .env                          # Development environment
│   ├── .env.production               # Production template
│   ├── .env.docker.example           # Docker environment template
│   ├── .dockerignore                 # Docker ignore rules
│   └── .gitignore                    # Git ignore rules
│
└── 📚 Documentation
    ├── README.md                     # This file
    ├── DOCKER-README.md              # Docker deployment guide
    ├── SUPABASE-EMAIL-CONFIG.md      # Email configuration guide
    └── CARA-MENJALANKAN.txt          # Indonesian quick start guide
```

## 🎯 Development Workflow

### 🔄 **Daily Development**
```bash
# Start development server
.\start-and-open.bat

# Atau manual
php artisan serve
npm run dev  # Dalam terminal terpisah untuk hot reload
```

### 🧪 **Testing & Debugging**
```bash
# Run tests
php artisan test

# Check application status
.\start-inventaris-lab.bat

# Clear caches during development
php artisan cache:clear && php artisan config:clear
```

### 🐳 **Docker Development**
```bash
# Build dan test di environment mirip production
.\docker-build.bat

# Debug container
docker exec -it lab-inventory-app bash
```

### 🚀 **Production Deployment**
```bash
# Build optimized assets
npm run build

# Deploy dengan Docker
docker-compose -f docker-compose.yml up -d --build

# Atau setup di web server (Apache/Nginx)
```

## 🎨 Technology Stack

### 🖥️ **Backend**
- **Framework**: Laravel 10 (PHP 8.1+)
- **Database**: Supabase (PostgreSQL)
- **Authentication**: Supabase Auth
- **API**: REST API via Supabase
- **Caching**: File-based caching
- **Session**: File-based sessions

### 🎨 **Frontend**
- **CSS Framework**: Tailwind CSS 3.4
- **JavaScript**: Alpine.js (lightweight)
- **Build Tool**: Vite 4
- **Icons**: Heroicons (via Tailwind)
- **Responsive**: Mobile-first design

### 🐳 **Infrastructure**
- **Containerization**: Docker & Docker Compose
- **Web Server**: Apache 2.4 (in container)
- **Process Manager**: Apache MPM Prefork
- **Health Checks**: Custom health check script
- **Logging**: Laravel logs + Apache logs

### 🔧 **Development Tools**
- **Package Manager**: Composer (PHP) + npm/pnpm (Node.js)
- **Code Style**: Laravel Pint (PHP CS Fixer)
- **Testing**: PHPUnit (unit & feature tests)
- **Asset Building**: Vite (fast HMR)
- **Environment**: Multiple .env configurations

## 📞 Support & Resources

### 📖 **Documentation**
- **Quick Start**: `CARA-MENJALANKAN.txt` (Bahasa Indonesia)
- **Docker Guide**: `DOCKER-README.md` (Complete Docker setup)
- **Email Config**: `SUPABASE-EMAIL-CONFIG.md` (Email confirmation setup)
- **Laravel Docs**: https://laravel.com/docs/10.x
- **Supabase Docs**: https://supabase.com/docs

### 🆘 **Getting Help**

1. **Check Documentation**: Baca README dan file dokumentasi lainnya
2. **Run Diagnostics**: Jalankan `start-inventaris-lab.bat` untuk sistem check
3. **Check Logs**: 
   ```bash
   # Laravel logs
   tail -f storage/logs/laravel.log
   
   # Docker logs
   docker logs -f lab-inventory-app
   ```
4. **Browser Console**: F12 → Console untuk JavaScript errors
5. **Environment Check**: `php artisan about` untuk Laravel info

### 🛠️ **Common Solutions**

| Problem | Solution |
|---------|----------|
| Port conflict | Change port in docker-compose.yml or stop other services |
| PHP not found | Install PHP 8.1+ or add to PATH |
| Composer error | Install Composer from https://getcomposer.org |
| Docker build fails | Run `docker system prune` then rebuild |
| Email confirmation issues | Check Supabase redirect URLs in dashboard |
| Database connection | Verify SUPABASE_DB_PASSWORD in .env |

### 🔗 **Useful Links**
- **Supabase Dashboard**: https://supabase.com/dashboard
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Alpine.js**: https://alpinejs.dev/
- **Laravel**: https://laravel.com
- **Docker**: https://docs.docker.com

## 🎉 Ready to Go!

Aplikasi Lab Inventory siap digunakan! 

### 🚀 **Quick Commands**
```bash
# Development
.\start-and-open.bat

# Docker Production
.\docker-build.bat

# Manual Development
php artisan serve
```

**Happy Coding!** 🎊

---

## 📄 License & Credits

### **Framework & Libraries**
- **Laravel**: [MIT License](https://opensource.org/licenses/MIT)
- **Tailwind CSS**: [MIT License](https://github.com/tailwindlabs/tailwindcss/blob/master/LICENSE)
- **Alpine.js**: [MIT License](https://github.com/alpinejs/alpine/blob/main/LICENSE.md)

### **Services**
- **Supabase**: Database and Authentication platform
- **Docker**: Containerization platform

### **Project**
Sistem Inventaris Lab - Laboratorium Teknik Informatika  
Built with ❤️ using modern web technologies

---

*Last updated: June 2025*
