<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lplpo', function (Blueprint $table) {

            // RENAME PKD → FIELD1
            $table->renameColumn('stok_awal_pkd', 'stok_awal_field1');
            $table->renameColumn('penerimaan_pkd', 'penerimaan_field1');
            $table->renameColumn('persediaan_pkd', 'persediaan_field1');
            $table->renameColumn('pemakaian_pkd', 'pemakaian_field1');
            $table->renameColumn('stok_akhir_pkd', 'pemakaian_field1');

            // RENAME PROGRAM → FIELD2
            $table->renameColumn('stok_awal_program', 'stok_awal_field2');
            $table->renameColumn('penerimaan_program', 'penerimaan_field2');
            $table->renameColumn('persediaan_program', 'persediaan_field2');
            $table->renameColumn('pemakaian_program', 'pemakaian_field2');
            $table->renameColumn('stok_akhir_program', 'pemakaian_field2');
        });

        Schema::table('lplpo', function (Blueprint $table) {

            // TAMBAH FIELD3
            $table->integer('stok_awal_field3')->default(0);
            $table->integer('penerimaan_field3')->default(0);
            $table->integer('persediaan_field3')->default(0);
            $table->integer('pemakaian_field3')->default(0);
             $table->integer('stok_akhir_field3')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('lplpo', function (Blueprint $table) {

            // BALIKIN FIELD1 → PKD
            $table->renameColumn('stok_awal_field1', 'stok_awal_pkd');
            $table->renameColumn('penerimaan_field1', 'penerimaan_pkd');
            $table->renameColumn('persediaan_field1', 'persediaan_pkd');
            $table->renameColumn('pemakaian_field1', 'pemakaian_pkd');
             $table->renameColumn('stok_akhir_field1', 'stok_akhir_pkd');

            // BALIKIN FIELD2 → PROGRAM
            $table->renameColumn('stok_awal_field2', 'stok_awal_program');
            $table->renameColumn('penerimaan_field2', 'penerimaan_program');
            $table->renameColumn('persediaan_field2', 'persediaan_program');
            $table->renameColumn('pemakaian_field2', 'pemakaian_program');
             $table->renameColumn('stok_akhir_field2', 'stok_akhir_program');

            // HAPUS FIELD3
            $table->dropColumn([
                'stok_awal_field3',
                'penerimaan_field3',
                'persediaan_field3',
                'pemakaian_field3',
                'syok_akhir_field3'
            ]);
        });
    }
};
