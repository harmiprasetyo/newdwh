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
        Schema::create('tenants', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique(); // contoh: WWF, KEMKES
    $table->enum('environment', ['production', 'uat', 'development']); // production | uat | development
    $table->json('ip_whitelist')->nullable(); // hanya dipakai production
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
