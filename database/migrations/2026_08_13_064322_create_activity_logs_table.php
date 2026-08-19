<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {

            $table->id();

            // User
            $table->string('user_id')->nullable()->index();

            // Activity
            $table->string('action', 50)->index();
            $table->string('module', 100)->nullable()->index();
            $table->text('description')->nullable();

            // Object yang terkena perubahan
            $table->string('subject_type')->nullable();
            $table->string('subject_id',255)->nullable();

            // Before / After
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Request information
            $table->text('url')->nullable();
            $table->string('method', 10)->nullable();

            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index([
                'subject_type',
                'subject_id'
            ]);

            $table->index([
                'user_id',
                'created_at'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
