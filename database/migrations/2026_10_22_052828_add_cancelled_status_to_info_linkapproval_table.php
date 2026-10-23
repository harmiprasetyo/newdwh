
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan status cancelled untuk pembatalan approval.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE info_linkapproval
            MODIFY COLUMN status
            ENUM(
                'pending',
                'approved',
                'rejected',
                'cancelled'
            )
            NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Kembalikan struktur ENUM sebelumnya.
     */
    public function down(): void
    {
        // Jangan hapus nilai ENUM jika masih ada
        // approval dengan status cancelled.
        $jumlahCancelled = DB::table('info_linkapproval')
            ->where('status', 'cancelled')
            ->count();

        if ($jumlahCancelled > 0) {
            throw new RuntimeException(
                'Rollback ditolak: masih terdapat approval berstatus cancelled.'
            );
        }

        DB::statement("
            ALTER TABLE info_linkapproval
            MODIFY COLUMN status
            ENUM(
                'pending',
                'approved',
                'rejected'
            )
            NOT NULL DEFAULT 'pending'
        ");
    }
};
