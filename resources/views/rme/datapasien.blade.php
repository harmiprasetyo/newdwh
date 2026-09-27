@extends('layouts.mainrme')

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

    </section>

</div>

@endsection
