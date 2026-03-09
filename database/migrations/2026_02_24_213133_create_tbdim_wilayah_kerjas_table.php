<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use illuminate\Support\Str;

class CreateTbdimWilayahKerjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbdim_wilayah_kerja', function (Blueprint $table) {
            $table->uuid('rawid')->default(Str::uuid())->primary();
            $table->string('kode_desa');
            $table->string('kode_faskes');
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
        Schema::dropIfExists('tbdim_wilayah_kerja');
    }
}
