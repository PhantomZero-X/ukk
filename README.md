# Struktur Folder & File

```
RentCar/
├── README.md
├── index.php
└── app/
    ├── index.php
    ├── assets/
    │   ├── css/
    │   └── js/
    ├── server/
    │   ├── api/
    │   │   └── api_peminjaman.php
    │   └── data/
    └── system/
        ├── layout/
        │   └── main.php
        └── pages/
            ├── dashboard.php
            ├── history.php
            └── pinjaman.php
```
# ukk

kenapa saya menggunakan nama KomiDev?

Jawab : KomiDev adalah nama yang saya buat sendiri, saya menggunakan nama ini karena saya ingin nama yang unik dan mudah diingat.

1. Tab Character (Chr(9)) - Tidak terlihat di Excel:
$nik_text = "\t" . $row['nik'];  // Tab di depan

2. Equals Quote Formula - Excel formula text:
$nik_text = '="' . $row['nik'] . '"';  // ="3578123456789012"

Tab + Quote - Kombinasi terbaik:
$nik_text = "\t" . $row['nik'];  // Tab tersembunyi