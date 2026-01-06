# 📊 Tablo Dashboard By Mikhael

**Nama Website:** Tablo Dashboard By Mikhael  
**Website:** https://tablobymikhael.ct.ws  

**Deskripsi:**  
Tablo Dashboard By Mikhael adalah aplikasi manajemen data yang dirancang agar **fleksibel, efisien, dan cepat** dalam mengelola berbagai tabel database. Website ini menyediakan fitur CRUD dinamis berbasis API sehingga dapat digunakan untuk mengelola banyak jenis data melalui satu dashboard terpusat.

--------------------------------------------

# 📘 Website Manajemen Data (PHP + CRUD API)

Website ini dibuat sebagai **tugas pembuatan website** menggunakan **PHP Native**, **PHP CRUD API**, dan **Bootstrap**.  
Aplikasi ini mendukung pengelolaan data multi-tabel dengan sistem login serta tampilan dashboard yang responsif.

---

## 🧩 Teknologi yang Digunakan

- **PHP (Native)**
- **PHP CRUD API**
- **Bootstrap** (UI & Dashboard)
- **MySQL / MariaDB**
- **HTML, CSS, JavaScript**

---

## 📂 Struktur Halaman

Berikut adalah halaman-halaman utama pada website:

| Halaman | Deskripsi |
|-------|----------|
| `index.php` | Halaman utama / dashboard |
| `login.php` | Halaman login pengguna |
| `logout.php` | Logout pengguna |
| `tambah.php` | Halaman tambah data |
| `edit.php` | Halaman edit data |
| `delete.php` | Proses hapus data |
| `data_tabel.php?table=<nama_tabel>` | Menampilkan data berdasarkan tabel database |

---

## 🔐 Akun Login (Untuk Penilaian)

### User
- **Username:** `user`
- **Password:** `user123`

> ⚠️ *Akun ini disediakan khusus untuk keperluan tugas dan pengujian.*

---

## ⚙️ Fitur Utama

- ✅ Login & Logout pengguna
- ✅ Dashboard manajemen data
- ✅ CRUD multi-tabel database
- ✅ Integrasi **PHP CRUD API**
- ✅ Pemilihan tabel database secara dinamis
- ✅ Tampilan responsif menggunakan Bootstrap

---

## 🗄️ Konsep Database

Website **Tablo Dashboard By Mikhael** menggunakan **MySQL / MariaDB** sebagai database utama dan dikelola melalui **PHP CRUD API**.  
Sistem ini memungkinkan pengelolaan banyak tabel database melalui satu dashboard tanpa query SQL manual.

### 📑 Struktur Tabel Database

Database terdiri dari beberapa tabel berikut:

| Tabel | Deskripsi |
|------|----------|
| `cabang` | Data cabang perusahaan |
| `gudang` | Data gudang penyimpanan |
| `inventori` | Data stok dan inventaris |
| `kategori` | Kategori produk |
| `pegawai` | Data pegawai |
| `pelanggan` | Data pelanggan |
| `pengiriman` | Data pengiriman barang |
| `pesanan` | Data pesanan |
| `produk` | Data produk |
| `supplier` | Data supplier |

---

## 🔄 Konsep CRUD (Create, Read, Update, Delete)

Pengelolaan data dilakukan menggunakan endpoint API berikut:

### Contoh endpoint API:
```http
POST   /api.php/records/produk        # Create (Tambah Data)
GET    /api.php/records/produk        # Read (Lihat Data)
PUT    /api.php/records/produk/{id}   # Update (Ubah Data)
DELETE /api.php/records/produk/{id}   # Delete (Hapus Data)
```

## 🚀 Cara Pemasangan (Instalasi)

Ikuti langkah-langkah berikut untuk menjalankan website **Tablo Dashboard By Mikhael** secara lokal menggunakan **XAMPP**.

1. **Persiapan Server**
   - Install **XAMPP**
   - Jalankan **Apache** dan **MySQL**

2. **Buat Database**
   - Buka **phpMyAdmin**
   - Buat database baru dengan nama:
     ```
     crud_db
     ```

3. **Import Database**
   - Masuk ke database `crud_db`
   - Pilih menu **Import**
   - Import file database (`.sql`) yang tersedia pada project

4. **Buat Folder Project**
   - Masuk ke folder:
     ```
     xampp/htdocs/
     ```
   - Buat folder baru dengan nama:
     ```
     tablo
     ```

5. **Import File Project**
   - Salin seluruh file project ke dalam folder:
     ```
     xampp/htdocs/tablo/
     ```

6. **Jalankan Website**
   - Buka browser
   - Akses alamat:
     ```
     http://localhost/tablo/
     ```

7. **Login**
   - **Username:** `user`
   - **Password:** `user123`

### 📌 Catatan Instalasi
- Pastikan konfigurasi koneksi database pada file PHP sudah sesuai
- Website ini dibuat untuk **keperluan tugas akademik**, bukan penggunaan produksi
