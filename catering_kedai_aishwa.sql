-- ======================
-- 1. TABEL USER
-- ======================
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  nama_lengkap VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','pelanggan') DEFAULT 'pelanggan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO users (nama_lengkap, email, password, role)
VALUES ('Admin', 'admin@gmail.com', 'admin123', 'admin');

-- ======================
-- 2. TABEL MENU
-- ======================
CREATE TABLE menu (
  menu_id INT AUTO_INCREMENT PRIMARY KEY,
  nama_menu VARCHAR(100) NOT NULL,
  deskripsi TEXT,
  harga_satuan DECIMAL(15,2) NOT NULL,
  gambar VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================
-- 3. TABEL TAMBAHAN
-- ======================
CREATE TABLE tambahan (
  tambahan_id INT AUTO_INCREMENT PRIMARY KEY,
  nama_tambahan VARCHAR(100) NOT NULL,
  harga_satuan DECIMAL(15,2) NOT NULL,
  gambar VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================
-- 4. TABEL PESANAN
-- ======================
CREATE TABLE pesanan (
  pesanan_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  tanggal_pesan DATE,
  tanggal_acara DATE,
  waktu_acara TIME,
  alamat TEXT,
  no_handphone VARCHAR(15),
  total_pesan DECIMAL(15,2),
  status_pesanan VARCHAR(50),

  FOREIGN KEY (user_id) REFERENCES users(user_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================
-- 5. TABEL DETAIL PESANAN
-- ======================
CREATE TABLE detail_pesanan (
  detail_id INT AUTO_INCREMENT PRIMARY KEY,
  pesanan_id INT,
  menu_id INT,
  harga_satuan DECIMAL(15,2),
  jumlah_menu INT,
  tambahan_id INT,
  harga_satuan_tambahan DECIMAL(15,2),
  jumlah_tambahan INT,
  tanggal_acara DATE,
  waktu_acara TIME,
  total_detail_pesanan DECIMAL(15,2),
  status_detail_pesanan VARCHAR(50),

  FOREIGN KEY (pesanan_id) REFERENCES pesanan(pesanan_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  FOREIGN KEY (tambahan_id) REFERENCES tambahan(tambahan_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================
-- 6. TABEL PEMBAYARAN
-- ======================
CREATE TABLE pembayaran (
  pembayaran_id INT AUTO_INCREMENT PRIMARY KEY,
  pesanan_id INT,
  metode_pembayaran VARCHAR(50),
  tanggal_pembayaran DATE,
  waktu_pembayaran TIME,
  total_pembayaran DECIMAL(15,2),
  status_pembayaran VARCHAR(50),

  FOREIGN KEY (pesanan_id) REFERENCES pesanan(pesanan_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;