-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 03:04 PM
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
(212, 112, 1013, 20000.00, 10, NULL, NULL, NULL, NULL, NULL, 200000.00, 'Diproses'),
(213, 113, 1019, 300000.00, 1, NULL, NULL, NULL, NULL, NULL, 300000.00, 'Diproses');

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
  `id` int(11) NOT NULL,
  `pesanan_id` varchar(50) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto_bukti` varchar(255) NOT NULL,
  `waktu_masuk` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `komplain`
--

INSERT INTO `komplain` (`id`, `pesanan_id`, `nama_user`, `user_email`, `deskripsi`, `foto_bukti`, `waktu_masuk`) VALUES
(2, 'ord-987', 'fazi', 'faizbilah979@gmail.com', 'kurang enak', '1780037003_bukti.png', '2026-05-29 06:43:23');

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
  `kategori_id` int(11) DEFAULT NULL,
  `is_rekomendasi` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `nama_menu`, `deskripsi`, `harga_satuan`, `gambar`, `kategori_id`, `is_rekomendasi`) VALUES
(1013, 'Paket Ayam Goreng', ' Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.', 20000, '1780638634_WhatsApp Image 2026-02-21 at 21.48.58.jpeg', 1, 0),
(1014, 'Paket Ayam Bakar', ' Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.', 20000, '1780638670_WhatsApp Image 2026-02-21 at 22.03.08.jpeg', 1, 0),
(1015, 'Paket Ayam Geprek', ' Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.', 20000, '1780638705_WhatsApp Image 2026-02-21 at 22.07.53.jpeg', 1, 0),
(1016, 'Rice Bowl', 'Nasi, ayam krispi, sayur/kentang, sambal (geprek, matah, tomat).', 10000, '1780638803_WhatsApp Image 2026-02-16 at 22.03.25.jpeg', 1, 0),
(1017, 'Tumpeng Nusantara', 'Nasi kuning, telor balado, mie goreng jawa, orek, urap, sambal.', 200000, '1780638847_WhatsApp_Image_2026-02-11_at_08.53.28-removebg-preview.png', 3, 0),
(1018, 'Tumpeng Nusantara Spesial', 'Nasi kuning, ayam bakar/goreng, mie goreng jawa, orek, urap, sambal.', 250000, '1780638969_Gemini_Generated_Image_vgy9v9vgy9v9vgy9.png', 3, 0),
(1019, 'Tumpeng Nusantara Premium', 'Nasi kuning, ayam bakar/goreng, telor balado, mie goreng jawa, orek, urap, sambal.', 300000, '1780639014_tumpeng.png', 3, 0),
(1021, 'Puding', 'Menu Tambahan', 2000, '1780639191_puding.jpeg', 2, 0),
(1022, 'Putu Ayu', 'Menu Tambahan', 2000, '1780639230_Resep Putu Ayu Enak Dan Lembut Ala Ncc oleh BunnaBintang.jpeg', 2, 0),
(1023, 'Bolu Kukus', 'Menu Tambahan', 2000, '1780639262_WhatsApp Image 2026-03-06 at 13.55.39.jpeg', 2, 0),
(1027, 'Bolu Jadul', '', 40000, '1780639502_WhatsApp Image 2026-02-21 at 21.47.17.jpeg', 2, 0),
(1028, 'Bolu Meses Keju', '', 50000, '1780639537_WhatsApp Image 2026-02-21 at 21.45.35.jpeg', 2, 0),
(1029, 'Brownies Keju', '', 35000, '1780639561_WhatsApp Image 2026-02-21 at 21.43.57.jpeg', 2, 0),
(1030, 'Tempe Tahu', 'Menu Tambahan ', 2000, '1780639787_Tempe tahu goreng.jpeg', 2, 0),
(1031, 'Pisang', 'Menu Tambahan', 2000, '1780639831_Banana-fied Recipes!.jpeg', 2, 0),
(1032, 'Jeruk', 'Menu Tambahan', 2000, '1780639860_Buah buahan.jpeg', 2, 0),
(1033, 'Rengginang', 'Menu Tambahan', 1000, '1780639889_download (8).jpeg', 2, 0);

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
  `waktu_dp` time DEFAULT NULL,
  `bukti_pelunasan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`pembayaran_id`, `pesanan_id`, `metode_pembayaran`, `tanggal_pembayaran`, `waktu_pembayaran`, `total_pembayaran`, `status_pembayaran`, `jumlah_dp`, `status_dp`, `tanggal_dp`, `waktu_dp`, `bukti_pelunasan`) VALUES
