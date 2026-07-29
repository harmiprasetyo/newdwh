<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_posyandu', function (Blueprint $table) {

            $table->id();

            $table->char('province_code',2);
            $table->char('city_code',4);
            $table->char('district_code',7);
            $table->char('village_code',10);

            $table->string('kodeFaskes',20);

            $table->string('kodePosyandu',50)->unique();
            $table->string('namaPosyandu',255);

            $table->boolean('isActive')->default(true);

            $table->timestamps();

            $table->index('province_code');
            $table->index('city_code');
            $table->index('district_code');
            $table->index('village_code');
             $table->foreign('kodeFaskes')
        ->references('kodeFaskes')
        ->on('master_faskes')
        ->cascadeOnUpdate()
        ->restrictOnDelete();



        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_posyandu');
    }
};
