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
        $qty = $request->input('qty');

        if (isset($keranjang[$id])) {
            // If qty is present in the request, set it; otherwise, add 1
            if ($qty !== null) {
                $keranjang[$id]['qty'] = (int) $qty;
            } else {
                $keranjang[$id]['qty'] += 1;
            }
        } else {
            $keranjang[$id] = [
                'nama' => $nama,
                'harga' => $harga,
                'qty' => $qty !== null ? (int) $qty : 1,
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
        // Reset keranjang
        session()->forget('keranjang');
        // Redirect ke WhatsApp (tab baru sudah dihandle oleh target="_blank" di form)
        return redirect($request->waUrl);
    }
}
