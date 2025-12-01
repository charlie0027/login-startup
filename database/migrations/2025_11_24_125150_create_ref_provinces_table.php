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
        Schema::create('ref_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('psgcCode')->nullable();
            $table->string('provDesc')->nullable();

            // need to create column first before referencing foreign key
            $table->string('regCode');
            $table->foreign('regCode')->references('regCode')->on('ref_regions');

            $table->string('provCode')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_provinces');
    }
};
