document.addEventListener('DOMContentLoaded', () => {
    // Select all tables with the 'sortable' class
    const tables = document.querySelectorAll('table.sortable');

    tables.forEach(table => {
        const headers = table.querySelectorAll('thead th');
        
        headers.forEach((header, index) => {
            // Skip columns marked with 'no-sort' or select columns (like the checkbox column)
            if (header.classList.contains('no-sort') || header.classList.contains('selectCol') || !header.textContent.trim()) {
                return;
            }

            // Set cursor pointer and add visual cue
            header.style.cursor = 'pointer';
            header.classList.add('select-none');

            // Add sorting icons container if not present
            let iconContainer = header.querySelector('.sort-icon');
            if (!iconContainer) {
                iconContainer = document.createElement('span');
                iconContainer.className = 'sort-icon inline-block ml-2 transition-transform duration-200 text-gray-400 opacity-60';
                iconContainer.innerHTML = '↕'; // default indicator
                header.appendChild(iconContainer);
            }

            header.addEventListener('click', () => {
                const currentOrder = header.getAttribute('data-sort-order') || 'none';
                let newOrder = 'asc';
                
                if (currentOrder === 'asc') {
                    newOrder = 'desc';
                } else if (currentOrder === 'desc') {
                    newOrder = 'asc';
                }

                // Reset all other headers in this table
                headers.forEach(h => {
                    if (h !== header) {
                        h.removeAttribute('data-sort-order');
                        const icon = h.querySelector('.sort-icon');
                        if (icon) {
                            icon.innerHTML = '↕';
                            icon.className = 'sort-icon inline-block ml-2 transition-transform duration-200 text-gray-400 opacity-60';
                        }
                    }
                });

                // Update current header state
                header.setAttribute('data-sort-order', newOrder);
                const icon = header.querySelector('.sort-icon');
                if (icon) {
                    if (newOrder === 'asc') {
                        icon.innerHTML = '▲';
                        icon.className = 'sort-icon inline-block ml-2 transition-transform duration-200 text-blue-600 dark:text-blue-400 font-bold opacity-100';
                    } else {
                        icon.innerHTML = '▼';
                        icon.className = 'sort-icon inline-block ml-2 transition-transform duration-200 text-blue-600 dark:text-blue-400 font-bold opacity-100';
                    }
                }

                // Sort the table rows
                sortTable(table, index, newOrder);
            });
        });
    });

    function sortTable(table, columnIndex, order) {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (rows.length === 0) return;

        const isAscending = order === 'asc';

        // Custom parser to handle currency, numbers, dates, and plain text
        function getCellValue(row, idx) {
            const cell = row.children[idx];
            if (!cell) return '';
            
            const text = cell.textContent.trim();
            
            // 1. Check if it's a number/currency (e.g. Rp 1.000.000 or -Rp 500)
            const cleanNum = text.replace(/Rp\.?\s?/g, '').replace(/\./g, '').replace(/,/g, '.').replace(/\s/g, '').trim();
            if (cleanNum && !isNaN(cleanNum) && !text.includes('-') && !text.includes('/') && !text.includes(':')) {
                return parseFloat(cleanNum);
            }

            // 2. Check if it's a date (e.g. YYYY-MM-DD or DD-MM-YYYY or DD/MM/YYYY)
            const dateMatch = text.match(/^(\d{1,4})[-/](\d{1,2})[-/](\d{1,4})$/);
            if (dateMatch) {
                const parsedDate = Date.parse(text);
                if (!isNaN(parsedDate)) {
                    return parsedDate;
                }
            }

            // 3. Fallback to lowercase text
            return text.toLowerCase();
        }

        // Sort rows
        rows.sort((rowA, rowB) => {
            const valA = getCellValue(rowA, columnIndex);
            const valB = getCellValue(rowB, columnIndex);

            if (valA === valB) return 0;
            if (valA === '') return 1; // Empty values go to bottom
            if (valB === '') return -1;

            if (typeof valA === 'number' && typeof valB === 'number') {
                return isAscending ? valA - valB : valB - valA;
            }

            // String comparison
            return isAscending 
                ? String(valA).localeCompare(String(valB)) 
                : String(valB).localeCompare(String(valA));
        });

        // Re-append rows in sorted order using Fragment for performance
        const fragment = document.createDocumentFragment();
        rows.forEach(row => fragment.appendChild(row));
        tbody.appendChild(fragment);
    }
});
