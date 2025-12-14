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

    // create IMC chart
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
        options: { responsive: true, maintainAspectRatio: false }
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
            data: { labels: svcLabels, datasets: [{ label: 'Stock', data: svcData, backgroundColor: 'rgba(34,90,151,0.8)' }] },
            options: {
                indexAxis: 'y', // <-- makes the bar chart horizontal
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // gather filters and build query string
    function buildQuery() {
        const form = document.getElementById('filters-form');
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
});
