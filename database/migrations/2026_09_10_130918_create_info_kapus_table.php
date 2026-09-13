<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('info_kapus', function (Blueprint $table) {

            $table->id();

            $table->string('kodeFaskes', 20)
                ->unique();

            $table->string('namaKapus', 150);

            $table->string('nipKapus', 50);

            $table->string('emailKapus', 150);

            /*
             * Kode e-sign disimpan dalam bentuk HASH.
             * Jangan simpan kode asli/plain text.
             */
            $table->string('kodeEsign', 255);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('info_kapus');
    }
};

