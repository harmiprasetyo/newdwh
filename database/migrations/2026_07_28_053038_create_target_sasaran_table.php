<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('target_sasaran', function (Blueprint $table) {

            $table->id();

            $table->foreignId('posyandu_id')
                ->constrained('master_posyandu')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->tinyInteger('bulan');

            $table->year('tahun');

            $table->string('rw',5);

            $table->string('rt',5);

            $table->integer('sasaran_ibu_hamil')->default(0);

            $table->integer('sasaran_ibu_melahirkan')->default(0);

            $table->integer('sasaran_bayi_baru_lahir')->default(0);

            $table->foreignId('created_by')->nullable();

            $table->foreignId('updated_by')->nullable();
            $table->string('province_code',2);
$table->string('city_code',4);
$table->string('district_code',6);
$table->string('village_code',10);
$table->string('kodePosyandu',20);
$table->string('namaPosyandu',200);

            $table->timestamps();

            $table->unique([
                'posyandu_id',
                'bulan',
                'tahun',
                'rw',
                'rt'
            ],'uk_target_sasaran');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_sasaran');
    }
};
