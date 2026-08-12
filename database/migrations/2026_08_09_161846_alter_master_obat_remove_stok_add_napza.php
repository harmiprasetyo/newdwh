<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_obat', function (Blueprint $table) {

            // Hapus kolom stok yang sudah dipindahkan
            if (Schema::hasColumn('master_obat', 'stok_minimum')) {
                $table->dropColumn('stok_minimum');
            }

            if (Schema::hasColumn('master_obat', 'stok_optimum')) {
                $table->dropColumn('stok_optimum');
            }

            // Tambahkan status Napza
            if (!Schema::hasColumn('master_obat', 'obat_napza')) {
                $table->enum('obat_napza', ['ya', 'tidak'])
                    ->default('tidak')
                    ->after('satuan');
            }

        });
    }

    public function down(): void
    {
        Schema::table('master_obat', function (Blueprint $table) {

            // Kembalikan kolom stok
            if (!Schema::hasColumn('master_obat', 'stok_minimum')) {
                $table->integer('stok_minimum')
                    ->default(0)
                    ->after('satuan');
            }

            if (!Schema::hasColumn('master_obat', 'stok_optimum')) {
                $table->integer('stok_optimum')
                    ->default(0)
                    ->after('stok_minimum');
            }

            // Hapus Napza
            if (Schema::hasColumn('master_obat', 'obat_napza')) {
                $table->dropColumn('obat_napza');
            }

        });
    }
};
