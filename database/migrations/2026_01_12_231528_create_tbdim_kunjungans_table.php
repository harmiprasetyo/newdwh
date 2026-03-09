<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbdimKunjungansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbdim_kunjungans', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('id_tanggal');
            $table->string('kode_faskes');
            $table->string('kode_prop');
            $table->string('kode_kab');
            $table->string('kode_kec');
            $table->integer('usia_kehamilan');
            $table->integer('tinggi_badan');
            $table->integer('lila');
            $table->string('status_mt');
            $table->integer('sistole');
            $table->integer('diastole');
            $table->string('penyakit_menular');
            $table->string('penyulit_kehamilan');
            $table->string('pemeriksaan_10t');
            $table->string('golongan_darah');
             $table->string('pemeriksaan_usg');
            $table->string('trimester');
             $table->string('status_tt');


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
        Schema::dropIfExists('tbdim_kunjungans');
    }
}
