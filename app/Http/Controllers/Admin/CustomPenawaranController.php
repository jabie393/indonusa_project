<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPenawaran;
use App\Models\CustomPenawaranItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CustomPenawaranController extends Controller
{
    /**
     * List semua custom penawarans milik sales
     */
    public function index()
    {
        $customPenawarans = CustomPenawaran::where('sales_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('admin.sales.custom-penawaran.index', compact('customPenawarans'));
    }

    /**
     * Form untuk membuat custom penawaran baru
     */
    public function create()
    {
        return view('admin.sales.custom-penawaran.create');
    }

    /**
     * Simpan custom penawaran baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'to' => 'required|string|max:255',
            'up' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'email' => 'required|email',
            'date' => 'required|date',
            'intro_text' => 'nullable|string',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.images' => 'nullable|array',
            'items.*.images.*' => 'nullable|image|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $penawaran = CustomPenawaran::create([
                'sales_id' => Auth::id(),
                'penawaran_number' => CustomPenawaran::generatePenawaranNumber(),
                'to' => $validated['to'],
                'up' => $validated['up'] ?? null,
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'our_ref' => CustomPenawaran::generateUniqueRef(),
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
                'status' => 'draft',
            ]);

            $subtotal = 0;
            foreach ($validated['items'] as $i => $itemData) {
                $itemImages = [];
                if ($request->hasFile("items.$i.images")) {
                    foreach ($request->file("items.$i.images") as $file) {
                        if ($file) {
                            $itemImages[] = $file->store('custom-penawaran-images', 'public');
                        }
                    }
                }

                $itemSubtotal = $itemData['qty'] * $itemData['harga'];
                $subtotal += $itemSubtotal;

                CustomPenawaranItem::create([
                    'custom_penawaran_id' => $penawaran->id,
                    'nama_barang' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'satuan' => $itemData['satuan'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $itemSubtotal,
                    'images' => !empty($itemImages) ? $itemImages : null,
                ]);
            }

            $penawaran->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ]);

            DB::commit();

            return redirect()->route('sales.custom-penawaran.show', $penawaran->id)
                ->with('success', "Penawaran {$penawaran->penawaran_number} berhasil dibuat.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal membuat penawaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Lihat detail custom penawaran
     */
    public function show(CustomPenawaran $customPenawaran)
    {
        if ($customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        $customPenawaran->load('items', 'sales');
        return view('admin.sales.custom-penawaran.show', compact('customPenawaran'));
    }

    /**
     * Form edit custom penawaran
     */
    public function edit(CustomPenawaran $customPenawaran)
    {
        if ($customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        $customPenawaran->load('items');
        return view('admin.sales.custom-penawaran.edit', compact('customPenawaran'));
    }

    /**
     * Update custom penawaran
     */
    public function update(Request $request, CustomPenawaran $customPenawaran)
    {
        if ($customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'to' => 'required|string|max:255',
            'up' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'email' => 'required|email',
            'date' => 'required|date',
            'intro_text' => 'nullable|string',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.images' => 'nullable|array',
            'items.*.images.*' => 'nullable|image|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $customPenawaran->update([
                'to' => $validated['to'],
                'up' => $validated['up'] ?? null,
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
            ]);

            $customPenawaran->items()->delete();

            $subtotal = 0;
            foreach ($validated['items'] as $i => $itemData) {
                $itemImages = [];
                if ($request->hasFile("items.$i.images")) {
                    foreach ($request->file("items.$i.images") as $file) {
                        if ($file) {
                            $itemImages[] = $file->store('custom-penawaran-images', 'public');
                        }
                    }
                }

                $itemSubtotal = $itemData['qty'] * $itemData['harga'];
                $subtotal += $itemSubtotal;

                CustomPenawaranItem::create([
                    'custom_penawaran_id' => $customPenawaran->id,
                    'nama_barang' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'satuan' => $itemData['satuan'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $itemSubtotal,
                    'images' => !empty($itemImages) ? $itemImages : null,
                ]);
            }

            $customPenawaran->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ]);

            DB::commit();

            return redirect()->route('sales.custom-penawaran.show', $customPenawaran->id)
                ->with('success', 'Penawaran berhasil diubah.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah penawaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus custom penawaran
     */
    public function destroy(CustomPenawaran $customPenawaran)
    {
        if ($customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        $customPenawaran->delete();
        return redirect()->route('sales.custom-penawaran.index')
            ->with('success', 'Penawaran berhasil dihapus.');
    }

    /**
     * View PDF penawaran
     */
    public function pdf(CustomPenawaran $customPenawaran)
    {
        if ($customPenawaran->sales_id !== Auth::id() && !in_array(Auth::user()->role, ['Supervisor', 'Admin'])) {
            abort(403);
        }

        return view('admin.pdf.custom-penawaran-pdf', compact('customPenawaran'));
    }
}
