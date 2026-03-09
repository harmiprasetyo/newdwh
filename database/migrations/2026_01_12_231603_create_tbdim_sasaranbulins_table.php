<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbdimSasaranbulinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbdim_sasaranbulins', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('tahun');
            $table->integer('sasaran_bulin');
            $table->string('wilayah_kerja');
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
        Schema::dropIfExists('tbdim_sasaranbulins');
    }
}
