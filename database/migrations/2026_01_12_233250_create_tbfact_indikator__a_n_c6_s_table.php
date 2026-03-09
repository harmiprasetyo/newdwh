<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbfactIndikatorANC6STable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbfact_indikator__a_n_c6_s', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('tahun');
            $table->integer('bulan');
            $table->string('faskes');
            $table->integer('total_kunjungan');
            $table->integer('sasaran_bulin');
            $table->decimal('cakupan_anc6',3,2);
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
        Schema::dropIfExists('tbfact_indikator__a_n_c6_s');
    }
}
