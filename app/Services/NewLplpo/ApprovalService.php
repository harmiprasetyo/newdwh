<?php
namespace App\Services\NewLplpo;
use App\Models\NewLplpo\Report;
use App\Models\NewLplpo\InfoKapus;
use App\Models\NewLplpo\InfoLinkApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Mail\LplpoApprovalMail;
use Illuminate\Support\Facades\Mail;

class ApprovalService
{
    public function send(Report $report): InfoLinkApproval
{
    $approval = DB::transaction(function () use ($report) {

        if (!in_array($report->lplpo_status, [
            'draft',
            'rejected'
        ], true)) {

            throw ValidationException::withMessages([
                'report' =>
                    'Laporan tidak dapat dikirim approval karena status LPLPO saat ini adalah '
                    . $report->lplpo_status . '.'
            ]);
        }


        if (!in_array($report->report_status, [
            'DRAFT',
            'REJECTED'
        ], true)) {

            throw ValidationException::withMessages([
                'report' =>
                    'Laporan tidak dapat dikirim approval karena status laporan adalah '
                    . $report->report_status . '.'
            ]);
        }


        if (!$report->kunjungan()->exists()) {

            throw ValidationException::withMessages([
                'kunjungan' =>
                    'Data kunjungan belum diinput.'
            ]);
        }


        if (!$report->items()->exists()) {

            throw ValidationException::withMessages([
                'items' =>
                    'Item obat belum diinput.'
            ]);
        }


        $kapus = InfoKapus::where(
            'kodeFaskes',
            $report->kode_faskes
        )->first();


        if (!$kapus) {

            throw ValidationException::withMessages([
                'kapus' =>
                    'Data Kepala Puskesmas untuk fasilitas kesehatan ini belum tersedia.'
            ]);
        }


        if (empty($kapus->emailKapus)) {

            throw ValidationException::withMessages([
                'emailKapus' =>
                    'Email Kepala Puskesmas belum tersedia.'
            ]);
        }


        $approval = InfoLinkApproval::firstOrNew([
            'reportId' => $report->id,
        ]);


        $approval->kapusId = $kapus->id;

        $approval->approvalToken = Str::random(64);

        $approval->verificationToken = null;

        $approval->status = 'pending';

        $approval->approvedBy = null;

        $approval->approvedAt = null;

        $approval->rejectedReason = null;

        // Jangan gunakan rejectedAt
        // karena kolom tersebut tidak ada di tabel.


        $approval->save();


        $report->update([
            'lplpo_status' => 'waiting',
            'report_status' => 'DRAFT',
        ]);


        return $approval->fresh([
            'report',
            'kapus',
        ]);
    });


    /*
     * ==========================================================
     * KIRIM EMAIL SETELAH TRANSACTION BERHASIL
     * ==========================================================
     */

    Mail::to($approval->kapus->emailKapus)
        ->send(new LplpoApprovalMail($approval));


    return $approval;
}


    /**
     * Approve LPLPO menggunakan kode E-Sign Kapus.
     */
    public function approve(
        InfoLinkApproval $approval,
        string $kodeEsign
    ): InfoLinkApproval {

        return DB::transaction(function () use (
            $approval,
            $kodeEsign
        ) {

            $approval->loadMissing([
                'report',
                'kapus',
            ]);

            if ($approval->status !== 'pending') {

                throw ValidationException::withMessages([
                    'approval' =>
                        'Approval ini sudah tidak dapat diproses.'
                ]);
            }

            $report = $approval->report;
            $kapus = $approval->kapus;

            if (!$report) {

                throw ValidationException::withMessages([
                    'report' =>
                        'Data LPLPO tidak ditemukan.'
                ]);
            }

            if ($report->lplpo_status !== 'waiting') {

                throw ValidationException::withMessages([
                    'report' =>
                        'Laporan tidak sedang menunggu approval.'
                ]);
            }

            if (!$kapus) {

                throw ValidationException::withMessages([
                    'kapus' =>
                        'Data Kepala Puskesmas tidak ditemukan.'
                ]);
            }

            /*
             * Validasi kode E-Sign.
             *
             * kodeEsign di database adalah HASH,
             * sehingga harus menggunakan checkEsign().
             */
            if (!$kapus->checkEsign($kodeEsign)) {

                throw ValidationException::withMessages([
                    'kodeEsign' =>
                        'Kode E-Sign tidak valid.'
                ]);
            }

            /*
             * Token untuk halaman verifikasi / QR.
             */
            $verificationToken = Str::random(64);

            $approval->update([
                'status' => 'approved',
                'approvedBy' => $kapus->nipKapus,
                'approvedAt' => now(),
                'verificationToken' => $verificationToken,
            ]);

            $report->update([
                'report_status' => 'SUBMITED',
                'lplpo_status' => 'approved',
            ]);

            return $approval->fresh([
                'report',
                'kapus',
            ]);
        });
    }


    /**
     * Menolak LPLPO.
     */
    public function reject(
        InfoLinkApproval $approval,
        string $reason
    ): InfoLinkApproval {

        return DB::transaction(function () use (
            $approval,
            $reason
        ) {

            $approval->loadMissing([
                'report',
                'kapus',
            ]);

            if ($approval->status !== 'pending') {

                throw ValidationException::withMessages([
                    'approval' =>
                        'Approval ini sudah tidak dapat diproses.'
                ]);
            }

            $report = $approval->report;

            if (!$report) {

                throw ValidationException::withMessages([
                    'report' =>
                        'Data LPLPO tidak ditemukan.'
                ]);
            }

            if ($report->lplpo_status !== 'waiting') {

                throw ValidationException::withMessages([
                    'report' =>
                        'Laporan tidak sedang menunggu approval.'
                ]);
            }

            $approval->update([
                'status' => 'rejected',
                'rejectedReason' => $reason,
                'approvedBy' => null,
                'approvedAt' => null,
                'verificationToken' => null,
            ]);

            $report->update([
                'report_status' => 'REJECTED',
                'lplpo_status' => 'rejected',
            ]);

            return $approval->fresh([
                'report',
                'kapus',
            ]);
        });
    }
}

