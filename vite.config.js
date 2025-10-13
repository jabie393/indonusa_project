import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'node_modules/flowbite/dist/flowbite.min.js',
                'resources/js/add-stock.js',
                'resources/js/sweetalert.js',
                'resources/js/warehouse.js',
                'resources/js/goods-in-status.js',
                'resources/js/supply-orders.js',
                'resources/js/delivery-orders.js',
                'resources/js/goods-in.js',
                'resources/js/checker.js',
                'resources/js/order-modal.js',
                'resources/js/requestorder-modal.js',
                'resources/js/dataTable.js',
            ],
            refresh: true,
        }),
    ],
});
