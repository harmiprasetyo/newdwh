@extends('newlplpo.layouts.master')

@section('title', 'Rekap Bulanan LPLPO Bekasi')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card shadow-sm mb-3">

        <div class="card-body">

            <div class="d-flex
                        justify-content-between
                        align-items-center">

                <div>

                    <h5 class="mb-1">
                        <i class="bi bi-bar-chart-line me-2"></i>
                        Rekap Bulanan LPLPO
                    </h5>

                    <div class="text-muted small">
                        Rekap LPLPO Kota Bekasi berdasarkan
                        Puskesmas dan Kode Obat KFA
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- FILTER --}}
    <div class="card shadow-sm mb-3">

        <div class="card-body">

            <form id="formRekapFilter">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">

                        <label class="form-label">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="start_date"
                            value="{{ $startDate }}"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="end_date"
                            value="{{ $endDate }}"
                        >

                    </div>


                    <div class="col-md-4">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="btnFilter"
                            >
                                <i class="bi bi-search me-1"></i>
                                Tampilkan
                            </button>

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="btnReset"
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


    {{-- INFO PERIODE --}}
    <div
        class="alert alert-light border
               d-flex align-items-center mb-3"
    >

        <i class="bi bi-calendar3 me-2"></i>

        <div>

            Periode:

            <strong id="periodeLabel">
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </strong>

        </div>

    </div>


    {{-- ERROR --}}
    <div
        id="errorContainer"
        class="alert alert-danger d-none"
    >

        <i class="bi bi-exclamation-triangle me-2"></i>

        <span id="errorMessage"></span>

    </div>


    {{-- TABLE --}}
    <div class="card shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-bordered
                           table-hover table-sm
                           align-middle mb-0"
                    id="tableRekap"
                >

                    <thead class="table-light">

                        <tr>

                            <th
                                rowspan="2"
                                class="text-center"
                            >
                                No
                            </th>

                            <th
                                rowspan="2"
                                style="min-width:220px"
                            >
                                Puskesmas
                            </th>

                            <th
                                rowspan="2"
                                style="min-width:120px"
                            >
                                Kode KFA
                            </th>

                            <th
                                rowspan="2"
                                style="min-width:240px"
                            >
                                Nama Obat
                            </th>

                            <th
                                rowspan="2"
                                class="text-center"
                            >
                                Satuan
                            </th>

                           <th colspan="3" class="text-center">
    Stok Awal
</th>

<th colspan="3" class="text-center">
    Penerimaan
</th>

<th colspan="3" class="text-center">
    Penggunaan
</th>

<th colspan="3" class="text-center">
    Stok Akhir
</th>

<th rowspan="2" class="text-center">
    Stok Optimum
</th>

<th rowspan="2" class="text-center">
    Permintaan
</th>

                        </tr>


                       <tr>

    <th class="text-center">Rutin</th>
    <th class="text-center">Program</th>
    <th class="text-center">JKN</th>

    <th class="text-center">Rutin</th>
    <th class="text-center">Program</th>
    <th class="text-center">JKN</th>

    <th class="text-center">Rutin</th>
    <th class="text-center">Program</th>
    <th class="text-center">JKN</th>

    <th class="text-center">Rutin</th>
    <th class="text-center">Program</th>
    <th class="text-center">JKN</th>

</tr>

                    </thead>


                    <tbody id="tableBody">

                        <tr>

                            <td
                                colspan="15"
                                class="text-center py-5"
                            >

                                Memuat data...

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection


@push('script')

<script>

    window.LplpoBekasiRekapConfig = {

        dataUrl:
            @json(route('newlplpo.bekasi.rekap.data')),

        defaultStartDate:
            @json($startDate),

        defaultEndDate:
            @json($endDate)

    };

</script>

<script src="{{ mix('js/newlplpo/bekasi/rekap.js') }}"></script>

@endpush