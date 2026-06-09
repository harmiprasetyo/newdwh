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
        Schema::create('master_baseline_pemakaian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_faskes');
            $table->string('kode_obat')->unique();
            $table->string('nama_obat');

            $table->integer('bulan');
            $table->integer('tahun');
            $table->decimal('rerata_pemakaian', 10, 2)->default(0);

            $table->foreign('kode_faskes')
        ->references('kodeFaskes')
        ->on('master_faskes')
        ->onUpdate('cascade')
        ->onDelete('cascade');

    $table->foreign('kode_obat')
        ->references('kode_obat')
        ->on('master_obat')
        ->onUpdate('cascade')
        ->onDelete('cascade');

            $table->timestamps();
           $table->unique(
    ['kode_obat', 'kode_faskes', 'bulan', 'tahun'],
    'uniq_baseline'
);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_baseline_pemakaian');
    }
};
