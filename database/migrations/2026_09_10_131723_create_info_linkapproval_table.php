
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('info_linkapproval', function (Blueprint $table) {

            $table->id();

            /*
             * Satu report hanya mempunyai satu
             * proses approval aktif.
             */
            $table->unsignedBigInteger('reportId')
                ->unique();

            /*
             * Kapus yang melakukan approval.
             */
            $table->foreignId('kapusId')
                ->constrained('info_kapus')
                ->onDelete('cascade');

            /*
             * Token untuk link approval Kapus.
             *
             * Contoh:
             * /newlplpo/approval/AbCdEf123...
             */
            $table->string('approvalToken', 100)
                ->unique();

            /*
             * Token untuk QR verification.
             *
             * Baru dibuat setelah approval berhasil.
             */
            $table->string('verificationToken', 100)
                ->nullable()
                ->unique();

            /*
             * Status approval.
             */
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            /*
             * Identitas yang melakukan approval.
             *
             * Untuk saat ini dapat berisi NIP Kapus.
             */
            $table->string('approvedBy', 100)
                ->nullable();

            /*
             * Waktu approval.
             */
            $table->timestamp('approvedAt')
                ->nullable();

            /*
             * Alasan jika ditolak.
             */
            $table->text('rejectedReason')
                ->nullable();

            $table->timestamps();

            /*
             * Relasi ke laporan LPLPO.
             */
            $table->foreign('reportId')
                ->references('id')
                ->on('new_lplpo_reports')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('info_linkapproval');
    }
};

