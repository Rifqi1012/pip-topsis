<div align="center">
    <h1>🎓 Sistem Rekomendasi Penerima PIP (Metode TOPSIS)</h1>
    <p>Aplikasi berbasis web untuk menyeleksi dan merekomendasikan siswa penerima bantuan Program Indonesia Pintar (PIP) menggunakan algoritma <strong>TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution)</strong>.</p>
</div>

---

## 🌟 Tentang Proyek

Sistem ini dirancang khusus untuk mempermudah instansi sekolah dalam melakukan seleksi penerima dana bantuan PIP secara objektif, transparan, dan terkomputerisasi. Dengan memanfaatkan metode **TOPSIS All-Benefit**, sistem ini secara otomatis memberikan peringkat (ranking) kepada siswa berdasarkan tingkat prioritas dan ketidakmampuan ekonominya.

## 🚀 Fitur Utama

- 🔐 **Multi-Role Authentication** - **Kepala Sekolah** mengelola sistem dan seleksi; **Wali Kelas** memasukkan data serta penilaian siswa miliknya.
- 👥 **Manajemen Pengguna (User Management)** - Kepala Sekolah mengelola dan menonaktifkan akun. Akun nonaktif tidak dapat login dan sesi aktifnya dihentikan pada permintaan berikutnya.
- 👨‍🎓 **Manajemen Data Siswa** - Wali kelas dapat menambahkan data siswa beserta atribut lengkap (NISN, Alamat Lengkap, Kelas).
- 🧮 **Dynamic TOPSIS Engine** - Algoritma TOPSIS yang membaca bobot dan atribut kriteria secara dinamis. Menggunakan pendekatan *All-Benefit* di mana kondisi ekonomi yang lebih membutuhkan akan mendapatkan poin *ranking* lebih tinggi.
- ⚙️ **Master Kriteria Fleksibel** - Kepala Sekolah mengelola kriteria dan sub-kriteria dinamis. Seeder menyediakan delapan kriteria; atribut Benefit dan Cost mengikuti konfigurasi masing-masing.
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
Langkah ini membangun tabel database, data master, dan akun contoh untuk pengembangan lokal. Seeder tidak membuat data siswa.
```bash
php artisan migrate --seed
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
| **Wali Kelas** | `wali@sekolah.com` | `password` | Tambah/Edit Data Siswa & Penilaian |
| **Kepala Sekolah** | `kepsek@sekolah.com` | `password` | Kelola Master dan User, Hitung TOPSIS, Cetak Laporan |

---

## 🧪 Pengujian (Unit & Feature Testing)

Aplikasi ini dilengkapi dengan serangkaian pengujian otomatis (TDD) untuk memastikan stabilitas logika *engine* TOPSIS dan autentikasi.

Untuk menjalankan *test suite*, gunakan perintah:
```bash
php artisan test
```

Tes menggunakan SQLite in-memory terpisah dari database aplikasi. Cakupannya
meliputi autentikasi, hak akses, CRUD, validasi impor, perhitungan TOPSIS, dan ekspor.
Jalankan pemeriksaan sebelum deployment:

```bash
composer validate --strict
vendor/bin/pint --test
php artisan test
npm run build
composer audit
npm audit
```

## 🐳 Docker & Coolify

Image produksi memakai PHP 8.4 + Apache pada port **80**. Composer dipasang tanpa
dependensi development, dan aset Vite dibangun dengan `npm ci`. File `.env`, data
lokal, dan kredensial tidak disertakan dalam image.

### Build dan uji lokal

```bash
docker build -t pip-topsis:coolify .
docker run --rm --entrypoint php pip-topsis:coolify artisan key:generate --show
```

Untuk menguji image dengan database MySQL sementara tanpa membuka port host:

```bash
sh docker/smoke-test.sh
```

Tes memeriksa migrasi, endpoint `/up`, halaman login, aset, izin storage, serta
pembuatan PDF dan Excel.
Container serta network pengujian dibersihkan otomatis; database memakai tmpfs.

Salin `.env.production.example` ke `.env.production`, isi `APP_KEY` dengan hasil
perintah kedua, lalu isi koneksi MySQL/MariaDB dan SMTP. Untuk uji melalui HTTP
lokal, gunakan `APP_URL=http://localhost:8080` dan `SESSION_SECURE_COOKIE=false`.
Jika database berada di Mac, gunakan `DB_HOST=host.docker.internal`.

