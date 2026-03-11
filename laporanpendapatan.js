document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    
    // Gradien untuk grafik
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(243, 156, 18, 0.4)');
    gradient.addColorStop(1, 'rgba(243, 156, 18, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: [3500000, 4200000, 3800000, 6500000], // Data dummy
                backgroundColor: gradient,
                borderColor: '#f39c12',
                borderWidth: 3,
                fill: true,
                tension: 0.4, // Membuat garis melengkung lembut
                pointRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#f39c12'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});