<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotation_histories', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel asli (bisa null jika data dihapus)
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->unsignedBigInteger('custom_quotation_id')->nullable();

            $table->string('quotation_type'); // 'request_order' atau 'custom_quotation'
            $table->string('quotation_number');
            $table->string('customer_name')->nullable();
            $table->string('pic_name')->nullable();
            
            // Relasi ke sales
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->string('sales_name')->nullable();
            
            $table->decimal('grand_total', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->text('reason')->nullable();

            // Siapa yang mengubah (relasi ke users)
            $table->unsignedBigInteger('changed_by')->nullable();

            // Timestamp perubahan
            $table->timestamp('changed_at')->useCurrent();

            $table->timestamps();

            // Foreign keys
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('set null');
            $table->foreign('custom_quotation_id')->references('id')->on('custom_quotations')->onDelete('set null');
            $table->foreign('sales_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
        });

        // Ambil quotation standar (request_order) yang sudah diapprove/ditolak supervisor
        $regularQuotations = DB::table('quotations')
            ->join('orders', 'quotations.id', '=', 'orders.quotation_id')
            ->leftJoin('users as sales', 'quotations.sales_id', '=', 'sales.id')
            ->leftJoin('customers', 'quotations.customer_id', '=', 'customers.id')
            ->where('orders.status', 'rejected_supervisor')
            ->orWhere(function ($query) {
                $query->where('orders.status', 'open')
                      ->whereNotNull('orders.supervisor_id');
            })
            ->select(
                'quotations.id',
                'quotations.quotation_number',
                'quotations.customer_name',
                'quotations.customer_id',
                'quotations.sales_id',
                'quotations.grand_total',
                'quotations.created_at',
                'quotations.updated_at',
                'orders.status as order_status',
                'orders.reason as order_reason',
                'orders.supervisor_id as order_supervisor_id',
                'orders.updated_at as order_approved_at',
                'sales.name as sales_name'
            )
            ->get();

        foreach ($regularQuotations as $ro) {
            // Dapatkan nama PIC jika ada
            $picName = '-';
            if ($ro->customer_id) {
                $pic = DB::table('pics')->where('customer_id', $ro->customer_id)->first();
                if ($pic) {
                    $picName = $pic->name;
                }
            }

            DB::table('quotation_histories')->insert([
                'quotation_id' => $ro->id,
                'quotation_type' => 'request_order',
                'quotation_number' => $ro->quotation_number,
                'customer_name' => $ro->customer_name,
                'pic_name' => $picName,
                'sales_id' => $ro->sales_id,
                'sales_name' => $ro->sales_name ?? '-',
                'grand_total' => $ro->grand_total,
                'status' => $ro->order_status,
                'reason' => $ro->order_reason ?? '-',
                'changed_by' => $ro->order_supervisor_id,
                'changed_at' => $ro->order_approved_at ?? $ro->updated_at ?? now(),
                'created_at' => $ro->created_at ?? now(),
                'updated_at' => $ro->updated_at ?? now(),
            ]);
        }

        // Ambil custom quotation yang berstatus approved_supervisor atau rejected_supervisor
        $customQuotations = DB::table('custom_quotations')
            ->leftJoin('users as sales', 'custom_quotations.sales_id', '=', 'sales.id')
            ->whereIn('custom_quotations.status', ['approved_supervisor', 'rejected_supervisor'])
            ->select(
                'custom_quotations.*',
                'sales.name as sales_name'
            )
            ->get();

        foreach ($customQuotations as $cp) {
            DB::table('quotation_histories')->insert([
                'custom_quotation_id' => $cp->id,
                'quotation_type' => 'custom_quotation',
                'quotation_number' => $cp->quotation_number,
                'customer_name' => $cp->to,
                'pic_name' => $cp->up ?? '-',
                'sales_id' => $cp->sales_id,
                'sales_name' => $cp->sales_name ?? '-',
                'grand_total' => $cp->grand_total,
                'status' => $cp->status,
                'reason' => $cp->reason ?? '-',
                'changed_by' => $cp->approved_by ?? $cp->sales_id,
                'changed_at' => $cp->approved_at ?? $cp->updated_at ?? now(),
                'created_at' => $cp->created_at ?? now(),
                'updated_at' => $cp->updated_at ?? now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_histories');
    }
};
