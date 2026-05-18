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

    #$table->string('kode_propinsi')->nullable();
    #$table->string('kode_kota')->nullable();
    #$table->string('kode_kecamatan')->nullable();
    $table->index('nik');
    $table->index('kode_kota');
    $table->index('kode_propinsi');

      // RELASI KE LARAVOLT
    $table->foreignId('kode_propinsi')
    ->references('code')
        ->constrained('indonesia_provinces')
        ->nullOnDelete();

    $table->foreignId('kode_kota')
    ->references('code')
        ->constrained('indonesia_cities')
        ->nullOnDelete();

    $table->foreignId('kode_kecamatan')
    ->references('code')
        ->constrained('indonesia_districts')
        ->nullOnDelete();

         $table->index('nik');



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
