# Antarmuka alternatif SIAKAD

## Login

Komposisi mengikuti gambar acuan pengguna. Dua panel memiliki lebar seimbang di desktop. Panel kiri memadukan foto wisuda, gradasi navy–indigo, lambang buku, dan nama SIAKAD. Panel putih di kanan memuat formulir akun lokal. Di layar hingga 640 piksel, identitas menjadi kepala formulir agar pengguna ponsel cepat mencapai kolom email.

Label email dan password selalu terlihat. Kesalahan ditampilkan di dekat kolom terkait. Opsi Ingat saya, status pengiriman, dan pilihan akun demo di lingkungan lokal mengikuti alur autentikasi aplikasi.

## Portal akademik

Halaman publik [SLCM UI](https://slcm.ui.ac.id/) mengelompokkan akses menurut layanan. SIAKAD menerapkan pendekatan tersebut pada modul yang tersedia untuk tiap peran. Header memuat identitas aplikasi, profil, dan logout. Sidebar desktop selebar 210 piksel menampilkan ikon dan label tiap modul. Di layar hingga 760 piksel, tombol Menu membuka panel samping modal dengan navigasi peran. Fitur yang menunggu route backend menggunakan label Belum tersedia.

Panel sambutan memuat tanggal Jakarta dan tautan kerja sesuai peran. Empat kartu statistik admin berjajar di desktop dan membentuk dua kolom di ponsel. Dashboard dosen dan mahasiswa menggunakan susunan konten sesuai perannya. Halaman data menggunakan toolbar untuk pencarian dan aksi tambah. Form mempertahankan label, validasi, tombol simpan, dan batal. Halaman informasi memakai daftar judul dan panel baca.

## Warna dan keterbacaan

| Elemen | Warna proyek |
|---|---|
| Header | Gradasi navy `#152238` ke indigo `#312e81` |
| Aksi utama dan modul aktif | Indigo `#4338ca` |
| Tab aktif | Indigo muda `#eef2ff` dengan garis bawah indigo |
| Latar halaman | Abu muda `#f5f6f8` |
| Permukaan form dan tabel | Putih `#ffffff` |
| Teks utama | `#172033` |

Gunakan Segoe UI atau font sistem untuk data dan kontrol. Wordmark login memakai serif. Tabel memakai angka dengan lebar seragam agar nilai mudah dibandingkan. Pertahankan fokus keyboard, judul kolom, dan area gulir tabel di layar sempit.

Foto wisuda bersifat ilustratif, disimpan bersama aset proyek, dan dikompilasi oleh Vite. [Sumber foto di Unsplash](https://images.unsplash.com/photo-1541339907198-e08756dedf3f).

## Menjalankan pratinjau lokal

Worktree ini menggunakan MySQL Community Server 8.4 pada `127.0.0.1:3307` dengan database `aplikasi_akademik_ui`. Konfigurasi akun aplikasi tersimpan di `.env` lokal.

Setelah komputer dinyalakan kembali, jalankan perintah berikut dari PowerShell untuk memulai instance MySQL proyek jika port 3307 belum aktif:

```powershell
$mysqlServer = "$env:LOCALAPPDATA\Programs\SIAKAD-MySQL\mysql-8.4.13.1-winx64\bin\mysqld.exe"
$mysqlConfig = "$env:LOCALAPPDATA\SIAKAD-MySQL\my.ini"
if (!(Get-NetTCPConnection -LocalPort 3307 -State Listen -ErrorAction SilentlyContinue)) {
    Start-Process -FilePath $mysqlServer -ArgumentList ('--defaults-file="' + $mysqlConfig + '"') -WindowStyle Hidden
}
```

Kemudian jalankan Laravel dari folder `FE-ui-alternatif`:

```powershell
php artisan serve --host=127.0.0.1 --port=8013
```

Buka `http://127.0.0.1:8013/login`. Setelah mengubah CSS atau JavaScript, jalankan `npm.cmd run build` dari folder proyek.
