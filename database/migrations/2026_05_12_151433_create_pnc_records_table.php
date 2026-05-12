<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pnc_records', function (Blueprint $table) {
            $table->id();

            $table->string('patient_id');
            $table->string('encounter_id');

            $table->integer('gravida')->nullable();
            $table->integer('parity')->nullable();
            $table->integer('abortus')->nullable();

            $table->dateTime('delivery_time')->nullable();

            $table->timestamps();

            $table->unique(['patient_id', 'encounter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pnc_records');
    }
};
