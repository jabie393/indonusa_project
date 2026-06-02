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
    paging: true,
    searching: true,
    info: true,
    pageLength: 10,
    fixedColumns: {
        left: 0,
        right: 1
    },
    select: false,
    columnDefs: false,
    layout: {
        topStart: null,
        topEnd: 'search',
        bottomStart: ['info', 'length'],
        bottomEnd: 'paging'
    },
    language: {
        info: "Showing _START_-_END_ of _TOTAL_",
        lengthMenu: "_MENU_ per page",
        search: "",
        searchPlaceholder: "Search"
    }
});
