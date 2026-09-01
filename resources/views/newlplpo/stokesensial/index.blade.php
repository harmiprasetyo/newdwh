@extends('newlplpo.layouts.master')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
         HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="bi bi-grid-3x3-gap-fill me-2"></i>

                Monitoring Stok Obat Esensial

            </h4>

            <div class="text-muted">

                Heatmap ketersediaan stok obat esensial berdasarkan
                stok minimal

            </div>

        </div>

    </div>


    {{-- ==========================================================
         FILTER GROUP 3,4,5
    =========================================================== --}}

    @if(in_array($groupId, [3, 4, 5]))

        <div
            class="card border-0 shadow-sm mb-3"
            id="filterPeriode">

            <div class="card-header bg-success text-white">

                <strong>

                    <i class="bi bi-calendar-range me-1"></i>

                    Filter Periode

                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Periode Mulai
                        </label>

                        <div class="input-group">

                            <select
                                id="bulan_mulai"
                                class="form-select">

                                @foreach(range(1, 12) as $i)

                                    <option
                                        value="{{ $i }}"
                                        {{ $bulanMulai == $i ? 'selected' : '' }}>

                                        {{ \Carbon\Carbon::create()
                                            ->month($i)
                                            ->translatedFormat('F') }}

                                    </option>

                                @endforeach

                            </select>

                            <select
                                id="tahun_mulai"
                                class="form-select">

                                @for(
                                    $y = now()->year - 5;
                                    $y <= now()->year + 1;
                                    $y++
                                )

                                    <option
                                        value="{{ $y }}"
                                        {{ $tahunMulai == $y ? 'selected' : '' }}>

                                        {{ $y }}

                                    </option>

                                @endfor

                            </select>

                        </div>

                    </div>


                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Periode Sampai
                        </label>

                        <div class="input-group">

                            <select
                                id="bulan_sampai"
                                class="form-select">

                                @foreach(range(1, 12) as $i)

                                    <option
                                        value="{{ $i }}"
                                        {{ $bulanSampai == $i ? 'selected' : '' }}>

                                        {{ \Carbon\Carbon::create()
                                            ->month($i)
                                            ->translatedFormat('F') }}

                                    </option>

                                @endforeach

                            </select>

                            <select
                                id="tahun_sampai"
                                class="form-select">

                                @for(
                                    $y = now()->year - 5;
                                    $y <= now()->year + 1;
                                    $y++
                                )

                                    <option
                                        value="{{ $y }}"
                                        {{ $tahunSampai == $y ? 'selected' : '' }}>

                                        {{ $y }}

                                    </option>

                                @endfor

                            </select>

                        </div>

                    </div>


                    <div class="col-lg-2 col-md-6">

                        <button
                            type="button"
                            class="btn btn-success w-100"
                            id="btnFilter">

                            <i class="bi bi-search me-1"></i>

                            Tampilkan

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ==========================================================
         FILTER GROUP 1,2
    =========================================================== --}}

    @if(in_array($groupId, [1, 2]))

        <div
            class="card border-0 shadow-sm mb-3"
            id="filterFaskes">

            <div class="card-header bg-success text-white">

                <strong>

                    <i class="bi bi-funnel me-1"></i>

                    Filter Monitoring Stok

                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-3 col-md-6">

                        <label
                            for="bulan"
                            class="form-label fw-semibold">

                            Bulan

                        </label>

                        <select
                            id="bulan"
                            class="form-select">

                            @foreach(range(1, 12) as $i)

                                <option
                                    value="{{ $i }}"
                                    {{ $bulan == $i ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()
                                        ->month($i)
                                        ->translatedFormat('F') }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-6">

                        <label
                            for="tahun"
                            class="form-label fw-semibold">

                            Tahun

                        </label>

                        <select
                            id="tahun"
                            class="form-select">

                            @for(
                                $y = now()->year - 5;
                                $y <= now()->year + 1;
                                $y++
                            )

                                <option
                                    value="{{ $y }}"
                                    {{ $tahun == $y ? 'selected' : '' }}>

                                    {{ $y }}

                                </option>

                            @endfor

                        </select>

                    </div>


                    @if($groupId == 2)

                        <div class="col-lg-4 col-md-6">

                            <label
                                for="kode_faskes"
                                class="form-label fw-semibold">

                                Faskes

                            </label>

                            <select
                                id="kode_faskes"
                                class="form-select">

                                <option value="">
                                    Semua Faskes
                                </option>

                                @foreach($faskes as $f)

                                    <option
                                        value="{{ $f->kodeFaskes }}">

                                        {{ $f->kodeFaskes }}
                                        -
                                        {{ $f->namaFaskes }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    @endif


                    @if($groupId == 1)

                        <div class="col-lg-4 col-md-6">

                            <label
                                for="kode_faskes"
                                class="form-label fw-semibold">

                                Faskes

                            </label>

                            <select
                                id="kode_faskes"
                                class="form-select">

                                <option value="">
                                    Semua Faskes
                                </option>

                                @foreach($faskes as $f)

                                    <option
                                        value="{{ $f->kodeFaskes }}">

                                        {{ $f->kodeFaskes }}
                                        -
                                        {{ $f->namaFaskes }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    @endif


                    <div class="col-lg-2 col-md-6">

                        <button
                            type="button"
                            class="btn btn-success w-100"
                            id="btnFilter">

                            <i class="bi bi-search me-1"></i>

                            Tampilkan

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ==========================================================
         INFO
    =========================================================== --}}

    <div
        class="card border-0 shadow-sm mb-3"
        id="infoCard">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <div class="text-muted small">
                        PERIODE
                    </div>

                    <div
                        class="fw-bold fs-5"
                        id="infoPeriode">

                        -

                    </div>

                </div>

                <div class="col-md-6 text-md-end">

                    <span
                        class="badge bg-secondary"
                        id="infoFaskes">

                        -

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         LEGEND
    =========================================================== --}}

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-body py-2">

            <div
                class="d-flex flex-wrap align-items-center gap-3">

                <strong class="me-2">
                    Keterangan:
                </strong>

                <div class="legend-item">

                    <span class="legend-box heat-danger"></span>

                    <span>
                        < 25%
                    </span>

                </div>

                <div class="legend-item">

                    <span class="legend-box heat-warning"></span>

                    <span>
                        25% - < 35%
                    </span>

                </div>

                <div class="legend-item">

                    <span class="legend-box heat-yellow"></span>

                    <span>
                        35% - 50%
                    </span>

                </div>

                <div class="legend-item">

                    <span class="legend-box heat-success"></span>

                    <span>
                        > 50%
                    </span>

                </div>

                <div class="legend-item">

                    <span class="legend-box heat-nodata"></span>

                    <span>
                        Tidak ada data
                    </span>

                </div>

                <div class="legend-item">

                    <span class="legend-box heat-napza"></span>

                    <span>
                        NAPZA
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         HEATMAP
    =========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-success text-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>

                    <i class="bi bi-grid-3x3-gap me-1"></i>

                    Heatmap Stok Obat Esensial

                </strong>

                <span
                    id="jumlahObat"
                    class="badge bg-light text-success">

                    0 Obat

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div
                class="table-responsive heatmap-wrapper">

                <table
                    id="tableHeatmap"
                    class="table table-bordered table-sm mb-0">

                    <thead id="heatmapHead">

                        <tr>

                            <th
                                colspan="5"
                                class="text-center py-4">

                                Memuat data...

                            </th>

                        </tr>

                    </thead>

                    <tbody id="heatmapBody">

                        <tr>

                            <td
                                colspan="5"
                                class="text-center text-muted py-5">

                                <div
                                    class="spinner-border text-success mb-2">
                                </div>

                                <div>
                                    Memuat data...
                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         FOOTER
    =========================================================== --}}

    <div class="mt-3 text-end">

        <button
            type="button"
            class="btn btn-secondary"
            onclick="window.history.back()">

            <i class="bi bi-arrow-left me-1"></i>

            Kembali

        </button>

        <button
            type="button"
            class="btn btn-success"
            onclick="window.print()">

            <i class="bi bi-printer me-1"></i>

            Cetak

        </button>

    </div>

