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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            // e.g. "viewTab", "updateSettings", "updateUserDetails"

            $table->json('allowed_roles')->nullable();
            // store role IDs as JSON: [1,4,5] or ["teacher","student"]

            $table->string('description')->nullable();
            // optional: human-readable description

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
