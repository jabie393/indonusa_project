import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const imc = document.getElementById('IMC');

    if (imc) {
        const labels = JSON.parse(imc.dataset.labels || '[]');
        const masuk = JSON.parse(imc.dataset.masuk || '[]');
        const keluar = JSON.parse(imc.dataset.keluar || '[]');

        new Chart(imc, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Masuk',
                        data: masuk,
                        backgroundColor: '#225A97',
                        borderColor: '#225A97',
                        borderWidth: 1,
                        borderRadius: 6
                    },
                    {
                        label: 'Keluar',
                        data: keluar,
                        backgroundColor: '#E53E3E',
                        borderColor: '#E53E3E',
                        borderWidth: 1,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    const svc = document.getElementById('SVC');

    if (svc) {
        const labels = JSON.parse(svc.dataset.labels || '[]');
        const values = JSON.parse(svc.dataset.values || '[]');

        new Chart(svc, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Stock',
                    data: values,
                    borderWidth: 1,
                    backgroundColor: '#225A97',
                    borderColor: '#225A97',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
