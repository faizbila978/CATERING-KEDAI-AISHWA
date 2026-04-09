-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table catering_kedai_aishwa.detail_pesanan
CREATE TABLE IF NOT EXISTS `detail_pesanan` (
  `detail_id` int NOT NULL AUTO_INCREMENT,
  `pesanan_id` int DEFAULT NULL,
  `menu_id` int DEFAULT NULL,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `jumlah_menu` int DEFAULT NULL,
  `tambahan_id` int DEFAULT NULL,
  `harga_satuan_tambahan` decimal(15,2) DEFAULT NULL,
  `jumlah_tambahan` int DEFAULT NULL,
  `tanggal_acara` date DEFAULT NULL,
  `waktu_acara` time DEFAULT NULL,
  `total_detail_pesanan` decimal(15,2) DEFAULT NULL,
  `status_detail_pesanan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `pesanan_id` (`pesanan_id`),
  KEY `menu_id` (`menu_id`),
  KEY `tambahan_id` (`tambahan_id`),
  CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`pesanan_id`),
  CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`),
  CONSTRAINT `detail_pesanan_ibfk_3` FOREIGN KEY (`tambahan_id`) REFERENCES `tambahan` (`tambahan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table catering_kedai_aishwa.detail_pesanan: ~0 rows (approximately)

-- Dumping structure for table catering_kedai_aishwa.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `menu_id` int NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(100) DEFAULT NULL,
  `deskripsi` text,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table catering_kedai_aishwa.menu: ~0 rows (approximately)

-- Dumping structure for table catering_kedai_aishwa.pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `pembayaran_id` int NOT NULL AUTO_INCREMENT,
  `pesanan_id` int DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `waktu_pembayaran` time DEFAULT NULL,
  `total_pembayaran` decimal(15,2) DEFAULT NULL,
  `status_pembayaran` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pembayaran_id`),
  KEY `pesanan_id` (`pesanan_id`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`pesanan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table catering_kedai_aishwa.pembayaran: ~0 rows (approximately)

-- Dumping structure for table catering_kedai_aishwa.pesanan
CREATE TABLE IF NOT EXISTS `pesanan` (
  `pesanan_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `tanggal_pesan` date DEFAULT NULL,
  `tanggal_acara` date DEFAULT NULL,
  `waktu_acara` time DEFAULT NULL,
  `alamat` text,
  `no_handphone` varchar(15) DEFAULT NULL,
  `total_pesan` decimal(15,2) DEFAULT NULL,
  `status_pesanan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pesanan_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table catering_kedai_aishwa.pesanan: ~0 rows (approximately)

-- Dumping structure for table catering_kedai_aishwa.tambahan
CREATE TABLE IF NOT EXISTS `tambahan` (
  `tambahan_id` int NOT NULL AUTO_INCREMENT,
  `nama_tambahan` varchar(100) DEFAULT NULL,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tambahan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table catering_kedai_aishwa.tambahan: ~0 rows (approximately)

-- Dumping structure for table catering_kedai_aishwa.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','pelanggan') DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table catering_kedai_aishwa.user: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
