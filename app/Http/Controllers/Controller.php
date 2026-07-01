<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function getBrowsershot(string $html): \Spatie\Browsershot\Browsershot
    {
        $browsershot = \Spatie\Browsershot\Browsershot::html($html);

        $chromePath = $this->resolveChromePath();
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

    protected function resolveChromePath(): ?string
    {
        $configuredPath = config('services.browsershot.chrome_path');
        if (!empty($configuredPath) && file_exists($configuredPath)) {
            return $configuredPath;
        }

        $candidates = [];

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $candidates = [
                'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
                'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
                'C:\\Program Files\\Chromium\\Application\\chrome.exe',
                'C:\\Program Files (x86)\\Chromium\\Application\\chrome.exe',
            ];
        } else {
            $candidates = [
                '/usr/bin/google-chrome',
                '/usr/bin/google-chrome-stable',
                '/usr/bin/chromium',
                '/usr/bin/chromium-browser',
            ];
        }

        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && (PHP_OS_FAMILY === 'Windows' || is_executable($candidate))) {
                return $candidate;
            }
        }

        return null;
    }
}
