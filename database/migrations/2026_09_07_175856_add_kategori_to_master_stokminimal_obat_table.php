<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'master_stokminimal_obat',
            function (Blueprint $table) {

                $table->string('kategori', 100)
                    ->nullable()
                    ->after('obat_esensial');

            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'master_stokminimal_obat',
            function (Blueprint $table) {

                $table->dropColumn('kategori');

            }
        );
    }
};
