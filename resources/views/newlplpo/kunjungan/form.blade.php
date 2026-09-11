@extends('newlplpo.layouts.master')

@section('title', 'Input Kunjungan LPLPO')

@section('content')

@php
    /*
     * Data yang digunakan untuk mengisi form.
     *
     * Prioritas:
     * 1. Data kunjungan report saat ini
     * 2. Data template dari report lain
     * 3. Nilai default 0
     */
    $formKunjungan = $kunjungan ?? $kunjunganTemplate ?? null;

    /*
     * Apakah form menggunakan template?
     */
    $isTemplate =
        !$kunjungan &&
        $kunjunganTemplate;
@endphp


<div class="card shadow-sm border-0">

    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">

            <i class="bi bi-people-fill"></i>

            Laporan Kunjungan

        </h4>

    </div>


    <div class="card-body">


        {{-- ================================================== --}}
        {{-- INFORMASI LAPORAN --}}
        {{-- ================================================== --}}

        <div class="alert alert-info">

            <strong>Laporan:</strong>
            {{ $report->nomor_lplpo }}

            <br>

            <strong>Faskes:</strong>
            {{ $report->nama_faskes }}

            <br>

            <strong>Periode:</strong>
            {{ $report->bulan }}/{{ $report->tahun }}

        </div>


        {{-- ================================================== --}}
        {{-- INFORMASI TEMPLATE --}}
        {{-- ================================================== --}}

        @if($isTemplate)

            <div class="alert alert-warning">

                <i class="bi bi-info-circle me-1"></i>

                Data kunjungan pada form ini
                <strong>diisi otomatis</strong>
                berdasarkan data kunjungan pada laporan lain
                dengan faskes, bulan, dan tahun yang sama.

                <br>

                Silakan periksa dan sesuaikan data sebelum
                menyimpan.

            </div>

        @endif


        {{-- ================================================== --}}
        {{-- FORM --}}
        {{-- ================================================== --}}

        <form
            method="POST"
            action="{{
                $kunjungan
                    ? route(
                        'newlplpo.kunjungan.update',
                        $report->id
                    )
                    : route(
                        'newlplpo.kunjungan.store',
                        $report->id
                    )
            }}"
            id="formKunjungan"
        >

            @csrf


            {{-- ================================================= --}}
            {{-- UPDATE METHOD --}}
            {{-- ================================================= --}}

            @if($kunjungan)

                @method('PUT')

            @endif


            {{-- ================================================= --}}
            {{-- KATEGORI KUNJUNGAN --}}
            {{-- ================================================= --}}

            <div class="card mb-3">

                <div class="card-header bg-success text-white">

                    <strong>
                        Laporan Kunjungan
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- JKN --}}
                        <div class="col-md-4">

                            <label
                                for="kunjungan_jkn"
                                class="form-label"
                            >
                                JKN
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_jkn"
                                id="kunjungan_jkn"
                                class="form-control kunjungan-kategori"
                                value="{{ old(
                                    'kunjungan_jkn',
                                    $formKunjungan->kunjungan_jkn ?? 0
                                ) }}"
                                required
                            >

                        </div>


                        {{-- TUNAI --}}
                        <div class="col-md-4">

                            <label
                                for="kunjungan_tunai"
                                class="form-label"
                            >
                                Tunai
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_tunai"
                                id="kunjungan_tunai"
                                class="form-control kunjungan-kategori"
                                value="{{ old(
                                    'kunjungan_tunai',
                                    $formKunjungan->kunjungan_tunai ?? 0
                                ) }}"
                                required
                            >

                        </div>


                        {{-- GRATIS --}}
                        <div class="col-md-4">

                            <label
                                for="kunjungan_gratis"
                                class="form-label"
                            >
                                Gratis
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_gratis"
                                id="kunjungan_gratis"
                                class="form-control kunjungan-kategori"
                                value="{{ old(
                                    'kunjungan_gratis',
                                    $formKunjungan->kunjungan_gratis ?? 0
                                ) }}"
                                required
                            >

                        </div>

                    </div>


                    <div class="row mt-3">

                        <div class="col-md-4 offset-md-4">

                            <label
                                for="total_kunjungan_perkategori"
                                class="form-label fw-bold"
                            >
                                Total
                            </label>

                            <input
                                type="number"
                                id="total_kunjungan_perkategori"
                                class="form-control bg-light fw-bold"
                                value="{{ $formKunjungan->total_kunjungan_perkategori ?? 0 }}"
                                readonly
                            >

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- GENDER --}}
            {{-- ================================================= --}}

            <div class="card mb-3">

                <div class="card-header bg-primary text-white">

                    <strong>
                        Kunjungan Berdasarkan Gender
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- ANAK --}}
                        <div class="col-md-6">

                            <label
                                for="kunjungan_anak"
                                class="form-label"
                            >
                                Anak
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_anak"
                                id="kunjungan_anak"
                                class="form-control kunjungan-gender"
                                value="{{ old(
                                    'kunjungan_anak',
                                    $formKunjungan->kunjungan_anak ?? 0
                                ) }}"
                                required
                            >

                        </div>


                        {{-- DEWASA --}}
                        <div class="col-md-6">

                            <label
                                for="kunjungan_dewasa"
                                class="form-label"
                            >
                                Dewasa
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_dewasa"
                                id="kunjungan_dewasa"
                                class="form-control kunjungan-gender"
                                value="{{ old(
                                    'kunjungan_dewasa',
                                    $formKunjungan->kunjungan_dewasa ?? 0
                                ) }}"
                                required
                            >

                        </div>

                    </div>


                    <div class="row mt-3">

                        <div class="col-md-6 offset-md-3">

                            <label
                                for="total_kunjungan_pergender"
                                class="form-label fw-bold"
                            >
                                Total
                            </label>

                            <input
                                type="number"
                                id="total_kunjungan_pergender"
                                class="form-control bg-light fw-bold"
                                value="{{ $formKunjungan->total_kunjungan_pergender ?? 0 }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- WARNING --}}
                    <div
                        id="warningTotal"
                        class="alert alert-danger mt-3 d-none"
                    >

                        Total berdasarkan gender harus sama
                        dengan total berdasarkan kategori.

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- JENIS PELAYANAN --}}
            {{-- ================================================= --}}

            <div class="card mb-3">

                <div class="card-header bg-secondary text-white">

                    <strong>
                        Jenis Pelayanan
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- LAB --}}
                        <div class="col-md-4 mb-3">

                            <label
                                for="kunjungan_lab"
                                class="form-label"
                            >
                                Lab
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_lab"
                                id="kunjungan_lab"
                                class="form-control"
                                value="{{ old(
                                    'kunjungan_lab',
                                    $formKunjungan->kunjungan_lab ?? 0
                                ) }}"
                                required
                            >

                        </div>


                        {{-- GIGI --}}
                        <div class="col-md-4 mb-3">

                            <label
                                for="kunjungan_gigi"
                                class="form-label"
                            >
                                Gigi
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_gigi"
                                id="kunjungan_gigi"
                                class="form-control"
                                value="{{ old(
                                    'kunjungan_gigi',
                                    $formKunjungan->kunjungan_gigi ?? 0
                                ) }}"
                                required
                            >

                        </div>


                        {{-- PONED --}}
                        <div class="col-md-4 mb-3">

                            <label
                                for="kunjungan_poned"
                                class="form-label"
                            >
                                PONED
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_poned"
                                id="kunjungan_poned"
                                class="form-control"
                                value="{{ old(
                                    'kunjungan_poned',
                                    $formKunjungan->kunjungan_poned ?? 0
                                ) }}"
                                required
                            >

                        </div>


                        {{-- RAWAT INAP --}}
                        <div class="col-md-6">

                            <label
                                for="kunjungan_rawatinap"
                                class="form-label"
                            >
                                Rawat Inap
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_rawatinap"
                                id="kunjungan_rawatinap"
                                class="form-control"
                                value="{{ old(
                                    'kunjungan_rawatinap',
                                    $formKunjungan->kunjungan_rawatinap ?? 0
                                ) }}"
                                required
                            >

                        </div>


                        {{-- RAWAT JALAN --}}
                        <div class="col-md-6">

                            <label
                                for="kunjungan_rawatjalan"
                                class="form-label"
                            >
                                Rawat Jalan
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_rawatjalan"
                                id="kunjungan_rawatjalan"
                                class="form-control"
                                value="{{ old(
                                    'kunjungan_rawatjalan',
                                    $formKunjungan->kunjungan_rawatjalan ?? 0
                                ) }}"
                                required
                            >

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- BUTTON --}}
            {{-- ================================================= --}}

            <div class="d-flex justify-content-between">

                <a
                    href="{{ route(
                        'newlplpo.edit',
                        $report->id
                    ) }}"
                    class="btn btn-secondary"
                >

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>


                <button
                    type="submit"
                    id="btnSimpanKunjungan"
                    class="btn btn-success"
                >

                    <i class="bi bi-save"></i>

                    {{ $kunjungan
                        ? 'Update Kunjungan'
                        : 'Simpan Kunjungan'
                    }}

                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('script')

<script src="{{ mix('js/newlplpo/kunjungan.js') }}"></script>

@endpush
