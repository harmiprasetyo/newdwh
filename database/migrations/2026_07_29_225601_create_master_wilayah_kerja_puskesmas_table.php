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
      Schema::create('master_wilayah_kerja_puskesmas', function (Blueprint $table) {
    $table->id();

    $table->string('kodeFaskes', 20);
    $table->string('kodeDesa', 10)->nullable();

    $table->timestamps();

    $table->foreign('kodeFaskes')
        ->references('kodeFaskes')
        ->on('master_faskes')
        ->cascadeOnUpdate()
        ->restrictOnDelete();

    $table->foreign('kodeDesa')
        ->references('code')
        ->on('indonesia_villages')
        ->cascadeOnUpdate()
        ->nullOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_wilayah_kerja_puskesmas');
    }
};
