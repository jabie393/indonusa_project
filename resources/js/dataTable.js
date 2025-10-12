
let table = new DataTable('#warehouseTable', {
    responsive: true,
    searching: true,
    layout: {
        topStart: {
            search: {
                search: 'Fred',
                className: 'my-search'
            }
            
            
        }
    }
});