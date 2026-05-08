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
        Schema::create('master_faskes', function (Blueprint $table) {
            $table->id();

            // KODE FASKES
            $table->char('kodeFaskes', 20)->unique();

            // RELASI TYPE FASKES
            $table->foreignId('typeFaskes')
                ->constrained('list_typefaskes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // WILAYAH INDONESIA
            $table->string('kodePropinsi', 10)->nullable();
            $table->string('kodeKabupaten', 10)->nullable();
            $table->string('kodeKecamatan', 10)->nullable();

            // ENUM KEPEMILIKAN
            $table->enum('kepemilikan', ['Pemerintah', 'Swasta']);

            // NAMA FASKES
            $table->string('namaFaskes', 155);

            $table->timestamps();

            //Foreign Key
             $table->foreign('kodePropinsi')
                ->references('code')
                ->on('indonesia_provinces')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('kodeKabupaten')
                ->references('code')
                ->on('indonesia_cities')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('kodeKecamatan')
                ->references('code')
                ->on('indonesia_districts')
                ->onUpdate('cascade')
                ->onDelete('set null');

            // INDEX (optional tapi disarankan)
            $table->index('kodeFaskes');
            $table->index('typeFaskes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_faskes');
    }
};
