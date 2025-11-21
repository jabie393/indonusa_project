import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('IMC');

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Mari', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'December'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1,
                    backgroundColor: '#225A97',
                    borderRadius: 100,

                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('SVC');
    const NUMBER_CFG = { count: DATA_COUNT, min: -100, max: 100 };

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Mari', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'December'],
                datasets: [{
                    label: '# of Votes',
                    data: NUMBER_CFG,
                    borderWidth: 1,
                    backgroundColor: '#225A97',
                    borderRadius: 100,

                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
