@extends('newlplpo.layouts.master')

@section('title', 'Monitoring LPLPO')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">
            <div class="row align-items-center">

                <div class="col-md-6">
                    <h5 class="mb-0">
                        Monitoring LPLPO
                    </h5>
                </div>

                <div class="col-md-3 ms-auto">

                    <label for="filterTahun" class="form-label mb-1">
                        Tahun
                    </label>

                    <select
                        id="filterTahun"
                        class="form-select form-select-sm"
                    >
                        @for($tahun = now()->year + 1; $tahun >= now()->year - 5; $tahun--)
                            <option
                                value="{{ $tahun }}"
                                {{ $tahun == now()->year ? 'selected' : '' }}
                            >
                                {{ $tahun }}
                            </option>
                        @endfor
                    </select>

                </div>

            </div>
        </div>

        <div class="card-body">

            {{-- Legend --}}
            <div class="mb-3">

                <div class="d-flex flex-wrap gap-2">

                    <span class="status-legend status-submited">
                        SUBMITED
                    </span>

                    <span class="status-legend status-verified">
                        VERIFIED
                    </span>

                    <span class="status-legend status-rejected">
                        REJECTED
                    </span>

                    <span class="status-legend status-final">
                        FINAL
                    </span>

                </div>

            </div>

            {{-- Loading --}}
            <div
                id="monitoringLoading"
                class="text-center py-4 d-none"
            >
                <div
                    class="spinner-border spinner-border-sm"
                    role="status"
                ></div>

                <span class="ms-2">
                    Memuat data...
                </span>
            </div>

            {{-- Error --}}
            <div
                id="monitoringError"
                class="alert alert-danger d-none"
            ></div>

            {{-- Table --}}
            <div class="table-responsive">

                <table
                    id="tableMonitoringLplpo"
                    class="table table-bordered table-sm align-middle text-center mb-0"
                >

                    <thead>

                        <tr>

                            <th
                                rowspan="2"
                                class="faskes-header"
                            >
                                Faskes
                            </th>

                            <th style="width: 60%"
                                colspan="12"
                                class="bulan-header"
                            >
                                Bulan
                            </th>

                        </tr>

                        <tr>

                            <th>Jan</th>
                            <th>Feb</th>
                            <th>Mar</th>
                            <th>Apr</th>
                            <th>Mei</th>
                            <th>Jun</th>
                            <th>Jul</th>
                            <th>Agt</th>
                            <th>Sep</th>
                            <th>Okt</th>
                            <th>Nov</th>
                            <th>Des</th>

                        </tr>

                    </thead>

                    <tbody id="monitoringTableBody">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
@push('styles')

<style>

    /*
     * =========================================================
     * TABLE MONITORING
     * =========================================================
     */

    #tableMonitoringLplpo {
        min-width: 1320px;
        width: 100%;
        table-layout: fixed;
    }

    /*
     * Semua cell
     */
    #tableMonitoringLplpo th,
    #tableMonitoringLplpo td {
        border: 1px solid #999;
        vertical-align: middle;
    }

    /*
     * Header
     */
    #tableMonitoringLplpo thead th {
        background-color: #d9d9d9 !important;
        font-weight: 500;
        text-align: center;
        vertical-align: middle;
    }

    /*
     * Kolom Faskes
     */
    #tableMonitoringLplpo .faskes-header {
        width: 220px !important;
        min-width: 220px !important;
    }

    #tableMonitoringLplpo tbody td:first-child {
        width: 220px;
        min-width: 220px;
        text-align: left;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /*
     * Kolom bulan
     *
     * 12 x 90px = 1080px
     * + faskes 220px
     * = minimal sekitar 1300px
     */
    #tableMonitoringLplpo thead tr:nth-child(2) th {
        width: 90px !important;
        min-width: 90px !important;
    }

    /*
     * Cell report
     */
    #tableMonitoringLplpo .report-cell {
        width: 90px !important;
        min-width: 90px !important;
        height: 38px;
        padding: 4px !important;

        font-size: 12px;
        font-weight: 600;

        text-align: center;
        vertical-align: middle;

        white-space: nowrap;
    }


    /*
     * =========================================================
     * CELL KOSONG
     * =========================================================
     */

    #tableMonitoringLplpo .report-empty {
        background-color: #e9ecef !important;
        color: #6c757d !important;
    }


    /*
     * =========================================================
     * STATUS SUBMITED
     * =========================================================
     */

    #tableMonitoringLplpo .report-cell.status-submited {
        background-color: #fff3cd !important;
        color: #664d03 !important;
    }


    /*
     * =========================================================
     * STATUS VERIFIED
     * =========================================================
     */

    #tableMonitoringLplpo .report-cell.status-verified {
        background-color: #cfe2ff !important;
        color: #084298 !important;
    }


    /*
     * =========================================================
     * STATUS REJECTED
     * =========================================================
     */

    #tableMonitoringLplpo .report-cell.status-rejected {
        background-color: #f8d7da !important;
        color: #842029 !important;
    }


    /*
     * =========================================================
     * STATUS FINAL
     * =========================================================
     */

    #tableMonitoringLplpo .report-cell.status-final {
        background-color: #d1e7dd !important;
        color: #0f5132 !important;
    }


    /*
     * =========================================================
     * LEGEND
     * =========================================================
     */

    .status-legend {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-legend.status-submited {
        background-color: #fff3cd !important;
        color: #664d03 !important;
    }

    .status-legend.status-verified {
        background-color: #cfe2ff !important;
        color: #084298 !important;
    }

    .status-legend.status-rejected {
        background-color: #f8d7da !important;
        color: #842029 !important;
    }

    .status-legend.status-final {
        background-color: #d1e7dd !important;
        color: #0f5132 !important;
    }

</style>

@endpush

@push('script')

<script src="{{ mix('js/newlplpo/report_monitoring.js') }}"></script>

@endpush
