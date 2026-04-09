CREATE TABLE PEMBAYARAN (
    pembayaran_id INT AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT,
    metode_pembayaran VARCHAR(50),
    tanggal_pembayaran DATE,
    waktu_pembayaran TIME,
    total_pembayaran DECIMAL(15,2),
    status_pembayaran VARCHAR(50),
    FOREIGN KEY (pesanan_id) REFERENCES PESANAN(pesanan_id)
);