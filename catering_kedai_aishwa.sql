-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 02:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catering_kedai_aishwa`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `detail_id` int(11) NOT NULL,
  `pesanan_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `jumlah_menu` int(11) DEFAULT NULL,
  `tambahan_id` int(11) DEFAULT NULL,
  `harga_satuan_tambahan` decimal(15,2) DEFAULT NULL,
  `jumlah_tambahan` int(11) DEFAULT NULL,
  `tanggal_acara` date DEFAULT NULL,
  `waktu_acara` time DEFAULT NULL,
  `total_detail_pesanan` decimal(15,2) DEFAULT NULL,
  `status_detail_pesanan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`detail_id`, `pesanan_id`, `menu_id`, `harga_satuan`, `jumlah_menu`, `tambahan_id`, `harga_satuan_tambahan`, `jumlah_tambahan`, `tanggal_acara`, `waktu_acara`, `total_detail_pesanan`, `status_detail_pesanan`) VALUES
(43, 44, 2, 20000.00, 100, NULL, NULL, NULL, NULL, NULL, 2000000.00, 'Diproses');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `nama_kategori`) VALUES
(1, 'Nasi Kotak'),
(2, 'Kue'),
(3, 'Tumpeng');

-- --------------------------------------------------------

--
-- Table structure for table `komplain`
--

CREATE TABLE `komplain` (
  `id` int(11) NOT NULL COMMENT 'AUTO_INCREMENT',
  `nama_produk` varchar(100) NOT NULL,
  `tanggal_acara` date NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(255) NOT NULL,
  `waktu_masuk` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `nama_menu` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga_satuan` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `nama_menu`, `deskripsi`, `harga_satuan`, `gambar`, `kategori_id`) VALUES
(1, 'Paket Ayam Goreng', 'Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.', 20000, '1778421489_WhatsApp Image 2026-02-21 at 21.48.58.jpeg', 1),
(2, 'Paket Ayam Bakar', 'Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.', 20000, '1778118960_WhatsApp Image 2026-02-21 at 22.03.08.jpeg', 1),
(3, 'Paket Ayam Geprek', 'Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.', 20000, '1778119151_WhatsApp Image 2026-02-21 at 22.07.53.jpeg', 1),
(4, 'Rice Bowl', 'Nasi, ayam krispi, sayur/kentang, sambal (geprek, matah, tomat).', 10000, '1778119269_WhatsApp Image 2026-02-16 at 22.03.25.jpeg', 1),
(5, 'Tumpeng Nusantara', 'Nasi kuning, telor balado, mie goreng jawa, orek, urap, sambal.', 200000, '1778119339_WhatsApp_Image_2026-02-11_at_08.53.28-removebg-preview.png', 1),
(6, 'Tumpeng Nusantara Spesial', 'Nasi kuning, ayam bakar/goreng, orek, mie goreng jawa, urap, sambal.', 250000, '1778119387_Gemini_Generated_Image_erm4mderm4mderm4.png', 1),
(7, 'Tumpeng Nusantara Premium', 'Nasi kuning, ayam bakar/goreng, telor balado, mie goreng jawa, orek, urap, sambal.', 300000, '1778119468_Gemini_Generated_Image_vgy9v9vgy9v9vgy9.png', 1),
(101, 'Tempe Tahu', 'Menu Tambahan', 2000, '1778421835_Tempe tahu goreng.jpeg', 1),
(103, 'Puding', 'Menu Tambahan', 2000, '1778421959_puding.jpeg', 1),
(104, 'Putu Ayu', 'Menu Tambahan', 2000, '1778422042_Resep Putu Ayu Enak Dan Lembut Ala Ncc oleh BunnaBintang.jpeg', 1),
(105, 'Bolu Kukus', 'Menu Tambahan', 2000, '1778421994_WhatsApp Image 2026-03-06 at 13.55.39.jpeg', 1),
(106, 'Pisang', 'Menu Tambahan', 2000, '1778422080_Banana Bread Muffin Tops – Oh She Glows.jpeg', 1),
(107, 'Jeruk', 'Menu Tambahan', 2000, '1778422102_Buah buahan.jpeg', 1),
(108, 'Rengginang', 'Menu Tambahan', 1000, '1778422135_download (8).jpeg', 1),
(109, 'Bolu Jadul', 'Menu Tambahan', 40000, '1778422180_WhatsApp Image 2026-02-21 at 21.47.17.jpeg', 1),
(110, 'Bolu Meses Keju', 'Menu Tambahan', 50000, '1778422207_WhatsApp Image 2026-02-21 at 21.45.35.jpeg', 1),
(111, 'Brownies Keju', 'Menu Tambahan', 35000, '1778422248_WhatsApp Image 2026-02-21 at 21.43.57.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `pembayaran_id` int(11) NOT NULL,
  `pesanan_id` int(11) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `waktu_pembayaran` time DEFAULT NULL,
  `total_pembayaran` decimal(15,2) DEFAULT NULL,
  `status_pembayaran` varchar(50) DEFAULT 'Belum Bayar',
  `jumlah_dp` decimal(15,2) DEFAULT 0.00,
  `status_dp` varchar(50) DEFAULT 'Belum Bayar',
  `tanggal_dp` date DEFAULT NULL,
  `waktu_dp` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`pembayaran_id`, `pesanan_id`, `metode_pembayaran`, `tanggal_pembayaran`, `waktu_pembayaran`, `total_pembayaran`, `status_pembayaran`, `jumlah_dp`, `status_dp`, `tanggal_dp`, `waktu_dp`) VALUES