</div>

@endsection


{{-- ==========================================================
     STYLE
=========================================================== --}}

@push('styles')

<style>

.heatmap-wrapper {
    max-height: 72vh;
    overflow: auto;
}

#tableHeatmap {
    min-width: 100%;
    font-size: 12px;
}

#tableHeatmap th,
#tableHeatmap td {
    vertical-align: middle;
    white-space: nowrap;
}

#tableHeatmap thead th {
    position: sticky;
    top: 0;
    z-index: 5;
}

#tableHeatmap th:first-child,
#tableHeatmap td:first-child {
    position: sticky;
    left: 0;
    z-index: 4;
    background-color: #fff;
}

#tableHeatmap thead th:first-child {
    z-index: 8;
}

.obat-name {
    min-width: 250px;
}

.obat-code {
    min-width: 100px;
}

.obat-satuan {
    min-width: 70px;
}

.obat-form {
    min-width: 100px;
}

.stock-cell {
    min-width: 110px;
    text-align: center;
    font-weight: 600;
    cursor: pointer;
}

.stock-value {
    font-size: 14px;
    font-weight: 700;
}

.stock-percent {
    font-size: 10px;
    opacity: .85;
}

.heat-danger {
    background-color: #eb081b !important;
    color: #0c0b0b !important;
}

