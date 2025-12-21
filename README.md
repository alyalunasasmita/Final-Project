# StudyYou 

## Cara Menjalankan Aplikasi (Local)

1. Pastikan Laragon sudah terinstall
2. Jalankan Apache dan MySQL
3. Pindahkan folder project ke: laragon/www
4. Akses aplikasi melalui:
   http://localhost/studyYou



   ## Import Database

1. Buka phpMyAdmin
2. Buat database baru dengan nama `studyyou`
3. Klik Import
4. Pilih file `data_aplikasi.sql`
5. Klik Go

## Akun Demo

Admin:
- username : admin
- Password: admin123

User:
- username: user
- Password: user123

## Daftar Routing Aplikasi

### Routing Halaman (Web Routes)
### routing halaman materi (user)
| Role | Halaman | Path | Deskripsi |
|------|--------|------|----------|
| User | Materi | /public/pages/user/materi/listMateri.php | Daftar materi |
| User | Detail Materi | /public/pages/user/materi/materi_detail.php | Detail materi |
| User | detai submateri | /public/pages/user/materi/submateri_detail.php | Detail submateri | 

### routing halaman schedule (user)
| Role | Halaman | Path | Deskripsi |
|------|--------|------|----------|
| User | Jadwal | /public/pages/user/schedule/detailJadwal.php | Detail jadwal |
| User | Edit Jadwal | /public/pages/user/schedule/editJadwal.php | Edit jadwal |
| User | Lihat Jadwal | /public/pages/user/schedule/lihatJadwal.php | lihat jadwal |
| User | hapus Jadwal | /public/pages/user/schedule/hapusJadwal.php | hapus jadwal |


### routing halaman catatan (user)
| Role | Halaman | Path | Deskripsi |
|------|--------|------|----------|
| User | Lihat catatan | /public/pages/user/catatan/lihatCatatan.php | lihat catatan |
| User | hapus catatan | /public/pages/user/catatan/hapusCatatan.php | hapus catatan |
| User | edit  catatan | /public/pages/user/catatan/editCatatan.php | edit catatan |
| User | tambah catatan | /public/pages/user/catatan/tambahCatatan.php | tambah catatan |
| User | detail catatan | /public/pages/user/catatan/detailCatatan.php | detail catatan |


### routing halaman daftar tugas (user)
| Role | Halaman | Path | Deskripsi |
|------|--------|------|----------|
| User | Lihat daftar tugas | /public/pages/user/daftartugas/lihatTugas.php | lihat daftar tugas |
| User | hapus daftar tugas | /public/pages/user/daftartugas/hapusTugas.php | hapus daftar tugas |
| User | tambah daftar tugas | /public/pages/user/daftartugas/tambahTugas.php | tambah daftar tugas |
| User | edit daftar tugas | /public/pages/user/daftartugas/editTugas.php | edit daftar tugas |


### routing halaman akun (user)
| Role | Halaman | Path | Deskripsi |
|------|--------|------|----------|
| User | Lihat akun | /public/pages/user/akunuser/lihatakun.php | lihat informasi akun user |
| User | hapus akun | /public/pages/user/akunuser/hapusakun.php | hapus akun user |
| User | edit akun | /public/pages/user/akunuser/editakun.php | edit informasi akun user |
| User | ganti password akun | /public/pages/user/akunuser/gantipass.php | ganti password |


### routing halaman materi (admin)
| Role | Halaman | Path | Deskripsi |
|------|--------|------|----------|
| Admin | Lihat materi | /public/pages/admin/lihatMateri.php | lihat daftar materi |
| Admin | hapus materi | /public/pages/admin/hapusMateri.php | hapus  materi |
| Admin | edit materi | /public/pages/admin/editMateri.php | edit  materi |
| Admin | tambah materi | /public/pages/admin/tambahMateri.php | tambah materi |
| Admin | Lihat materi arsip | /public/pages/admin/ArchieveMateri.php | lihat daftar arsip materi |


### routing halaman submateri (admin)
| Role | Halaman | Path | Deskripsi |
|------|--------|------|----------|
| Admin | Lihat submateri | /public/pages/admin/lihatSubmateri.php | lihat daftar submateri |
| Admin | hapus submateri | /public/pages/admin/hapusSubmateri.php | hapus submateri |
| Admin | tambah submateri | /public/pages/admin/tambahSubmateri.php | tambah submateri |
| Admin | edit submateri | /public/pages/admin/updateSubMateri.php | edit submateri |



## Daftar Endpoint API (Auth)

| Method | Endpoint | Deskripsi |
|--------|----------|----------|
| POST | /public/api/auth/forgot_password.php | Request lupa password (kirim OTP / token) |
| POST | /public/api/auth/verify_otp.php | Verifikasi OTP |
| POST | /public/api/auth/reset_password.php | Reset password setelah OTP valid |

> Catatan: Aplikasi StudyYou menggunakan PHP server-side (pages), sehingga API hanya digunakan untuk proses tertentu seperti fitur autentikasi (lupa password).


