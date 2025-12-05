let table = new DataTable('#warehouseTable', {
    searching: false,
    paging: false,
    info: false,
    fixedHeader: true,
    colReorder: true,
});

let datatable = new DataTable('#DataTable', {
    fixedHeader: true,
    searching: false,
    paging: false,
    info: false,
    colReorder: true,

});
let datatable2 = new DataTable('#DataTable2', {
    fixedHeader: true,
    searching: false,
    paging: false,
    info: false,
    colReorder: true,
    
});
let datatable3 = new DataTable('#DataTable3', {
    fixedHeader: true,
    searching: false,
    paging: false,
    info: false,
    colReorder: true,

});

let datatableExcel = new DataTable('#DataTableExcel', {
    fixedHeader: true,
    scrollX: true,
    searching: false,
    paging: false,
    info: true,
    colReorder: true,
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
