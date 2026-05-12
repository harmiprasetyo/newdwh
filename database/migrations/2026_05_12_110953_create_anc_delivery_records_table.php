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
       Schema::create('anc_delivery_records', function (Blueprint $table) {
    $table->id();

    $table->string('patient_id')->nullable();
    $table->string('encounter_id')->nullable();

    $table->integer('gestational_age')->nullable();
    $table->integer('gravida')->nullable();
    $table->integer('parity')->nullable();
    $table->integer('abortus')->nullable();

    $table->dateTime('delivery_time')->nullable();
    $table->string('postpartum_condition')->nullable();

    $table->string('delivery_helper')->nullable();
    $table->string('delivery_method')->nullable();

    $table->dateTime('stage1')->nullable();
    $table->dateTime('stage2')->nullable();
    $table->dateTime('stage3')->nullable();
    $table->dateTime('stage4')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anc_delivery_records');
    }
};
