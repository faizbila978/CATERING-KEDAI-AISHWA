<!-- Bagian Script di bawah file payment.php -->
<script>
    const paymentOptions = document.querySelectorAll('.payment-option');

    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            paymentOptions.forEach(el => el.classList.remove('selected'));
            this.classList.add('selected');
            document.getElementById('selectedMethod').value = this.getAttribute('data-method');
        });
    });

    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-button').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        event.currentTarget.classList.add('active');
        paymentOptions.forEach(el => el.classList.remove('selected'));
        document.getElementById('selectedMethod').value = "";
    }

    function prosesePembayaran() {
        const method = document.getElementById('selectedMethod').value;
        const pesananId = "<?php echo $pesanan_id; ?>";

        if (!method) {
            alert('Pilih metode pembayaran terlebih dahulu!');
            return;
        }

        const isDP = method.includes('-dp');
        const pembayaranType = isDP ? 'dp' : 'full';

        // Gunakan Fetch API untuk menyimpan metode ke database secara otomatis
        fetch('simpan_metode.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `pesanan_id=${pesananId}&method=${method}&type=${pembayaranType}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Setelah tersimpan, baru pindah ke halaman status
                window.location.href = 'status.php?pesanan_id=' + pesananId;
            } else {
                alert('Gagal memproses metode pembayaran.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem.');
        });
    }
</script>