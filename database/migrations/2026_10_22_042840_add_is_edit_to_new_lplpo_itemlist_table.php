
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('new_lplpo_itemlist', function (Blueprint $table) {
            $table->enum('is_edit', ['0', '1'])
                ->default('0')
                ->after('pemberian_jkn');
        });
    }

    public function down(): void
    {
        Schema::table('new_lplpo_itemlist', function (Blueprint $table) {
            $table->dropColumn('is_edit');
        });
    }
};
