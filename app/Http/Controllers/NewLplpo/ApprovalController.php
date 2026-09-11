<?php
namespace App\Http\Controllers\NewLplpo;
use App\Http\Controllers\Controller;
use App\Models\NewLplpo\Report;
use App\Models\NewLplpo\InfoLinkApproval;
use App\Services\NewLplpo\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApprovalController extends Controller
{
    protected ApprovalService $service;

    public function __construct(
        ApprovalService $service
    ) {
        $this->service = $service;
    }


    /**
     * Kirim LPLPO untuk approval Kapus.
     */
   public function send(Request $request, $id)
{
    try {

        $report = Report::findOrFail($id);

        $approval = $this->service->send($report);

        return response()->json([
            'success' => true,

            'message' =>
                'Laporan berhasil dikirim untuk approval Kepala Puskesmas.',

            'data' => [
                'report_id' =>
                    $report->id,

                'report_status' =>
                    $report->fresh()->report_status,

                'lplpo_status' =>
                    $report->fresh()->lplpo_status,

                'approval_id' =>
                    $approval->id,

                'approval_status' =>
                    $approval->status,
            ]
        ]);

    } catch (ValidationException $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'errors' => $e->errors(),
        ], 422);

    } catch (\Throwable $e) {

        report($e);

        return response()->json([
            'success' => false,
            'message' =>
                'Terjadi kesalahan saat mengirim approval.',
        ], 500);
    }
}


    /**
     * Menampilkan halaman approval.
     */
    public function show(string $token)
    {
        $approval = InfoLinkApproval::with([
            'report.items',
            'report.kunjungan',
            'report.faskes',
            'kapus',
        ])
        ->where('approvalToken', $token)
        ->firstOrFail();

        if ($approval->status !== 'pending') {

            return view(
                'newlplpo.approval.expired',
                [
                    'approval' => $approval,
                ]
            );
        }

        if ($approval->report->lplpo_status !== 'waiting') {

            return view(
                'newlplpo.approval.expired',
                [
                    'approval' => $approval,
                ]
            );
        }

        return view(
            'newlplpo.approval.show',
            [
                'approval' => $approval,
                'report' => $approval->report,
                'kapus' => $approval->kapus,
            ]
        );
    }


    /**
     * Approve LPLPO.
     */
    public function approve(
        Request $request,
        string $token
    ) {

        $request->validate([
            'kodeEsign' => [
                'required',
                'string',
                'min:1',
            ],
        ], [
            'kodeEsign.required' =>
                'Kode E-Sign wajib diisi.',
        ]);

        try {

            $approval = InfoLinkApproval::where(
                'approvalToken',
                $token
            )->firstOrFail();

            $approval = $this->service->approve(
                $approval,
                $request->kodeEsign
            );

            return redirect()
                ->route(
                    'newlplpo.approval.verify',
                    $approval->verificationToken
                )
                ->with(
                    'success',
                    'LPLPO berhasil disetujui.'
                );

        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memproses approval.'
                )
                ->withInput();
        }
    }


    /**
     * Reject LPLPO.
     */

public function reject(
    Request $request,
    string $token
) {
    $request->validate([
        'rejectedReason' => [
            'required',
            'string',
            'min:5',
            'max:1000',
        ],
    ], [
        'rejectedReason.required' =>
            'Alasan penolakan wajib diisi.',

        'rejectedReason.min' =>
            'Alasan penolakan minimal 5 karakter.',

        'rejectedReason.max' =>
            'Alasan penolakan maksimal 1000 karakter.',
    ]);

    try {

        $approval = InfoLinkApproval::where(
            'approvalToken',
            $token
        )->firstOrFail();

        $approval = $this->service->reject(
            $approval,
            $request->rejectedReason
        );

        return view(
            'newlplpo.approval.rejected',
            [
                'approval' => $approval,
                'report' => $approval->report,
                'kapus' => $approval->kapus,
            ]
        );

    } catch (ValidationException $e) {

        return back()
            ->withErrors($e->errors())
            ->withInput();

    } catch (\Throwable $e) {

        report($e);

        return back()
            ->with(
                'error',
                'Terjadi kesalahan saat memproses penolakan.'
            )
            ->withInput();
    }
}




    /**
     * Halaman verifikasi approval.
     *
     * QR akan diarahkan ke route ini.
     */
    public function verify(string $token)
    {
        $approval = InfoLinkApproval::with([
            'report',
            'kapus',
        ])
        ->where('verificationToken', $token)
        ->where('status', 'approved')
        ->firstOrFail();



        return view(
            'newlplpo.approval.verify',
            [
                'approval' => $approval,
                'report' => $approval->report,
                'kapus' => $approval->kapus,
            ]
        );
    }




}

