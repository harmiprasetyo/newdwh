<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbdimKelurahansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbdim_kelurahan', function (Blueprint $table) {
            $table->string('kode_desa')->primary()->unique();
            $table->string('kode_kecamatan');
            $table->string('kode_kabupaten');
            $table->string('kode_propinsi');
            $table->string('nama_desa');
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
        Schema::dropIfExists('tbdim_kelurahan');
    }
}
