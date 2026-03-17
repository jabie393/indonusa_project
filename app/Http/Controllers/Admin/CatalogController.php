<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalog;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
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
            'catalog_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:102400',
            'catalog_file_path' => 'nullable|string', // Path for pre-uploaded file
        ]);

        $catalog = new Catalog();
        $catalog->brand_name = $request->brand_name;
        $catalog->catalog_name = $request->catalog_name;

        $path = null;
        if ($request->filled('catalog_file_path')) {
            $path = $request->input('catalog_file_path');
            $catalog->catalog_file = $path;
        } elseif ($request->hasFile('catalog_file')) {
            $file = $request->file('catalog_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Store using storage/app/public disk
            $path = $file->storeAs('catalogs', $filename, 'public');
            $catalog->catalog_file = $path;
        }

        if ($path) {
            // Handle client-side generated thumbnail if provided
            if ($request->filled('catalog_cover_base64')) {
                $base64Image = $request->input('catalog_cover_base64');
                $catalog->catalog_cover = $this->saveBase64Cover($base64Image, $path);
            } elseif (Str::endsWith($path, '.pdf')) {
                // Fallback to server-side generation
                $catalog->catalog_cover = $this->generateThumbnail($path);
            } else {
                $catalog->catalog_cover = $path;
            }
        }

        $catalog->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Catalog created successfully',
                'catalog' => $catalog
            ]);
        }
        
        return redirect()->route('catalog.index')->with('title', 'Berhasil!')->with('text', 'Catalog created successfully.');
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'catalog_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:102400',
            ]);

            if ($request->hasFile('catalog_file')) {
                $file = $request->file('catalog_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Store using storage/app/public disk
                $path = $file->storeAs('catalogs', $filename, 'public');
                
                return response()->json([
                    'success' => true,
                    'path' => $path,
                    'filename' => $filename
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
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
            'catalog_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:102400',
            'catalog_file_path' => 'nullable|string',
        ]);

        $catalog = Catalog::findOrFail($id);
        $catalog->brand_name = $request->brand_name;
        $catalog->catalog_name = $request->catalog_name;

        $path = null;
        if ($request->filled('catalog_file_path')) {
            // Delete old files from public disk
            if ($catalog->catalog_file) {
                Storage::disk('public')->delete($catalog->catalog_file);
            }
            if ($catalog->catalog_cover && $catalog->catalog_cover !== $catalog->catalog_file) {
                Storage::disk('public')->delete($catalog->catalog_cover);
            }

            $path = $request->input('catalog_file_path');
            $catalog->catalog_file = $path;
        } elseif ($request->hasFile('catalog_file')) {
            // Delete old files from public disk
            if ($catalog->catalog_file) {
                Storage::disk('public')->delete($catalog->catalog_file);
            }
            if ($catalog->catalog_cover && $catalog->catalog_cover !== $catalog->catalog_file) {
                Storage::disk('public')->delete($catalog->catalog_cover);
            }

            $file = $request->file('catalog_file');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store using storage/app/public disk
            $path = $file->storeAs('catalogs', $filename, 'public');
            $catalog->catalog_file = $path;
        }

        if ($path) {
            // Handle client-side generated thumbnail if provided
            if ($request->filled('catalog_cover_base64')) {
                $base64Image = $request->input('catalog_cover_base64');
                $catalog->catalog_cover = $this->saveBase64Cover($base64Image, $path);
            } elseif (Str::endsWith($path, '.pdf')) {
                // Fallback to server-side generation
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
        if ($catalog->catalog_file) {
            Storage::disk('public')->delete($catalog->catalog_file);
        }
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
            if (!Storage::disk('public')->exists($filePath)) return null;

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
            
        } catch (\Throwable $e) {
            Log::error('PDF Thumbnail Error for ' . $filePath . ': ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Save base64 image as cover
     */
    private function saveBase64Cover($base64Data, $filePath)
    {
        try {
            if (empty($base64Data)) return null;

            // Remove header if present (e.g. data:image/png;base64,)
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]); // png, jpg, etc
            } else {
                $type = 'png';
            }

            $base64Data = base64_decode($base64Data);
            if ($base64Data === false) return null;

            $thumbnailName = 'covers/' . pathinfo($filePath, PATHINFO_FILENAME) . '.' . $type;
            
            if (!Storage::disk('public')->exists('covers')) {
                Storage::disk('public')->makeDirectory('covers');
            }

            Storage::disk('public')->put($thumbnailName, $base64Data);

            return $thumbnailName;
        } catch (\Throwable $e) {
            Log::error('Saving base64 cover failed: ' . $e->getMessage());
            return null;
        }
    }
}
