<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('new_lplpo_kunjungan', function (Blueprint $table) {

            $table->id();

            $table->foreignId('report_id')
                ->constrained('new_lplpo_reports')
                ->cascadeOnDelete();

            // Kategori kunjungan
            $table->unsignedInteger('kunjungan_jkn')->default(0);
            $table->unsignedInteger('kunjungan_tunai')->default(0);
            $table->unsignedInteger('kunjungan_gratis')->default(0);

            $table->unsignedInteger('total_kunjungan_perkategori')
                ->default(0);

            // Gender
            $table->unsignedInteger('kunjungan_anak')->default(0);
            $table->unsignedInteger('kunjungan_dewasa')->default(0);

            $table->unsignedInteger('total_kunjungan_pergender')
                ->default(0);

            // Jenis pelayanan
            $table->unsignedInteger('kunjungan_lab')->default(0);
            $table->unsignedInteger('kunjungan_gigi')->default(0);
            $table->unsignedInteger('kunjungan_poned')->default(0);
            $table->unsignedInteger('kunjungan_rawatinap')->default(0);
            $table->unsignedInteger('kunjungan_rawatjalan')->default(0);

            $table->timestamps();

            // Satu laporan hanya memiliki satu data kunjungan
            $table->unique('report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('new_lplpo_kunjungan');
    }
};
