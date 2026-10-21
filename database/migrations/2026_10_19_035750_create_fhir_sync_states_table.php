
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fhir_sync_states', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type', 50)->unique();
            $table->text('next_url')->nullable();
            $table->timestamp('last_completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fhir_sync_states');
    }
};
