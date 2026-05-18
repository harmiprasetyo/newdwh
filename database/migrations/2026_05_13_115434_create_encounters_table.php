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
       Schema::create('encounters', function (Blueprint $table) {
    $table->id();
    $table->string('encounter_id')->unique();

    $table->string('patient_id')->index();

    $table->string('status')->nullable();
    $table->string('class_code')->nullable();
    $table->string('class_display')->nullable();

    $table->string('practitioner_name')->nullable();
    $table->string('location')->nullable();

    $table->string('provider_id')->nullable();

    $table->string('visit_type')->nullable(); // K1, K2, dll

    $table->dateTime('start_at')->nullable();
    $table->dateTime('end_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounters');
    }
};
