<?php
namespace App\Services\NewLplpo;

use App\Mail\LplpoEsignMail;
use App\Models\NewLplpo\InfoKapus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class InfoKapusService
{
    /**
     * =========================================================
     * VALIDASI USER
     * =========================================================
     */

    protected function validateUser($user): void
    {
        if (!$user) {
            throw ValidationException::withMessages([
                'user' => 'User tidak ditemukan.'
            ]);
        }

        if ((int) $user->groupid !== 3) {
            throw ValidationException::withMessages([
                'user' => 'Anda tidak memiliki akses untuk mengelola data Kepala Puskesmas.'
            ]);
        }

        if (empty($user->kodeFaskes)) {
            throw ValidationException::withMessages([
                'user' => 'Kode Faskes user belum tersedia.'
            ]);
        }
    }

    /**
     * =========================================================
     * GET DATA KAPUS USER
     * =========================================================
     */
    public function getByUser($user): ?InfoKapus
    {
        $this->validateUser($user);

        return InfoKapus::where(
            'kodeFaskes',
            $user->kodeFaskes
        )->first();
    }

    /**
     * =========================================================
     * CREATE
     * =========================================================
     *
     * Return:
     *
     * [
     *     'kapus' => InfoKapus,
     *     'kodeEsign' => plaintext 8 digit
     * ]
     */
    public function create(
        $user,
        array $data
    ): array {

        $this->validateUser($user);

        return DB::transaction(function () use (
            $user,
            $data
        ) {

            /**
             * Satu Kapus untuk satu Faskes.
             */
            $existing = InfoKapus::where(
                'kodeFaskes',
                $user->kodeFaskes
            )->lockForUpdate()->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'kapus' =>
                        'Data Kepala Puskesmas untuk fasilitas kesehatan ini sudah tersedia.'
                ]);
            }

            /**
             * Generate kode E-Sign 8 digit.
             */
            $kodeEsign = InfoKapus::generateEsign();

            /**
             * Simpan data.
             */
            $kapus = new InfoKapus();

            $kapus->kodeFaskes =
                $user->kodeFaskes;

            $kapus->namaKapus =
                $data['namaKapus'];

            $kapus->nipKapus =
                $data['nipKapus'];

            $kapus->emailKapus =
                $data['emailKapus'];

            /**
             * IMPORTANT:
             * hanya HASH yang disimpan.
             */
            $kapus->setEsign($kodeEsign);

            $kapus->save();

            return [
                'kapus' => $kapus->fresh(),
                'kodeEsign' => $kodeEsign,
            ];
        });
    }

    /**
     * =========================================================
     * UPDATE
     * =========================================================
     *
     * E-Sign TIDAK berubah.
     */
    public function update(
        $user,
        InfoKapus $kapus,
        array $data
    ): InfoKapus {

        $this->validateUser($user);

        /**
         * Security:
         * pastikan record memang milik Faskes user.
         */
        if (
            $kapus->kodeFaskes !==
            $user->kodeFaskes
        ) {
            throw ValidationException::withMessages([
                'kapus' =>
                    'Anda tidak memiliki akses terhadap data ini.'
            ]);
        }

        $kapus->update([
            'namaKapus' =>
                $data['namaKapus'],

            'nipKapus' =>
                $data['nipKapus'],

            'emailKapus' =>
                $data['emailKapus'],
        ]);

        return $kapus->fresh();
    }

    /**
     * =========================================================
     * RESET E-SIGN
     * =========================================================
     *
     * Generate kode baru.
     *
     * Return plaintext hanya untuk dikirim email.
     */
    public function resetEsign(
        $user,
        InfoKapus $kapus
    ): array {

        $this->validateUser($user);

        /**
         * Security:
         * pastikan Kapus milik Faskes user.
         */
        if (
            $kapus->kodeFaskes !==
            $user->kodeFaskes
        ) {
            throw ValidationException::withMessages([
                'kapus' =>
                    'Anda tidak memiliki akses terhadap data ini.'
            ]);
        }

        return DB::transaction(function () use ($kapus) {

            /**
             * Generate kode baru.
             */
            $kodeEsign =
                InfoKapus::generateEsign();

            /**
             * Replace HASH lama.
             *
             * Kode lama otomatis tidak berlaku.
             */
            $kapus->setEsign($kodeEsign);

            $kapus->save();

            return [
                'kapus' => $kapus->fresh(),
                'kodeEsign' => $kodeEsign,
            ];
        });
    }

    /**
     * =========================================================
     * DELETE
     * =========================================================
     */
    public function delete(
        $user,
        InfoKapus $kapus
    ): void {

        $this->validateUser($user);

        if (
            $kapus->kodeFaskes !==
            $user->kodeFaskes
        ) {
            throw ValidationException::withMessages([
                'kapus' =>
                    'Anda tidak memiliki akses terhadap data ini.'
            ]);
        }

        /**
         * Jangan hapus Kapus yang sudah memiliki
         * histori approval.
         */
        if ($kapus->linkApprovals()->exists()) {
            throw ValidationException::withMessages([
                'kapus' =>
                    'Data Kepala Puskesmas tidak dapat dihapus karena sudah memiliki histori approval LPLPO.'
            ]);
        }

        $kapus->delete();
    }

    /**
     * =========================================================
     * SEND EMAIL E-SIGN
     * =========================================================
     */
    public function sendEsignEmail(
        InfoKapus $kapus,
        string $kodeEsign
    ): void {

        if (empty($kapus->emailKapus)) {
            throw ValidationException::withMessages([
                'emailKapus' =>
                    'Email Kepala Puskesmas belum tersedia.'
            ]);
        }

        Mail::to($kapus->emailKapus)
            ->send(
                new LplpoEsignMail(
                    $kapus,
                    $kodeEsign
                )
            );
    }
}