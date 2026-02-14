const commonConfig = {
    fixedHeader: true,
    searching: false,
    paging: false,
    info: false,
    scrollX: true,
    colReorder: true,

    // Select row
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    columnDefs: [
        {
            targets: 0,
            orderable: false,
            render: DataTable.render.select()
        }
    ]
};

let table = new DataTable('#warehouseTable', commonConfig);
let datatable = new DataTable('#DataTable', commonConfig);
let datatable2 = new DataTable('#DataTable2', commonConfig);
let datatable3 = new DataTable('#DataTable3', commonConfig);
let dashTable = new DataTable('#dashTable', {
    ...commonConfig,
    select: false,
    columnDefs:false,
});
let dashTable2 = new DataTable('#dashTable2', {
    ...commonConfig,
    select: false,
    columnDefs:false,
});
let dashTable3 = new DataTable('#dashTable3', {
    ...commonConfig,
    select: false,
    columnDefs:false,
});

let datatableExcel = new DataTable('#DataTableExcel', {
    ...commonConfig,
    fixedColumns: {
        left: 0,
        right: 1
    },
    select: false,
    columnDefs:false,
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
