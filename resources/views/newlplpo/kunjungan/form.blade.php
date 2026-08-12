@extends('newlplpo.layouts.master')

@section('title', 'Input Kunjungan LPLPO')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">
            <i class="bi bi-people-fill"></i>
            Laporan Kunjungan
        </h4>

    </div>


    <div class="card-body">

        <div class="alert alert-info">

            <strong>Laporan:</strong>
            {{ $report->nomor_lplpo }}

            <br>

            <strong>Faskes:</strong>
            {{ $report->nama_faskes }}

        </div>


        <form
            method="POST"
            action="{{
                isset($kunjungan)
                    ? route('newlplpo.kunjungan.update', $report->id)
                    : route('newlplpo.kunjungan.store', $report->id)
            }}"
            id="formKunjungan">

            @csrf

            @if(isset($kunjungan))
                @method('PUT')
            @endif


            {{-- ================================================= --}}
            {{-- KATEGORI KUNJUNGAN --}}
            {{-- ================================================= --}}

            <div class="card mb-3">

                <div class="card-header bg-success text-white">
                    <strong>Laporan Kunjungan</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">

                            <label class="form-label">
                                JKN
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_jkn"
                                id="kunjungan_jkn"
                                class="form-control kunjungan-kategori"
                                value="{{ old('kunjungan_jkn', $kunjungan->kunjungan_jkn ?? 0) }}"
                                required>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Tunai
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_tunai"
                                id="kunjungan_tunai"
                                class="form-control kunjungan-kategori"
                                value="{{ old('kunjungan_tunai', $kunjungan->kunjungan_tunai ?? 0) }}"
                                required>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Gratis
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_gratis"
                                id="kunjungan_gratis"
                                class="form-control kunjungan-kategori"
                                value="{{ old('kunjungan_gratis', $kunjungan->kunjungan_gratis ?? 0) }}"
                                required>

                        </div>

                    </div>


                    <div class="row mt-3">

                        <div class="col-md-4 offset-md-4">

                            <label class="form-label fw-bold">
                                Total
                            </label>

                            <input
                                type="number"
                                id="total_kunjungan_perkategori"
                                class="form-control bg-light fw-bold"
                                value="{{ $kunjungan->total_kunjungan_perkategori ?? 0 }}"
                                readonly>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- GENDER --}}
            {{-- ================================================= --}}

            <div class="card mb-3">

                <div class="card-header bg-primary text-white">
                    <strong>Kunjungan Berdasarkan Gender</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <label class="form-label">
                                Anak
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_anak"
                                id="kunjungan_anak"
                                class="form-control kunjungan-gender"
                                value="{{ old('kunjungan_anak', $kunjungan->kunjungan_anak ?? 0) }}"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Dewasa
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_dewasa"
                                id="kunjungan_dewasa"
                                class="form-control kunjungan-gender"
                                value="{{ old('kunjungan_dewasa', $kunjungan->kunjungan_dewasa ?? 0) }}"
                                required>

                        </div>

                    </div>


                    <div class="row mt-3">

                        <div class="col-md-6 offset-md-3">

                            <label class="form-label fw-bold">
                                Total
                            </label>

                            <input
                                type="number"
                                id="total_kunjungan_pergender"
                                class="form-control bg-light fw-bold"
                                value="{{ $kunjungan->total_kunjungan_pergender ?? 0 }}"
                                readonly>

                        </div>

                    </div>


                    <div
                        id="warningTotal"
                        class="alert alert-danger mt-3 d-none">

                        Total berdasarkan gender harus sama dengan
                        total berdasarkan kategori.

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- JENIS PELAYANAN --}}
            {{-- ================================================= --}}

            <div class="card mb-3">

                <div class="card-header bg-secondary text-white">
                    <strong>Jenis Pelayanan</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Lab
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_lab"
                                class="form-control"
                                value="{{ old('kunjungan_lab', $kunjungan->kunjungan_lab ?? 0) }}"
                                required>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Gigi
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_gigi"
                                class="form-control"
                                value="{{ old('kunjungan_gigi', $kunjungan->kunjungan_gigi ?? 0) }}"
                                required>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                PONED
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_poned"
                                class="form-control"
                                value="{{ old('kunjungan_poned', $kunjungan->kunjungan_poned ?? 0) }}"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Rawat Inap
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_rawatinap"
                                class="form-control"
                                value="{{ old('kunjungan_rawatinap', $kunjungan->kunjungan_rawatinap ?? 0) }}"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Rawat Jalan
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="kunjungan_rawatjalan"
                                class="form-control"
                                value="{{ old('kunjungan_rawatjalan', $kunjungan->kunjungan_rawatjalan ?? 0) }}"
                                required>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- BUTTON --}}
            {{-- ================================================= --}}

            <div class="d-flex justify-content-between">

                <a
                    href="{{ route('newlplpo.edit', $report->id) }}"
                    class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Kembali

                </a>


                <button
                    type="submit"
                    id="btnSimpanKunjungan"
                    class="btn btn-success">

                    <i class="bi bi-save"></i>

                    {{ isset($kunjungan)
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

<script>

function hitungKunjungan()
{
    let jkn =
        parseInt($('#kunjungan_jkn').val()) || 0;

    let tunai =
        parseInt($('#kunjungan_tunai').val()) || 0;

    let gratis =
        parseInt($('#kunjungan_gratis').val()) || 0;


    let anak =
        parseInt($('#kunjungan_anak').val()) || 0;

    let dewasa =
        parseInt($('#kunjungan_dewasa').val()) || 0;


    let totalKategori =
        jkn + tunai + gratis;

    let totalGender =
        anak + dewasa;


    $('#total_kunjungan_perkategori')
        .val(totalKategori);

    $('#total_kunjungan_pergender')
        .val(totalGender);


    if (totalKategori !== totalGender) {

        $('#warningTotal')
            .removeClass('d-none');

        $('#btnSimpanKunjungan')
            .prop('disabled', true);

    } else {

        $('#warningTotal')
            .addClass('d-none');

        $('#btnSimpanKunjungan')
            .prop('disabled', false);

    }
}


$(document).on(
    'input',
    '.kunjungan-kategori, .kunjungan-gender',
    function () {

        hitungKunjungan();

    }
);


$(document).ready(function () {

    hitungKunjungan();

});

</script>

@endpush
