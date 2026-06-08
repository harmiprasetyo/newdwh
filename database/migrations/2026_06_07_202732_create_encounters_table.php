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
       Schema::create('dashboard_encounters', function (Blueprint $table) {
    $table->id();
    $table->string('encounter_id')->unique();
    $table->foreignId('patient_id')->nullable()->constrained('dashboard_patients')->nullOnDelete();

    $table->string('service_provider')->nullable();
    $table->string('location')->nullable();
    $table->dateTime('encounter_date')->nullable();
    $table->string('status')->nullable();

    $table->json('raw_json')->nullable();

    $table->timestamps();

    $table->index('service_provider');
    $table->index('location');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_encounters');
    }
};