```bash
docker run -d --name pip-topsis --env-file .env.production \
  -p 127.0.0.1:8080:80 \
  -v pip-topsis-storage:/var/www/html/storage \
  pip-topsis:coolify
docker exec --user www-data pip-topsis php artisan migrate --force
curl --fail http://localhost:8080/up
```

### Pengaturan Coolify

1. Hubungkan repository dan pilih build pack **Dockerfile**. Base Directory `/`,
   Dockerfile Location `/Dockerfile`, dan Ports Exposes `80`.
2. Buat database **MySQL 8.4** atau **MariaDB**, lalu gunakan hostname internal dan
   kredensialnya pada environment aplikasi. Database dan aplikasi harus berada
   pada Docker network yang sama; aktifkan **Connect to Predefined Network**
   jika resource database berada pada network Coolify tersebut. Jangan memakai
   `127.0.0.1` sebagai host database terpisah.
3. Masukkan variabel dari `.env.production.example` sebagai **runtime environment**.
   Isi `APP_KEY` sekali dan pertahankan nilainya pada redeploy. Atur `APP_URL`
   sesuai domain HTTPS, `APP_DEBUG=false`, dan `SESSION_SECURE_COOKIE=true`.
   Rahasia database, SMTP, dan `APP_KEY` tidak perlu tersedia saat build.
4. Tambahkan persistent volume dengan destination `/var/www/html/storage` agar
   file impor dan upload tetap tersedia setelah redeploy. Database juga harus
   memakai persistent storage miliknya sendiri.
5. Gunakan health check HTTP `GET /up` pada port `80` dengan status `200`.
   Endpoint ini memeriksa aplikasi dapat boot, bukan koneksi database atau SMTP.
6. Atur post-deployment command `php artisan migrate --force`, lalu deploy.
   Migrasi tidak dijalankan otomatis oleh entrypoint. Cadangkan database sebelum
   deployment yang mengubah skema.

Container membuat direktori storage dan cache konfigurasi, route, serta Blade
saat mulai, setelah environment runtime tersedia. Tidak ada seeding otomatis.
Untuk instalasi baru, jalankan seeder master berikut sekali dari terminal Coolify:

```bash
php artisan db:seed --class=KelasSeeder --force
php artisan db:seed --class=KriteriaSeeder --force
php artisan db:seed --class=SubKriteriaSeeder --force
php artisan db:seed --class=PengaturanSeeder --force
```

Buat akun kepala sekolah dengan kredensial sendiri melalui `php artisan tinker`
dan `App\Models\User::create(...)`; isi `name`, `email`, `password`,
`role` (`kepala_sekolah`), serta `is_active` (`true`). Model meng-hash password
secara otomatis. Jangan gunakan `DatabaseSeeder` untuk produksi karena membuat
akun contoh dengan password tetap dan memakai factory development.

`QUEUE_CONNECTION=sync` sesuai alur aplikasi saat ini yang belum memakai job
background. Jika kelak menggunakan queue asynchronous, tambahkan worker terpisah.
Konfigurasi produksi Docker menggunakan MySQL/MariaDB. Migrasi juga mendukung
SQLite untuk pengembangan dan pengujian otomatis.

Referensi: [Coolify Dockerfile](https://coolify.io/docs/applications/builds/dockerfile),
[Laravel deployment](https://laravel.com/docs/12.x/deployment).

## 🤝 Kontribusi

Jika Anda ingin berkontribusi pada proyek ini:
1. *Fork* repositori ini
2. Buat *branch* fitur Anda (`git checkout -b feature/FiturBaru`)
3. Lakukan *commit* pada perubahan Anda (`git commit -m 'Menambahkan Fitur Baru'`)
4. *Push* ke *branch* (`git push origin feature/FiturBaru`)
5. Buka sebuah *Pull Request*