(37, 40, NULL, NULL, NULL, 29999999.00, 'Selesai', 0.00, 'Belum Bayar', NULL, NULL),
(38, 41, 'gopay', '2026-05-02', NULL, 29999999.00, 'Selesai', 0.00, 'Belum Bayar', NULL, NULL),
(39, 42, 'bca-dp', '2026-05-02', NULL, 999999.00, 'Selesai', 499999.50, 'Selesai', NULL, NULL),
(40, 43, 'mandiri', '2026-05-02', NULL, 999999.00, 'Selesai', 0.00, 'Belum Bayar', NULL, NULL),
(41, 44, NULL, NULL, NULL, 2000000.00, 'Selesai', 0.00, 'Belum Bayar', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `pesanan_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tanggal_pesan` date DEFAULT NULL,
  `tanggal_acara` date DEFAULT NULL,
  `waktu_acara` time DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_handphone` varchar(15) DEFAULT NULL,
  `total_pesan` decimal(15,2) DEFAULT NULL,
  `status_pesanan` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`pesanan_id`, `user_id`, `tanggal_pesan`, `tanggal_acara`, `waktu_acara`, `alamat`, `no_handphone`, `total_pesan`, `status_pesanan`, `catatan`) VALUES
(40, 3, '2026-05-02', '2026-05-02', '09:35:00', 'desa kiajaran kulon blok pelabuhan', '085795244257', 29999999.00, 'Dikirim', ''),
(41, 2, '2026-05-02', '2026-05-16', '23:16:00', 'desa kiajaran kulon blok pelabuhan', '085795244257', 29999999.00, 'Dikonfirmasi', ''),
(42, 3, '2026-05-02', '2026-05-15', '11:23:00', 'desa kiajaran kulon blok pelabuhan', '085795244257', 999999.00, 'Dikonfirmasi', ''),
(43, 2, '2026-05-02', '2026-07-17', '04:40:00', 'desa kiajaran kulon blok pelabuhan', '085795244257', 999999.00, 'Dikonfirmasi', 'ayam nya sampe gosong\r\n'),
(44, 10, '2026-05-07', '2026-05-07', '16:00:00', 'lohbener', '0859797932', 2000000.00, 'Dikonfirmasi', '');

-- --------------------------------------------------------

--
-- Table structure for table `tambahan`
--

CREATE TABLE `tambahan` (
  `tambahan_id` int(11) NOT NULL,
  `nama_tambahan` varchar(100) DEFAULT NULL,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
--

CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL COMMENT 'AUTO_INCREMENT',
  `nama_produk` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(255) NOT NULL,
  `waktu_masuk` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` enum('admin','pelanggan') DEFAULT 'pelanggan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `nama_lengkap`, `email`, `password`, `role`) VALUES
(2, 'fazi', 'faizbilah979@gmail.com', '123456', 'pelanggan'),
(3, 'Admin', 'admin@gmail.com', 'admin123', 'admin'),
(9, 'anja', 'anja@gmail.com', '12', 'pelanggan'),
(10, 'fika', 'fika@gmail.com', '123', 'pelanggan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `fk_detail_pesanan` (`pesanan_id`),
  ADD KEY `fk_detail_menu` (`menu_id`),
  ADD KEY `fk_detail_tambahan` (`tambahan_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `fk_menu_kategori` (`kategori_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`pembayaran_id`),
  ADD KEY `fk_pembayaran_pesanan` (`pesanan_id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`pesanan_id`),
  ADD KEY `fk_pesanan_user` (`user_id`);

--
-- Indexes for table `tambahan`
--
ALTER TABLE `tambahan`
  ADD PRIMARY KEY (`tambahan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1012;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `pembayaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `pesanan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tambahan`
--
ALTER TABLE `tambahan`
  MODIFY `tambahan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `fk_detail_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`pesanan_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_tambahan` FOREIGN KEY (`tambahan_id`) REFERENCES `tambahan` (`tambahan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`pesanan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pesanan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
