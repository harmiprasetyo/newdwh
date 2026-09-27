@extends('layouts.mainrme')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/rme/detailpasien.css') }}">
@endpush
@section('container')

<div class="rme-page">

    {{-- =========================================================
         PATIENT PROFILE
    ========================================================== --}}
    <div class="patient-card">

        <div class="patient-header">

            <div class="patient-avatar">
                <i class="bi bi-person-vcard"></i>
            </div>

            <div>
                <h2 class="patient-name">
                    {{ $dt['PATIENTID']['name'] }}
                </h2>

                <div class="patient-id">
                    ID Pasien :
                    <strong>{{ $dt['PATIENTID']['patient_id'] }}</strong>
                </div>
            </div>

        </div>


        <div class="patient-body">

            {{-- Kolom kiri --}}
            <div class="patient-column">

                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-person"></i>
                        Nama Pasien
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['PATIENTID']['name'] }}
                    </div>

                </div>


                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-card-text"></i>
                        NIK
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['PATIENTID']['nik'] }}
                    </div>

                </div>


                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-calendar3"></i>
                        Tanggal Lahir
                    </div>

                    <div class="patient-info-value">
                        {{ \Carbon\Carbon::parse($dt['PATIENTID']['birth_date'])->format('d M Y') }}
                    </div>

                </div>

            </div>


            {{-- Kolom kanan --}}
            <div class="patient-column">

                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-gender-ambiguous"></i>
                        Jenis Kelamin
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['PATIENTID']['gender'] }}
                    </div>

                </div>


                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-telephone"></i>
                        No. Telp
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['PATIENTID']['phone'] }}
                    </div>

                </div>


                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-hospital"></i>
                        Fasilitas Kesehatan
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['ENCOUNTER'][0]['service_provider_name'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Informasi kunjungan --}}
        <div class="patient-body">

            <div class="patient-column">

                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-calendar-check"></i>
                        Tanggal Kunjungan
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['ENCOUNTER'][0]['start'] }}
                    </div>

                </div>

            </div>


            <div class="patient-column">

                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-heart-pulse"></i>
                        Status G/P/A
                    </div>

                    <div class="patient-info-value">

                        G : {{ $dt['ANC']['gravida'] }}

                        <span class="mx-2">|</span>

                        P : {{ $dt['ANC']['parity'] }}

                        <span class="mx-2">|</span>

                        A : {{ $dt['ANC']['abortions'] }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         DETAIL RME
    ========================================================== --}}
    <div class="rme-content-card">


        {{-- Header --}}
        <div class="rme-content-header">

            <div>

                <h3 class="rme-content-title">
                    Detail Rekam Medis Elektronik
                </h3>

                <div class="rme-content-subtitle">
                    Informasi pelayanan dan pemeriksaan pasien
                </div>

            </div>


            <div class="rme-content-badge">

                <i class="bi bi-file-medical"></i>

                RME Pasien

            </div>

        </div>


        {{-- =====================================================
             TAB NAVIGATION
        ====================================================== --}}

        @include('partials.tabpasien')

        @include('rme.tabs.vital')

@include('rme.tabs.layanan-umum')

@include('rme.tabs.anc')

@include('rme.tabs.inc')

@include('rme.tabs.pnc')

@include('rme.tabs.neonatus')

@include('rme.tabs.imunisasi')
















    </div>

</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/rme/detailpasien.js') }}"></script>

@endpush
