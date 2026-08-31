import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const imcCanvas = document.getElementById('IMC');
    const svcCanvas = document.getElementById('SVC');

    if (!imcCanvas) return;
    const endpoint = imcCanvas.dataset.endpoint || '/admin/dashboard/warehouse/data';

    // helper parse initial data from blade
    const imcLabels = JSON.parse(imcCanvas.dataset.labels || '[]');
    const imcMasuk = JSON.parse(imcCanvas.dataset.masuk || '[]');
    const imcKeluar = JSON.parse(imcCanvas.dataset.keluar || '[]');

    function formatRupiahShort(value) {
        const number = Number(value) || 0;
        const abs = Math.abs(number);
        const formatter = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 1 });

        if (abs >= 1000000000) {
            return `Rp ${formatter.format(number / 1000000000)} M`;
        }

        if (abs >= 1000000) {
            return `Rp ${formatter.format(number / 1000000)} jt`;
        }

        if (abs >= 1000) {
            return `Rp ${formatter.format(number / 1000)} rb`;
        }

        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(number);
    }

    // create IMC chart
    const imcCtx = imcCanvas.getContext('2d');
    window.imcChart = new Chart(imcCtx, {
        type: 'bar',
        data: {
            labels: imcLabels,
            datasets: [
                { label: 'Potensi Pendapatan', data: imcMasuk, backgroundColor: 'rgba(34,90,151,0.8)' },
                { label: 'Pendapatan Selesai', data: imcKeluar, backgroundColor: 'rgba(13,34,58,0.8)' }
            ]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return formatRupiahShort(value);
                        }
                    }
                }
            }
        }
    });

    // SVC initial
    let svcLabels = [];
    let svcData = [];
    if (svcCanvas) {
        svcLabels = JSON.parse(svcCanvas.dataset.labels || '[]');
        svcData = JSON.parse(svcCanvas.dataset.values || '[]');
        const svcCtx = svcCanvas.getContext('2d');
        window.svcChart = new Chart(svcCtx, {
            type: 'bar',
            data: { labels: svcLabels, datasets: [{ label: 'Total Qty', data: svcData, backgroundColor: 'rgba(34,90,151,0.8)' }] },
            options: {
                indexAxis: 'y', // <-- makes the bar chart horizontal
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Purchasing Trend Chart (Bar + Line Overlay)
    const purchasingTrendCanvas = document.getElementById('purchasingTrendChart');
    if (purchasingTrendCanvas) {
        const labels = JSON.parse(purchasingTrendCanvas.dataset.labels || '["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"]');
        const values = JSON.parse(purchasingTrendCanvas.dataset.values || '[]');
        const trendValues = [...values];

        const purchasingCtx = purchasingTrendCanvas.getContext('2d');
        window.purchasingTrendChart = new Chart(purchasingCtx, {
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Tren',
                        data: trendValues,
                        borderColor: '#67c23a',
                        backgroundColor: '#67c23a',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        order: 1
                    },
                    {
                        type: 'bar',
                        label: 'Total Belanja',
                        data: values,
                        backgroundColor: 'rgba(34, 90, 151, 0.8)',
                        hoverBackgroundColor: '#0D223A',
                        borderRadius: 4,
                        order: 2
                    }
                ]
            },
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
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += formatRupiahShort(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatRupiahShort(value);
                            }
                        }
                    }
                }
            }
        });
    }

    // Purchasing Category Donut Chart
    const purchasingCategoryCanvas = document.getElementById('purchasingCategoryChart');
    if (purchasingCategoryCanvas) {
        const catLabels = JSON.parse(purchasingCategoryCanvas.dataset.labels || '["Belum Ada Data"]');
        const catValues = JSON.parse(purchasingCategoryCanvas.dataset.values || '[1]');
        const catColors = JSON.parse(purchasingCategoryCanvas.dataset.colors || '["#e5e7eb"]');
        let hasData = purchasingCategoryCanvas.dataset.hasData === 'true';

        const catCtx = purchasingCategoryCanvas.getContext('2d');
        window.purchasingCategoryChart = new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: catColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (!hasData) return ' Belum Ada Data';
                                let label = context.label || '';
                                if (label) label += ': ';
                                const total = (window.purchasingCategoryChart.data.datasets[0].data || []).reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return `${label} ${formatRupiahShort(context.parsed)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Vendor Database Donut Chart
    const vendorDatabaseCanvas = document.getElementById('vendorDatabaseChart');
    if (vendorDatabaseCanvas) {
        const vLabels = JSON.parse(vendorDatabaseCanvas.dataset.labels || '["PKP (850 Vendors)", "Non PKP (650 Vendors)"]');
        const vValues = JSON.parse(vendorDatabaseCanvas.dataset.values || '[850, 650]');

        const vCtx = vendorDatabaseCanvas.getContext('2d');
        window.vendorDatabaseChart = new Chart(vCtx, {
            type: 'doughnut',
            data: {
                labels: vLabels,
                datasets: [{
                    data: vValues,
                    backgroundColor: [
                        '#225A97', // PKP - Blue
                        '#f97316'  // Non PKP - Orange
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) label += ': ';
                                const total = vValues.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return `${label} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Average Timeline SO - GR Chart
    const averageTimelineCanvas = document.getElementById('averageTimelineChart');
    if (averageTimelineCanvas) {
        const atLabels = JSON.parse(averageTimelineCanvas.dataset.labels || '["Sales Order ke Purchasing", "Purchase Order ke Vendor", "Barang Tiba dari Vendor"]');
        const atValues = JSON.parse(averageTimelineCanvas.dataset.values || '[0, 3, 10]');

        const atCtx = averageTimelineCanvas.getContext('2d');
        window.averageTimelineChart = new Chart(atCtx, {
            type: 'line',
            data: {
                labels: atLabels,
                datasets: [{
                    label: 'Timeline',
                    data: atValues,
                    borderColor: '#5b9bd5',
                    backgroundColor: 'rgba(91, 155, 213, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#5b9bd5',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
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
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y + ' hari';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 10,
                        ticks: {
                            callback: function(value) {
                                return value + ' hari';
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    }

    // gather filters and build query string
    function buildQuery() {
        const form = document.getElementById('filters-form');
        if (!form) return '';
        const formData = new FormData(form);
        const params = new URLSearchParams();
        for (const [k,v] of formData.entries()) {
            if (v !== null && v !== '') params.set(k, v);
        }
        // include selected year
        const yearSelect = document.getElementById('imc-year-select');
        if (yearSelect && yearSelect.value) params.set('year', yearSelect.value);
        return params.toString();
    }

    async function fetchAndUpdate() {
        const qs = buildQuery();
        const url = endpoint + (qs ? ('?' + qs) : '');
        try {
            const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
            if (!res.ok) throw new Error('Network error');
            const json = await res.json();

            // update IMC
            if (window.imcChart) {
                window.imcChart.data.labels = json.imc_labels;
                window.imcChart.data.datasets[0].data = json.imc_masuk;
                window.imcChart.data.datasets[1].data = json.imc_keluar;
                window.imcChart.update();
            }
            // update SVC
            if (window.svcChart) {
                window.svcChart.data.labels = json.svc_labels;
                window.svcChart.data.datasets[0].data = json.svc_data;
                window.svcChart.update();
            }

            // update purchasing trend chart
            if (window.purchasingTrendChart && json.purchasing_spending) {
                if (json.purchasing_months) {
                    window.purchasingTrendChart.data.labels = json.purchasing_months;
                }
                window.purchasingTrendChart.data.datasets[0].data = json.purchasing_spending;
                window.purchasingTrendChart.data.datasets[1].data = json.purchasing_spending;
                window.purchasingTrendChart.update();
            }

            // update average timeline chart
            if (window.averageTimelineChart && json.timeline_values) {
                window.averageTimelineChart.data.datasets[0].data = json.timeline_values;
                window.averageTimelineChart.update();
            }

            // update purchasing category donut chart
            if (window.purchasingCategoryChart && json.purchasing_categories) {
                const pcData = json.purchasing_categories;
                hasData = pcData.has_data;
                window.purchasingCategoryChart.data.labels = pcData.labels;
                window.purchasingCategoryChart.data.datasets[0].data = pcData.values;
                window.purchasingCategoryChart.data.datasets[0].backgroundColor = pcData.colors;
                window.purchasingCategoryChart.update();

                // update legend cards
                const legendContainer = document.getElementById('purchasing-category-legends');
                if (legendContainer) {
                    if (pcData.categories && pcData.categories.length > 0) {
                        legendContainer.innerHTML = pcData.categories.map(cat => `
                            <div class="flex items-center gap-1.5 rounded-xl border border-gray-100 bg-gray-50/80 p-2 dark:border-gray-700 dark:bg-gray-800">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: ${cat.color}"></span>
                                <div class="flex flex-col truncate">
                                    <span class="font-medium text-gray-600 dark:text-gray-400 truncate">${cat.name}</span>
                                    <span class="font-bold text-gray-900 dark:text-gray-100">${cat.percentage}%</span>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        legendContainer.innerHTML = `
                            <div class="col-span-2 md:col-span-3 text-center py-4 text-xs text-gray-400">
                                Belum ada data kedatangan barang yang disetujui.
                            </div>
                        `;
                    }
                }
            }

            // update year select options if server returns different available years
            if (json.imc_years && Array.isArray(json.imc_years)) {
                const sel = document.getElementById('imc-year-select');
                if (sel) {
                    const current = sel.value;
                    sel.innerHTML = '';
                    json.imc_years.forEach(y => {
                        const opt = document.createElement('option');
                        opt.value = y;
                        opt.text = y;
                        if (String(y) === String(json.selectedYear)) opt.selected = true;
                        sel.appendChild(opt);
                    });
                    // keep current selection if still present
                    if (current && Array.from(sel.options).some(o => o.value === current)) sel.value = current;
                }
            }

            if (json.purchasing_years && Array.isArray(json.purchasing_years)) {
                const pSel = document.getElementById('purchasing-trend-year-select');
                if (pSel) {
                    const currentP = pSel.value;
                    pSel.innerHTML = '';
                    json.purchasing_years.forEach(y => {
                        const opt = document.createElement('option');
                        opt.value = y;
                        opt.text = y;
                        opt.className = 'text-black font-normal';
                        if (String(y) === String(json.selectedYear)) opt.selected = true;
                        pSel.appendChild(opt);
                    });
                    if (currentP && Array.from(pSel.options).some(o => o.value === currentP)) pSel.value = currentP;
                }
            }
        } catch (e) {
            console.error('Failed to fetch chart data', e);
        }
    }

    // DO NOT prevent full form submit (ke butuh update low-stock / tabel di server)
    // instead: listen to changes on filter inputs and update charts via AJAX,
    // but keep submit button to perform a full page refresh when user explicitly submits.
    const form = document.getElementById('filters-form');
    if (form) {
        const threshold = form.querySelector('select[name="threshold"]');
        const dateStart = form.querySelector('input[name="date_start"]');
        const dateEnd = form.querySelector('input[name="date_end"]');
        [threshold, dateStart, dateEnd].forEach(el => {
            if (!el) return;
            el.addEventListener('change', () => {
                fetchAndUpdate();
                // update URL so filters are bookmarkable without reloading page
                const qs = buildQuery();
                const newUrl = window.location.pathname + (qs ? ('?' + qs) : '');
                history.replaceState(null, '', newUrl);
            });
        });
        // keep default submit behavior (full page refresh) so low-stock/tables update on server
    }

    // year select change for IMC
    const yearSelect = document.getElementById('imc-year-select');
    if (yearSelect) {
        yearSelect.addEventListener('change', () => {
            fetchAndUpdate();
            const qs = buildQuery();
            const newUrl = window.location.pathname + (qs ? ('?' + qs) : '');
            history.replaceState(null, '', newUrl);
        });
    }

    // year select change for Purchasing Trend
    const purchasingYearSelect = document.getElementById('purchasing-trend-year-select');
    if (purchasingYearSelect) {
        purchasingYearSelect.addEventListener('change', async () => {
            const year = purchasingYearSelect.value;
            const url = endpoint + '?year=' + encodeURIComponent(year);
            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Network error');
                const json = await res.json();
                if (window.purchasingTrendChart && json.purchasing_spending) {
                    if (json.purchasing_months) {
                        window.purchasingTrendChart.data.labels = json.purchasing_months;
                    }
                    window.purchasingTrendChart.data.datasets[0].data = json.purchasing_spending;
                    window.purchasingTrendChart.data.datasets[1].data = json.purchasing_spending;
                    window.purchasingTrendChart.update();
                }
            } catch (e) {
                console.error('Failed to update purchasing trend chart', e);
            }
        });
    }
});
