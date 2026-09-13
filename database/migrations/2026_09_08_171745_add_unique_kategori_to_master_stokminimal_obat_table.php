<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_stokminimal_obat', function (Blueprint $table) {

            $table->unique(
                ['kategori', 'kodeFaskes', 'tahun'],
                'unique_stokminimal_kategori'
            );

        });
    }

    public function down(): void
    {
        Schema::table('master_stokminimal_obat', function (Blueprint $table) {

            $table->dropUnique(
                'unique_stokminimal_kategori'
            );

        });
    }
};

