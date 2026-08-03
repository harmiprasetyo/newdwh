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


        {{-- FILTER --}}

        <form
            method="GET"
            action="{{ route('newlplpo.index') }}"
            class="d-flex gap-2">

            <select
                name="bulan"
                class="form-select">

                @foreach([
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
                    12 => 'Desember'
                ] as $key => $nama)

                    <option
                        value="{{ $key }}"
                        {{ $bulan == $key ? 'selected' : '' }}>

                        {{ $nama }}

                    </option>

                @endforeach

            </select>


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


            <button
                type="submit"
                class="btn btn-primary">

                <i class="bi bi-filter me-1"></i>

                Tampilkan

            </button>

        </form>

    </div>


    {{-- ==========================================================
         DINKES
    =========================================================== --}}

    @if($groupId == 2)

        @php
            $dinkes = $dashboard['dinkes'];
        @endphp


        {{-- ======================================================
             KPI
        ======================================================= --}}

        <div class="row g-3 mb-4">

            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Puskesmas Melapor
                                </div>

                                <h2 class="fw-bold mb-0">

                                    {{ $dinkes['jumlah_puskesmas_melapor'] }}

                                </h2>

                                <small class="text-muted">

                                    {{ $bulan }}/{{ $tahun }}

                                </small>

                            </div>

                            <div class="dashboard-icon bg-primary-subtle">

                                <i class="bi bi-hospital"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Laporan Terverifikasi
                                </div>

                                <h2 class="fw-bold mb-0">

                                    {{ $dinkes['jumlah_verified'] }}

                                </h2>

                                <small class="text-muted">
                                    Status VERIFIED
                                </small>

                            </div>

                            <div class="dashboard-icon bg-success-subtle">

                                <i class="bi bi-patch-check-fill"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Laporan Selesai
                                </div>

                                <h2 class="fw-bold mb-0">

                                    {{ $dinkes['jumlah_selesai'] }}

                                </h2>

                                <small class="text-muted">
                                    {{ $bulan }}/{{ $tahun }}
                                </small>

                            </div>

                            <div class="dashboard-icon bg-info-subtle">

                                <i class="bi bi-check2-all"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ======================================================
             CHART FASKES
        ======================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-3">

                <h6 class="fw-bold mb-0">

                    <i class="bi bi-bar-chart-fill me-2"></i>

                    Jumlah Item Obat Dilaporkan per Faskes

                </h6>

                <small class="text-muted">

                    Periode:
                    {{ $bulan }}/{{ $tahun }}

                </small>

            </div>

            <div class="card-body">

                <div
                    id="chartFaskes"
                    style="min-height: 380px;">
                </div>

            </div>

        </div>


        {{-- ======================================================
             TABEL REKAP
        ======================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-3">

                <h6 class="fw-bold mb-0">

                    <i class="bi bi-table me-2"></i>

                    Rekap Laporan LPLPO

                </h6>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

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
                                $dinkes['rekap_laporan']
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

                                        {{ $row->created_at
                                            ? \Carbon\Carbon::parse($row->created_at)
                                                ->format('d-m-Y H:i')
                                            : '-'
                                        }}

                                    </td>

                                    <td>

                                        @php
                                            $status = strtoupper(
                                                $row->report_status ?? ''
                                            );
                                        @endphp

                                        @if($status === 'VERIFIED')

                                            <span class="badge bg-success">
                                                VERIFIED
                                            </span>

                                        @elseif(
                                            in_array(
                                                $status,
                                                ['COMPLETED','SELESAI']
                                            )
                                        )

                                            <span class="badge bg-primary">
                                                SELESAI
                                            </span>

                                        @elseif($status === 'SUBMITTED')

                                            <span class="badge bg-warning text-dark">
                                                SUBMITTED
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ $status ?: '-' }}
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

                                        Belum ada laporan
                                        pada periode ini.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


    {{-- ==========================================================
         PUSKESMAS
    =========================================================== --}}

    @else

        @php
            $puskesmas = $dashboard['puskesmas'];
        @endphp


        {{-- ======================================================
             STATUS BOX
        ======================================================= --}}

        <div class="row g-3 mb-4">

            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="text-muted small">
                            Total Laporan
                        </div>

                        <h2 class="fw-bold mb-0">

                            {{ $puskesmas['total_laporan'] }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="text-muted small">
                            Draft
                        </div>

                        <h2 class="fw-bold text-secondary mb-0">

                            {{ $puskesmas['draft'] }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="text-muted small">
                            Terverifikasi
                        </div>

                        <h2 class="fw-bold text-success mb-0">

                            {{ $puskesmas['verified'] }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="text-muted small">
                            Selesai
                        </div>

                        <h2 class="fw-bold text-primary mb-0">

                            {{ $puskesmas['selesai'] }}

                        </h2>

                    </div>

                </div>

            </div>

        </div>


        {{-- ======================================================
             TOP 10 PEMAKAIAN
        ======================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-3">

                <h6 class="fw-bold mb-0">

                    <i class="bi bi-bar-chart-fill me-2"></i>

                    10 Besar Pemakaian Obat

                </h6>

                <small class="text-muted">

                    Periode:
                    {{ $bulan }}/{{ $tahun }}

                </small>

            </div>


            <div class="card-body">

                <div
                    id="chartPemakaian"
                    style="min-height: 450px;">
                </div>

            </div>

        </div>

    @endif

</div>

@endsection


@push('styles')

<style>

.dashboard-icon {

    width: 48px;

    height: 48px;

    border-radius: 12px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 22px;

}

</style>

@endpush


@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | APEXCHART
    |--------------------------------------------------------------------------
    */

    @if($groupId == 2)

        const faskesData = @json(
            $dashboard['dinkes']['chart_faskes']
        );


        const faskesNames =
            faskesData.map(function (item) {

                return item.nama_faskes ?? '-';

            });


        const faskesValues =
            faskesData.map(function (item) {

                return Number(
                    item.jumlah_item
                );

            });


        new ApexCharts(

            document.querySelector(
                "#chartFaskes"
            ),

            {

                chart: {

                    type: 'bar',

                    height: 380,

                    toolbar: {

                        show: false

                    }

                },

                plotOptions: {

                    bar: {

                        horizontal: false,

                        columnWidth: '55%',

                        borderRadius: 5

                    }

                },

                dataLabels: {

                    enabled: false

                },

                xaxis: {

                    categories:
                        faskesNames,

                    title: {

                        text:
                            'Nama Faskes'

                    }

                },

                yaxis: {

                    min: 0,

                    title: {

                        text:
                            'Jumlah Item Obat'

                    }

                },

                series: [

                    {

                        name:
                            'Item Obat',

                        data:
                            faskesValues

                    }

                ],

                tooltip: {

                    y: {

                        formatter:
                            function (value) {

                                return value +
                                    ' item obat';

                            }

                    }

                }

            }

        ).render();

    @else

        const obatData = @json(
            $dashboard['puskesmas']['top_pemakaian']
        );


        const obatNames =
            obatData.map(function (item) {

                return (

                    item.nama_obat ??

                    item.kode_obat ??

                    '-'

                );

            });


        const obatValues =
            obatData.map(function (item) {

                return Number(
                    item.total_pemakaian
                );

            });


        new ApexCharts(

            document.querySelector(
                "#chartPemakaian"
            ),

            {

                chart: {

                    type: 'bar',

                    height: 450,

                    toolbar: {

                        show: false

                    }

                },

                plotOptions: {

                    bar: {

                        horizontal: true,

                        borderRadius: 5,

                        barHeight: '65%'

                    }

                },

                dataLabels: {

                    enabled: true

                },

                xaxis: {

                    title: {

                        text:
                            'Jumlah Pemakaian'

                    }

                },

                yaxis: {

                    categories:
                        obatNames,

                    title: {

                        text:
                            'Jenis Obat'

                    }

                },

                series: [

                    {

                        name:
                            'Pemakaian',

                        data:
                            obatValues

                    }

                ],

                tooltip: {

                    y: {

                        formatter:
                            function (value) {

                                return value;

                            }

                    }

                }

            }

        ).render();

    @endif

});

</script>

@endpush
