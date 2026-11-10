
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fhir_incremental_states', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type', 50)->unique();
            $table->timestamp('last_success_at')->nullable();
            $table->timestamp('window_until')->nullable();
            $table->longText('next_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fhir_incremental_states');
    }
};
