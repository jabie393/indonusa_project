let table = new DataTable('#warehouseTable', {
    responsive: true,
    searching: false,
    paging: false,
    info: false,
});

let datatable = new DataTable('#DataTable', {
    responsive: true,
    searching: false,
    paging: false,
    info: false,
});
let datatable2 = new DataTable('#DataTable2', {
    responsive: true,
    searching: false,
    paging: false,
    info: false,
});
let datatable3 = new DataTable('#DataTable3', {
    responsive: true,
    searching: false,
    paging: false,
    info: false,
});

// Wait for DataTable to finish rendering
table.on('draw', function () {
    const headers = document.querySelectorAll('#warehouseTable thead th div span');
    headers.forEach(span => {
        if (span.textContent.trim() === 'Aksi') {
            span.classList.add('text-center'); // Add your desired class
            // th.classList.remove('old-class'); // Remove unwanted class if needed
        }
    });
});

// Or, if you want to do it once after initialization:
setTimeout(() => {
    const headers = document.querySelectorAll('#warehouseTable thead th div span');
    headers.forEach(span => {
        if (span.textContent.trim() === 'Aksi') {
            span.classList.add('text-center');
        }
    });
}, 100);
