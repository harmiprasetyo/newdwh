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
        Schema::create('users_app', function (Blueprint $table) {
    $table->uuid('userid')->primary();
    $table->string('username')->unique();
    $table->unsignedBigInteger('groupid');
    $table->string('email')->unique();
    $table->string('namalengkap');

    $table->string('kodeFaskes')->nullable();
    $table->string('namaFaskes')->nullable();

    $table->string('kodePropinsi')->nullable();
    $table->string('kodeKota')->nullable();
    $table->string('kodeKecamatan')->nullable();

    $table->string('password');
    $table->timestamps();

    // FK
    $table->foreign('groupid')->references('group_id')->on('usergroups');
     $table->foreign('kodePropinsi')->references('code')->on('indonesia_provinces');
        $table->foreign('kodeKota')->references('code')->on('indonesia_cities');
        $table->foreign('kodeKecamatan')->references('code')->on('indonesia_districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_app');
    }
};
