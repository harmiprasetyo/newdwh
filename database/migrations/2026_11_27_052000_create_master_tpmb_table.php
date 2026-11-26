<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('master_tpmb', function (Blueprint $table) {
   $table->uuid('id')->primary();
   $table->string('fhir_id',255)->nullable()->index();
   $table->char('kode_tpmb',50)->unique();
   // Sesuaikan tipe, panjang, charset dan collation dengan master_faskes.kodeFaskes.
   $table->string('kodeFaskes',50);
   $table->char('nama_tpmb',255);
   $table->text('alamat_tpmb');
   $table->enum('status_tpmb',['aktif','tidak'])->default('aktif');
   $table->timestamps();
   $table->foreign('kodeFaskes')->references('kodeFaskes')->on('master_faskes')->restrictOnDelete()->cascadeOnUpdate();
  });
 }
 public function down(): void { Schema::dropIfExists('master_tpmb'); }
};
