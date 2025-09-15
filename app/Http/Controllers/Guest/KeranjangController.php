<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function tambah(Request $request)
    {
        $keranjang = session()->get('keranjang', []);
        $id = $request->input('id');
        $nama = $request->input('nama');
        $harga = $request->input('harga');

        if (isset($keranjang[$id])) {
            $keranjang[$id]['qty'] += 1;
        } else {
            $keranjang[$id] = [
                'nama' => $nama,
                'harga' => $harga,
                'qty' => 1,
            ];
        }

        session(['keranjang' => $keranjang]);
        return back()->with('success', 'Barang ditambahkan ke keranjang!');
    }

    public function index()
    {
        $keranjang = session('keranjang', []);
        return view('guest.order.keranjang', compact('keranjang'));
    }

    public function kurangi($id)
    {
        $keranjang = session()->get('keranjang', []);
        if (isset($keranjang[$id])) {
            $keranjang[$id]['qty']--;
            if ($keranjang[$id]['qty'] <= 0) {
                unset($keranjang[$id]);
            }
        }
        session(['keranjang' => $keranjang]);
        return back();
    }

    public function hapus($id)
    {
        $keranjang = session()->get('keranjang', []);
        unset($keranjang[$id]);
        session(['keranjang' => $keranjang]);
        return back();
    }

    public function checkout(Request $request)
    {
        $keranjang = session('keranjang', []);
        $total = 0;
        $pesan = "Halo, saya ingin memesan:\n";
        foreach ($keranjang as $item) {
            $subtotal = $item['harga'] * $item['qty'];
            $total += $subtotal;
            $pesan .= "- {$item['nama']} (Qty: {$item['qty']}) @ Rp " . number_format($item['harga'], 0, ',', '.') . "\n";
        }
        $pesan .= "Total: Rp " . number_format($total, 0, ',', '.');
        $waNumber = '6281234567890'; // WA admin
        $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($pesan);

        // Reset keranjang
        session()->forget('keranjang');

        // Tampilkan view sukses dan kirim url wa
        return view('guest.order.checkout-success', compact('waUrl'));
    }
}
