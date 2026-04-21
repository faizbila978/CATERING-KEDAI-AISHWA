<?php
// 1. Simulasi Data dari Halaman Sebelumnya (Bisa diganti dengan $_SESSION nanti)
$order_item = "Nasi Box Ayam";
$qty = 20;
$subtotal = 900000;
$delivery_fee = 0; // 0 berarti FREE
$tax = 45000; // Contoh pajak/biaya tambahan agar total match Rp 945.000
$total = $subtotal + $delivery_fee + $tax;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Formulir - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="formulir.css">
    <style>
<<<<<<< HEAD
        .text-pink { color: #800000; }
        .btn-pink { background-color: #800000; color: white; border: none; }
        .btn-pink:hover { background-color: #600000; color: white; }
=======
        .text-maroon { color: #800000; }
        .btn-maroon { background-color: #800000; color: white; border: none; }
        .btn-maroon:hover { background-color: #600000; color: white; }
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
    </style>
</head>
<body>

    <div class="container py-5">
        <a href="menu.php" class="text-decoration-none text-muted mb-4 d-inline-block">&larr; Back to Selection</a>
        
        <div class="row">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h2 class="fw-bold mb-4">Isi Formulir</h2>
                    
                    <form action="payment.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
<<<<<<< HEAD
                                <label class="form-label small fw-bold text-muted">NAMA LENGKAP</label>
                                <input type="text" name="customer_name" class="form-control bg-light border-0 py-2" placeholder="e.g. Julian Vercetti" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Nomer WHATSAPP</label>
                                <input type="tel" name="whatsapp" class="form-control bg-light border-0 py-2" placeholder="+62 812-3456-7890" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label small fw-bold text-muted">ALAMAT PENGIRIMAN</label>
                                <input type="text" name="address" class="form-control bg-light border-0 py-2" placeholder="Enter street name, building, or unit number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">TANGGAL ACARA</label>
                                <input type="date" name="event_date" class="form-control bg-light border-0 py-2" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">WAKTU ACARA</label>
                                <input type="time" name="event_time" class="form-control bg-light border-0 py-2" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label small fw-bold text-muted">CATATAN TAMBAHAN</label>
=======
                                <label class="form-label small fw-bold text-muted">FULL NAME</label>
                                <input type="text" name="customer_name" class="form-control bg-light border-0 py-2" placeholder="e.g. Julian Vercetti" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">WHATSAPP NUMBER</label>
                                <input type="tel" name="whatsapp" class="form-control bg-light border-0 py-2" placeholder="+62 812-3456-7890" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label small fw-bold text-muted">DELIVERY ADDRESS</label>
                                <input type="text" name="address" class="form-control bg-light border-0 py-2" placeholder="Enter street name, building, or unit number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">EVENT DATE</label>
                                <input type="date" name="event_date" class="form-control bg-light border-0 py-2" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">EVENT TIME</label>
                                <input type="time" name="event_time" class="form-control bg-light border-0 py-2" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label small fw-bold text-muted">ADDITIONAL NOTES</label>
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
                                <textarea name="notes" class="form-control bg-light border-0" rows="4" placeholder="Mention any allergies or specific themes..."></textarea>
                            </div>
                            
                            <div class="col-12 d-lg-none">
                                <button type="submit" class="btn btn-maroon w-100 py-3 rounded-3 fw-bold shadow">Confirm Request</button>
                            </div>
                        </div>
                </div>
            </div>

            <div class="col-lg-5 ps-lg-5 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm p-4 rounded-4 sticky-top" style="top: 20px;">
<<<<<<< HEAD
                    <h5 class="fw-bold mb-4">Ringkasan Seleksi</h5>
=======
                    <h5 class="fw-bold mb-4">Selection Summary</h5>
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <div class="d-flex gap-3">
                            <img src="img/food-1.jpg" width="60" height="60" class="rounded-3 object-fit-cover" alt="item">
                            <div>
                                <h6 class="mb-0 fw-bold"><?php echo $order_item; ?></h6>
                                <small class="text-muted">x <?php echo $qty; ?> Persons</small>
                            </div>
                        </div>
                        <span class="fw-bold text-maroon">Rp <?php echo number_format($subtotal/1000, 0); ?>k</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 mt-4">
                        <span>Subtotal</span>
                        <span class="fw-bold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery Fee</span>
                        <span class="<?php echo ($delivery_fee == 0) ? 'text-success' : ''; ?> fw-bold">
                            <?php echo ($delivery_fee == 0) ? 'FREE' : 'Rp ' . number_format($delivery_fee, 0, ',', '.'); ?>
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold">Total</h4>
                        <h3 class="fw-bold text-maroon">Rp <?php echo number_format($total, 0, ',', '.'); ?></h3>
                    </div>
                    
                    <button type="submit" class="btn btn-maroon w-100 py-3 rounded-3 fw-bold shadow">Confirm Request</button>
                    </form> </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>