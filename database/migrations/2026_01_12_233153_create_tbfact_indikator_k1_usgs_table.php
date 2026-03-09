<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbfactIndikatorK1UsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbfact_indikator_k1_usgs', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('tahun');
            $table->integer('bulan');
            $table->integer('total_kunjungan_k1USG');
            $table->integer('sasaran_bumil');
            $table->decimal('cakupan_k1USG',3,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbfact_indikator_k1_usgs');
    }
}
