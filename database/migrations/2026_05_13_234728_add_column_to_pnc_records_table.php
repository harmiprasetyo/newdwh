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
        Schema::table('pnc_records', function (Blueprint $table) {
            //
             $table->integer('pendarahan')->nullable()->unique()->after('delivery_time');
             $table->string('kondisi_perineum', 100)->nullable()->after('delivery_time');
            $table->string('tanda_infeksi_perineum', 100)->nullable()->after('delivery_time');
             $table->string('tanda_infeksi_luka_sc', 100)->nullable()->after('delivery_time');
               $table->string('kontraksi_uteus', 100)->nullable()->after('delivery_time');
                 $table->string('lochia', 100)->nullable()->after('delivery_time');
                   $table->string('bau_lochia', 100)->nullable()->after('delivery_time');
                     $table->string('produksi_asi', 100)->nullable()->after('delivery_time');
                       $table->string('bak', 100)->nullable()->after('delivery_time');
                         $table->string('bab', 100)->nullable()->after('delivery_time');
                           $table->string('pemeriksaan_payudara', 100)->nullable()->after('delivery_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pnc_records', function (Blueprint $table) {
            //
            $table->dropColumn('pendarahan');
            $table->dropColumn('kondisi_perineum');
            $table->dropColumn('tanda_infeksi_perineum');
            $table->dropColumn('tanda_infeksi_luka_sc');
            $table->dropColumn('kontraksi_uteus');
            $table->dropColumn('lochia');
            $table->dropColumn('bau_lochia');
            $table->dropColumn('produksi_asi');
            $table->dropColumn('bak');
            $table->dropColumn('bab');
            $table->dropColumn('pemeriksaan_payudara');
        });
    }
};
