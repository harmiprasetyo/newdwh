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
        Schema::create('new_lplpo_reports', function (Blueprint $table) {
            $table->id();
            $table->string('kode_faskes');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->string('nama_faskes');
            $table->string('nomor_lplpo');
            $table->enum('report_status',['DRAFT','SUBMITED','VERIFIED','REJECTED','FINAL']);

            $table->enum('lplpo_status',[
    'draft',
    'waiting',
    'approved',
    'rejected'
])->default('draft');

$table->integer('total_item')->default(0);

$table->integer('total_permintaan')->default(0);

$table->timestamp('last_saved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_lplpo_reports');
    }
};
