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
       Schema::create('new_lplpo_report_notes', function (Blueprint $table) {

    $table->id();

    $table->foreignId('report_id')
        ->constrained('new_lplpo_reports')
        ->cascadeOnDelete();

    $table->enum('note_type',[
        'rejected',
        'revision',
        'verification'
    ])->default('rejected');

    $table->text('note');

    $table->string('created_by');

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_lplpo_report_notes');
    }
};
