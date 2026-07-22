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
        Schema::create('new_lplpo_itemlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')
      ->constrained('new_lplpo_reports')
      ->cascadeOnDelete();
      $table->foreignId('program_id')->constrained('new_lplpo_program_list')->cascadeOnDelete();
            $table->string('program_name')->nullable();
            $table->string('kode_obat');
            $table->string('nama_obat');
            $table->string('satuan');
            $table->integer('stok_awal_progam_pkd');
            $table->integer('stok_awal_jkn');
            $table->integer('penerimaan_program_pkd');
            $table->integer('penerimaan_jkn');
            $table->integer('persediaan_program_pkd');
            $table->integer('persediaan_jkn');
            $table->integer('pemakaian_program_pkd');
            $table->integer('pemakaian_jkn');
            $table->integer('item_expired');
            $table->integer('stok_akhir_program_pkd');
            $table->integer('stok_akhir_jkn');
            $table->integer('stok_minimum');
            $table->integer('stok_optimum');
            $table->integer('permintaan');
            $table->integer('pemberian_program_pkd')->default(0);
            $table->integer('pemberian_jkn')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_lplpo_itemlist');
    }
};
