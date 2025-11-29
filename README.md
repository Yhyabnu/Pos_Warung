# POS Warung

Dokumentasi ini menjelaskan secara lengkap mengenai aplikasi **POS Warung**, sebuah sistem Point of Sale berbasis **Laravel** yang dirancang untuk membantu pengelolaan warung atau toko kecil secara efisien, modern, dan terstruktur.

---

## 🚀 Apa Itu POS Warung?

**POS Warung** adalah aplikasi kasir sederhana yang berfungsi untuk:

* Mengelola data produk
* Mengelola kategori
* Memproses transaksi penjualan
* Melihat riwayat transaksi
* Mengelola stok barang

Aplikasi ini dikembangkan menggunakan **Laravel**, **Blade Template**, **Tailwind/Bootstrap (sesuai konfigurasi)** serta database **MySQL**.

---

## 🧩 Fitur Utama

### 1. **Manajemen Produk**

* Tambah, edit, hapus produk
* Menyimpan informasi produk (nama, harga, stok, kategori)
* Upload gambar produk

### 2. **Manajemen Kategori**

* Membuat kategori baru
* Edit & hapus kategori
* Menghubungkan kategori dengan produk

### 3. **Transaksi Penjualan**

* Membuat transaksi secara cepat
* Menambah item ke keranjang
* Menghitung total otomatis
* Cetak struk (**opsional tergantung implementasi**)

### 4. **Riwayat Transaksi**

* Menampilkan daftar transaksi
* Detail transaksi lengkap
* Pencarian & filter

### 5. **Manajemen Stok Barang**

* Stok otomatis berkurang saat transaksi dibuat
* Validasi stok saat pemesanan

---

## 🏗️ Arsitektur & Teknologi

| Komponen    | Teknologi                          |
| ----------- | ---------------------------------- |
| Backend     | Laravel 10+                        |
| Frontend    | Blade Template, Tailwind/Bootstrap |
| Database    | MySQL                              |
| Build Tools | Vite                               |
| Bahasa      | PHP, JavaScript                    |

---

## 📂 Struktur Folder Penting

```
POS_Warung/
├── app/
│   ├── Http/Controllers/   # Controller aplikasi
│   ├── Models/             # Model database
│   └── ...
├── resources/
│   ├── views/              # File Blade (frontend)
│   ├── css/js              # Asset frontend
├── routes/
│   └── web.php             # Routing utama
├── database/
│   ├── migrations/         # Struktur tabel
├── public/                 # Public assets
└── composer.json           # Dependensi PHP
```

---

## 📦 Instalasi & Cara Menjalankan

Ikuti langkah berikut untuk meng-clone dan menjalankan aplikasi.

### 1️⃣ Clone Repository

```bash
git clone https://github.com/Yhyabnu/Pos_Warung.git
cd Pos_Warung
```

### 2️⃣ Install Dependensi Laravel

```bash
composer install
```

### 3️⃣ Install Dependensi Frontend

```bash
npm install
```

### 4️⃣ Buat File .env

```bash
cp .env.example .env
```

Lalu atur konfigurasi database:

```
DB_DATABASE=pos_warung
DB_USERNAME=root
DB_PASSWORD=
```

### 5️⃣ Generate Key

```bash
php artisan key:generate
```

### 6️⃣ Migrasi Database

```bash
php artisan migrate
```

### 7️⃣ Jalankan Server

```bash
php artisan serve
```

Buka di browser:

```
http://localhost:8000
```

### 8️⃣ Jalankan Vite (opsional)

```bash
npm run dev
```

---

## 🗄️ Struktur Database

Tabel utama:

* **products** — menyimpan data barang
* **categories** — menyimpan kategori
* **transactions** — menyimpan transaksi
* **transaction_items** — detail item tiap transaksi

Diagram sederhananya:

```
categories (1) ---- (∞) products
products (1) ---- (∞) transaction_items ---- (1) transactions
```

---

## 💡 Alur Kerja Aplikasi

1. Admin membuat kategori
2. Admin menambahkan produk ke dalam kategori tersebut
3. Kasir memilih produk dan membuat transaksi
4. Sistem menghitung total dan menyimpan transaksi
5. Stok otomatis berkurang
6. Riwayat transaksi bisa dilihat kapan saja

---

## 📸 Screenshot

Berikut adalah contoh tampilan antarmuka dari **POS Warung**. Ganti URL gambar sesuai dengan lokasi file screenshot Anda.

### 🏠 Dashboard

![Dashboard POS Warung](https://via.placeholder.com/900x500?text=Dashboard+POS+Warung)

### 📦 Halaman Produk

![Halaman Produk](https://via.placeholder.com/900x500?text=Halaman+Produk)

### 🗂️ Halaman Kategori

![Halaman Kategori](https://via.placeholder.com/900x500?text=Halaman+Kategori)

### 🛒 Transaksi Kasir

![Halaman Transaksi](https://via.placeholder.com/900x500?text=Transaksi+Kasir)

### 🧾 Detail Riwayat Transaksi

![Riwayat Transaksi](https://via.placeholder.com/900x500?text=Riwayat+Transaksi)

---

## 🧑‍💻 Kontribusi

Jika ingin berkontribusi:

1. Fork repository
2. Buat branch baru
3. Commit perubahan
4. Ajukan Pull Request

---

## ⚖️ Lisensi

Proyek ini menggunakan lisensi MIT. Silakan digunakan, dimodifikasi, dan dikembangkan.

---

## ⭐ Dukungan

Jika proyek ini bermanfaat, berikan **star ⭐** di repository GitHub!

Terima kasih telah menggunakan **POS Warung** 🙌
