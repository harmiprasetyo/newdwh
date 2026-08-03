@extends('newlplpo.layouts.master')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
         HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="bi bi-speedometer2 me-2"></i>

                Dashboard LPLPO

            </h4>

            <div class="text-muted">

                Monitoring Laporan LPLPO

            </div>

        </div>

    </div>


    {{-- ==========================================================
         FILTER
    =========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('newlplpo.index') }}"
                class="row g-3 align-items-end">

                <div class="col-md-3">

                    <label class="form-label fw-semibold">

                        Bulan

                    </label>

                    <select
                        name="bulan"
                        class="form-select">

                        @php

                            $namaBulan = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];

                        @endphp

                        @foreach($namaBulan as $key => $value)

                            <option
                                value="{{ $key }}"
                                {{ $bulan == $key ? 'selected' : '' }}>

                                {{ $value }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label fw-semibold">

                        Tahun

                    </label>

                    <select
                        name="tahun"
                        class="form-select">

                        @for(
                            $year = now()->year;
                            $year >= now()->year - 5;
                            $year--
                        )

                            <option
                                value="{{ $year }}"
                                {{ $tahun == $year ? 'selected' : '' }}>

                                {{ $year }}

                            </option>

                        @endfor

                    </select>

                </div>


                <div class="col-md-2">

                    <button
                        type="submit"
                        class="btn btn-primary w-100">

                        <i class="bi bi-funnel me-1"></i>

                        Filter

                    </button>

                </div>

            </form>

        </div>

    </div>


    @if(auth()->user()->groupid == 2)

        {{-- ======================================================
             DASHBOARD DINKES
        ======================================================= --}}

        <div class="row g-3 mb-4">

            {{-- PUSKESMAS MELAPOR --}}

            <div class="col-xl-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">

                                    Puskesmas Melapor

                                </div>

                                <h2 class="fw-bold mb-0">

                                    {{ $jumlahPuskesmas }}

                                </h2>

                                <small class="text-muted">

                                    Puskesmas unik

                                </small>

                            </div>

                            <div class="fs-1 text-primary">

                                <i class="bi bi-hospital"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- VERIFIED --}}

            <div class="col-xl-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">

                                    Sudah Diverifikasi

                                </div>

                                <h2 class="fw-bold mb-0">

                                    {{ $jumlahVerified }}

                                </h2>

                                <small class="text-muted">

                                    Status VERIFIED

                                </small>

                            </div>

                            <div class="fs-1 text-success">

                                <i class="bi bi-patch-check"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- FINAL --}}

            <div class="col-xl-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">

                                    Laporan Selesai

                                </div>

                                <h2 class="fw-bold mb-0">

                                    {{ $jumlahFinal }}

                                </h2>

                                <small class="text-muted">

                                    Status FINAL

                                </small>

                            </div>

                            <div class="fs-1 text-info">

                                <i class="bi bi-check2-all"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ======================================================
             GRAFIK DINKES
        ======================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-bar-chart me-2"></i>

                    Jumlah Item Obat Dilaporkan

                </h5>

                <small class="text-muted">

                    Per Faskes -
                    {{ $namaBulan[$bulan] ?? '' }}
                    {{ $tahun }}

                </small>

            </div>

            <div class="card-body">

                <div style="height:350px">

                    <canvas id="chartDinkes"></canvas>

                </div>

            </div>

        </div>


        {{-- ======================================================
             TABEL REKAP DINKES
        ======================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-table me-2"></i>

                    Rekap Laporan

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle"
                        id="tableRekap">

                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Nama Faskes</th>

                                <th>Tanggal Laporan</th>

                                <th>Status</th>

                                <th class="text-center">

                                    Item Dilaporkan

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse(
                                $rekapDinkes
                                as $index => $row
                            )

                                <tr>

                                    <td>

                                        {{ $index + 1 }}

                                    </td>

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $row->nama_faskes ?? '-' }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $row->kode_faskes ?? '-' }}

                                        </small>

                                    </td>

                                    <td>

                                        @if($row->created_at)

                                            {{ \Carbon\Carbon::parse(
                                                $row->created_at
                                            )->format('d-m-Y H:i') }}

                                        @else

                                            -

                                        @endif

                                    </td>

                                    <td>

                                        @php

                                            $status =
                                                strtoupper(
                                                    $row->report_status ?? ''
                                                );

                                        @endphp

                                        @if($status === 'DRAFT')

                                            <span class="badge bg-secondary">
                                                Draft
                                            </span>

                                        @elseif($status === 'SUBMITED')

                                            <span class="badge bg-warning text-dark">
                                                Terkirim
                                            </span>

                                        @elseif($status === 'VERIFIED')

                                            <span class="badge bg-success">
                                                Sudah Diverifikasi
                                            </span>

                                        @elseif(
                                            $status === 'REJECTED'
                                            ||
                                            $status === 'REJECTED'
                                        )

                                            <span class="badge bg-danger">
                                                Ditolak
                                            </span>

                                        @elseif($status === 'FINAL')

                                            <span class="badge bg-primary">
                                                Selesai
                                            </span>

                                        @else

                                            <span class="badge bg-light text-dark">
                                                {{ $row->report_status ?? '-' }}
                                            </span>

                                        @endif

                                    </td>

                                    <td class="text-center">

                                        <span class="badge bg-light text-dark">

                                            {{ $row->item_dilaporkan }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center text-muted py-4">

                                        Belum ada laporan pada periode ini.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


    @elseif(
        in_array(
            auth()->user()->groupid,
            [3, 5]
        )
    )


        {{-- ======================================================
             DASHBOARD PUSKESMAS
        ======================================================= --}}

        <div class="row g-3 mb-4">

            {{-- DRAFT --}}

            <div class="col-xl col-md-4 col-sm-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Draft
                        </div>

                        <h2 class="fw-bold">
                            {{ $statusSummary['DRAFT'] ?? 0 }}
                        </h2>

                        <i class="bi bi-file-earmark text-secondary fs-3"></i>

                    </div>

                </div>

            </div>


            {{-- SUBMITED --}}

            <div class="col-xl col-md-4 col-sm-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Terkirim
                        </div>

                        <h2 class="fw-bold">
                            {{ $statusSummary['SUBMITED'] ?? 0 }}
                        </h2>

                        <i class="bi bi-send text-warning fs-3"></i>

                    </div>

                </div>

            </div>


            {{-- VERIFIED --}}

            <div class="col-xl col-md-4 col-sm-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Sudah Diverifikasi
                        </div>

                        <h2 class="fw-bold">
                            {{ $statusSummary['VERIFIED'] ?? 0 }}
                        </h2>

                        <i class="bi bi-patch-check text-success fs-3"></i>

                    </div>

                </div>

            </div>


            {{-- REJECTED --}}

            <div class="col-xl col-md-4 col-sm-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Ditolak
                        </div>

                        <h2 class="fw-bold">
                            {{ $statusSummary['REJECTED'] ?? 0 }}
                        </h2>

                        <i class="bi bi-x-circle text-danger fs-3"></i>

                    </div>

                </div>

            </div>


            {{-- FINAL --}}

            <div class="col-xl col-md-4 col-sm-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Selesai
                        </div>

                        <h2 class="fw-bold">
                            {{ $statusSummary['FINAL'] ?? 0 }}
                        </h2>

                        <i class="bi bi-check2-all text-primary fs-3"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- ======================================================
             TOP 10 PEMAKAIAN OBAT
        ======================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-bar-chart-fill me-2"></i>

                    10 Besar Pemakaian Obat

                </h5>

                <small class="text-muted">

                    {{ $namaBulan[$bulan] ?? '' }}
                    {{ $tahun }}

                </small>

            </div>

            <div class="card-body">

                <div style="height:400px">

                    <canvas id="chartPuskesmas"></canvas>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection


@push('scripts')

{{-- ==============================================================
     CHART JS
================================================================ --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | DINKES CHART
    |--------------------------------------------------------------------------
    */

    @if(auth()->user()->groupid == 2)

        const dinkesData = @json($chartDinkes);


        const dinkesLabels =
            dinkesData.map(function (item) {

                return item.nama_faskes ?? '-';

            });


        const dinkesValues =
            dinkesData.map(function (item) {

                return Number(
                    item.jumlah_item ?? 0
                );

            });


        const dinkesCanvas =
            document.getElementById(
                'chartDinkes'
            );


        if (dinkesCanvas) {

            new Chart(
                dinkesCanvas,
                {

                    type: 'bar',

                    data: {

                        labels:
                            dinkesLabels,

                        datasets: [

                            {

                                label:
                                    'Jumlah Item Obat',

                                data:
                                    dinkesValues

                            }

                        ]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        scales: {

                            x: {

                                title: {

                                    display: true,

                                    text:
                                        'Nama Faskes'

                                }

                            },

                            y: {

                                beginAtZero: true,

                                ticks: {

                                    precision: 0

                                },

                                title: {

                                    display: true,

                                    text:
                                        'Jumlah Item Obat'

                                }

                            }

                        },

                        plugins: {

                            legend: {

                                display: false

                            }

                        }

                    }

                }
            );

        }

    @endif


    /*
    |--------------------------------------------------------------------------
    | PUSKESMAS CHART
    |--------------------------------------------------------------------------
    */

    @if(
        in_array(
            auth()->user()->groupid,
            [3, 5]
        )
    )

        const puskesmasData =
            @json($chartPuskesmas);


        const puskesmasLabels =
            puskesmasData.map(function (item) {

                return (
                    item.nama_obat ??
                    item.kode_obat ??
                    '-'
                );

            });


        const puskesmasValues =
            puskesmasData.map(function (item) {

                return Number(
                    item.total_pemakaian ?? 0
                );

            });


        const puskesmasCanvas =
            document.getElementById(
                'chartPuskesmas'
            );


        if (puskesmasCanvas) {

            new Chart(
                puskesmasCanvas,
                {

                    type: 'bar',

                    data: {

                        labels:
                            puskesmasLabels,

                        datasets: [

                            {

                                label:
                                    'Total Pemakaian',

                                data:
                                    puskesmasValues

                            }

                        ]

                    },

                    options: {

                        indexAxis: 'y',

                        responsive: true,

                        maintainAspectRatio: false,

                        scales: {

                            x: {

                                beginAtZero: true,

                                ticks: {

                                    precision: 0

                                },

                                title: {

                                    display: true,

                                    text:
                                        'Jumlah Pemakaian'

                                }

                            },

                            y: {

                                title: {

                                    display: true,

                                    text:
                                        'Jenis Obat'

                                }

                            }

                        },

                        plugins: {

                            legend: {

                                display: false

                            }

                        }

                    }

                }
            );

        }

    @endif

});

</script>

@endpush
