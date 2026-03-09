<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbfactIndikatorK1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbfact_indikator_k1s', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('tahun');
            $table->integer('bulan');
            $table->string('faskes');
            $table->integer('total_kunjungan_bumil');
            $table->integer('sasaran_bumil');
            $table->decimal('cakupan_k1',3,2);
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
        Schema::dropIfExists('tbfact_indikator_k1s');
    }
}
