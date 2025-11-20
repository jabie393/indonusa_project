<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicsTable extends Migration
{
    public function up()
    {
        Schema::create('pics', function (Blueprint $table) {
            $table->id();

            // Field PIC
            $table->string('name'); // Nama PIC
            $table->string('phone')->nullable(); // No HP PIC
            $table->string('email')->nullable(); // Email PIC
            $table->string('position')->nullable(); // Jabatan (Manager, SPV, dll)

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pics');
    }

}