(81, 84, 'ovo-dp', '2026-06-02', NULL, 20000.00, 'Belum Bayar', 10000.00, 'Menunggu Konfirmasi', NULL, NULL, NULL),
(201, 91, 'Transfer Bank', '2026-05-11', '09:15:00', 100000.00, 'Selesai', 0.00, 'Lunas', '2026-05-11', '09:15:00', 'bukti_mei.png'),
(202, 92, 'OVO', '2026-06-01', '13:00:00', 250000.00, 'Selesai', 100000.00, 'Lunas', '2026-06-01', '10:00:00', 'bukti_juni_1.png'),
(203, 93, 'Tunai', '2026-06-02', '17:30:00', 60000.00, 'Selesai', 0.00, 'Lunas', '2026-06-02', '17:30:00', 'bukti_juni_2.png'),
(301, 101, 'transfer bank', '2026-01-11', '09:00:00', 1000000.00, 'Selesai', 0.00, 'Lunas', '2026-01-11', '09:00:00', 'bukti_101.png'),
(302, 102, 'cod', '2026-02-16', '14:22:00', 250000.00, 'Selesai', 0.00, 'Lunas', '2026-02-16', '14:22:00', 'bukti_102.png'),
(303, 103, 'ovo', '2026-03-05', '12:45:00', 600000.00, 'Selesai', 0.00, 'Lunas', '2026-03-05', '12:45:00', 'bukti_103.png'),
(304, 104, 'transfer bank', '2026-03-21', '08:15:00', 300000.00, 'Selesai', 0.00, 'Lunas', '2026-03-21', '08:15:00', 'bukti_104.png'),
(305, 105, 'transfer bank', '2026-04-12', '10:00:00', 2000000.00, 'Selesai', 0.00, 'Lunas', '2026-04-12', '10:00:00', 'bukti_105.png'),
(306, 106, 'dana', '2026-04-26', '17:30:00', 400000.00, 'Selesai', 0.00, 'Lunas', '2026-04-26', '17:30:00', 'bukti_106.png'),
(307, 107, 'ovo', '2026-05-03', '11:12:00', 1500000.00, 'Selesai', 0.00, 'Lunas', '2026-05-03', '11:12:00', 'bukti_107.png'),
(308, 108, 'transfer bank', '2026-05-19', '15:40:00', 750000.00, 'Selesai', 0.00, 'Lunas', '2026-05-19', '15:40:00', 'bukti_108.png'),
(309, 109, 'gopay', '2026-06-01', '13:00:00', 800000.00, 'Selesai', 0.00, 'Lunas', '2026-06-01', '13:00:00', 'bukti_109.png'),
(310, 110, 'transfer bank', '2026-06-02', '10:05:00', 300000.00, 'Selesai', 0.00, 'Lunas', '2026-06-02', '10:05:00', 'bukti_110.png'),
(311, 111, 'gopay', '2026-06-04', NULL, 20000.00, 'Selesai', 0.00, 'Belum Bayar', NULL, NULL, NULL),
(312, 112, 'bca', '2026-06-07', '14:39:18', 200000.00, 'Selesai', 0.00, 'Lunas', NULL, NULL, NULL),
(313, 113, 'mandiri', '2026-06-07', '14:48:30', 300000.00, 'Selesai', 0.00, 'Lunas', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_sistem`
--

CREATE TABLE `pengaturan_sistem` (
  `id` int(11) NOT NULL,
  `minimal_porsi` int(11) DEFAULT 500,
  `batasan_hari` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan_sistem`
--

INSERT INTO `pengaturan_sistem` (`id`, `minimal_porsi`, `batasan_hari`) VALUES
(1, 2147483647, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_web`
--

CREATE TABLE `pengaturan_web` (
  `id` int(11) NOT NULL,
  `hero_tagline` varchar(100) DEFAULT NULL,
  `hero_judul` varchar(255) DEFAULT NULL,
  `hero_deskripsi` text DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `akun_fb` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan_web`
--

INSERT INTO `pengaturan_web` (`id`, `hero_tagline`, `hero_judul`, `hero_deskripsi`, `no_wa`, `akun_fb`, `alamat`) VALUES
(1, 'cartering kedai aishwa', 'Kelezatan Tradisi, Sentuhan Modern.', 'Menghadirkan hidangan Nusantara bercita rasa otentik dengan penyajian berkelas...', '0895323107636', 'FB: Azwan Coker', 'BLOK DESA, Rt.02/Rw.01 Desa Tanjungsari, Karangampel, Indramayu');

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
(84, 3, '2026-06-02', '2026-06-05', '13:09:00', 'desa kiajaran kulon blok pelabuhan', '085795244257', 20000.00, 'Selesai', 'jangan pakai ayam'),
(91, 2, '2026-05-10', '2026-05-13', '10:00:00', 'Jl. Merdeka No. 12, Indramayu', '081234567890', 100000.00, 'Selesai', 'Sambal dipisah'),
(92, 9, '2026-06-01', '2026-06-04', '11:30:00', 'Perumahan Pesona Blok C, Karangampel', '085711223344', 250000.00, 'Selesai', 'Ayam bakar bumbu rujak'),
(93, 10, '2026-06-02', '2026-06-05', '16:00:00', 'Desa Tanjungsari RT 03/02, Karangampel', '089988776655', 60000.00, 'Selesai', 'Kue bolu jangan terlalu manis'),
(101, 2, '2026-01-10', '2026-01-15', '11:00:00', 'Jl. Merdeka No. 10, Blok A', '081234567890', 1000000.00, 'Selesai', 'Pedas sedang'),
(102, 9, '2026-02-14', '2026-02-18', '10:00:00', 'Perumahan Asri Jaya, Blok C3', '089876543210', 250000.00, 'Selesai', 'Tumpeng hiasan kuning'),
(103, 10, '2026-03-05', '2026-03-08', '12:30:00', 'Jl. Mawar No. 45, Indramayu', '085211223344', 600000.00, 'Selesai', 'Ayam bakar bumbu rujak'),
(104, 11, '2026-03-20', '2026-03-25', '16:00:00', 'Desa Karangampel Rt 05/02', '087766554433', 300000.00, 'Selesai', 'Tanpa sayur urap'),
(105, 2, '2026-04-12', '2026-04-15', '11:30:00', 'Jl. Merdeka No. 10, Blok A', '081234567890', 2000000.00, 'Selesai', 'Sendok plastik lengkap'),
(106, 10, '2026-04-25', '2026-04-28', '09:00:00', 'Jl. Mawar No. 45, Indramayu', '085211223344', 400000.00, 'Selesai', 'Porsi jumbo'),
(107, 9, '2026-05-02', '2026-05-05', '13:00:00', 'Perumahan Asri Jaya, Blok C3', '089876543210', 1500000.00, 'Selesai', 'Sambal dipisah'),
(108, 11, '2026-05-18', '2026-05-22', '10:30:00', 'Desa Karangampel Rt 05/02', '087766554433', 750000.00, 'Selesai', 'Ucapannya ditulis di kertas'),
(109, 2, '2026-06-01', '2026-06-04', '12:00:00', 'Jl. Merdeka No. 10, Blok A', '081234567890', 800000.00, 'Selesai', 'Ayam dada semua'),
(110, 10, '2026-06-02', '2026-06-05', '11:00:00', 'Jl. Mawar No. 45, Indramayu', '085211223344', 300000.00, 'Selesai', 'Jangan terlalu pedas'),
(111, 2, '2026-06-04', '2026-06-13', '10:09:00', 'desa kiajaran kulon blok pelabuhan', '085795244257', 20000.00, 'Selesai', ''),
(112, 3, '2026-06-07', '2026-06-27', '19:40:00', 'Karangampel', '089652833058', 200000.00, 'Dibatalkan', ''),
(113, 3, '2026-06-07', '2026-06-27', '19:40:00', 'Karangampel', '089652833058', 300000.00, 'Menunggu Verifikasi', '');

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
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `waktu_masuk` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimoni`
--

INSERT INTO `testimoni` (`id`, `nama_produk`, `rating`, `deskripsi`, `foto`, `waktu_masuk`) VALUES
(3, 'Paket Ayam Geprek', 5, 'sangat enak', '1780036503_testi.png', '2026-05-29 06:35:03');

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
(10, 'fika', 'fika@gmail.com', '123', 'pelanggan'),
(11, 'arr', 'arr@gmail.com', '123', 'pelanggan'),
(12, 'awa', 'awaa@gmail.com', '12345', 'pelanggan');

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
-- Indexes for table `komplain`
--
ALTER TABLE `komplain`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengaturan_web`
--
ALTER TABLE `pengaturan_web`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `komplain`
--
ALTER TABLE `komplain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1034;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `pembayaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=314;

--
-- AUTO_INCREMENT for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengaturan_web`
--
ALTER TABLE `pengaturan_web`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `pesanan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `tambahan`
--
ALTER TABLE `tambahan`
  MODIFY `tambahan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
