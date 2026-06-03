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
       Schema::table('lplpo_temp', function (Blueprint $table) {
    $table->foreignId('header_id')
        ->after('id')
        ->constrained('lplpo_header_report')
        ->cascadeOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lplpo_temp', function (Blueprint $table) {
            $table->dropForeign(['header_id']);
            $table->dropColumn('header_id');
        });
    }
};
