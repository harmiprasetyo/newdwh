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
       Schema::create('lplpo_temp', function (Blueprint $table) {
    $table->id();


    $table->integer('bulan');
    $table->integer('tahun');
    $table->string('kode_faskes');

    $table->string('nama_obat');
    $table->string('satuan');
    $table->string('kode_obat');

    // STOK AWAL
    $table->integer('stok_awal_field1')->default(0);
    $table->integer('stok_awal_field2')->default(0);
      $table->integer('stok_awal_field3')->default(0);

    // PENERIMAAN
    $table->integer('penerimaan_field1')->default(0);
    $table->integer('penerimaan_field2')->default(0);
     $table->integer('penerimaan_field3')->default(0);

    // PERSEDIAAN
    $table->integer('persediaan_field1')->default(0);
    $table->integer('persediaan_field2')->default(0);
    $table->integer('persediaan_field3')->default(0);

    // PEMAKAIAN
    $table->integer('pemakaian_field1')->default(0);
    $table->integer('pemakaian_field2')->default(0);
    $table->integer('pemakaian_field3')->default(0);

    // PENGELUARAN
    $table->integer('kadaluarsa')->default(0);
    $table->integer('pengembalian')->default(0);

    // STOK AKHIR
    $table->integer('stok_akhir_field1')->default(0);
    $table->integer('stok_akhir_field2')->default(0);
    $table->integer('stok_akhir_field3')->default(0);

    // LAINNYA
    $table->integer('rko')->default(0);
    $table->integer('stok_optimum')->default(0);
    $table->integer('permintaan')->default(0);
    $table->integer('pemberian')->default(0);
    $table->text('keterangan')->nullable();
    $table->unique(['kode_faskes', 'kode_obat', 'bulan', 'tahun'], 'stok_unique');

    $table->timestamps();
    /* $table->foreign('kode_obat')
        ->references('kode_obat')
        ->on('master_obat')
        ->onDelete('cascade'); */

$table->index('kode_faskes');
$table->index('kode_obat');
$table->index(['bulan','tahun']);

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
        Schema::dropIfExists('lplpo_temp');
    }
};
