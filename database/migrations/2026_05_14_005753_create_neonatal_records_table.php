<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('neonatal_records', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->index();
            $table->string('encounter_id')->index();
            $table->time('jam_lahir')->nullable();
            $table->integer('berat_lahir')->nullable();
            $table->integer('panjang_badan')->nullable();
            $table->integer('lingkar_kepala')->nullable();
             $table->string('jenis_kelamin', 20)->nullable();
             $table->string('apgar_score_1')->nullable();
             $table->string('apgar_score_5')->nullable();
             $table->string('apgar_score_10')->nullable();
             $table->integer('pernapasan')->nullable();
             $table->integer('nadi')->nullable();
             $table->decimal('suhu', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neonatal_records');
    }
};
