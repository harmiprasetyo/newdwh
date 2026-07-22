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
        Schema::table('master_obat', function (Blueprint $table) {
            //
            $table->integer('stok_minimum')->default(0);
            $table->integer('stok_optimum')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_obat', function (Blueprint $table) {
            //
        });
    }
};
