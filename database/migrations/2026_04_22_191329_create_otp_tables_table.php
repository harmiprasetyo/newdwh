<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_tables', function (Blueprint $table) {
    $table->id();
    $table->string('identifier'); // email / no hp / NIK
    $table->string('otp', 6);
    $table->timestamp('expires_at');
    $table->integer('attempts')->default(0);
    $table->boolean('is_used')->default(false);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp_tables');
    }
}
