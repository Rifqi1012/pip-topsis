<div align="center">
    <h1>🎓 Sistem Rekomendasi Penerima PIP (Metode TOPSIS)</h1>
    <p>Aplikasi berbasis web untuk menyeleksi dan merekomendasikan siswa penerima bantuan Program Indonesia Pintar (PIP) menggunakan algoritma <strong>TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution)</strong>.</p>
</div>

---

## 🌟 Tentang Proyek

Sistem ini dirancang khusus untuk mempermudah instansi sekolah dalam melakukan seleksi penerima dana bantuan PIP secara objektif, transparan, dan terkomputerisasi. Dengan memanfaatkan metode **TOPSIS All-Benefit**, sistem ini secara otomatis memberikan peringkat (ranking) kepada siswa berdasarkan tingkat prioritas dan ketidakmampuan ekonominya.

## 🚀 Fitur Utama

- 🔐 **Multi-Role Authentication** - Akses terpisah untuk **Kepala Sekolah** (Pemantauan), **Tata Usaha** (Admin Sistem), dan **Wali Kelas** (Input Data).
- 👥 **Manajemen Pengguna (User Management)** - Fitur khusus Tata Usaha untuk mengelola (Create, Read, Update) dan membekukan (Nonaktifkan) akun guru tanpa menghapus data.
- 👨‍🎓 **Manajemen Data Siswa** - Wali kelas dapat menambahkan data siswa beserta atribut lengkap (NISN, Alamat Lengkap, Kelas).
- 🧮 **Dynamic TOPSIS Engine** - Algoritma TOPSIS yang membaca bobot dan atribut kriteria secara dinamis. Menggunakan pendekatan *All-Benefit* di mana kondisi ekonomi yang lebih membutuhkan akan mendapatkan poin *ranking* lebih tinggi.
- ⚙️ **Master Kriteria Fleksibel** - Data Kriteria dan Sub-kriteria (seperti Pekerjaan Orang Tua, Penghasilan, Tanggungan) dapat disesuaikan bobot dan skalanya sewaktu-waktu oleh Tata Usaha.
- 📄 **Cetak & Ekspor Laporan** - Laporan hasil seleksi dapat diekspor langsung ke dalam format **PDF** dan **Excel**.
- 🎨 **Modern & Interactive UI** - Antarmuka yang bersih, responsif, dan elegan menggunakan *Tailwind CSS*.

## 🛠️ Teknologi yang Digunakan

- **Backend:** Laravel 12, PHP ^8.2
- **Frontend:** TailwindCSS, Blade Templating, Alpine.js, Vite
- **Database:** MySQL / SQLite
- **Laporan:** `barryvdh/laravel-dompdf` (PDF), `maatwebsite/excel` (Excel)

## 📋 Prasyarat Sistem

Sebelum melakukan instalasi, pastikan sistem Anda memiliki lingkungan berikut:
- **PHP** >= 8.2
- **Composer** (Package Manager untuk PHP)
- **Node.js & npm** (Package Manager untuk Frontend Assets)
- **Database Server** (MySQL, MariaDB, atau SQLite)

---

## ⚙️ Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi secara lokal:

### 1. Kloning Repositori
```bash
git clone https://github.com/your-username/pip-topsis.git
cd pip-topsis
```

### 2. Instalasi Dependensi PHP (Composer)
```bash
composer install
```

### 3. Konfigurasi Environment
Salin berkas `.env.example` menjadi `.env`.
```bash
cp .env.example .env
```
Buka berkas `.env` dan sesuaikan kredensial koneksi *database* Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pip_topsis
DB_USERNAME=root
DB_PASSWORD=
```
*(Catatan: Anda juga bisa menggunakan `DB_CONNECTION=sqlite` tanpa perlu menyetel host/username).*

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Migrasi dan Seeding Database
Langkah ini akan membangun seluruh tabel database beserta data *dummy* awal (Data Kriteria, Sub Kriteria, Akun User, dan Siswa).
```bash
php artisan migrate:fresh --seed
```

### 6. Instalasi Dependensi Frontend & Build Assets
```bash
npm install
npm run build
```

### 7. Jalankan Server Lokal
Anda membutuhkan dua terminal yang berjalan secara bersamaan untuk PHP dan Vite:

**Terminal 1 (Backend):**
```bash
php artisan serve
```

**Terminal 2 (Frontend):**
```bash
npm run dev
```

Aplikasi sekarang dapat diakses melalui browser di alamat: `http://localhost:8000`

---

## 🔑 Akun Default (Seeder)

Setelah melakukan *seeding*, Anda dapat login menggunakan akun berikut:

| Peran (Role) | Email | Password | Akses Utama |
| :--- | :--- | :--- | :--- |
| **Tata Usaha** | `tu@sekolah.com` | `password` | Kelola Kriteria, Hitung TOPSIS, Kelola User |
| **Wali Kelas** | `wali@sekolah.com` | `password` | Tambah/Edit Data Siswa & Penilaian |
| **Kepala Sekolah** | `kepsek@sekolah.com` | `password` | Pantau Hasil TOPSIS, Cetak Laporan |

---

## 🧪 Pengujian (Unit & Feature Testing)

Aplikasi ini dilengkapi dengan serangkaian pengujian otomatis (TDD) untuk memastikan stabilitas logika *engine* TOPSIS dan autentikasi.

Untuk menjalankan *test suite*, gunakan perintah:
```bash
php artisan test
```

## 🤝 Kontribusi

Jika Anda ingin berkontribusi pada proyek ini:
1. *Fork* repositori ini
2. Buat *branch* fitur Anda (`git checkout -b feature/FiturBaru`)
3. Lakukan *commit* pada perubahan Anda (`git commit -m 'Menambahkan Fitur Baru'`)
4. *Push* ke *branch* (`git push origin feature/FiturBaru`)
5. Buka sebuah *Pull Request*

## 📜 Lisensi

Proyek ini berada di bawah lisensi [MIT License](https://opensource.org/licenses/MIT). Silakan modifikasi dan gunakan secara bebas untuk keperluan Anda.
