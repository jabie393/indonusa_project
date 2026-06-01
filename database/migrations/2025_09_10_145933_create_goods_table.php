<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->enum('request_type', ['primary', 'new_stock'])->default('primary');
            $table->enum('goods_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('status_listing')->default('listing');
            $table->string('goods_code')->unique();
            $table->string('goods_name');
            $table->enum('category', [
                'HANDTOOLS',
                'ADHESIVE AND SEALANT',
                'AUTOMOTIVE EQUIPMENT',
                'CLEANING',
                'COMPRESSOR',
                'CONSTRUCTION',
                'CUTTING TOOLS',
                'LIGHTING',
                'FASTENING',
                'GENERATOR',
                'HEALTH CARE EQUIPMENT',
                'HOSPITALITY',
                'HYDRAULIC TOOLS',
                'MARKING MACHINE',
                'MATERIAL HANDLING EQUIPMENT',
                'MEASURING AND TESTING EQUIPMENT',
                'METAL CUTTING MACHINERY',
                'PACKAGING',
                'PAINTING AND COATING',
                'PNEUMATIC TOOLS',
                'POWER TOOLS',
                'SAFETY AND PROTECTION EQUIPMENT',
                'SECURITY',
                'SHEET METAL MACHINERY',
                'STORAGE SYSTEM',
                'WELDING EQUIPMENT',
                'WOODWORKING EQUIPMENT',
                'MISCELLANEOUS',
                'OTHER CATEGORIES'
            ])->nullable();
            $table->integer('stock')->default(0);
            $table->string('unit');
            $table->string('location')->nullable();
            $table->decimal('buy_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->unsignedSmallInteger('discount_percent')->default(0);
            $table->text('description');
            $table->string('image')->nullable();
            $table->text('note')->nullable();
            $table->text('submission_reason')->nullable();
            $table->string('form')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('goods')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};
