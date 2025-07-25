-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 25 Jul 2025 pada 08.34
-- Versi server: 5.7.39
-- Versi PHP: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_koperasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `member_number` varchar(50) DEFAULT NULL,
  `gender` enum('Laki-laki','Perempuan') NOT NULL,
  `birth_date` date NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `position` enum('Anggota','Ketua','Wakil Ketua','Sekretaris','Bendahara','Pengawas','Manajer','Staf') NOT NULL DEFAULT 'Anggota',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `members`
--

INSERT INTO `members` (`id`, `user_id`, `member_number`, `gender`, `birth_date`, `phone`, `address`, `position`, `created_at`, `updated_at`) VALUES
(1, 3, 'KOP-2025-001', 'Perempuan', '2004-05-19', '085923163479', 'Taman Puspa Sarirogo N1-03, Sidoarjo', 'Wakil Ketua', '2025-07-17 07:57:55', '2025-07-20 23:47:27'),
(3, 4, 'KOP-2025-003', 'Laki-laki', '2004-03-30', '082333151697', 'Sumenep', 'Ketua', '2025-07-17 21:11:32', '2025-07-20 19:09:25'),
(4, 2, 'KOP-2025-002', 'Perempuan', '2004-03-09', '085712398498', 'Kalianget, Sumenep', 'Sekretaris', '2025-07-20 19:06:06', '2025-07-20 19:14:07'),
(6, 1, 'KOP-2025-004', 'Perempuan', '2004-05-22', '085712345678', 'Bangkalan', 'Anggota', '2025-07-22 01:45:22', '2025-07-22 01:45:22'),
(8, 5, 'KOP-2025-005', 'Perempuan', '2004-02-20', '085912345679', 'Madiun', 'Anggota', '2025-07-23 22:06:49', '2025-07-23 22:06:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `keycloak_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'anggota',
  `email_verified_at` timestamp NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `keycloak_id`, `name`, `username`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `profile_photo`) VALUES
(1, '8d96e762-5df4-4e9a-be71-3ce0fe4269cf', 'Windi Wulandari', 'windi123', 'windiwulandari@gmail.com', 'anggota', '2025-07-15 00:35:15', '$2y$12$WQ2Fhjmc4c3iXMAnkvxeROFTlKltN.WJUBV0E8LzuzuRF.URRgrE6', NULL, '2025-07-15 00:35:15', '2025-07-15 00:35:15', NULL),
(2, '43d85ee2-0d58-4c83-9116-e5483902b791', 'Hasanatun Fajariya', 'natun64', 'fajariyahasanatun@gmail.com', 'anggota', '2025-07-15 00:59:05', '$2y$12$M2OCNHu/x1DKWMQA2Xix2.Z8gJQGLcT2QYt3qDPzdZEWxVSpafRy.', NULL, '2025-07-15 00:59:05', '2025-07-15 00:59:05', NULL),
(3, '6a3addd7-9bc6-4f4d-a652-b7a94890cc5e', 'Putri Qurratu Aini', 'putri123', 'putriqurratu05@gmail.com', 'admin', '2025-07-15 22:03:47', '$2y$12$26o0dkwrFJUpdV05e8G7.OvTw9/l0zZPhTIQI.i0inuEg0UhAOK9u', NULL, '2025-07-15 22:03:47', '2025-07-21 20:39:09', 'storage/profile_photos/profile_1753085412.jpg'),
(4, 'fc6d42b0-c21b-4ef0-9180-a8e365f3900b', 'Muhammad Muhtar Riyansyah', 'riyansyah19', 'riyansyah3001@gmail.com', 'anggota', '2025-07-17 20:41:19', '$2y$12$fI8nwY.3lHJqF230JaEyX.qXpPqKozRAiBukAlpuEfRA6ZVAkAaN2', NULL, '2025-07-17 20:41:20', '2025-07-17 21:34:36', NULL),
(5, '5c06a9ea-deb0-4db0-85ef-46ed701118e6', 'Riska Febri Wahyuningtyas', 'febri123', 'febri123@gmail.com', 'anggota', '2025-07-22 19:27:42', '$2y$12$Tht7Q6HGFdj4NfPSN1kk/e9NEVfKLrZdOutuloS/nxkkJXx7e6hfu', NULL, '2025-07-22 19:27:43', '2025-07-23 21:11:49', 'storage/profile_photos/profile_1753330309.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
