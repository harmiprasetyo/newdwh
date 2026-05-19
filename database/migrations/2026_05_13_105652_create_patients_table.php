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
      Schema::create('patients', function (Blueprint $table) {
    $table->id();

    $table->string('patient_id')->unique();
    $table->string('ihs_number')->nullable();
    $table->string('nik')->nullable();
    $table->string('bpjs')->nullable();

    $table->string('name');
    $table->string('phone')->nullable();
    $table->string('email')->nullable();
    $table->string('gender')->nullable();
    $table->date('birth_date')->nullable();

    $table->text('address')->nullable();

    // COLUMNS
    $table->string('kode_propinsi')->nullable();
    $table->string('kode_kota')->nullable();
    $table->string('kode_kecamatan')->nullable();

    // INDEXES
    $table->index('nik');
    $table->index('kode_propinsi');
    $table->index('kode_kota');

    // FOREIGN KEYS
    $table->foreign('kode_propinsi')
        ->references('code')
        ->on('indonesia_provinces')
        ->nullOnDelete();

    $table->foreign('kode_kota')
        ->references('code')
        ->on('indonesia_cities')
        ->nullOnDelete();

    $table->foreign('kode_kecamatan')
        ->references('code')
        ->on('indonesia_districts')
        ->nullOnDelete();

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
