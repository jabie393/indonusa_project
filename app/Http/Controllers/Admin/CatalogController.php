<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalog;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $catalogs = Catalog::query()
            ->when($search, function ($query, $search) {
                $query->where('brand_name', 'like', "%{$search}%")
                    ->orWhere('catalog_name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);

        $brands = Catalog::distinct()->pluck('brand_name');

        return view('admin.catalog.index', compact('catalogs', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'catalog_name' => 'required|string|max:255',
            'catalog_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $catalog = new Catalog();
        $catalog->brand_name = $request->brand_name;
        $catalog->catalog_name = $request->catalog_name;

        if ($request->hasFile('catalog_file')) {
            $path = $request->file('catalog_file')->store('catalogs', 'public');
            $catalog->catalog_file = $path;
            
            if (Str::endsWith($path, '.pdf')) {
                $catalog->catalog_cover = $this->generateThumbnail($path);
            } else {
                $catalog->catalog_cover = $path; // If it's an image, use it as cover
            }
        }

        $catalog->save();
        return redirect()->route('catalog.index')->with('title', 'Berhasil!')->with('text', 'Catalog created successfully.');
    }

    public function edit($id)
    {
        $catalog = Catalog::findOrFail($id);
        return view('admin.catalog.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'catalog_name' => 'required|string|max:255',
            'catalog_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $catalog = Catalog::findOrFail($id);
        $catalog->brand_name = $request->brand_name;
        $catalog->catalog_name = $request->catalog_name;

        if ($request->hasFile('catalog_file')) {
            // Delete old files
            if ($catalog->catalog_file) Storage::disk('public')->delete($catalog->catalog_file);
            if ($catalog->catalog_cover && $catalog->catalog_cover !== $catalog->catalog_file) {
                Storage::disk('public')->delete($catalog->catalog_cover);
            }

            $path = $request->file('catalog_file')->store('catalogs', 'public');
            $catalog->catalog_file = $path;

            if (Str::endsWith($path, '.pdf')) {
                $catalog->catalog_cover = $this->generateThumbnail($path);
            } else {
                $catalog->catalog_cover = $path;
            }
        }

        $catalog->save();
        return redirect()->route('catalog.index')->with('title', 'Berhasil!')->with('text', 'Catalog updated successfully.');
    }

    public function destroy($id)
    {
        $catalog = Catalog::findOrFail($id);
        if ($catalog->catalog_file) Storage::disk('public')->delete($catalog->catalog_file);
        if ($catalog->catalog_cover && $catalog->catalog_cover !== $catalog->catalog_file) {
            Storage::disk('public')->delete($catalog->catalog_cover);
        }
        $catalog->delete();
        return redirect()->route('catalog.index')->with('title', 'Berhasil!')->with('text', 'Catalog deleted successfully.');
    }

    /**
     * Generate a thumbnail from a PDF file using Browsershot.
     */
    private function generateThumbnail($filePath)
    {
        try {
            $pdfPath = Storage::disk('public')->path($filePath);
            if (!file_exists($pdfPath)) return null;

            $thumbnailName = 'covers/' . pathinfo($filePath, PATHINFO_FILENAME) . '.png';
            $thumbnailPath = Storage::disk('public')->path($thumbnailName);

            if (!Storage::disk('public')->exists('covers')) {
                Storage::disk('public')->makeDirectory('covers');
            }

            $pdfBase64 = base64_encode(file_get_contents($pdfPath));
            
            // Hybrid approach: Use pdf.js inside Browsershot to render the PDF data
            $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body { margin: 0; padding: 0; overflow: hidden; background: white; }
        #pdf-canvas { width: 100%; height: auto; display: block; }
    </style>
</head>
<body>
    <canvas id="pdf-canvas"></canvas>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
        const pdfData = atob('$pdfBase64');
        const loadingTask = pdfjsLib.getDocument({data: pdfData});
        loadingTask.promise.then(pdf => {
            return pdf.getPage(1);
        }).then(page => {
            const viewport = page.getViewport({scale: 2});
            const canvas = document.getElementById('pdf-canvas');
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            return page.render({canvasContext: context, viewport: viewport}).promise;
        }).then(() => {
            document.body.id = 'ready';
        }).catch(err => {
            console.error(err);
            document.body.id = 'error';
        });
    </script>
</body>
</html>
HTML;

            $browsershot = Browsershot::html($html)
                ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
                ->waitForSelector('#ready', ['timeout' => 15000])
                ->windowSize(800, 1100);
            
            // Set Node and NPM paths for Windows compatibility
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $browsershot->setNodeBinary('C:\Program Files\nodejs\node.exe')
                           ->setNpmBinary('C:\Program Files\nodejs\npm.cmd');
            }

            $browsershot->save($thumbnailPath);

            if (file_exists($thumbnailPath)) {
                return $thumbnailName;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('PDF Thumbnail Error for ' . $filePath . ': ' . $e->getMessage());
            return null;
        }
    }
}
