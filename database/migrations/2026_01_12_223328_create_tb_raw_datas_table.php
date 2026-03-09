<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRawDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_raw_data', function (Blueprint $table) {
            $table->uuid('idraw')->primary();
            $table->biginteger('PID_nik');
            $table->string('PID_no_rm');
            $table->string('PID_nama');
            $table->string('PID_jeniskelamin');
            $table->date('PID_tgl_lahir');
            $table->string('PID_propinsi');
            $table->string('PID_kabkota');
            $table->string('PID_kecamatan');
            $table->date('VIS_tgl_kunjungan');
            $table->string('VIS_jeniskunjungan');
            $table->string('VIS_faskes');
            $table->integer('EXM_beratbadan');
            $table->integer('EXM_tinggibadan');
            $table->integer('EXM_lila');
            $table->integer('EXM_gravida');
            $table->integer('EXM_partus');
            $table->integer('EXM_abortus');
            $table->integer('EXM_sistole');
            $table->integer('EXM_diastole');
            $table->integer('EXM_nadi');
            $table->integer('EXM_trimester');
            $table->integer('EXM_usiakehamilan');
            $table->date('EXM_hpht');
            $table->string('LAB_hb');
            $table->string('LAB_protein');
            $table->string('LAB_guladarah');
            $table->string('LAB_hbsag');
            $table->string('LAB_hiv');
            $table->string('LAB_malaria');
            $table->string('VIS_jenisimunisasi');
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
        Schema::dropIfExists('tb_raw_data');
    }
}
