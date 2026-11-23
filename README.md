# Sistem Informasi Akademik (SIAKAD)

Aplikasi Web Sistem Informasi Akademik berbasis **Laravel** dengan manajemen multi-role (**Admin**, **Dosen**, **Mahasiswa**), modul CRUD Mahasiswa, Dosen, Jadwal Kuliah, Penilaian Akademik (KHS), serta Manajemen Admin dan Pengaturan Profil Mandiri.

---

## 🛠️ Prasyarat Sistem (Prerequisites)

Pastikan di komputer/server Anda telah terinstall:
- **PHP** >= 8.2 (disarankan PHP 8.3+) dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`.
- **Composer** (Dependency Manager untuk PHP).
- **MySQL / MariaDB Database Server**.
- *(Opsional)* **Node.js & NPM** jika ingin memodifikasi aset frontend.

---

## 🚀 Panduan Setup Awal (Step by Step Clone)

Ikuti langkah-langkah berikut setelah Anda melakukan clone repositori:

### 1. Masuk ke Direktori Proyek
```bash
cd aplikasi-akademik
```

### 2. Install Dependensi PHP (Composer)
```bash
composer install
```

### 3. Duplikasi File Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
- **Linux / macOS:**
  ```bash
  cp .env.example .env
  ```
- **Windows (Command Prompt / PowerShell):**
  ```cmd
  copy .env.example .env
  ```

### 4. Generate Application Key (APP_KEY)
```bash
php artisan key:generate
```

### 5. Buat Database dan Sesuaikan File `.env`
1. Buat database baru di MySQL (misal bernama `aplikasi_akademik`):
   ```sql
   CREATE DATABASE aplikasi_akademik;
   ```
2. Buka file `.env` dan sesuaikan koneksi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=aplikasi_akademik
   DB_USERNAME=root
   DB_PASSWORD=password_mysql_anda
   ```

### 6. Jalankan Migrasi & Database Seeder
Perintah ini akan membuat seluruh tabel di database dan mengisi data awal (akun Admin, Dosen, Mahasiswa, Mata Kuliah, Jadwal, dan Nilai):
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Server Lokal Laravel
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses melalui browser di:  
👉 **[http://localhost:8000](http://localhost:8000)** (atau URL yang tertera di terminal).

---

## 🔑 Akun Demo Bawaan (Default Seeded Accounts)

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda dapat langsung login menggunakan akun berikut:

| Role | Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@akademik.com` | `password` | Kelola Admin, Mahasiswa, Dosen, Jadwal, Nilai |
| **Dosen** | `budi.santoso@akademik.com` | `password` | Lihat Jadwal Mengajar, Kelola/Input Nilai Mahasiswa |
| **Mahasiswa** | `ahmad.fauzi@student.akademik.com` | `password` | Lihat Jadwal Kuliah & Kartu Hasil Studi (KHS) |

*(Di halaman login juga tersedia tombol "Klik Otomatis" untuk mengisi akun demo tanpa mengetik).*

---

## 🧪 Menjalankan Pengujian Otomatis (Tests)

Untuk memverifikasi fungsionalitas aplikasi dan role-based access control:
```bash
php artisan test
```

Format styling kode dengan Laravel Pint:
```bash
vendor/bin/pint
```
