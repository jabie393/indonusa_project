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
                { label: 'Quotation Goal', data: imcMasuk, backgroundColor: 'rgba(34,90,151,0.8)' },
                { label: 'Sales Order Finish', data: imcKeluar, backgroundColor: 'rgba(13,34,58,0.8)' }
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

    // Custom Plugin to render formatted currency values directly above bars
    const barValuePlugin = {
        id: 'barValuePlugin',
        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            chart.data.datasets.forEach((dataset, i) => {
                const meta = chart.getDatasetMeta(i);
                meta.data.forEach((bar, index) => {
                    const val = dataset.data[index];
                    if (val !== undefined && val !== null && val > 0) {
                        ctx.save();
                        ctx.fillStyle = document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151';
                        ctx.font = 'bold 10px sans-serif';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        ctx.fillText(formatRupiahShort(val), bar.x, bar.y - 3);
                        ctx.restore();
                    }
                });
            });
        }
    };

    // SVC initial
    let svcLabels = [];
    let svcData = [];
    if (svcCanvas) {
        svcLabels = JSON.parse(svcCanvas.dataset.labels || '[]');
        svcData = JSON.parse(svcCanvas.dataset.values || '[]');
        const svcCtx = svcCanvas.getContext('2d');
        window.svcChart = new Chart(svcCtx, {
            type: 'bar',
            data: { labels: svcLabels, datasets: [{ label: 'Sales Quantity', data: svcData, backgroundColor: 'rgba(34,90,151,0.8)' }] },
            options: {
                indexAxis: 'y', // <-- makes the bar chart horizontal
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Target Quarter & Monthly Target Charts
    const tqCanvas = document.getElementById('targetQuarterChart');
    const mtCanvas = document.getElementById('monthlyTargetChart');

    let quarterTargets = {};
    let monthlyTargets = {};
    let selectedQuarterIndex = 0;

    const qKeys = ['Q1', 'Q2', 'Q3', 'Q4'];
    const qLabels = ['Jan - Mar', 'Apr - Jun', 'Jul - Sep', 'Okt - Des'];

    function updateMonthlyChart(qIndex) {
        const qKey = qKeys[qIndex] || 'Q1';
        const qLabel = qLabels[qIndex] || 'Jan - Mar';
        const qData = monthlyTargets[qKey] || {};
        const months = Object.keys(qData);
        const values = Object.values(qData);

        if (window.monthlyTargetChart) {
            window.monthlyTargetChart.data.labels = months;
            window.monthlyTargetChart.data.datasets[0].data = values;
            window.monthlyTargetChart.update();
        }

        const titleEl = document.getElementById('monthly-target-title');
        if (titleEl) {
            titleEl.textContent = `Sales Order Bulanan (${qLabel})`;
        }
    }

    if (tqCanvas && mtCanvas) {
        try {
            quarterTargets = JSON.parse(tqCanvas.dataset.targets || '{}');
        } catch (e) {}

        try {
            monthlyTargets = JSON.parse(mtCanvas.dataset.monthly || '{}');
        } catch (e) {}

        const qData = qKeys.map(q => Number(quarterTargets[q]) || 0);

        const getBgColors = (activeIdx) => {
            return qLabels.map((_, idx) => idx === activeIdx ? 'rgba(34, 90, 151, 0.95)' : 'rgba(34, 90, 151, 0.35)');
        };

        const tqCtx = tqCanvas.getContext('2d');
        window.targetQuarterChart = new Chart(tqCtx, {
            type: 'bar',
            plugins: [barValuePlugin],
            data: {
                labels: qLabels,
                datasets: [{
                    label: 'Sales Order Quarter',
                    data: qData,
                    backgroundColor: getBgColors(selectedQuarterIndex),
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                onClick: (e, elements) => {
                    if (elements && elements.length > 0) {
                        selectedQuarterIndex = elements[0].index;

                        // Highlight clicked bar
                        window.targetQuarterChart.data.datasets[0].backgroundColor = getBgColors(selectedQuarterIndex);
                        window.targetQuarterChart.update();

                        // Update monthly target chart
                        updateMonthlyChart(selectedQuarterIndex);
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `Total: ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(ctx.parsed.y)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (v) => formatRupiahShort(v)
                        }
                    }
                }
            }
        });

        // Initialize Monthly Target Chart (Defaults to Q1 / Jan - Mar)
        const mtCtx = mtCanvas.getContext('2d');
        const initialQData = monthlyTargets['Q1'] || {};
        const initialMLabels = Object.keys(initialQData);
        const initialMData = Object.values(initialQData);

        window.monthlyTargetChart = new Chart(mtCtx, {
            type: 'bar',
            plugins: [barValuePlugin],
            data: {
                labels: initialMLabels,
                datasets: [{
                    label: 'Sales Order Bulanan',
                    data: initialMData,
                    backgroundColor: 'rgba(13, 34, 58, 0.9)',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `Total: ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(ctx.parsed.y)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (v) => formatRupiahShort(v)
                        }
                    }
                }
            }
        });
    }

    // Sales Order Tracking Donut Chart Initial
    const trackingCanvas = document.getElementById('salesOrderTrackingChart');
    if (trackingCanvas) {
        const finishCount = Number(trackingCanvas.dataset.finish || 0);
        const processCount = Number(trackingCanvas.dataset.process || 0);

        const trackingCtx = trackingCanvas.getContext('2d');
        window.salesOrderTrackingChart = new Chart(trackingCtx, {
            type: 'doughnut',
            data: {
                labels: ['Finish', 'Proses'],
                datasets: [{
                    data: [finishCount, processCount],
                    backgroundColor: ['#225A97', '#10B981'],
                    borderWidth: 2,
                    borderColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const val = ctx.parsed;
                                const total = finishCount + processCount;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return `${ctx.label}: ${new Intl.NumberFormat('id-ID').format(val)} (${pct}%)`;
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
        const params = new URLSearchParams();
        if (form) {
            const formData = new FormData(form);
            for (const [k,v] of formData.entries()) {
                if (v !== null && v !== '') params.set(k, v);
            }
        }
        // include selected year
        const yearSelect = document.getElementById('imc-year-select');
        if (yearSelect && yearSelect.value) params.set('year', yearSelect.value);

        // include selected status for SO Quarter & Monthly charts
        const statusSelect = document.getElementById('so-status-select');
        if (statusSelect && statusSelect.value) params.set('status', statusSelect.value);

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
                if (window.imcChart.data.datasets[1]) {
                    window.imcChart.data.datasets[1].data = json.imc_keluar;
                }
                window.imcChart.update();
            }
            // update SVC
            if (window.svcChart) {
                window.svcChart.data.labels = json.svc_labels;
                window.svcChart.data.datasets[0].data = json.svc_data;
                window.svcChart.update();
            }

            // update Target Quarter & Monthly Target
            if (json.quarter_targets && window.targetQuarterChart) {
                quarterTargets = json.quarter_targets;
                window.targetQuarterChart.data.datasets[0].data = qKeys.map(q => Number(quarterTargets[q]) || 0);
                window.targetQuarterChart.update();
            }
            if (json.monthly_targets) {
                monthlyTargets = json.monthly_targets;
                updateMonthlyChart(selectedQuarterIndex);
            }

            // update Sales Order Tracking Donut Chart
            if (window.salesOrderTrackingChart && json.totalFinish !== undefined && json.totalProcess !== undefined) {
                window.salesOrderTrackingChart.data.datasets[0].data = [json.totalFinish, json.totalProcess];
                window.salesOrderTrackingChart.update();

                const totalOrders = json.totalSalesOrder || (json.totalFinish + json.totalProcess);
                const totalValue = json.totalValueSalesOrder || 0;
                const finishPct = totalOrders > 0 ? Math.round((json.totalFinish / totalOrders) * 100) : 0;
                const processPct = totalOrders > 0 ? Math.round((json.totalProcess / totalOrders) * 100) : 0;

                const centerCountEl = document.getElementById('tracking-center-count');
                const centerValueEl = document.getElementById('tracking-center-value');
                const finishInfoEl = document.getElementById('tracking-finish-info');
                const processInfoEl = document.getElementById('tracking-process-info');

                if (centerCountEl) centerCountEl.textContent = `${new Intl.NumberFormat('id-ID').format(totalOrders)} Orders`;
                if (centerValueEl) centerValueEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalValue)}`;
                if (finishInfoEl) finishInfoEl.textContent = `${new Intl.NumberFormat('id-ID').format(json.totalFinish)} (${finishPct}%)`;
                if (processInfoEl) processInfoEl.textContent = `${new Intl.NumberFormat('id-ID').format(json.totalProcess)} (${processPct}%)`;
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

    // year select change
    const yearSelect = document.getElementById('imc-year-select');
    if (yearSelect) {
        yearSelect.addEventListener('change', () => {
            fetchAndUpdate();
            const qs = buildQuery();
            const newUrl = window.location.pathname + (qs ? ('?' + qs) : '');
            history.replaceState(null, '', newUrl);
        });
    }

    // status select change for SO Quarter & Monthly charts
    const statusSelect = document.getElementById('so-status-select');
    if (statusSelect) {
        statusSelect.addEventListener('change', () => {
            fetchAndUpdate();
            const qs = buildQuery();
            const newUrl = window.location.pathname + (qs ? ('?' + qs) : '');
            history.replaceState(null, '', newUrl);
        });
    }
});
