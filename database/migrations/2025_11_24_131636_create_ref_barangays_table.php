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
        Schema::create('ref_barangays', function (Blueprint $table) {
            $table->id();
            $table->string('brgyCode')->unique();
            $table->string('brgyDesc')->nullable();

            $table->string('regCode');
            $table->foreign('regCode')->references('regCode')->on('ref_regions');

            $table->string('provCode');
            $table->foreign('provCode')->references('provCode')->on('ref_provinces');

            $table->string('citymunCode');
            $table->foreign('citymunCode')->references('citymunCode')->on('ref_cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_barangays');
    }
};
