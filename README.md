# PKL Penempatan - Sistem Manajemen Penempatan Siswa

Aplikasi ini merupakan sistem manajemen penempatan siswa PKL (Praktik Kerja Lapangan) yang digunakan untuk mengelola data penempatan siswa, guru pembimbing, dan instruktur di sekolah.

## Fitur

- Manajemen data penempatan siswa PKL
- Pengajuan Surat
- Penilaian Siswa
- Monitoring Siswa
- Absensi PKL
- Pilihan tahun akademik, siswa, guru, dan instruktur
- Tabel data interaktif dengan DataTables (pencarian, filter, pagination)
- Form tambah dan edit penempatan dengan validasi
- Integrasi Select2 untuk dropdown yang lebih interaktif

## Teknologi yang Digunakan

- Laravel (Blade Template)
- jQuery
- DataTables
- Select2
- Bootstrap 5

## Cara Instalasi

1. **Clone repository ini**
   ```
   git clone https://github.com/nurMangg/PKL-system.git
   cd <nama-folder>
   ```

2. **Install dependency**
   ```
   composer install
   npm install
   ```

3. **Copy file environment**
   ```
   cp .env.example .env
   ```

4. **Atur konfigurasi database di file `.env`**

5. **Generate key aplikasi**
   ```
   php artisan key:generate
   ```

6. **Jalankan migrasi dan seeder (jika ada)**
   ```
   php artisan migrate --seed
   ```

7. **Jalankan aplikasi**
   ```
   php artisan serve
   ```

## Cara Penggunaan

1. Login ke aplikasi.
2. Pilih tahun akademik, siswa, guru, dan instruktur pada form penempatan.
3. Klik tombol **Save** untuk menyimpan data penempatan.
4. Data penempatan akan muncul pada tabel dan dapat diedit atau dihapus.

## Kontribusi

Silakan buat pull request atau issue jika ingin berkontribusi atau menemukan bug.

## Lisensi

Aplikasi ini menggunakan lisensi [MIT](LICENSE).
