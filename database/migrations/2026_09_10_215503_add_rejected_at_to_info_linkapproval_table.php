<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('info_linkapproval', function (Blueprint $table) {
            $table->timestamp('rejectedAt')
                ->nullable()
                ->after('rejectedReason');
        });
    }

    public function down(): void
    {
        Schema::table('info_linkapproval', function (Blueprint $table) {
            $table->dropColumn('rejectedAt');
        });
    }
};
