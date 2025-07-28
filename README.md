# 📦 Sistem Informasi Koperasi - Struktur Database

## 📖 Deskripsi Umum

Sistem ini dirancang untuk mempermudah manajemen koperasi, yang mencakup fitur simpan pinjam, pengelolaan anggota, pencatatan kas besar (cash flow), hingga pemantauan bunga pinjaman dan cicilan.

## 📌 ERD Diagram

## 🧩 Penjelasan Setiap Tabel dan Kolom

### 🔐 1. `users`
Menyimpan data pengguna sistem (admin, kasir, dll).

| Kolom              | Deskripsi                                |
|--------------------|-------------------------------------------|
| `id_users`         | ID unik user                              |
| `keycloak_id`      | ID user dari sistem autentikasi Keycloak  |
| `name`             | Nama lengkap user                         |
| `username`         | Username untuk login                      |
| `email`, `password`| Kredensial login                          |
| `role`             | Role user (admin/kasir)                   |
| `email_verified_at`| Waktu verifikasi email                    |
| `profil_photo`     | Path atau URL foto profil                 |
| `created_at_user`, `updated_at_user` | Tanggal pembuatan dan update |

---

### 👥 2. `members`
Berisi data anggota koperasi.

| Kolom            | Deskripsi                                          |
|------------------|-----------------------------------------------------|
| `id_members`     | ID unik anggota                                     |
| `member_number`  | Nomor unik anggota (format: ANG-YYYYMMDD-XXX)       |
| `gender`, `birth_date`, `phone`, `address` | Informasi pribadi       |
| `position`       | Menandai apakah anggota juga pegawai                |
| `is_active`      | Status keanggotaan                                  |
| `created_at_member`, `updated_at_member` | Tanggal pembuatan dan update |

🔗 **Relasi**:  
- Satu user hanya punya satu `member`.  
- Satu `member` bisa punya banyak `savings`, `loans`.

---

### 💰 3. `savings`
Mencatat semua simpanan anggota.

| Kolom               | Deskripsi                            |
|---------------------|---------------------------------------|
| `id_saving`         | ID simpanan                           |
| `custom_id_saving`  | ID unik simpanan (misal: SP001)       |
| `saving_type`       | Jenis simpanan (pokok, wajib, sukarela) |
| `saving_amount`     | Nominal simpanan                      |
| `saving_date`       | Tanggal simpanan                      |
| `notes`             | Catatan tambahan                      |
| `created_at_saving`, `updated_at_saving` | Timestamp simpanan |

🔗 Relasi ke `members`.

---

### 🧾 4. `loans`
Mencatat pinjaman anggota.

| Kolom              | Deskripsi                            |
|--------------------|---------------------------------------|
| `id_loans`         | ID pinjaman                           |
| `custom_id_loans`  | ID unik pinjaman                      |
| `loan_amount`      | Jumlah pinjaman                       |
| `interest_rate`    | Persentase bunga                      |
| `duration_month`   | Lama pinjaman (bulan)                 |
| `loan_date`        | Tanggal pengajuan                     |
| `status`           | Aktif atau selesai                    |
| `created_at_loans`, `updated_at_loans` | Timestamp         |

🔗 Relasi ke `members`.

---

### 📆 5. `installments`
Mencatat pembayaran cicilan pinjaman.

| Kolom                    | Deskripsi                    |
|--------------------------|-------------------------------|
| `id_installments`        | ID angsuran                   |
| `custom_id_installments` | Kode angsuran                 |
| `installments_amount`    | Nominal cicilan               |
| `installments_date`      | Tanggal bayar cicilan         |
| `installments_is_paid`   | Status cicilan                |
| `created_at_installments`, `updated_at_installments` | Timestamp |

🔗 Relasi ke `loans`.

---

### 💸 6. `loan_interests`
Mencatat pembayaran bunga per bulan dari pinjaman.

| Kolom                    | Deskripsi                    |
|--------------------------|-------------------------------|
| `id_loan_interest`       | ID bunga                      |
| `custom_id_loan_interest`| Kode bunga                    |
| `loan_interest_amount`   | Jumlah bunga                  |
| `interest_date`          | Tanggal pembayaran bunga      |
| `loan_interest_is_paid`  | Status pembayaran bunga       |
| `created_at_loan_interest`, `updated_at_loan_interest` | Timestamp |

🔗 Relasi ke `loans`.

---

### 📒 7. `cash_book`
Pencatatan keluar masuk uang kas besar.

| Kolom              | Deskripsi                              |
|--------------------|-----------------------------------------|
| `id_cash_book`     | ID kas                                  |
| `transaction_date` | Tanggal transaksi                       |
| `description`      | Deskripsi transaksi                     |
| `transaction_type` | Tipe: true (masuk), false (keluar)      |
| `reference_table`  | Nama tabel referensi (misal: savings)   |
| `reference_id`     | ID dari tabel referensi                 |
| `debit`, `credit`  | Nominal masuk dan keluar                |
| `balance`          | Saldo kas setelah transaksi             |
| `created_at_cash`, `updated_at_cash` | Timestamp             |

💡 `reference_table` dan `reference_id` digunakan sebagai penghubung fleksibel agar bisa mereferensikan transaksi dari berbagai entitas (simpanan, pinjaman, bunga, dll).

## 📚 Contoh Transaksi
| Jenis Transaksi | Table Referensi | debit/credit | Keterangan |
|-----------------|------------------|--------------|------------|
| Simpanan        | `savings`        | debit        | Uang masuk |
| Pinjaman        | `loans`          | credit       | Uang keluar |
| Angsuran        | `installments`   | debit        | Uang masuk |
| Bunga Pinjaman  | `loan_interests` | debit        | Uang masuk |


