# 📖 Sistem Buku Tamu Digital

> Sistem Buku Tamu Digital merupakan aplikasi berbasis web yang dikembangkan untuk mendigitalisasi proses pencatatan tamu pada lingkungan internal instansi. Sistem ini menggantikan proses pencatatan manual menjadi lebih cepat, terstruktur, terdokumentasi, dan mudah dikelola melalui dashboard administrasi.

---

# 📌 Project Overview

Sistem Buku Tamu Digital dikembangkan sebagai solusi untuk mengelola data kunjungan tamu secara elektronik mulai dari proses pengisian data tamu, pengambilan foto, penyimpanan data, pengelolaan feedback pengunjung, hingga penyajian dashboard monitoring dan pelaporan.

Aplikasi ini terdiri dari beberapa modul utama yang saling terintegrasi, antara lain:

- Form Buku Tamu Digital
- Dashboard Daftar Tamu
- Form Feedback Pengunjung
- Dashboard Feedback
- Export Data ke Microsoft Excel
- Pengiriman Notifikasi Email
- Dashboard Statistik dan Monitoring

Seluruh data tersimpan ke dalam database sehingga dapat diakses kembali untuk kebutuhan monitoring maupun pelaporan.

---

# 📌 Latar Belakang

Sebelumnya proses pencatatan tamu dilakukan secara manual menggunakan buku tamu konvensional. Cara tersebut memiliki berbagai keterbatasan, antara lain:

- Proses pencatatan membutuhkan waktu lebih lama.
- Sulit melakukan pencarian data tamu.
- Risiko kehilangan atau kerusakan data cukup tinggi.
- Rekapitulasi laporan dilakukan secara manual.
- Tidak tersedia media untuk memperoleh feedback pengunjung.
- Monitoring jumlah kunjungan tidak dapat dilakukan secara real-time.

Untuk mengatasi permasalahan tersebut dikembangkan Sistem Buku Tamu Digital sebagai media pencatatan tamu berbasis web yang lebih efisien, terdokumentasi, dan mudah digunakan.

---

# 🎯 Tujuan Pengembangan

Pengembangan Sistem Buku Tamu Digital bertujuan untuk:

- Mendigitalisasi proses pencatatan tamu.
- Mempermudah proses pengisian data kunjungan.
- Mempercepat pencarian data tamu.
- Menyediakan dashboard monitoring kunjungan secara real-time.
- Menyediakan fitur feedback pengunjung sebagai bahan evaluasi pelayanan.
- Mempermudah proses rekapitulasi dan export data.
- Mengurangi penggunaan dokumen fisik.
- Meningkatkan akurasi dan keamanan penyimpanan data.

---

# ✨ Fitur Utama

## 📋 Form Buku Tamu

- Input data tamu
- Input instansi
- Input nomor telepon
- Input email
- Input tujuan kunjungan
- Input keperluan
- Pilihan tanggal kunjungan
- Pengambilan foto menggunakan kamera perangkat
- Validasi data input
- Penyimpanan data ke database

---

## 📊 Dashboard Daftar Tamu

- Menampilkan seluruh data tamu
- Statistik jumlah kunjungan
- Statistik kunjungan hari ini
- Statistik bulanan
- Filter berdasarkan tanggal
- Pencarian data
- Pagination
- Export Microsoft Excel
- Detail data tamu

---

## ⭐ Form Feedback

- Penilaian pelayanan
- Rating menggunakan emoticon
- Kritik dan saran
- Validasi input
- Penyimpanan feedback

---

## 📈 Dashboard Feedback

- Menampilkan seluruh feedback
- Statistik penilaian
- Filter berdasarkan rating
- Filter komentar
- Filter tanggal
- Pencarian data
- Pagination
- Export Microsoft Excel
- Dashboard visual monitoring

---

## 📧 Notifikasi Email

- Pengiriman email otomatis setelah data berhasil dikirim.
- Konfirmasi data kunjungan kepada pengunjung.

---

## 📂 Export Data

- Export Daftar Tamu ke Microsoft Excel
- Export Feedback ke Microsoft Excel

---

# 🔄 Perubahan dari Sistem Sebelumnya

| Sebelum | Sesudah |
|----------|----------|
| Buku tamu manual | Buku tamu digital berbasis web |
| Ditulis tangan | Input melalui form digital |
| Tidak ada foto tamu | Pengambilan foto langsung melalui kamera |
| Rekap manual | Dashboard otomatis |
| Sulit mencari data | Fitur pencarian dan filter |
| Tidak ada statistik | Dashboard statistik kunjungan |
| Tidak ada feedback | Form feedback digital |
| Tidak ada dashboard feedback | Dashboard monitoring feedback |
| Tidak ada export | Export Microsoft Excel |
| Tidak ada email konfirmasi | Email otomatis |

---

# 🛠 Teknologi yang Digunakan

## Backend

- PHP Native
- MySQL / MariaDB

## Frontend

- HTML5
- CSS3
- JavaScript
- Bootstrap 5

## Library

- Font Awesome
- Google Fonts (Poppins)
- SheetJS (Export Excel)
- PHPMailer (Email)

## Database

- MySQL

## Browser Support

- Google Chrome
- Microsoft Edge

---

# 📁 Struktur Repository

```text
digital-guest-book/
│
├── assets/
│
├── source-code/
│   ├── index.php
│   ├── simpan.php
│   ├── dashboard.php
│   ├── detail.php
|   ├── feedback.php
│   ├── dashboard_feedback.php
│   └── export.php
│
├── document/
|   ├── document qa.xlxs
│   └── dokumen teknis.pdf
│
└── README.md
```

---

# 🧪 Dokumentasi QA

Dokumentasi Quality Assurance disusun secara terpisah untuk memastikan seluruh kebutuhan sistem telah diuji dan terdokumentasi dengan baik.

Dokumen QA meliputi:

- Project Overview
- Functional Requirement
- Non-Functional Requirement
- Requirement Traceability Matrix (RTM)
- Functional Test Scenario
- Functional Test Case
- Non-Functional Test Scenario
- Non-Functional Test Case
- Bug Report
- Test Summary Report

Seluruh pengujian dilakukan menggunakan metode Manual Testing berdasarkan kebutuhan fungsional maupun non-fungsional sistem.

---

# 📝 Catatan Pengembangan

- Sistem dikembangkan sebagai pengganti proses pencatatan tamu secara manual.
- Fokus utama pengembangan adalah kemudahan penggunaan, efisiensi administrasi, dan pengelolaan data kunjungan.
- Desain antarmuka dibuat responsif sehingga dapat diakses melalui desktop maupun perangkat mobile.
- Seluruh data disimpan pada database relasional dan dapat diekspor ke Microsoft Excel.
- Sistem dirancang agar mudah dikembangkan untuk kebutuhan integrasi dengan sistem lain di masa mendatang.

---

# 👨‍💻 Pengembang

**Developer**

Nanda Inka

**Role**

- Full Stack Web Developer
- Database Designer
- Manual Quality Assurance (QA)

---

## 📄 Lisensi

Repositori ini dikembangkan sebagai bagian dari proyek pengembangan aplikasi internal. Seluruh kode sumber dan dokumentasi digunakan untuk tujuan pengembangan, pembelajaran, dan portofolio sesuai dengan ketentuan yang berlaku.

---

⭐ Apabila repositori ini bermanfaat, silakan berikan **Star** sebagai bentuk apresiasi.
