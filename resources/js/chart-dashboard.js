import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const imc = document.getElementById('IMC');

    if (imc) {
        new Chart(imc, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Mari', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'December'],
                datasets: [{
                    label: '# of Profit',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1,
                    backgroundColor: (context) => {
                        const value = context.dataset.data[context.dataIndex];
                        return value < 0 ? '#000000' : '#225A97';
                    },
                    borderColor: (context) => {
                        const value = context.dataset.data[context.dataIndex];
                        return value < 0 ? '#000000' : '#225A97';
                    },
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

    const svc = document.getElementById('SVC');

    if (svc) {
        new Chart(svc, {
            type: 'bar',
            data: {
                labels: ['Barang', 'Barang', 'Barang', 'Barang', 'Barang', 'Barang', 'Barang', 'Barang'],
                datasets: [{
                    label: '# of Profit',
                    data: [12, 19, 53, 58, 27, 32,],
                    borderWidth: 1,
                    backgroundColor: (context) => {
                        const value = context.dataset.data[context.dataIndex];
                        return value < 0 ? '#000000' : '#225A97';
                    },
                    borderColor: (context) => {
                        const value = context.dataset.data[context.dataIndex];
                        return value < 0 ? '#000000' : '#225A97';
                    },
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
