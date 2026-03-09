<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbdimKecamatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbdim_kecamatans', function (Blueprint $table) {
            $table->string('kecamatan_kode')->unique()->primary();
            $table->string('kecamatan_kab');
            $table->string('kecamatan_prop');
            $table->string('kecamatan_nama');
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
        Schema::dropIfExists('tbdim_kecamatans');
    }
}
