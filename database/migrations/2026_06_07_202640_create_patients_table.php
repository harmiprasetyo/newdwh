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
       Schema::create('dashboard_patients', function (Blueprint $table) {
    $table->id();
    $table->string('patient_id')->unique();
    $table->string('nik')->nullable();
    $table->string('name')->nullable();
    $table->string('gender')->nullable();
    $table->date('birth_date')->nullable();
    $table->json('raw_json')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
