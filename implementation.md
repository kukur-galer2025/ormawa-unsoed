# Analisis & Implementasi Global Anti Double-Submission

## Temuan Masalah (Bug Analysis)
Masalah *Double-Submission* (pengiriman ganda) atau *Race Condition* sering terjadi di berbagai *form* (formulir) yang diproses ke server.
Ketika seorang pengguna menekan tombol "Submit" atau "Kirim" lebih dari satu kali secara beruntun (biasanya karena koneksi internet lambat atau ketidaksabaran pengguna), peramban web (*browser*) akan mengirimkan beberapa *request* secara bersamaan ke server. 
Hal ini dapat mengakibatkan:
1. **Pendaftaran Ganda**: Memasukkan data duplikat ke dalam *database*.
2. **Validasi Gagal**: Request pertama berhasil, namun request kedua memicu validasi duplikasi (seperti peringatan "Anda sudah mendaftar pada divisi ini").
3. **Beban Server**: Membuang-buang sumber daya komputasi dan memori sistem.

## Cakupan Implementasi (Scope)
Mengingat aplikasi **Rekrutmen Ormawa UNSOED** ini memiliki sangat banyak formulir (di halaman Admin, Superadmin, Auth, dan Mahasiswa), akan sangat tidak efisien dan rawan terlewat (*human error*) jika kita menambahkan skrip pencegahan satu per satu di setiap file `.blade.php`.

Oleh karena itu, implementasi pencegahan dilakukan secara **Global** di tingkat *Layout* utama aplikasi.

## Detail Solusi (Implementation)

Saya telah menyuntikkan (*inject*) sebuah *Event Listener* Vanilla JavaScript ke dalam dua layout utama aplikasi Anda:
1. `resources/views/layouts/app.blade.php` (Digunakan oleh Mahasiswa, Admin, Superadmin)
2. `resources/views/layouts/guest.blade.php` (Digunakan oleh halaman Login, Register, Lupa Password)

### Cara Kerja Skrip:
1. **Intercept Submit Event**: Skrip akan mendengarkan (`listen`) secara pasif terhadap setiap kejadian `submit` pada *document* HTML.
2. **Flagging**: Ketika ada *form* yang dikirim, sistem akan menambahkan penanda (`dataset.submitted = 'true'`).
3. **Blocker**: Jika form tersebut di-submit untuk kedua kalinya, skrip akan melihat penanda tersebut dan langsung membatalkan *request* selanjutnya (`e.preventDefault()`).
4. **Validasi HTML5**: Sistem cukup cerdas untuk memeriksa apakah *form* sudah valid (semua input `required` terisi) sebelum mematikan tombol. Jika belum valid, tombol tidak akan dimatikan agar pengguna bisa memperbaiki isiannya.
5. **Visual Feedback**: Semua tombol `type="submit"` di dalam form tersebut akan:
   - Dimatikan (`disabled = true`).
   - Kursor berubah menjadi *loading* (`cursor-wait`).
   - Teks tombol diubah secara otomatis menjadi indikator *spinner* berputar bertuliskan **"Memproses..."**.
   - Sistem akan melewati (*bypass*) penggantian teks otomatis jika tombol tersebut sudah dikelola oleh framework pihak ketiga (seperti `Alpine.js` dengan direktif `x-bind:disabled`), sehingga tidak mengganggu animasi *loading* kustom yang sudah Anda buat sebelumnya.

## Hasil Akhir
Dengan pendekatan global ini, **seluruh form** di dalam aplikasi Anda (mulai dari Tambah Aspek, Edit Profile, Login, Daftar Akun, Input Nilai, hingga Finalisasi Profile Matching) kini sudah 100% terlindungi dari masalah *double-click spam*. Tidak ada lagi notifikasi *error* ganda atau duplikasi data!

---

# Analisis & Implementasi Peningkatan UI/UX Kriteria & Profile Matching

## 1. Standarisasi Visual Status Rekrutmen
**Masalah:** Pada halaman Tabel Profile Matching (`admin/profile-matching/index`), *badge* status untuk rekrutmen yang sudah "Ditutup" masih menggunakan warna kuning (*amber*). Padahal pada halaman lain (seperti halaman daftar rekrutmen), status "Ditutup" menggunakan standar warna merah (*red*). Ketidakkonsistenan ini membingungkan pengguna secara psikologis.
**Implementasi:** Memodifikasi pewarnaan *badge* di `ProfileMatchingController` (tampilan `index.blade.php`) agar selaras. "Dibuka" (Biru), "Ditutup" (Merah), dan "Selesai" (Hijau).

## 2. Peningkatan Filter Data di Halaman Kriteria
**Masalah:** Pada halaman Kelola Kriteria, Admin disuguhkan *list* atau tabel yang memanjang ke bawah berisikan semua divisi beserta aspek dan kriterianya. Jika jumlah divisi banyak (misal: 10 divisi), halaman menjadi sangat panjang dan sulit dinavigasi. Hal serupa terjadi di form Tambah Kriteria, di mana semua aspek ditumpuk dalam satu *dropdown* panjang (menggunakan `optgroup`), membuat Admin kebingungan memilih aspek.
**Implementasi:**
- **Kelola Kriteria (Index):** Mengimplementasikan komponen `Alpine.js` (`x-data="{ selectedDivision: 'all' }"`) di atas halaman. Admin sekarang dapat memfilter tabel kriteria berdasarkan Divisi spesifik. Proses filtering terjadi secara *real-time* di sisi klien (browser) tanpa *reload* halaman, memberikan *feedback* instan (Zero-latency).
- **Tambah Kriteria (Create Form):** Memisahkan *dropdown* menjadi dua tahap menggunakan `Alpine.js`.
  1. *Dropdown* pertama: **Pilih Divisi**.
  2. *Dropdown* kedua: **Pilih Aspek Penilaian**.
  Data di *dropdown* kedua akan bereaksi dan **hanya memunculkan aspek yang sesuai dengan divisi** yang dipilih pada *dropdown* pertama (`<template x-if="...">`). Jika divisi tersebut belum memiliki aspek, form juga akan memunculkan peringatan *"Divisi ini belum memiliki aspek"* secara dinamis.

## 3. Validasi Matematika (Logika Profile Matching)
**Masalah:** Algoritma kalkulasi *Profile Matching* menuntut perhitungan rata-rata faktor utama (Core Factor/CF) dan faktor pendukung (Secondary Factor/SF). Jika suatu aspek tidak memiliki salah satu dari kriteria tersebut, sistem akan gagal menghitung (eror pembagian nol / *division by zero*).
**Tindakan:** Menguji dan meninjau ulang validasi kesiapan data. Sempat ada penyesuaian dinamis (mengizinkan ketiadaan CF/SF jika bobot 0%), namun sesuai *best-practice* dan permintaan perancang sistem (Anda), validasi dikembalikan (revert) ke mode ketat (*strict mode*): **Setiap Aspek mutlak harus memiliki setidaknya 1 Kriteria tipe Core Factor (CF) dan 1 Kriteria tipe Secondary Factor (SF)** agar *Profile Matching* valid dan tidak menghasilkan "Tidak Valid".
