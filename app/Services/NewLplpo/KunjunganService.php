<?php

namespace App\Services\NewLplpo;

use App\Models\NewLplpo\Kunjungan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KunjunganService
{
    public function create(int $reportId, array $data)
    {
        return DB::transaction(function () use ($reportId, $data) {

            $jkn = (int) ($data['kunjungan_jkn'] ?? 0);
            $tunai = (int) ($data['kunjungan_tunai'] ?? 0);
            $gratis = (int) ($data['kunjungan_gratis'] ?? 0);

            $anak = (int) ($data['kunjungan_anak'] ?? 0);
            $dewasa = (int) ($data['kunjungan_dewasa'] ?? 0);

            $totalKategori = $jkn + $tunai + $gratis;
            $totalGender = $anak + $dewasa;

            if ($totalKategori !== $totalGender) {
                throw ValidationException::withMessages([
                    'kunjungan_anak' =>
                        'Total kunjungan berdasarkan gender harus sama dengan total kunjungan berdasarkan kategori.'
                ]);
            }

            return Kunjungan::create([

                'report_id' => $reportId,

                'kunjungan_jkn' => $jkn,
                'kunjungan_tunai' => $tunai,
                'kunjungan_gratis' => $gratis,

                'total_kunjungan_perkategori' => $totalKategori,

                'kunjungan_anak' => $anak,
                'kunjungan_dewasa' => $dewasa,

                'total_kunjungan_pergender' => $totalGender,

                'kunjungan_lab' =>
                    (int) ($data['kunjungan_lab'] ?? 0),

                'kunjungan_gigi' =>
                    (int) ($data['kunjungan_gigi'] ?? 0),

                'kunjungan_poned' =>
                    (int) ($data['kunjungan_poned'] ?? 0),

                'kunjungan_rawatinap' =>
                    (int) ($data['kunjungan_rawatinap'] ?? 0),

                'kunjungan_rawatjalan' =>
                    (int) ($data['kunjungan_rawatjalan'] ?? 0),

            ]);
        });
    }


    public function update(Kunjungan $kunjungan, array $data)
    {
        return DB::transaction(function () use ($kunjungan, $data) {

            $jkn = (int) ($data['kunjungan_jkn'] ?? 0);
            $tunai = (int) ($data['kunjungan_tunai'] ?? 0);
            $gratis = (int) ($data['kunjungan_gratis'] ?? 0);

            $anak = (int) ($data['kunjungan_anak'] ?? 0);
            $dewasa = (int) ($data['kunjungan_dewasa'] ?? 0);

            $totalKategori = $jkn + $tunai + $gratis;
            $totalGender = $anak + $dewasa;

            if ($totalKategori !== $totalGender) {
                throw ValidationException::withMessages([
                    'kunjungan_anak' =>
                        'Total kunjungan berdasarkan gender harus sama dengan total kunjungan berdasarkan kategori.'
                ]);
            }

            $kunjungan->update([

                'kunjungan_jkn' => $jkn,
                'kunjungan_tunai' => $tunai,
                'kunjungan_gratis' => $gratis,

                'total_kunjungan_perkategori' => $totalKategori,

                'kunjungan_anak' => $anak,
                'kunjungan_dewasa' => $dewasa,

                'total_kunjungan_pergender' => $totalGender,

                'kunjungan_lab' =>
                    (int) ($data['kunjungan_lab'] ?? 0),

                'kunjungan_gigi' =>
                    (int) ($data['kunjungan_gigi'] ?? 0),

                'kunjungan_poned' =>
                    (int) ($data['kunjungan_poned'] ?? 0),

                'kunjungan_rawatinap' =>
                    (int) ($data['kunjungan_rawatinap'] ?? 0),

                'kunjungan_rawatjalan' =>
                    (int) ($data['kunjungan_rawatjalan'] ?? 0),

            ]);

            return $kunjungan->fresh();
        });
    }


    public function delete(Kunjungan $kunjungan)
    {
        return $kunjungan->delete();
    }
}
