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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name')->unique();
            $table->string('role_code')->unique();
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();
            $table->tinyInteger('is_default')->comment('0=false, 1=true')->nullable();
            $table->tinyInteger('status')->comment('0=inactive, 1=active')->nullable();
            $table->string('updated_by')->nullable();
            $table->tinyInteger('created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
