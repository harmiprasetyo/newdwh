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
       Schema::create('lplpo', function (Blueprint $table) {
    $table->id();

     $table->string('nama_obat');
    $table->string('kode_faskes');

    $table->integer('bulan');
    $table->integer('tahun');

    // STOK AWAL
    $table->integer('stok_awal_pkd')->default(0);
    $table->integer('stok_awal_program')->default(0);

    // PENERIMAAN
    $table->integer('penerimaan_pkd')->default(0);
    $table->integer('penerimaan_program')->default(0);

    // PERSEDIAAN
    $table->integer('persediaan_pkd')->default(0);
    $table->integer('persediaan_program')->default(0);

    // PEMAKAIAN
    $table->integer('pemakaian_pkd')->default(0);
    $table->integer('pemakaian_program')->default(0);

    // PENGELUARAN
    $table->integer('kadaluarsa')->default(0);
    $table->integer('pengembalian')->default(0);

    // STOK AKHIR
    $table->integer('stok_akhir_pkd')->default(0);
    $table->integer('stok_akhir_program')->default(0);

    // LAINNYA
    $table->integer('rko')->default(0);
    $table->integer('stok_optimum')->default(0);
    $table->integer('permintaan')->default(0);
    $table->integer('pemberian')->default(0);

    $table->text('keterangan')->nullable();

    $table->timestamps();
    /* $table->foreign('kode_obat')
        ->references('kode_obat')
        ->on('master_obat')
        ->onDelete('cascade'); */

    $table->foreign('kode_faskes')
        ->references('kodeFaskes')
        ->on('master_faskes')
        ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_lplpo');
    }
};
