@extends('newlplpo.layouts.headerapproval')

@section('title', 'Approval LPLPO')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         INFORMASI LPLPO
    ========================================================== --}}
    <div class="card mb-3">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-check"></i>
                Approval LPLPO
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Fasilitas Kesehatan</strong>
                    <div>
                        {{ $report->nama_faskes }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Kode Faskes</strong>
                    <div>
                        {{ $report->kode_faskes }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Nomor LPLPO</strong>
                    <div>
                        {{ $report->nomor_lplpo }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Periode</strong>
                    <div>
                        {{ $report->bulan }}/{{ $report->tahun }}
                    </div>
                </div>

                <div class="col-md-6 mb-0">
                    <strong>Kepala Puskesmas</strong>
                    <div>
                        {{ $kapus->namaKapus }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         DATA KUNJUNGAN
    ========================================================== --}}
    <div class="card mb-3">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-people"></i>
                Data Kunjungan
            </h5>
        </div>

        <div class="card-body">

            @if($report->kunjungan)

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <strong>JKN</strong>
                        <div>
                            {{ number_format($report->kunjungan->kunjungan_jkn) }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Tunai</strong>
                        <div>
                            {{ number_format($report->kunjungan->kunjungan_tunai) }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Gratis</strong>
                        <div>
                            {{ number_format($report->kunjungan->kunjungan_gratis) }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Anak</strong>
                        <div>
                            {{ number_format($report->kunjungan->kunjungan_anak) }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Dewasa</strong>
                        <div>
                            {{ number_format($report->kunjungan->kunjungan_dewasa) }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Total Kunjungan</strong>
                        <div>
                            {{ number_format($report->kunjungan->total_kunjungan_perkategori) }}
                        </div>
                    </div>

                </div>

            @else

                <div class="alert alert-warning mb-0">
                    Data kunjungan belum tersedia.
                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         DAFTAR OBAT
    ========================================================== --}}
    <div class="card mb-3">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-capsule"></i>
                Daftar Obat
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Kode Obat</th>
                            <th>Nama Obat</th>
                            <th>Satuan</th>
                            <th>Stok Akhir PKD</th>
                            <th>Stok Akhir JKN</th>
                            <th>Permintaan</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($report->items as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $item->kode_obat }}
                                </td>

                                <td>
                                    {{ $item->nama_obat }}
                                </td>

                                <td>
                                    {{ $item->satuan }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($item->stok_akhir_program_pkd ?? 0) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($item->stok_akhir_jkn ?? 0) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($item->permintaan ?? 0) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center">
                                    Tidak ada item obat.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



{{-- =========================================================
     STATUS APPROVAL
========================================================== --}}

<div class="alert alert-info">

    <strong>Status:</strong>

    Menunggu persetujuan Kepala Puskesmas.

</div>


{{-- =========================================================
     TOMBOL APPROVAL
========================================================== --}}

@if($approval->status === 'pending')

    <div class="card mb-3">

        <div class="card-body">

            <div class="d-flex justify-content-end gap-2">

                {{-- TOLAK --}}
                <button
                    type="button"
                    class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#modalReject"
                >
                    <i class="bi bi-x-circle"></i>
                    Tolak LPLPO
                </button>


                {{-- APPROVE --}}
                <button
                    type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#modalApprove"
                >
                    <i class="bi bi-check-circle"></i>
                    Approve LPLPO
                </button>

            </div>

        </div>

    </div>

@endif




{{-- =========================================================
     MODAL APPROVE
========================================================== --}}

<div
    class="modal fade"
    id="modalApprove"
    tabindex="-1"
    aria-labelledby="modalApproveLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'newlplpo.approval.approve',
                    $approval->approvalToken
                ) }}"
            >

                @csrf

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="modalApproveLabel"
                    >
                        <i class="bi bi-check-circle text-success"></i>
                        Approve LPLPO
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-warning">

                        <i class="bi bi-exclamation-triangle"></i>

                        Pastikan seluruh data LPLPO telah diperiksa
                        sebelum melakukan approval.

                    </div>


                    <div class="mb-3">

                        <label
                            for="kodeEsign"
                            class="form-label"
                        >
                            Kode E-Sign
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="password"
                            name="kodeEsign"
                            id="kodeEsign"
                            class="form-control @error('kodeEsign') is-invalid @enderror"
                            placeholder="Masukkan kode E-Sign"
                            autocomplete="off"
                            required
                        >

                        @error('kodeEsign')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="form-text">
                            Masukkan kode E-Sign Kepala Puskesmas
                            untuk mengesahkan laporan.
                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="bi bi-check-circle"></i>
                        Ya, Approve LPLPO
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL REJECT
========================================================== --}}

<div
    class="modal fade"
    id="modalReject"
    tabindex="-1"
    aria-labelledby="modalRejectLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'newlplpo.approval.reject',
                    $approval->approvalToken
                ) }}"
            >

                @csrf

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="modalRejectLabel"
                    >
                        <i class="bi bi-x-circle text-danger"></i>
                        Tolak LPLPO
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-danger">

                        <i class="bi bi-exclamation-circle"></i>

                        Laporan akan dikembalikan kepada
                        Puskesmas untuk diperbaiki.

                    </div>


                    <div class="mb-3">

                        <label
                            for="rejectedReason"
                            class="form-label"
                        >
                            Alasan Penolakan
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="rejectedReason"
                            id="rejectedReason"
                            rows="4"
                            class="form-control @error('rejectedReason') is-invalid @enderror"
                            placeholder="Tuliskan alasan penolakan..."
                            required
                        >{{ old('rejectedReason') }}</textarea>

                        @error('rejectedReason')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        <i class="bi bi-x-circle"></i>
                        Ya, Tolak LPLPO
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



@endsection

