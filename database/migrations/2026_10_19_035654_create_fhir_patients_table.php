
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fhir_patient', function (Blueprint $table) {
            $table->id();

            // Identitas resource FHIR
            $table->string('fhir_id', 100)->unique();
            $table->string('version_id', 50)->nullable();
            $table->timestamp('fhir_last_updated')->nullable();

            // Identitas pasien
            $table->string('ihs_number', 50)->nullable()->index();
            $table->string('nik', 30)->nullable()->index();
            $table->string('bpjs_number', 50)->nullable()->index();

            // Biodata
            $table->boolean('active')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('family_name', 255)->nullable();
            $table->json('given_names')->nullable();
            $table->string('gender', 20)->nullable();
            $table->date('birth_date')->nullable();

            // Kontak
            $table->string('phone', 50)->nullable();
            $table->string('email', 255)->nullable();

            // Alamat
            $table->text('address_line')->nullable();
            $table->string('province_code', 30)->nullable();
            $table->string('province_name', 100)->nullable();
            $table->string('city_code', 30)->nullable();
            $table->string('city_name', 100)->nullable();
            $table->string('district_code', 30)->nullable();
            $table->string('district_name', 100)->nullable();
            $table->string('village_code', 30)->nullable();
            $table->string('village_name', 100)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('rt', 10)->nullable();

            // Jejak sinkronisasi
            $table->json('raw_resource')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->index('fhir_last_updated');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fhir_patient');
    }
};
