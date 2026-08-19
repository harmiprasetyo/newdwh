@extends('newlplpo.layouts.master')

@section('title', 'LPLPO Bekasi')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1 fw-semibold">
                Laporan LPLPO Bekasi
            </h4>

            <div class="text-muted small">
                Monitoring data LPLPO berdasarkan periode tanggal
            </div>
        </div>

    </div>


    {{-- =========================================================
        FILTER
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-3">

        <div class="card-body">

            <form id="formFilter">

                <div class="row g-3 align-items-end">

                    {{-- TANGGAL MULAI --}}
                    <div class="col-12 col-md-4 col-lg-3">

                        <label
                            for="start_date"
                            class="form-label fw-semibold"
                        >
                            Tanggal Mulai
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-calendar3"></i>
                            </span>

                            <input
                                type="date"
                                class="form-control"
                                id="start_date"
                                name="start_date"
                                value="{{ $startDate }}"
                            >

                        </div>

                    </div>


                    {{-- TANGGAL AKHIR --}}
                    <div class="col-12 col-md-4 col-lg-3">

                        <label
                            for="end_date"
                            class="form-label fw-semibold"
                        >
                            Tanggal Akhir
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-calendar3"></i>
                            </span>

                            <input
                                type="date"
                                class="form-control"
                                id="end_date"
                                name="end_date"
                                value="{{ $endDate }}"
                            >

                        </div>

                    </div>


                    {{-- BUTTON --}}
                    <div class="col-12 col-md-4 col-lg-4">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                id="btnFilter"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-search me-1"></i>

                                Tampilkan

                            </button>


                            <button
                                type="button"
                                id="btnReset"
                                class="btn btn-outline-secondary"
                            >

                                <i class="bi bi-arrow-counterclockwise me-1"></i>

                                Reset

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PERIOD + LIMIT
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-3">

        <div class="card-body py-2">

            <div class="row align-items-center">

                {{-- PERIOD --}}
                <div class="col-md-8">

                    <div class="small">

                        <i class="bi bi-calendar-range text-primary me-1"></i>

                        <span class="text-muted">
                            Periode:
                        </span>

                        <strong id="periodeLabel">
                            -
                        </strong>

                    </div>

                </div>


                {{-- LIMIT --}}
                <div class="col-md-4 text-md-end">

                    <div class="d-inline-flex align-items-center gap-2">

                        <label
                            for="limit"
                            class="small text-muted mb-0"
                        >
                            Data per halaman
                        </label>


                        <select
                            id="limit"
                            name="limit"
                            class="form-select form-select-sm"
                            style="width: 90px;"
                        >

                            <option value="25">
                                25
                            </option>

                            <option
                                value="50"
                                selected
                            >
                                50
                            </option>

                            <option value="100">
                                100
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ERROR
    ========================================================== --}}
    <div
        id="errorContainer"
        class="alert alert-danger d-none"
        role="alert"
    >

        <div class="d-flex align-items-start">

            <i
                class="bi bi-exclamation-triangle-fill me-2 mt-1"
            ></i>

            <div>

                <div class="fw-semibold">
                    Terjadi kesalahan
                </div>

                <div
                    id="errorMessage"
                    class="small"
                ></div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        {{-- TABLE HEADER --}}
        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        Data LPLPO
                    </div>

                    <div class="small text-muted">
                        Data sarana, obat, stok, penerimaan dan penggunaan
                    </div>

                </div>


                {{-- LOADING --}}
                <div
                    id="loadingIndicator"
                    class="d-none"
                >

                    <div class="d-flex align-items-center">

                        <div
                            class="spinner-border spinner-border-sm text-primary me-2"
                            role="status"
                        ></div>

                        <span class="small text-muted">
                            Memuat data...
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="card-body p-0">

            <div
                id="tableWrapper"
                class="table-responsive"
            >

                <table
                    id="tblLplpo"
                    class="table table-bordered table-hover table-striped align-middle mb-0"
                >

                    <thead class="table-light">

                        <tr>

                            <th
                                class="text-center"
                                width="55"
                            >
                                No
                            </th>

                            <th>
                                Kode Sarana
                            </th>

                            <th>
                                Nama Sarana
                            </th>

                            <th>
                                Nama Puskesmas
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Nomor LPLPO
                            </th>

                            <th>
                                Kode KFA
                            </th>

                            <th>
                                Nama Obat
                            </th>

                            <th>
                                Satuan
                            </th>

                            <th class="text-end">
                                Stok Awal Rutin
                            </th>

                            <th class="text-end">
                                Stok Awal Program
                            </th>

                            <th class="text-end">
                                Stok Awal JKN
                            </th>

                            <th class="text-end">
                                Penerimaan Rutin
                            </th>

                            <th class="text-end">
                                Penerimaan Program
                            </th>

                            <th class="text-end">
                                Penerimaan JKN
                            </th>

                            <th class="text-end">
                                Penggunaan Rutin
                            </th>

                            <th class="text-end">
                                Penggunaan Program
                            </th>

                            <th class="text-end">
                                Penggunaan JKN
                            </th>

                            <th>
                                Expired Date
                            </th>

                            <th class="text-end">
                                Stok Optimum
                            </th>

                            <th class="text-end">
                                Permintaan
                            </th>

                        </tr>

                    </thead>


                    <tbody id="tableBody">

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PAGINATION --}}
        <div class="card-footer bg-white">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div
                    id="pageInfo"
                    class="small text-muted"
                >
                    Halaman 1
                </div>


                <div class="d-flex gap-2">

                    <button
                        type="button"
                        id="btnPrevious"
                        class="btn btn-outline-secondary btn-sm"
                        disabled
                    >

                        <i class="bi bi-chevron-left me-1"></i>

                        Previous

                    </button>


                    <button
                        type="button"
                        id="btnNext"
                        class="btn btn-outline-primary btn-sm"
                        disabled
                    >

                        Next

                        <i class="bi bi-chevron-right ms-1"></i>

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


{{-- =============================================================
    CSS
============================================================= --}}
@push('styles')

<style>

    #tblLplpo {
        font-size: 0.82rem;
        white-space: nowrap;
    }


    #tblLplpo thead th {
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }


    #tblLplpo tbody td {
        vertical-align: middle;
    }


    #tableWrapper {
        max-height: calc(100vh - 390px);
        min-height: 300px;
        overflow: auto;
    }


    #tblLplpo thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa;
    }


    .number-cell {
        text-align: right;
        white-space: nowrap;
    }


    .empty-row {
        height: 220px;
    }


    #btnPrevious,
    #btnNext {
        min-width: 105px;
    }


    #loadingIndicator {
        white-space: nowrap;
    }

</style>

@endpush

@push('script')
    <script>
        window.LplpoBekasiConfig = {
            dataUrl: @json(route('newlplpo.bekasi.data')),

            defaultStartDate: @json($startDate),

            defaultEndDate: @json($endDate),

            defaultLimit: 50,

            allowedLimits: [25, 50, 100]
        };
    </script>

    <script src="{{ mix('js/newlplpo/bekasi/lplpo.js') }}"></script>
@endpush
