const commonConfig = {
    fixedHeader: true,
    searching: false,
    paging: false,
    info: false,
    scrollX: true,
    colReorder: true,
};

let datatable = new DataTable('#DataTable', commonConfig);

let dashTableCat = new DataTable('#DataTableCat', {
    ...commonConfig,
    select: false,
    autoWidth: false,
    columnDefs: [{ width: '150px', targets: 2 }]

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
