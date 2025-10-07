<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestOrder extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin_sales';
    }

    public function rules(): array
    {
        return [
            'barang_id' => ['required','array','min:1'],
            'barang_id.*' => ['required','exists:barangs,id'],
            'quantity' => ['required','array','min:1'],
            'quantity.*' => ['required','integer','min:1'],
            'customer_name' => ['nullable','string','max:255'],
            'customer_id' => ['nullable','integer'],
            'catatan' => ['nullable','string','max:2000'],
            'tanggal_kebutuhan' => ['nullable','date','after:today'],
        ];
    }
}
