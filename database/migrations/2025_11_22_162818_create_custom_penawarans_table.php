<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('custom_penawarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->string('penawaran_number')->unique();
            $table->string('to')->nullable();
            $table->string('up')->nullable();
            $table->string('subject')->nullable();
            $table->string('email')->nullable();
            $table->string('our_ref')->unique();
            $table->date('date')->nullable();
            $table->longText('intro_text')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->nullable();
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->string('status')->default('draft'); // draft, sent, approved, rejected, expired, sent_to_warehouse, etc.
            $table->dateTime('expired_at')->nullable();
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('sales_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_penawarans');
    }
};
