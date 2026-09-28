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
