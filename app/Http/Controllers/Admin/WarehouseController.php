<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\GoodsReceipt;
use App\Models\GoodsHistory;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        // Jika ada status di URL, simpan ke session dan redirect ke URL bersih
        if ($request->has('status')) {
            session(['warehouse_filter_status' => $request->input('status')]);
            return redirect()->route('warehouse.index', $request->except('status'));
        }

        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');
        $category = $request->input('category');
        $stockStatus = $request->input('stock_status');

        $goods = Goods::where('goods_status', 'approved');

        if ($query) {
            $goods = $goods->where(function ($q) use ($query) {
                $q->where('goods_name', 'like', "%{$query}%")
                    ->orWhere('goods_code', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if (!empty($category) && $category !== 'all') {
            $goods->where('category', $category);
        }

        if (!empty($stockStatus) && $stockStatus !== 'all') {
            if ($stockStatus === 'low') {
                $goods->where('stock', '>', 0)->where('stock', '<=', 20);
            } elseif ($stockStatus === 'ready') {
                $goods->where('stock', '>', 20);
            } elseif ($stockStatus === 'out') {
                $goods->where('stock', '<=', 0);
            }
        }

        $goods = $goods->paginate($perPage)->appends($request->except('page'));
        $kategoriList = Goods::KATEGORI;

        $barang = $goods->first();

        return view('admin.warehouse.index', compact('goods', 'kategoriList', 'barang'));
    }

    /**
     * Export WMS Goods inventory data to Excel.
     */
    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $stockStatus = $request->input('stock_status');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AllGoodsExport($search, $category, $stockStatus),
            'Inventory_Report_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    /**
     * Export WMS Goods inventory data to PDF.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $stockStatus = $request->input('stock_status');

        $query = Goods::where('goods_status', 'approved');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('goods_name', 'like', "%{$search}%")
                  ->orWhere('goods_code', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($stockStatus && $stockStatus !== 'all') {
            if ($stockStatus === 'low') {
                $query->where('stock', '>', 0)->where('stock', '<=', 20);
            } elseif ($stockStatus === 'ready') {
                $query->where('stock', '>', 20);
            } elseif ($stockStatus === 'out') {
                $query->where('stock', '<=', 0);
            }
        }

        $results = $query->orderBy('goods_name', 'asc')->get();

        $filters = [];
        if ($category && $category !== 'all') {
            $filters[] = 'Kategori: ' . $category;
        }
        if ($stockStatus && $stockStatus !== 'all') {
            $filters[] = 'Stok: ' . ucfirst($stockStatus);
        }
        $filterDescription = count($filters) > 0 ? implode(', ', $filters) : 'Semua Barang';

        $data = [
            'results' => $results,
            'filter_description' => $filterDescription,
            'company_name' => \App\Models\SystemSetting::get('company_name', 'PT. INDONUSA JAYA BERSAMA'),
            'company_address' => \App\Models\SystemSetting::get('company_address', 'Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296'),
            'company_phone' => \App\Models\SystemSetting::get('company_phone', '08121634173'),
            'company_email' => \App\Models\SystemSetting::get('company_email', 'info@indonusa.com'),
            'leader_name' => \App\Models\SystemSetting::get('leader_name', 'Alimul Imam S.AP'),
            'leader_position' => \App\Models\SystemSetting::get('leader_position', 'Direktur'),
            'print_date' => now()->format('d M Y H:i:s'),
        ];

        $html = view('admin.pdf.inventory-report-pdf', $data)->render();

        $pdf = $this->getBrowsershot($html)
            ->landscape()
            ->format('A4')
            ->margins(12.7, 12.7, 12.7, 12.7)
            ->showBackground()
            ->writeOptionsToFile()
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Laporan-Inventory-' . now()->format('YmdHis') . '.pdf"');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'goods_code' => 'required|string|max:255|unique:goods,goods_code',
            'goods_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer',
            'unit' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'selling_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        if (empty($validated['description'])) {
            $validated['description'] = '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."';
        }

        $validated['form'] = Auth::id();
        $validated['goods_status'] = 'approved';
        $validated['request_type'] = 'primary';

        $barang = Goods::create($validated);

        if ($request->hasFile('image')) {
            $folder = 'barang/' . $barang->id;
            $path = $request->file('image')->store($folder, 'public');
            $barang->image = $path;
            $barang->save();
        }

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil ditambahkan!']);
    }

    public function update(Request $request, $id)
    {
        $barang = Goods::findOrFail($id);
        // If selling_price is present, handle price update (General Affair)
        if ($request->has('selling_price')) {
            $validated = $request->validate([
                'selling_price' => 'required|numeric',
                'note' => 'nullable|string',
            ]);

            $oldPrice = $barang->selling_price;
            $barang->selling_price = $validated['selling_price'];
            $barang->save();

            GoodsHistory::create([
                'goods_id' => $barang->id,
                'goods_code' => $barang->goods_code,
                'goods_name' => $barang->goods_name,
                'category' => $barang->category,
                'stock' => $barang->stock,
                'unit' => $barang->unit,
                'location' => $barang->location,
                'buy_price' => $barang->buy_price,
                'selling_price' => $barang->selling_price,
                'description' => $barang->description,
                'old_status' => $barang->goods_status,
                'new_status' => $barang->goods_status,
                'changed_by' => Auth::id(),
                'note' => $validated['note'] ?? 'Perubahan harga jual dari ' . ($oldPrice ?? 0) . ' ke ' . $barang->selling_price,
                'action' => 'harga jual diubah dari Rp ' . number_format($oldPrice ?? 0, 0, ',', '.') . ' ke Rp ' . number_format($barang->selling_price, 0, ',', '.'),
                'form' => Auth::id(),
                'changed_at' => now(),
            ]);

            return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Harga jual berhasil diupdate!']);
        }

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fileKey = $request->hasFile('image') ? 'image' : ($request->hasFile('gambar') ? 'gambar' : null);

        if ($fileKey) {
            $oldGambar = $barang->image;
            
            $folder = 'barang/' . $barang->id;
            $path = $request->file($fileKey)->store($folder, 'public');
            $barang->image = $path;
            $barang->save();

            if ($oldGambar && \Storage::disk('public')->exists($oldGambar)) {
                \Storage::disk('public')->delete($oldGambar);
            }
        }

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Gambar barang berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $barang = Goods::findOrFail($id);
        $folder = 'barang/' . $barang->id;
        if (\Storage::disk('public')->exists($folder)) {
            \Storage::disk('public')->deleteDirectory($folder);
        }

        $barang->delete();

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil dihapus!']);
    }

    private function generateUniqueKodeBarang($kategori)
    {
        return Goods::generateUniqueKodeBarang($kategori);
    }

    public function getLogs($id)
    {
        $logs = GoodsReceipt::with(['supplier', 'approver'])
            ->where('good_id', $id)
            ->orderBy('received_at', 'desc')
            ->get();

        return response()->json($logs);
    }
}
