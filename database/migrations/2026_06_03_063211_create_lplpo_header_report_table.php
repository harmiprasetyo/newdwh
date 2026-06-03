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
       Schema::create('lplpo_header_report', function (Blueprint $table) {
    $table->id();
    $table->string('kode_faskes');
    $table->integer('bulan');
    $table->integer('tahun');
    $table->timestamps();
       $table->unique(['kode_faskes','bulan','tahun']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lplpo_header_report');
    }
};
