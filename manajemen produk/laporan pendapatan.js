document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi Grafik (Chart.js)
    const ctx = document.getElementById('incomeChart').getContext('2d');
    
    // Data dummy untuk grafik
    const dataPendapatan = {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: [1200000, 1900000, 1500000, 2500000, 2200000, 3100000, 2800000],
            borderColor: '#ea580c',
            backgroundColor: 'rgba(234, 88, 12, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    };

    const config = {
        type: 'line',
        data: dataPendapatan,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    };

    new Chart(ctx, config);

    // 2. Logika Tombol Keluar
    const btnLogout = document.querySelector('.btn-logout');
    btnLogout.addEventListener('click', function() {
        if(confirm('Apakah Anda yakin ingin keluar?')) {
            window.location.href = 'login.html'; // Contoh redirect
        }
    });

    // 3. Logika Filter Bulan (Placeholder)
    const filterMonth = document.querySelector('.filter-month');
    filterMonth.addEventListener('change', function(e) {
        console.log('Memfilter data untuk bulan:', e.target.value);
        // Di sini Anda bisa memanggil fungsi untuk update tabel/grafik via API
    });
});