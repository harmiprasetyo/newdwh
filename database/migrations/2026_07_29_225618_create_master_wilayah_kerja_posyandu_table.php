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
        Schema::create('master_wilayah_kerja_posyandu', function (Blueprint $table) {
            $table->id();
            $table->string('kodePosyandu',50);
            $table->string('rw');
            $table->timestamps();
            $table->index('kodePosyandu');
$table->index('village_code');


                 $table->foreign('kodePosyandu')
                ->references('kodePosyandu')
                ->on('master_posyandu')
                ->onUpdate('cascade')
                ->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_wilayah_kerja_posyandu');
    }
};
