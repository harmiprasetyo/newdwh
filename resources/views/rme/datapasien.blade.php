@extends('layouts.mainrme')

@push('styles')
<style>
/* Header Resume 12T */
.resume12t-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 16px 18px;
    margin-bottom: 15px;
    background: #f5f9ff;
    border: 1px solid #dce8f7;
    border-radius: 12px;
}

.resume12t-heading-icon {
    display: flex;
    flex: 0 0 44px;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #e2edff;
    color: #2563a6;
    font-size: 21px;
}

.resume12t-heading h5 {
    margin: 0 0 4px;
    color: #20334d;
    font-size: 16px;
    font-weight: 700;
}

.resume12t-heading p {
    margin: 0;
    color: #718096;
    font-size: 12px;
    line-height: 1.6;
}

/* Toolbar */
.resume12t-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 12px;
    color: #64748b;
    font-size: 12px;
}

.resume12t-total {
    display: flex;
    align-items: center;
    gap: 7px;
}

.resume12t-total i {
    color: #2563a6;
    font-size: 15px;
}

.resume12t-total strong {
    color: #334155;
}

.resume12t-hint {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Wrapper tabel */
.resume12t-table-wrapper {
    width: 100%;
    overflow: hidden;
    border: 1px solid #dfe7f0;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 3px 12px rgba(31, 55, 85, .04);
}

.resume12t-table-wrapper .table-responsive {
    width: 100%;
    max-height: 68vh;
    margin: 0;
    overflow: auto;
    scrollbar-width: thin;
    scrollbar-color: #b8c8dc #f1f5f9;
}

.resume12t-table-wrapper .table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.resume12t-table-wrapper .table-responsive::-webkit-scrollbar-thumb {
    border-radius: 8px;
    background: #b8c8dc;
}

/* Tabel */
.resume12t-table {
    width: max-content;
    min-width: 2200px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: #334155;
    font-size: 12px;
    line-height: 1.5;
}

.resume12t-table th,
.resume12t-table td {
    min-width: 95px;
    max-width: 210px;
    padding: 10px 12px;
    border-right: 1px solid #e5eaf1;
    border-bottom: 1px solid #e5eaf1;
    vertical-align: middle;
    overflow-wrap: anywhere;
}

.resume12t-table th:last-child,
.resume12t-table td:last-child {
    border-right: 0;
}

.resume12t-table tbody tr:last-child td {
    border-bottom: 0;
}

/* Header dua tingkat */
.resume12t-table thead th {
    position: sticky;
    z-index: 3;
    background: #edf4fc;
    color: #294b73;
    font-weight: 700;
    text-align: center;
    vertical-align: middle;
    white-space: normal;
}

.resume12t-table thead tr:first-child th {
    top: 0;
    height: 43px;
    background: #dceafb;
    color: #23466e;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .15px;
}

.resume12t-table thead tr:nth-child(2) th {
    top: 43px;
    font-size: 11px;
}

.resume12t-table thead tr:first-child th[rowspan="2"] {
    top: 0;
    z-index: 4;
}

/* Kelompok header */
.resume12t-table .group-antropometri {
    background: #dceafb;
}

.resume12t-table .group-tekanan {
    background: #d9f0ee;
    color: #17645e;
}

.resume12t-table .group-diagnosis {
    background: #f0e7ff;
    color: #6741a5;
}

/* Nomor dan tanggal */
.resume12t-table .col-no {
    min-width: 48px;
    width: 48px;
    text-align: center;
}

.resume12t-table .col-tanggal {
    min-width: 125px;
    white-space: nowrap;
}

.resume12t-table tbody .col-tanggal {
    color: #475569;
    font-weight: 600;
}

/* Baris data */
.resume12t-table tbody td {
    background: #fff;
}

.resume12t-table tbody tr:nth-child(even) td {
    background: #f8fafc;
}

.resume12t-table tbody tr:hover td {
    background: #edf6ff;
}

/* Nilai angka */
.resume12t-table tbody td:nth-child(3),
.resume12t-table tbody td:nth-child(4),
.resume12t-table tbody td:nth-child(5),
.resume12t-table tbody td:nth-child(6),
.resume12t-table tbody td:nth-child(7),
.resume12t-table tbody td:nth-child(8),
.resume12t-table tbody td:nth-child(9),
.resume12t-table tbody td:nth-child(14),
.resume12t-table tbody td:nth-child(16) {
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
}

/* Kondisi data kosong */
.resume12t-table .resume12t-empty {
    padding: 35px 20px;
    background: #fbfdff;
    color: #64748b;
    text-align: center;
}

.resume12t-empty i {
    display: block;
    margin-bottom: 8px;
    font-size: 28px;
    color: #94a3b8;
}

.resume12t-empty strong,
.resume12t-empty span {
    display: block;
}

.resume12t-empty strong {
    margin-bottom: 5px;
    color: #475569;
    font-size: 13px;
}

.resume12t-empty span {
    font-size: 12px;
}

@media (max-width: 768px) {
    .resume12t-heading {
        align-items: flex-start;
        padding: 13px;
    }

    .resume12t-heading h5 {
        font-size: 14px;
    }

    .resume12t-heading p {
        font-size: 11px;
    }

    .resume12t-hint {
        font-size: 11px;
    }

    .resume12t-table {
        min-width: 2000px;
        font-size: 11px;
    }

    .resume12t-table th,
    .resume12t-table td {
        padding: 8px 9px;
    }

    .resume12t-table thead tr:first-child th {
        height: 40px;
    }

    .resume12t-table thead tr:nth-child(2) th {
        top: 40px;
    }
}
</style>
@endpush

@section('container')

<div class="rme-page">

    {{-- =========================================================
        PATIENT PROFILE
    ========================================================== --}}
    <section class="patient-card">

        <div class="patient-header">
            <div class="patient-avatar">
                <i class="bi bi-person-fill"></i>
            </div>

            <div class="patient-heading">
                <div class="patient-name">
                    {{ $dt['PATIENTID']['name'] ?? '-' }}
                </div>

                <div class="patient-id">
                    ID Pasien:
                    <strong>{{ $dt['PATIENTID']['patient_id'] ?? '-' }}</strong>
                </div>
            </div>
        </div>

        <div class="patient-body">

    {{-- KOLOM KIRI --}}
    <div class="patient-column">

        <div class="patient-info-item">
            <div class="patient-info-label">
                <i class="bi bi-person-vcard"></i>
                NIK
            </div>
            <div class="patient-info-value">
                {{ $dt['PATIENTID']['nik'] ?? '-' }}
            </div>
        </div>

        <div class="patient-info-item">
            <div class="patient-info-label">
                <i class="bi bi-calendar3"></i>
                Tanggal Lahir
            </div>
            <div class="patient-info-value">
                @if(!empty($dt['PATIENTID']['birth_date']))
                    {{ \Carbon\Carbon::parse($dt['PATIENTID']['birth_date'])->format('d M Y') }}
                @else
                    -
                @endif
            </div>
        </div>

        <div class="patient-info-item">
            <div class="patient-info-label">
                <i class="bi bi-gender-ambiguous"></i>
                Jenis Kelamin
            </div>
            <div class="patient-info-value">
                @if(($dt['PATIENTID']['gender'] ?? '') === 'female')
                    Perempuan
                @elseif(($dt['PATIENTID']['gender'] ?? '') === 'male')
                    Laki-laki
                @else
                    {{ $dt['PATIENTID']['gender'] ?? '-' }}
                @endif
            </div>
        </div>

        <div class="patient-info-item">
            <div class="patient-info-label">
                <i class="bi bi-telephone"></i>
                No. Telepon
            </div>
            <div class="patient-info-value">
                {{ $dt['PATIENTID']['phone'] ?? '-' }}
            </div>
        </div>

    </div>


    {{-- KOLOM KANAN --}}
    <div class="patient-column">

        <div class="patient-info-item">
            <div class="patient-info-label">
                <i class="bi bi-geo-alt"></i>
                Alamat
            </div>
            <div class="patient-info-value">
                {{ $dt['PATIENTID']['address'] ?? '-' }}
            </div>
        </div>

        <div class="patient-info-item">
            <div class="patient-info-label">
                <i class="bi bi-pin-map"></i>
                Kecamatan
            </div>
            <div class="patient-info-value">
                {{ $dt['PATIENTID']['district']['name'] ?? '-' }}
            </div>
        </div>

        <div class="patient-info-item">
            <div class="patient-info-label">
                <i class="bi bi-buildings"></i>
                Kab/Kota
            </div>
            <div class="patient-info-value">
                {{ $dt['PATIENTID']['city']['name'] ?? '-' }}
            </div>
        </div>

        <div class="patient-info-item">
            <div class="patient-info-label">
                <i class="bi bi-map"></i>
                Provinsi
            </div>
            <div class="patient-info-value">
                {{ $dt['PATIENTID']['province']['name'] ?? '-' }}
            </div>
        </div>

    </div>

</div>
    </section>


    {{-- =========================================================
        CONTENT
    ========================================================== --}}
    <section class="rme-content-card">

        <div class="rme-content-header">

            <div>
                <div class="rme-content-title">
                    <i class="bi bi-clipboard2-pulse"></i>
                    Riwayat Rekam Medis
                </div>

                <div class="rme-content-subtitle">
                    Daftar riwayat kunjungan dan layanan pasien
                </div>
            </div>

            <div class="rme-content-badge">
                <i class="bi bi-clock-history"></i>
                Riwayat Kunjungan
            </div>

        </div>


        {{-- =====================================================
            TAB
        ====================================================== --}}
        <div class="rme-tabs">

            <button
                type="button"
                class="rme-tab active"
                id="tab1"
                data-target="history">
                <i class="bi bi-calendar2-week"></i>
                Riwayat Kunjungan
            </button>

            @if(($dt['PATIENTID']['gender'] ?? '') === 'female')
                <button
                    type="button"
                    class="rme-tab"
                    id="tab2"
                    data-target="kunjunganANC">
                    <i class="bi bi-heart-pulse"></i>
                    Resume Layanan ANC
                </button>

                <button
    type="button"
    class="rme-tab"
    id="tab3"
    data-target="resumeKunjungan12T">
    <i class="bi bi-clipboard2-data"></i>
    Resume Kunjungan 12T
</button>

            @endif

        </div>


        {{-- =====================================================
            HISTORY
        ====================================================== --}}
        <div id="history" class="rme-tab-content">

            <div class="table-responsive">

                <table
                    class="table rme-data-table"
                    id="riwayatKunjungan">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Jenis Kunjungan</th>
                            <th>Fasilitas Kesehatan</th>
                            <th>Unit / Poli</th>
                            <th>Jenis Layanan</th>
                            <th>Dokter</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    @if(isset($dt['ENCOUNTER']) && count($dt['ENCOUNTER']) > 0)

                        @foreach($dt['ENCOUNTER'] as $k => $encount)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <div class="visit-date">
                                        <i class="bi bi-calendar-event"></i>
                                        {{ $encount['start'] ?? '-' }}
                                    </div>
                                </td>

                                <td>

                                    @if(($encount['class_code'] ?? '') === 'AMB')

                                        <span class="visit-badge visit-outpatient">
                                            <i class="bi bi-person-walking"></i>
                                            Rawat Jalan
                                        </span>

                                    @else

                                        <span class="visit-badge visit-inpatient">
                                            <i class="bi bi-hospital"></i>
                                            Rawat Inap
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    <div class="table-main-text">
                                        {{ $encount['service_provider_name'] ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="table-main-text">
                                        {{ $encount['location_name'] ?? '-' }}
                                    </div>
                                </td>

                                <td>

                                    <div class="service-name">
                                        {{ $dt['SUBENC'][$k]['jeniskunjungan_name'] ?? '-' }}
                                    </div>

                                    @if(!empty($dt['SUBENC'][$k]['kunjunganANC']))

                                        <div class="service-subname">
                                            {{ $dt['SUBENC'][$k]['kunjunganANC'] }}
                                        </div>

                                    @endif

                                </td>

                                <td>
                                    <div class="doctor-name">
                                        <i class="bi bi-person-badge"></i>
                                        {{ $encount['practitioner_name'] ?? '-' }}
                                    </div>
                                </td>

                                <td class="text-center">

                                    <button
                                        type="button"
                                        class="btn-detail"
                                        data-patient-id="{{ $dt['PATIENTID']['patient_id'] ?? '' }}"
                                        data-encounter-id="{{ $encount['encounter_id'] ?? '' }}">

                                        <i class="bi bi-eye"></i>
                                        Detail

                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    @endif

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            ANC
        ====================================================== --}}
        @if(($dt['PATIENTID']['gender'] ?? '') === 'female')

            <div
                id="kunjunganANC"
                class="rme-tab-content"
                style="display: none;">

                <div class="anc-summary">

                    <div class="anc-summary-item">

                        <div class="anc-summary-icon">
                            <i class="bi bi-1-circle"></i>
                        </div>

                        <div class="anc-summary-content">

                            <div class="anc-summary-label">
                                Kunjungan Trimester Pertama
                            </div>

                            <div class="anc-summary-value">
                                {{ $dt['trimester1'] ?? 0 }}
                                <span>kali</span>
                            </div>

                        </div>

                    </div>


                    <div class="anc-summary-item">

                        <div class="anc-summary-icon">
                            <i class="bi bi-2-circle"></i>
                        </div>

                        <div class="anc-summary-content">

                            <div class="anc-summary-label">
                                Kunjungan Trimester Kedua
                            </div>

                            <div class="anc-summary-value">
                                {{ $dt['trimester2'] ?? 0 }}
                                <span>kali</span>
                            </div>

                        </div>

                    </div>


                    <div class="anc-summary-item">

                        <div class="anc-summary-icon">
                            <i class="bi bi-3-circle"></i>
                        </div>

                        <div class="anc-summary-content">

                            <div class="anc-summary-label">
                                Kunjungan Trimester Ketiga
                            </div>

                            <div class="anc-summary-value">
                                {{ $dt['trimester3'] ?? 0 }}
                                <span>kali</span>
                            </div>

                        </div>

                    </div>


                    <div class="anc-summary-item">

                        <div class="anc-summary-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>

                        <div class="anc-summary-content">

                            <div class="anc-summary-label">
                                Pemeriksaan USG Trimester Pertama
                            </div>

                            <div class="anc-summary-value">
                                0
                                <span>kali</span>
                            </div>

                        </div>

                    </div>


                    <div class="anc-summary-item">

                        <div class="anc-summary-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>

                        <div class="anc-summary-content">

                            <div class="anc-summary-label">
                                Pemeriksaan USG Trimester Kedua
                            </div>

                            <div class="anc-summary-value">
                                0
                                <span>kali</span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif

      {{-- =====================================================
    RESUME KUNJUNGAN 12T
====================================================== --}}
@if(($dt['PATIENTID']['gender'] ?? '') === 'female')

    <div
        id="resumeKunjungan12T"
        class="rme-tab-content"
        style="display: none;">

        {{-- Header Resume 12T --}}
        <div class="resume12t-heading">
            <div class="resume12t-heading-icon">
                <i class="bi bi-clipboard2-pulse"></i>
            </div>

            <div>
                <h5>Resume Kunjungan 12T</h5>
                <p>
                    Ringkasan pemeriksaan kehamilan berdasarkan
                    riwayat kunjungan pasien.
                </p>
            </div>
        </div>

        {{-- Informasi tabel --}}
        <div class="resume12t-toolbar">
            <div class="resume12t-total">
                <i class="bi bi-journal-medical"></i>
                Total kunjungan:
                <strong>{{ count($dt['RESUME12T'] ?? []) }}</strong>
            </div>

            <div class="resume12t-hint">
                <i class="bi bi-arrows-angle-expand"></i>
                Geser ke kanan untuk melihat seluruh kolom
            </div>
        </div>

        {{-- Tabel Resume 12T --}}
        <div class="resume12t-table-wrapper">
            <div class="table-responsive">

                <table class="table resume12t-table">

                    <thead>
                        <tr>
                            <th rowspan="2" class="col-no">No.</th>
                            <th rowspan="2" class="col-tanggal">
                                Tanggal Kunjungan
                            </th>

                            <th colspan="2" class="group-antropometri">
                                Antropometri
                            </th>

                            <th colspan="2" class="group-tekanan">
                                Tekanan Darah
                            </th>

                            <th rowspan="2">TFU</th>
                            <th rowspan="2">LILA</th>
                            <th rowspan="2">DJJ</th>
                            <th rowspan="2">Presentasi Kepala</th>
                            <th rowspan="2">Imunisasi Tetanus</th>
                            <th rowspan="2">Imunisasi yang Diberikan</th>
                            <th rowspan="2">TTD/MMS</th>
                            <th rowspan="2">Hb</th>
                            <th rowspan="2">Urinalisis</th>
                            <th rowspan="2">GDS</th>
                            <th rowspan="2">Golongan Darah</th>
                            <th rowspan="2">Sifilis</th>
                            <th rowspan="2">HBsAg</th>
                            <th rowspan="2">Anti HIV</th>

                            <th colspan="2" class="group-diagnosis">
                                Diagnosis
                            </th>

                            <th rowspan="2">USG</th>
                            <th rowspan="2">Edukasi</th>
                            <th rowspan="2">Disabilitas</th>
                            <th rowspan="2">Kelas Ibu Hamil</th>
                        </tr>

                        <tr>
                            <th>Berat Badan</th>
                            <th>Tinggi Badan</th>
                            <th>Sistole</th>
                            <th>Diastole</th>
                            <th>Primer</th>
                            <th>Sekunder</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse(($dt['RESUME12T'] ?? []) as $resume)

                            <tr>
                                <td class="col-no">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="col-tanggal">
                                    @if(!empty($resume['tanggal_kunjungan']))
                                        {{ \Carbon\Carbon::parse($resume['tanggal_kunjungan'])->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- =====================================================
    HELPER FORMAT ANGKA RESUME KUNJUNGAN 12T
====================================================== --}}
@php
    $formatAngka = function ($nilai) {
        if ($nilai === null || $nilai === '') {
            return '-';
        }

        if (!is_numeric($nilai)) {
            return $nilai;
        }

        return rtrim(
            rtrim(number_format((float) $nilai, 2, '.', ''), '0'),
            '.'
        );
    };
@endphp


                                <td>{{ $formatAngka($resume['berat_badan'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['tinggi_badan'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['sistole'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['diastole'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['tinggi_fundus'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['lila'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['djj'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['presentasi_kepala'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['imunisasi_tetanus'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['imunisasi_diberikan'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['ttd_mms'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['hemoglobin'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['urinalisis'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['gula_darah'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['golongan_darah'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['sifilis'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['hbsag'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['anti_hiv'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['diagnosis_primer'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['diagnosis_sekunder'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['usg'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['edukasi'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['disabilitas'] ?? '-') }}</td>
                                <td>{{ $formatAngka($resume['kelas_ibu_hamil'] ?? '-') }}</td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="26" class="resume12t-empty">
                                    <i class="bi bi-clipboard2-x"></i>
                                    <strong>
                                        Belum ada data Resume Kunjungan 12T
                                    </strong>
                                    <span>
                                        Data pemeriksaan pasien belum tersedia.
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>

@endif


    </section>

</div>

@endsection
