
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fhir_patient', function (Blueprint $table) {
            $table->string('nik_ibu', 32)
                ->nullable()
                ->after('nik');
        });
    }

    public function down(): void
    {
        Schema::table('fhir_patient', function (Blueprint $table) {
            $table->dropColumn('nik_ibu');
        });
    }
};
