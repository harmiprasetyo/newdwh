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
        Schema::create('master_stokminimal_obat', function (Blueprint $table) {
            $table->id();
            $table->string('kode_obat', 50);
            $table->string('kodeFaskes',255);
            $table->integer('stok_minimal');
            $table->integer('stok_optimum');
            $table->enum('obat_esensial', ['oe', 'noe'])->default('noe');
            $table->enum('obat_formularium_puskesmas',['true','false'])->default('false');
            $table->foreign('kode_obat')->references('kode_obat')->on('master_obat')->onDelete('cascade');
            $table->foreign('kodeFaskes')->references('kodeFaskes')->on('master_faskes')->onDelete('cascade');
            $table->integer('tahun');
            $table->unique(['kode_obat', 'kodeFaskes', 'tahun'], 'unique_stokminimal_obat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_stokminimal_obat');
    }
};
