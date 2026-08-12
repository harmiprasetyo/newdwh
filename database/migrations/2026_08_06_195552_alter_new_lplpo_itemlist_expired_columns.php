<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('new_lplpo_itemlist', function (Blueprint $table) {

            // Tambahkan expired PKD
            $table->integer('item_expired_pkd')
                ->default(0)
                ->after('pemakaian_jkn');

            // Tambahkan expired JKN
            $table->integer('item_expired_jkn')
                ->default(0)
                ->after('item_expired_pkd');

            // Hapus kolom expired lama
            $table->dropColumn('item_expired');
        });
    }

    public function down(): void
    {
        Schema::table('new_lplpo_itemlist', function (Blueprint $table) {

            // Kembalikan kolom lama
            $table->integer('item_expired')
                ->default(0)
                ->after('pemakaian_jkn');

            // Hapus kolom baru
            $table->dropColumn([
                'item_expired_pkd',
                'item_expired_jkn',
            ]);
        });
    }
};
