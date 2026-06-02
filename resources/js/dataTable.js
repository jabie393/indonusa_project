const commonConfig = {
    fixedHeader: true,
    searching: false,
    paging: false,
    info: false,
    scrollX: true,
    colReorder: true,
};

let datatable;
if (document.querySelector('#DataTable')) {
    datatable = new DataTable('#DataTable', commonConfig);
}

let dashTableCat;
if (document.querySelector('#DataTableCat')) {
    dashTableCat = new DataTable('#DataTableCat', {
        ...commonConfig,
        select: false,
        autoWidth: false,
        columnDefs: [{ width: '150px', targets: 2 }]
    });
}

let datatableExcel;
if (document.querySelector('#DataTableExcel')) {
    datatableExcel = new DataTable('#DataTableExcel', {
        ...commonConfig,
        paging: true,
        searching: true,
        info: true,
        pageLength: 10,
        pagingType: 'simple_numbers',
        select: false,
        columnDefs: [
            {
                targets: '_all',
                searchable: false
            }
        ],
        layout: {
            topStart: null,
            topEnd: 'search',
            bottomStart: ['info', 'pageLength'],
            bottomEnd: 'paging'
        },
        drawCallback: function(settings) {
            const api = this.api();
            const container = api.table().container();
            if (container) {
                const pagingEl = container.querySelector('.dt-paging');
                if (pagingEl) {
                    if (api.page.info().pages <= 1) {
                        pagingEl.style.setProperty('display', 'none', 'important');
                    } else {
                        pagingEl.style.removeProperty('display');
                    }
                }
            }
        },
        language: {
            info: "Menampilkan <span class='font-semibold text-gray-900 dark:text-white'>_START_-_END_</span> dari <span class='font-semibold text-gray-900 dark:text-white'>_TOTAL_</span>",
            lengthMenu: "_MENU_ per halaman",
            search: "",
            searchPlaceholder: "Search",
            paginate: {
                previous: '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>',
                next: '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>'
            }
        }
    });
}

// Custom search function to match text within inputs and select elements inside cells
if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.ext && $.fn.dataTable.ext.search) {
    if (!window.hasRegisteredExcelSearch) {
        window.hasRegisteredExcelSearch = true;
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            try {
                if (!settings || !settings.nTable || settings.nTable.id !== 'DataTableExcel') {
                    return true;
                }
                
                const rowNode = settings.aoData && settings.aoData[dataIndex] ? settings.aoData[dataIndex].nTr : null;
                if (!rowNode) {
                    if (dataIndex === 0) {
                        console.warn("[DataTableExcel Search] rowNode is null for index 0");
                    }
                    return true;
                }
                
                const searchObj = settings.oPreviousSearch || {};
                const searchTerm = (window.dataTableSearchTerm || searchObj.sSearch || searchObj.search || "").trim().toLowerCase();
                if (!searchTerm) {
                    return true;
                }
                
                // Check all inputs, select, and textarea elements in the row
                const inputs = rowNode.querySelectorAll('input, select, textarea');
                
                // Detailed log for debugging
                if (dataIndex === 0) {
                    console.log(`[DataTableExcel Search] Term: "${searchTerm}"`);
                    console.log(`[DataTableExcel Search] Row 0 outerHTML:`, rowNode.outerHTML);
                    console.log(`[DataTableExcel Search] Row 0 inputs count: ${inputs.length}`);
                    console.log(`[DataTableExcel Search] Row 0 inputs values:`, Array.from(inputs).map(inp => `${inp.tagName}:${inp.type}:${inp.value}`));
                }
                
                for (let i = 0; i < inputs.length; i++) {
                    const val = (inputs[i].value || "").toString().toLowerCase();
                    if (val.includes(searchTerm)) {
                        return true;
                    }
                }
                
                // Also fallback to the default textual values (if any columns don't have inputs)
                for (let i = 0; i < data.length; i++) {
                    if (data[i] && data[i].toString().toLowerCase().includes(searchTerm)) {
                        return true;
                    }
                }
                
                return false;
            } catch (e) {
                console.error("Error in DataTables custom search filter:", e);
                return true; // Keep row on error to avoid blank screen
            }
        });
    }
}
