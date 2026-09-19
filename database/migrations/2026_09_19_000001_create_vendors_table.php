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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_code')->unique()->nullable();
            $table->string('vendor_name');
            $table->string('company_type')->nullable(); // PT, CV, UD, Perorangan, dll
            $table->string('npwp')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('pic_name')->nullable();
            $table->string('pic_phone')->nullable();
            $table->string('pic_email')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->integer('term_of_payment')->nullable(); // dalam hari (misal 30)
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add vendor_id to procurement_of_goods if not exists
        if (Schema::hasTable('procurement_of_goods') && !Schema::hasColumn('procurement_of_goods', 'vendor_id')) {
            Schema::table('procurement_of_goods', function (Blueprint $table) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('notes');
                $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('procurement_of_goods') && Schema::hasColumn('procurement_of_goods', 'vendor_id')) {
            Schema::table('procurement_of_goods', function (Blueprint $table) {
                $table->dropForeign(['vendor_id']);
                $table->dropColumn('vendor_id');
            });
        }

        Schema::dropIfExists('vendors');
    }
};
