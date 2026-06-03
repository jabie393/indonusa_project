<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function getBrowsershot(string $html): \Spatie\Browsershot\Browsershot
    {
        $browsershot = \Spatie\Browsershot\Browsershot::html($html);

        $chromePath = config('services.browsershot.chrome_path');
        if (!empty($chromePath)) {
            $browsershot->setChromePath($chromePath);
        }

        if (config('services.browsershot.no_sandbox', true)) {
            $browsershot->noSandbox();
        }

        $env = [];
        $puppeteerCache = config('services.browsershot.puppeteer_cache_dir');
        if (!empty($puppeteerCache)) {
            $env['PUPPETEER_CACHE_DIR'] = $puppeteerCache;
        }

        // Set HOME ke /tmp pada Linux agar Chrome tidak mencoba menulis di /home/www yang tidak writable
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $env['HOME'] = '/tmp';
        }

        if (!empty($env)) {
            $browsershot->setNodeEnv($env);
        }

        $nodeBinary = config('services.browsershot.node_binary');
        if (!empty($nodeBinary)) {
            $browsershot->setNodeBinary($nodeBinary);
        }

        $npmBinary = config('services.browsershot.npm_binary');
        if (!empty($npmBinary)) {
            $browsershot->setNpmBinary($npmBinary);
        }

        // Fallback default untuk Windows jika node/npm path tidak diset di .env
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && empty($nodeBinary)) {
            $defaultNode = 'C:\\Program Files\\nodejs\\node.exe';
            $defaultNpm = 'C:\\Program Files\\nodejs\\npm.cmd';
            if (file_exists($defaultNode)) {
                $browsershot->setNodeBinary($defaultNode);
            }
            if (file_exists($defaultNpm)) {
                $browsershot->setNpmBinary($defaultNpm);
            }
        }

        return $browsershot;
    }
}
