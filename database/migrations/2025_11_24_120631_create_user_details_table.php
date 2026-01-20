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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('id_number')->unique();
            $table->tinyInteger('gender')->comment('0=Female, 1=Male')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('house_number')->comment('address')->nullable();
            $table->string('street')->comment('address')->nullable();
            $table->string('barangay')->comment('address')->nullable();
            $table->string('citymun')->comment('address')->nullable();
            $table->string('province')->comment('address')->nullable();
            $table->string('qr_code')->nullable()->comment('Path or data for user QR');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
