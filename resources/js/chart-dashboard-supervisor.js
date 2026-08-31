import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const imcCanvas = document.getElementById('IMC');
    const svcCanvas = document.getElementById('SVC');

    if (!imcCanvas) return;
    const endpoint = imcCanvas.dataset.endpoint || '/admin/dashboard/supervisor/data';

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

    function formatNumber(value) {
        return new Intl.NumberFormat('id-ID').format(Number(value) || 0);
    }

    function formatRupiahFull(value) {
        return 'Rp' + new Intl.NumberFormat('id-ID').format(Number(value) || 0);
    }

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

    // 1. Create Supervisor IMC chart
    const imcLabels = JSON.parse(imcCanvas.dataset.labels || '[]');
    const imcMasuk = JSON.parse(imcCanvas.dataset.masuk || '[]');
    const imcKeluar = JSON.parse(imcCanvas.dataset.keluar || '[]');

    const imcCtx = imcCanvas.getContext('2d');
    window.imcChart = new Chart(imcCtx, {
        type: 'bar',
        data: {
            labels: imcLabels,
            datasets: [
                { label: 'Masuk', data: imcMasuk, backgroundColor: 'rgba(34,90,151,0.8)' },
                { label: 'Keluar', data: imcKeluar, backgroundColor: 'rgba(13,34,58,0.8)' }
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
                            return label + formatRupiahShort(context.parsed.y);
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

    // 2. Create Supervisor SVC chart
    if (svcCanvas) {
        const svcLabels = JSON.parse(svcCanvas.dataset.labels || '[]');
        const svcData = JSON.parse(svcCanvas.dataset.values || '[]');
        const svcCtx = svcCanvas.getContext('2d');
        window.svcChart = new Chart(svcCtx, {
            type: 'bar',
            data: { labels: svcLabels, datasets: [{ label: 'Stock', data: svcData, backgroundColor: 'rgba(34,90,151,0.8)' }] },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // 3. Create Sales Performance Chart (#salesIMC)
    const salesImcCanvas = document.getElementById('salesIMC');
    if (salesImcCanvas) {
        const salesImcLabels = JSON.parse(salesImcCanvas.dataset.labels || '[]');
        const salesImcMasuk = JSON.parse(salesImcCanvas.dataset.masuk || '[]');
        const salesImcKeluar = JSON.parse(salesImcCanvas.dataset.keluar || '[]');

        const salesImcCtx = salesImcCanvas.getContext('2d');
        window.salesImcChart = new Chart(salesImcCtx, {
            type: 'bar',
            data: {
                labels: salesImcLabels,
                datasets: [
                    { label: 'Quotation Goal', data: salesImcMasuk, backgroundColor: 'rgba(34,90,151,0.8)' },
                    { label: 'Sales Order Finish', data: salesImcKeluar, backgroundColor: 'rgba(13,34,58,0.8)' }
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
                                    label += formatRupiahFull(context.parsed.y);
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
    }

    // 4. Create Sales Order Tracking Donut Chart (#salesOrderTrackingChart)
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
                                return `${ctx.label}: ${formatNumber(val)} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 5. Create Target Quarter & Monthly Target Charts
    const tqCanvas = document.getElementById('salesTargetQuarterChart');
    const mtCanvas = document.getElementById('salesMonthlyTargetChart');

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

        if (window.salesMonthlyTargetChart) {
            window.salesMonthlyTargetChart.data.labels = months;
            window.salesMonthlyTargetChart.data.datasets[0].data = values;
            window.salesMonthlyTargetChart.update();
        }

        const titleEl = document.getElementById('sales-monthly-target-title');
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
        window.salesTargetQuarterChart = new Chart(tqCtx, {
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

                        window.salesTargetQuarterChart.data.datasets[0].backgroundColor = getBgColors(selectedQuarterIndex);
                        window.salesTargetQuarterChart.update();

                        updateMonthlyChart(selectedQuarterIndex);
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `Total: ${formatRupiahFull(ctx.parsed.y)}`
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

        const mtCtx = mtCanvas.getContext('2d');
        const initialQData = monthlyTargets['Q1'] || {};
        const initialMLabels = Object.keys(initialQData);
        const initialMData = Object.values(initialQData);

        window.salesMonthlyTargetChart = new Chart(mtCtx, {
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
                            label: (ctx) => `Total: ${formatRupiahFull(ctx.parsed.y)}`
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

    // Gather filters and build query string
    function buildQuery() {
        const params = new URLSearchParams();

        // 1. Form inputs (Date filter)
        const form = document.getElementById('filters-form');
        if (form) {
            const formData = new FormData(form);
            for (const [k, v] of formData.entries()) {
                if (v !== null && v !== '') params.set(k, v);
            }
        }

        // 2. Sales Filter Dropdown
        const salesSelect = document.getElementById('supervisor-sales-select');
        if (salesSelect && salesSelect.value) {
            params.set('sales_id', salesSelect.value);
        }

        // 3. Sales Year Select
        const salesYearSelect = document.getElementById('sales-imc-year-select') || document.getElementById('imc-year-select');
        if (salesYearSelect && salesYearSelect.value) {
            params.set('year', salesYearSelect.value);
        }

        // 4. Sales SO Status Select
        const salesStatusSelect = document.getElementById('sales-so-status-select');
        if (salesStatusSelect && salesStatusSelect.value) {
            params.set('status', salesStatusSelect.value);
        }

        return params.toString();
    }

    async function fetchAndUpdate() {
        const qs = buildQuery();
        const url = endpoint + (qs ? ('?' + qs) : '');
        try {
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Network error');
            const json = await res.json();

            // Update Supervisor Charts
            if (window.imcChart && json.imc_labels) {
                window.imcChart.data.labels = json.imc_labels;
                window.imcChart.data.datasets[0].data = json.imc_masuk;
                window.imcChart.data.datasets[1].data = json.imc_keluar;
                window.imcChart.update();
            }
            if (window.svcChart && json.svc_labels) {
                window.svcChart.data.labels = json.svc_labels;
                window.svcChart.data.datasets[0].data = json.svc_data;
                window.svcChart.update();
            }

            // Update Supervisor Stat Cards DOM Elements
            if (json.totalPending !== undefined) {
                const el = document.getElementById('sup-total-pending');
                if (el) el.textContent = formatNumber(json.totalPending);
            }
            if (json.totalApproved !== undefined) {
                const el = document.getElementById('sup-total-approved');
                if (el) el.textContent = formatNumber(json.totalApproved);
            }
            if (json.lastMonthApproved !== undefined) {
                const el = document.getElementById('sup-last-month-approved');
                if (el) el.textContent = formatNumber(json.lastMonthApproved);
            }
            if (json.totalRevenue !== undefined) {
                const el = document.getElementById('sup-total-revenue');
                if (el) el.textContent = formatNumber(json.totalRevenue);
            }
            if (json.lastMonthRevenue !== undefined) {
                const el = document.getElementById('sup-last-month-revenue');
                if (el) el.textContent = formatRupiahFull(json.lastMonthRevenue);
            }
            if (json.salesPerformance !== undefined) {
                const el = document.getElementById('sup-sales-perf');
                if (el) el.textContent = formatNumber(json.salesPerformance);
            }
            if (json.lastMonthPerf !== undefined) {
                const el = document.getElementById('sup-last-month-perf');
                if (el) el.textContent = formatNumber(json.lastMonthPerf);
            }

            // Update Sales Stat Cards DOM Elements
            if (json.totalQuotation !== undefined) {
                const el = document.getElementById('sales-total-quotation');
                if (el) el.textContent = formatNumber(json.totalQuotation);
            }
            if (json.totalFailedQuotation !== undefined) {
                const el = document.getElementById('sales-failed-quotation');
                if (el) el.textContent = formatNumber(json.totalFailedQuotation);
            }
            if (json.totalGoalQuotation !== undefined) {
                const el = document.getElementById('sales-goal-quotation');
                if (el) el.textContent = formatNumber(json.totalGoalQuotation);
            }

            if (json.totalValueQuotation !== undefined) {
                const el = document.getElementById('sales-total-value-quotation');
                if (el) el.textContent = formatRupiahFull(json.totalValueQuotation);
            }
            if (json.totalFailedValueQuotation !== undefined) {
                const el = document.getElementById('sales-failed-value-quotation');
                if (el) el.textContent = formatRupiahFull(json.totalFailedValueQuotation);
            }
            if (json.totalGoalValueQuotation !== undefined) {
                const el = document.getElementById('sales-goal-value-quotation');
                if (el) el.textContent = formatRupiahFull(json.totalGoalValueQuotation);
            }

            if (json.totalSalesOrder !== undefined) {
                const el = document.getElementById('sales-total-so');
                if (el) el.textContent = formatNumber(json.totalSalesOrder);
            }
            if (json.totalProcess !== undefined) {
                const el = document.getElementById('sales-process-so');
                if (el) el.textContent = formatNumber(json.totalProcess);
            }
            if (json.totalFinish !== undefined) {
                const el = document.getElementById('sales-finish-so');
                if (el) el.textContent = formatNumber(json.totalFinish);
            }

            if (json.totalValueSalesOrder !== undefined) {
                const el = document.getElementById('sales-total-value-so');
                if (el) el.textContent = formatRupiahFull(json.totalValueSalesOrder);
            }
            if (json.totalProcessValueSalesOrder !== undefined) {
                const el = document.getElementById('sales-process-value-so');
                if (el) el.textContent = formatRupiahFull(json.totalProcessValueSalesOrder);
            }
            if (json.totalFinishValueSalesOrder !== undefined) {
                const el = document.getElementById('sales-finish-value-so');
                if (el) el.textContent = formatRupiahFull(json.totalFinishValueSalesOrder);
            }

            // Update Sales Performance Chart (#salesIMC)
            if (window.salesImcChart && json.sales_imc_labels) {
                window.salesImcChart.data.labels = json.sales_imc_labels;
                window.salesImcChart.data.datasets[0].data = json.sales_imc_masuk;
                window.salesImcChart.data.datasets[1].data = json.sales_imc_keluar;
                window.salesImcChart.update();
            }

            // Update Sales Order Tracking Donut Chart
            if (window.salesOrderTrackingChart && json.totalFinish !== undefined && json.totalProcess !== undefined) {
                window.salesOrderTrackingChart.data.datasets[0].data = [json.totalFinish, json.totalProcess];
                window.salesOrderTrackingChart.update();

                const totalOrders = json.totalSalesOrder || (json.totalFinish + json.totalProcess);
                const totalValue = json.totalValueSalesOrder || 0;
                const finishPct = totalOrders > 0 ? Math.round((json.totalFinish / totalOrders) * 100) : 0;
                const processPct = totalOrders > 0 ? Math.round((json.totalProcess / totalOrders) * 100) : 0;

                const centerCountEl = document.getElementById('sales-tracking-center-count');
                const centerValueEl = document.getElementById('sales-tracking-center-value');
                const finishInfoEl = document.getElementById('sales-tracking-finish-info');
                const processInfoEl = document.getElementById('sales-tracking-process-info');

                if (centerCountEl) centerCountEl.textContent = `${formatNumber(totalOrders)} Orders`;
                if (centerValueEl) centerValueEl.textContent = formatRupiahFull(totalValue);
                if (finishInfoEl) finishInfoEl.textContent = `${formatNumber(json.totalFinish)} (${finishPct}%)`;
                if (processInfoEl) processInfoEl.textContent = `${formatNumber(json.totalProcess)} (${processPct}%)`;
            }

            // Update Target Quarter & Monthly Target Charts
            if (json.sales_quarter_targets && window.salesTargetQuarterChart) {
                quarterTargets = json.sales_quarter_targets;
                window.salesTargetQuarterChart.data.datasets[0].data = qKeys.map(q => Number(quarterTargets[q]) || 0);
                window.salesTargetQuarterChart.update();
            }
            if (json.sales_monthly_targets) {
                monthlyTargets = json.sales_monthly_targets;
                updateMonthlyChart(selectedQuarterIndex);
            }

            // Update Sales Year Select options if returned
            if (json.sales_imc_years && Array.isArray(json.sales_imc_years)) {
                const sel = document.getElementById('sales-imc-year-select');
                if (sel) {
                    const current = sel.value;
                    sel.innerHTML = '';
                    json.sales_imc_years.forEach(y => {
                        const opt = document.createElement('option');
                        opt.value = y;
                        opt.text = y;
                        opt.className = 'text-black font-normal';
                        if (String(y) === String(json.selectedSalesYear)) opt.selected = true;
                        sel.appendChild(opt);
                    });
                    if (current && Array.from(sel.options).some(o => o.value === current)) sel.value = current;
                }
            }
        } catch (e) {
            console.error('Failed to fetch chart data', e);
        }
    }

    // Filter Form Inputs Listener
    const form = document.getElementById('filters-form');
    if (form) {
        const dateStart = form.querySelector('input[name="date_start"]');
        const dateEnd = form.querySelector('input[name="date_end"]');
        [dateStart, dateEnd].forEach(el => {
            if (!el) return;
            el.addEventListener('change', () => {
                fetchAndUpdate();
                const qs = buildQuery();
                const newUrl = window.location.pathname + (qs ? ('?' + qs) : '');
                history.replaceState(null, '', newUrl);
            });
        });
    }

    // Supervisor Sales Select Listener
    const salesSelect = document.getElementById('supervisor-sales-select');
    if (salesSelect) {
        salesSelect.addEventListener('change', () => {
            const hiddenSalesId = document.getElementById('filters-sales-id');
            if (hiddenSalesId) hiddenSalesId.value = salesSelect.value;
            fetchAndUpdate();
            const qs = buildQuery();
            const newUrl = window.location.pathname + (qs ? ('?' + qs) : '');
            history.replaceState(null, '', newUrl);
        });
    }

    // Sales Year Select Listener
    const salesYearSelect = document.getElementById('sales-imc-year-select');
    if (salesYearSelect) {
        salesYearSelect.addEventListener('change', () => {
            fetchAndUpdate();
            const qs = buildQuery();
            const newUrl = window.location.pathname + (qs ? ('?' + qs) : '');
            history.replaceState(null, '', newUrl);
        });
    }

    // Sales SO Status Select Listener
    const salesStatusSelect = document.getElementById('sales-so-status-select');
    if (salesStatusSelect) {
        salesStatusSelect.addEventListener('change', () => {
            fetchAndUpdate();
            const qs = buildQuery();
            const newUrl = window.location.pathname + (qs ? ('?' + qs) : '');
            history.replaceState(null, '', newUrl);
        });
    }
});
