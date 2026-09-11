<?php
namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Models\NewLplpo\InfoKapus;
use App\Services\NewLplpo\InfoKapusService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InfoKapusController extends Controller
{
    protected InfoKapusService $service;

    public function __construct(
        InfoKapusService $service
    ) {
        $this->service = $service;
    }

    /**
     * =========================================================
     * INDEX
     * =========================================================
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        /**
         * Hanya group 3.
         */
        if ((int) $user->groupid !== 3) {
            abort(403);
        }

        $kapus = $this->service->getByUser($user);

        return view(
            'newlplpo.infokapus.index',
            compact('kapus')
        );
    }

    /**
     * =========================================================
     * CREATE
     * =========================================================
     */
    public function create()
    {
        $user = auth()->user();

        if ((int) $user->groupid !== 3) {
            abort(403);
        }

        /**
         * Jangan izinkan create jika sudah ada.
         */
        $kapus = $this->service->getByUser($user);

        if ($kapus) {
            return redirect()
                ->route('newlplpo.infokapus.index')
                ->with(
                    'error',
                    'Data Kepala Puskesmas sudah tersedia.'
                );
        }

        return view(
            'newlplpo.infokapus.create'
        );
    }

    /**
     * =========================================================
     * STORE
     * =========================================================
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ((int) $user->groupid !== 3) {
            abort(403);
        }

        $validated = $request->validate([
            'namaKapus' => [
                'required',
                'string',
                'max:150',
            ],

            'nipKapus' => [
                'required',
                'string',
                'max:50',
            ],

            'emailKapus' => [
                'required',
                'email',
                'max:150',
            ],
        ], [

            'namaKapus.required' =>
                'Nama Kepala Puskesmas wajib diisi.',

            'nipKapus.required' =>
                'NIP Kepala Puskesmas wajib diisi.',

            'emailKapus.required' =>
                'Email Kepala Puskesmas wajib diisi.',

            'emailKapus.email' =>
                'Format email tidak valid.',
        ]);

        try {

            $result =
                $this->service->create(
                    $user,
                    $validated
                );

            /**
             * Kirim kode E-Sign ke email Kapus.
             */
            $this->service->sendEsignEmail(
                $result['kapus'],
                $result['kodeEsign']
            );

            return redirect()
                ->route(
                    'newlplpo.infokapus.index'
                )
                ->with(
                    'success',
                    'Data Kepala Puskesmas berhasil disimpan. Kode E-Sign telah dikirim ke email Kapus.'
                );

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                )
                ->withInput();

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Data Kepala Puskesmas gagal disimpan.'
                )
                ->withInput();
        }
    }

    /**
     * =========================================================
     * EDIT
     * =========================================================
     */
    public function edit(InfoKapus $kapus)
    {
        $user = auth()->user();

        if ((int) $user->groupid !== 3) {
            abort(403);
        }

        if (
            $kapus->kodeFaskes !==
            $user->kodeFaskes
        ) {
            abort(403);
        }

        return view(
            'newlplpo.infokapus.edit',
            compact('kapus')
        );
    }

    /**
     * =========================================================
     * UPDATE
     * =========================================================
     */
    public function update(
        Request $request,
        InfoKapus $kapus
    ) {
        $user = auth()->user();

        if ((int) $user->groupid !== 3) {
            abort(403);
        }

        $validated = $request->validate([
            'namaKapus' => [
                'required',
                'string',
                'max:150',
            ],

            'nipKapus' => [
                'required',
                'string',
                'max:50',
            ],

            'emailKapus' => [
                'required',
                'email',
                'max:150',
            ],
        ], [

            'namaKapus.required' =>
                'Nama Kepala Puskesmas wajib diisi.',

            'nipKapus.required' =>
                'NIP Kepala Puskesmas wajib diisi.',

            'emailKapus.required' =>
                'Email Kepala Puskesmas wajib diisi.',

            'emailKapus.email' =>
                'Format email tidak valid.',
        ]);

        try {

            $this->service->update(
                $user,
                $kapus,
                $validated
            );

            return redirect()
                ->route(
                    'newlplpo.infokapus.index'
                )
                ->with(
                    'success',
                    'Data Kepala Puskesmas berhasil diperbarui.'
                );

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                )
                ->withInput();

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Data Kepala Puskesmas gagal diperbarui.'
                )
                ->withInput();
        }
    }

    /**
     * =========================================================
     * RESET E-SIGN
     * =========================================================
     */
    public function resetEsign(
        InfoKapus $kapus
    ) {
        $user = auth()->user();

        if ((int) $user->groupid !== 3) {
            abort(403);
        }

        try {

            $result =
                $this->service->resetEsign(
                    $user,
                    $kapus
                );

            /**
             * Kirim kode baru.
             */
            $this->service->sendEsignEmail(
                $result['kapus'],
                $result['kodeEsign']
            );

            return response()->json([
                'success' => true,
                'message' =>
                    'Kode E-Sign baru berhasil dibuat dan dikirim ke email Kapus.',
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' =>
                    $e->getMessage(),
                'errors' =>
                    $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Kode E-Sign gagal direset.',
            ], 500);
        }
    }

    /**
     * =========================================================
     * DELETE
     * =========================================================
     */
    public function destroy(
        InfoKapus $kapus
    ) {
        $user = auth()->user();

        if ((int) $user->groupid !== 3) {
            abort(403);
        }

        try {

            $this->service->delete(
                $user,
                $kapus
            );

            return redirect()
                ->route(
                    'newlplpo.infokapus.index'
                )
                ->with(
                    'success',
                    'Data Kepala Puskesmas berhasil dihapus.'
                );

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Data Kepala Puskesmas gagal dihapus.'
                );
        }
    }
}