# 📦 Sistem Informasi Koperasi - Struktur Database

---

## 📌 ERD Diagram
---

## 🧩 Penjelasan Setiap Tabel dan Kolom

### 1. `users`
| Kolom          | Fungsi |
|----------------|--------|
| `id`           | Primary key untuk identifikasi user |
| `name`         | Nama user |
| `email`        | Email login |
| `password`     | Password terenkripsi |
| `created_at`   | Waktu pembuatan akun |
| `updated_at`   | Waktu update terakhir |

---

### 2. `members`
| Kolom          | Fungsi |
|----------------|--------|
| `id`           | Primary key untuk anggota |
| `user_id`      | Foreign key ke tabel `users` |
| `member_number`| Nomor unik anggota |
| `gender`       | Jenis kelamin anggota |
| `birth_date`   | Tanggal lahir |
| `phone`        | Nomor telepon |
| `address`      | Alamat lengkap |
| `position`     | Jabatan (anggota, admin, pegawai, dll) |

---

### 3. `savings`
| Kolom          | Fungsi |
|----------------|--------|
| `id`           | Primary key simpanan |
| `member_id`    | Foreign key ke anggota |
| `type`         | Jenis simpanan: pokok, wajib, sukarela |
| `amount`       | Nominal uang disimpan |
| `saved_at`     | Waktu simpanan masuk sistem |

---

### 4. `loans`
| Kolom          | Fungsi |
|----------------|--------|
| `id`           | Primary key pinjaman |
| `member_id`    | Foreign key ke anggota |
| `amount`       | Jumlah pinjaman yang diajukan |
| `tenor`        | Lama tenor dalam bulan |
| `monthly_payment` | Cicilan per bulan |
| `loan_date`    | Tanggal pinjaman dibuat |

---

### 5. `installments`
| Kolom          | Fungsi |
|----------------|--------|
| `id`           | Primary key cicilan |
| `loan_id`      | Foreign key ke tabel `loans` |
| `payment_amount` | Jumlah yang dibayarkan untuk angsuran |
| `due_date`     | Tanggal jatuh tempo cicilan |
| `paid_at`      | Tanggal pembayaran dilakukan |

---

### 6. `loan_interests`
| Kolom          | Fungsi |
|----------------|--------|
| `id`           | Primary key bunga |
| `loan_id`      | Foreign key ke pinjaman |
| `interest_amount` | Jumlah bunga yang harus dibayar |
| `due_date`     | Tanggal jatuh tempo bunga |
| `paid_at`      | Waktu pembayaran bunga dilakukan |

---

### 7. `cash_ledger`
| Kolom          | Fungsi |
|----------------|--------|
| `id`           | Primary key kas |
| `member_id`    | (Opsional) siapa yang melakukan transaksi |
| `transaction_date` | Tanggal transaksi dilakukan |
| `description`  | Keterangan transaksi |
| `transaction_type` | Jenis transaksi: Simpanan, Pinjaman, Angsuran, Bunga, Pengeluaran |
| `reference_table` | Nama tabel sumber transaksi (`savings`, `loans`, dll) |
| `reference_id` | ID dari entitas sumber (misal: `loan_id`) |
| `debit`        | Uang masuk koperasi |
| `credit`       | Uang keluar koperasi |
| `balance`      | Saldo setelah transaksi |
| `created_at`   | Waktu pencatatan dibuat |

---

## ✅ Catatan
- Tabel `cash_ledger` fleksibel, cukup menggunakan `reference_table` dan `reference_id` untuk menghubungkan transaksi manapun.
- `member_id` di `cash_ledger` digunakan jika transaksi berhubungan langsung dengan anggota.

---

## 📚 Contoh Transaksi
| Jenis Transaksi | Table Referensi | debit/credit | Keterangan |
|-----------------|------------------|--------------|------------|
| Simpanan Pokok  | `savings`        | debit        | Uang masuk |
| Pinjaman        | `loans`          | credit       | Uang keluar |
| Angsuran        | `installments`   | debit        | Uang masuk |
| Bunga Pinjaman  | `loan_interests` | debit        | Uang masuk |
| Pengeluaran     | `expenses`       | credit       | Uang keluar |

---

Silakan modifikasi struktur sesuai kebutuhan kebijakan koperasi di tempatmu.