.heat-warning {
    background-color: #ee966e !important;
    color: #0e0d0c !important;
}

.heat-yellow {
    background-color: #f0db7d !important;
    color: #141413 !important;
}

.heat-success {
    background-color: #57eba8 !important;
    color: #0d0e0d !important;
}

.heat-nodata {
    background-color: #e9ecef !important;
    color: #6c757d !important;
}

.heat-normal {
    background-color: #d1e7dd !important;
}

.heat-napza {
    background-color: #f5c2d7 !important;
    color: #7a1f45 !important;
}

.legend-item {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
}

.legend-box {
    width: 18px;
    height: 18px;
    display: inline-block;
    border: 1px solid rgba(0,0,0,.15);
}

.napza-label {
    display: inline-block;
    margin-left: 5px;
    font-size: 10px;
    font-weight: 700;
}

.formularium-ya {
    color: #0b0c0c;
    font-weight: 700;
}

.formularium-tidak {
    color: #6c757d;
}

@media print {

    .sidebar,
    .navbar,
    #btnFilter,
    .btn,
    #filterPeriode,
    #filterFaskes,
    #infoCard,
    .legend-item {
        display: none !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .heatmap-wrapper {
        max-height: none;
        overflow: visible;
    }

    #tableHeatmap {
        font-size: 8px;
    }

    #tableHeatmap th,
    #tableHeatmap td {
        padding: 3px;
    }

}

</style>

@endpush


{{-- ==========================================================
     SCRIPT CONFIG
=========================================================== --}}

@push('script')

<script>

window.lplpoStokEsensialConfig = {

    dataUrl: @json(
        route('newlplpo.stokesensial.data')
    ),

    groupId: @json($groupId),

    bulanMulai: @json($bulanMulai),

    tahunMulai: @json($tahunMulai),

    bulanSampai: @json($bulanSampai),

    tahunSampai: @json($tahunSampai),

    bulan: @json($bulan),

    tahun: @json($tahun),

    csrfToken: @json(csrf_token())

};

</script>

<script src="{{ mix('js/newlplpo/stokesensial.js') }}"></script>

@endpush
