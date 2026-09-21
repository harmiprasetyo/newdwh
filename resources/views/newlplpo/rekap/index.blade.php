@extends('newlplpo.layouts.master')

@section('content')

<div class="container-fluid">

```
{{-- ==========================================================
     HEADER
=========================================================== --}}

<div class="d-flex justify-content-between align-items-center mb-3">

    <div>

        <h4 class="fw-bold mb-1">
            <i class="bi bi-clipboard-data me-2"></i>
            Rekap Laporan LPLPO
        </h4>

        <div class="text-muted">
            Rekapitulasi LPLPO FINAL berdasarkan periode
        </div>

    </div>

</div>


{{-- ==========================================================
     FILTER PERIODE
=========================================================== --}}

<div class="card border-0 shadow-sm mb-3">

    <div class="card-header bg-success text-white">

        <strong>
            <i class="bi bi-calendar-range me-1"></i>
            Filter Periode Rekap
        </strong>

    </div>

    <div class="card-body">

        <div class="row g-3 align-items-end">

            {{-- PERIODE MULAI --}}

            <div class="col-lg-3 col-md-6">

                <label class="form-label fw-semibold">
                    Periode Mulai
                </label>

                <div class="input-group">

                    <select
                        id="bulan_mulai"
                        class="form-select"
                    >

                        @foreach(range(1, 12) as $i)

                            <option
                                value="{{ $i }}"
                                {{ (int) $bulanMulai === $i ? 'selected' : '' }}
                            >
                                {{ \Carbon\Carbon::create()
                                    ->month($i)
                                    ->translatedFormat('F') }}
                            </option>

                        @endforeach

                    </select>

                    <select
                        id="tahun_mulai"
                        class="form-select"
                    >

                        @for(
                            $y = now()->year - 5;
                            $y <= now()->year + 1;
                            $y++
                        )

                            <option
                                value="{{ $y }}"
                                {{ (int) $tahunMulai === $y ? 'selected' : '' }}
                            >
                                {{ $y }}
                            </option>

                        @endfor

                    </select>

                </div>

            </div>


            {{-- PERIODE SAMPAI --}}

            <div class="col-lg-3 col-md-6">

                <label class="form-label fw-semibold">
                    Periode Sampai
                </label>

                <div class="input-group">

                    <select
                        id="bulan_sampai"
                        class="form-select"
                    >

                        @foreach(range(1, 12) as $i)

                            <option
                                value="{{ $i }}"
                                {{ (int) $bulanSampai === $i ? 'selected' : '' }}
                            >
                                {{ \Carbon\Carbon::create()
                                    ->month($i)
                                    ->translatedFormat('F') }}
                            </option>

                        @endforeach

                    </select>

                    <select
                        id="tahun_sampai"
                        class="form-select"
                    >

                        @for(
                            $y = now()->year - 5;
                            $y <= now()->year + 1;
                            $y++
                        )

                            <option
                                value="{{ $y }}"
                                {{ (int) $tahunSampai === $y ? 'selected' : '' }}
                            >
                                {{ $y }}
                            </option>

                        @endfor

                    </select>

                </div>

            </div>


            {{-- FASKES --}}

            @if($groupId == 2)

                <div class="col-lg-4 col-md-6">

                    <label class="form-label fw-semibold">
                        Faskes
                    </label>

                    <select
                        id="kode_faskes"
                        class="form-select"
                    >

                        <option value="">
                            Semua Faskes
                        </option>

                        @foreach($faskes as $f)

                            <option value="{{ $f->kodeFaskes }}">
                                {{ $f->kodeFaskes }}
                                -
                                {{ $f->namaFaskes }}
                            </option>

                        @endforeach

                    </select>

                </div>

            @endif


            {{-- BUTTON --}}

            <div class="col-lg-2 col-md-6">

                <button
                    type="button"
                    class="btn btn-success w-100"
                    id="btnFilter"
                >
                    <i class="bi bi-search me-1"></i>
                    Tampilkan
                </button>

            </div>

        </div>

    </div>

</div>


{{-- ==========================================================
     INFO PERIODE
=========================================================== --}}

<div class="card border-0 shadow-sm mb-3">

    <div class="card-header bg-success text-white">

        <div class="d-flex justify-content-between align-items-center">

            <strong>
                <i class="bi bi-file-earmark-bar-graph me-1"></i>
                Rekap Laporan LPLPO
            </strong>

            <span class="badge bg-light text-success">
                FINAL
            </span>

        </div>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">

                <div class="text-muted small">
                    PERIODE
                </div>

                <div
                    class="fw-bold fs-5"
                    id="infoPeriode"
                >
                    -
                </div>

            </div>

            <div class="col-md-4">

                <div class="text-muted small">
                    JUMLAH LAPORAN
                </div>

                <div
                    class="fw-bold fs-5"
                    id="infoJumlahLaporan"
                >
                    -
                </div>

            </div>

            <div class="col-md-4">

                <div class="text-muted small">
                    JUMLAH ITEM
                </div>

                <div
                    class="fw-bold fs-5"
                    id="infoJumlahItem"
                >
                    -
                </div>

            </div>

        </div>

    </div>

</div>


{{-- ==========================================================
     LEGEND
=========================================================== --}}

<div class="card border-0 shadow-sm mb-3">

    <div class="card-body py-2">

        <div class="d-flex flex-wrap gap-3 align-items-center small">

            <div>
                <span class="badge bg-success">
                    OE
                </span>
                Obat Esensial
            </div>

            <div>
                <span class="badge bg-secondary">
                    NOE
                </span>
                Non Obat Esensial
            </div>

            <div>
                <span class="badge bg-primary">
                    Ya
                </span>
                Formularium PKM
            </div>

            <div>
                <span class="badge bg-danger">
                    NAPZA
                </span>
                Obat NAPZA
            </div>

            <div class="napza-legend">

                <span class="legend-box"></span>

                Baris obat NAPZA

            </div>

        </div>

    </div>

</div>


{{-- ==========================================================
     DETAIL ITEM
=========================================================== --}}

<div class="card border-0 shadow-sm">

    <div class="card-header bg-success text-white">

        <div class="d-flex justify-content-between align-items-center">

            <strong>
                <i class="bi bi-capsule me-1"></i>
                Detail Rekap Item Obat
            </strong>

            <div class="d-flex align-items-center gap-2">

                <button
                    type="button"
                    class="btn btn-light btn-sm"
                    id="btnExportExcel"
                >
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Excel
                </button>

                <button
                    type="button"
                    class="btn btn-light btn-sm"
                    id="btnExportPdf"
                >
                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    PDF
                </button>

                <span
                    class="badge bg-light text-dark"
                    id="jumlahItem"
                >
                    0 Item
                </span>

            </div>

        </div>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table
                class="table table-bordered table-hover table-sm align-middle mb-0"
                id="tableRekap"
            >

                <thead class="table-success text-center align-middle">

                    <tr>

                        <th rowspan="2">
                            No
                        </th>

                        <th rowspan="2">
                            Kode
                        </th>

                        <th rowspan="2">
                            Nama Obat
                        </th>

                        <th rowspan="2">
                            Sat
                        </th>

                        <th rowspan="2">
                            Esensial
                        </th>

                        <th rowspan="2">
                            Formularium PKM
                        </th>

                        <th colspan="2">
                            Stok Awal
                        </th>

                        <th colspan="2">
                            Penerimaan
                        </th>

                        <th colspan="2">
                            Persediaan
                        </th>

                        <th colspan="2">
                            Pemakaian
                        </th>

                        <th colspan="2">
                            Expired
                        </th>

                        <th colspan="2">
                            Stok Akhir
                        </th>

                        <th rowspan="2">
                            Permintaan
                        </th>

                        <th rowspan="2">
                            Pemberian
                        </th>

                    </tr>

                    <tr>

                        <th>PKD</th>
                        <th>JKN</th>

                        <th>PKD</th>
                        <th>JKN</th>

                        <th>PKD</th>
                        <th>JKN</th>

                        <th>PKD</th>
                        <th>JKN</th>

                        <th>PKD</th>
                        <th>JKN</th>

                        <th>PKD</th>
                        <th>JKN</th>



                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td
                            colspan="20"
                            class="text-center text-muted py-5"
                        >

                            <i
                                class="bi bi-hourglass-split fs-3 d-block mb-2"
                            ></i>

                            Memuat data...

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
        onclick="window.history.back()"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Kembali
    </button>

    <button
        type="button"
        class="btn btn-success"
        onclick="window.print()"
    >
        <i class="bi bi-printer me-1"></i>
        Cetak
    </button>

</div>
```

</div>

@endsection

{{-- ==============================================================
ASSET CSS
=============================================================== --}}

@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/newlplpo/rekap/index.css') }}"
>

@endpush

{{-- ==============================================================
JAVASCRIPT CONFIG
=============================================================== --}}

@push('script')

<script>
    window.lplpoRekap = {
        dataUrl: @json(route('newlplpo.rekap.data')),
        exportExcelUrl: @json(route('newlplpo.rekap.export.excel')),
        exportPdfUrl: @json(route('newlplpo.rekap.export.pdf'))
    };
</script>

<script
    src="{{ asset('js/newlplpo/rekap/index.js') }}"
></script>

@endpush
