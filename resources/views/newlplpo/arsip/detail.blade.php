@extends('newlplpo.layouts.master')

@section('title', 'Detail LPLPO')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/newlplpo/arsip/detail.css') }}"
    >
@endpush


@section('content')

<div class="container-fluid">

    {{-- ==========================================================
        HEADER LAPORAN
    =========================================================== --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-success text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Detail Laporan LPLPO
                </h4>

                @php

                    $badge = match ($report->report_status) {

                        'DRAFT'    => 'secondary',
                        'SUBMITED' => 'info',
                        'VERIFIED' => 'primary',
                        'FINAL'    => 'success',
                        'REJECTED' => 'danger',

                        default => 'dark',
                    };

                    $status = match ($report->report_status) {

                        'DRAFT'    => 'DRAFT',
                        'SUBMITED' => 'TERKIRIM',
                        'VERIFIED' => 'TERVERIFIKASI',
                        'FINAL'    => 'SELESAI',
                        'REJECTED' => 'DITOLAK',

                        default => 'NEW',
                    };

                @endphp

                <span class="badge bg-{{ $badge }} fs-6">
                    {{ $status }}
                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- ==================================================
                    INFORMASI LAPORAN
                =================================================== --}}
                <div class="col-md-6">

                    <table class="table table-borderless">

                        <tr>
                            <th width="180">
                                Nomor LPLPO
                            </th>

                            <td>
                                {{ $report->nomor_lplpo }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Bulan
                            </th>

                            <td>
                                {{ $report->bulan }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Tahun
                            </th>

                            <td>
                                {{ $report->tahun }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Tanggal Laporan
                            </th>

                            <td>
                                {{ optional($report->created_at)->format('d-m-Y H:i') }}
                            </td>
                        </tr>

                    </table>

                </div>


                {{-- ==================================================
                    INFORMASI FASKES
                =================================================== --}}
                <div class="col-md-6">

                    <table class="table table-borderless">

                        <tr>
                            <th width="180">
                                Nama Faskes
                            </th>

                            <td>
                                {{ optional($faskes)->namaFaskes }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Kecamatan
                            </th>

                            <td>
                                {{ optional(optional($faskes)->kecamatan)->name }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Kabupaten
                            </th>

                            <td>
                                {{ optional(optional($faskes)->kota)->name }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Provinsi
                            </th>

                            <td>
                                {{ optional(optional($faskes)->provinsi)->name }}
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>



    {{-- ==========================================================
        LEGEND
    =========================================================== --}}
    <div class="card shadow-sm mb-3">

        <div class="card-body py-2">

            <div class="d-flex flex-wrap align-items-center gap-3">

                <strong>
                    Keterangan:
                </strong>


                {{-- OE --}}
                <span class="d-inline-flex align-items-center gap-1">

                    <span class="badge bg-success">
                        OE
                    </span>

                    <span>
                        Obat Esensial
                    </span>

                </span>


                {{-- NOE --}}
                <span class="d-inline-flex align-items-center gap-1">

                    <span class="badge bg-secondary">
                        NOE
                    </span>

                    <span>
                        Non Obat Esensial
                    </span>

                </span>


                {{-- FORMULARIUM --}}
                <span class="d-inline-flex align-items-center gap-1">

                    <span class="badge bg-primary">
                        Ya
                    </span>

                    <span>
                        Formularium PKM
                    </span>

                </span>


                {{-- NAPZA --}}
                <span class="d-inline-flex align-items-center gap-1">

                    <span class="badge bg-danger">
                        NAPZA
                    </span>

                    <span>
                        Obat NAPZA
                    </span>

                </span>


                {{-- BARIS NAPZA --}}
                <span class="d-inline-flex align-items-center gap-1">

                    <span class="legend-box">
                    </span>

                    <span>
                        Baris obat NAPZA
                    </span>

                </span>

            </div>

        </div>

    </div>



    {{-- ==========================================================
        DETAIL ITEM OBAT
    =========================================================== --}}
    <div class="card shadow-sm mt-3">

        <div class="card-header bg-success text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Detail Item Obat
                </h5>

                <span class="badge bg-light text-dark">

                    {{ $items->count() }}

                    Item

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    id="tableDetailLplpo"
                    class="table table-bordered table-hover table-sm mb-0"
                >

                    {{-- ==================================================
                        HEADER LEVEL 1
                    =================================================== --}}
                    <thead>

                        <tr class="table-success text-center">

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


                        {{-- ==================================================
                            HEADER LEVEL 2
                        =================================================== --}}
                        <tr class="table-success text-center">

                            <th>
                                PKD
                            </th>

                            <th>
                                JKN
                            </th>


                            <th>
                                PKD
                            </th>

                            <th>
                                JKN
                            </th>


                            <th>
                                PKD
                            </th>

                            <th>
                                JKN
                            </th>


                            <th>
                                PKD
                            </th>

                            <th>
                                JKN
                            </th>


                            <th>
                                PKD
                            </th>

                            <th>
                                JKN
                            </th>


                            <th>
                                PKD
                            </th>

                            <th>
                                JKN
                            </th>




                        </tr>

                    </thead>



                    {{-- ==================================================
                        BODY
                    =================================================== --}}
                    <tbody>

                    @php

                        $program = '';

                        $no = 1;

                    @endphp


                    @forelse($items as $item)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | PROGRAM
                            |--------------------------------------------------------------------------
                            */
                            $programName =
                                trim(
                                    $item->program_name
                                    ?? optional($item->program)->program_name
                                    ?? 'Non Program'
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | NON PROGRAM
                            |--------------------------------------------------------------------------
                            |
                            | program_id = 1 ATAU nama program = Non Program
                            |
                            */
                            $isNonProgram =
                                (int) $item->program_id === 1
                                ||
                                strtolower($programName) === 'non program';


                            /*
                            |--------------------------------------------------------------------------
                            | NAPZA
                            |--------------------------------------------------------------------------
                            */
                            $isNapza =
                                strtolower(
                                    (string) (
                                        $item->obat_napza
                                        ?? 'tidak'
                                    )
                                ) === 'ya';


                            /*
                            |--------------------------------------------------------------------------
                            | ESENSIAL
                            |--------------------------------------------------------------------------
                            */
                            $isEssential =
                                strtolower(
                                    (string) (
                                        $item->obat_esensial
                                        ?? 'noe'
                                    )
                                ) === 'oe';


                            /*
                            |--------------------------------------------------------------------------
                            | FORMULARIUM
                            |--------------------------------------------------------------------------
                            */
                            $isFormularium =
                                strtolower(
                                    (string) (
                                        $item->obat_formularium_puskesmas
                                        ?? 'false'
                                    )
                                ) === 'true';

                        @endphp



                        {{-- ==================================================
                            PROGRAM SEPARATOR
                        =================================================== --}}
                        @if(!$isNonProgram && $program !== $programName)

                            <tr class="table-primary">

                                <td colspan="21">

                                    <strong>
                                        {{ $programName }}
                                    </strong>

                                </td>

                            </tr>

                            @php
                                $program = $programName;
                            @endphp

                        @elseif($isNonProgram)

                            @php
                                $program = '__NON_PROGRAM__';
                            @endphp

                        @endif



                        {{-- ==================================================
                            DATA ITEM
                        =================================================== --}}
                        <tr
                            class="{{ $isNapza ? 'table-napza' : '' }}"
                        >

                            {{-- NO --}}
                            <td class="text-center">

                                {{ $no++ }}

                            </td>


                            {{-- KODE --}}
                            <td>

                                {{ $item->kode_obat }}

                            </td>


                            {{-- NAMA OBAT --}}
                            <td>

                                <span>

                                    {{ $item->nama_obat }}

                                </span>


                                @if($isNapza)

                                    <span class="badge bg-danger ms-1">

                                        NAPZA

                                    </span>

                                @endif

                            </td>


                            {{-- SATUAN --}}
                            <td>

                                {{ $item->satuan }}

                            </td>


                            {{-- ESENSIAL --}}
                            <td class="text-center">

                                @if($isEssential)

                                    <span class="badge bg-success">
                                        OE
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        NOE
                                    </span>

                                @endif

                            </td>


                            {{-- FORMULARIUM --}}
                            <td class="text-center">

                                @if($isFormularium)

                                    <span class="badge bg-primary">
                                        Ya
                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- STOK AWAL --}}
                            <td class="text-end">
                                {{ number_format($item->stok_awal_progam_pkd) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->stok_awal_jkn) }}
                            </td>


                            {{-- PENERIMAAN --}}
                            <td class="text-end">
                                {{ number_format($item->penerimaan_program_pkd) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->penerimaan_jkn) }}
                            </td>


                            {{-- PERSEDIAAN --}}
                            <td class="text-end">
                                {{ number_format($item->persediaan_program_pkd) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->persediaan_jkn) }}
                            </td>


                            {{-- PEMAKAIAN --}}
                            <td class="text-end">
                                {{ number_format($item->pemakaian_program_pkd) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->pemakaian_jkn) }}
                            </td>


                            {{-- EXPIRED --}}
                            <td class="text-end">
                                {{ number_format($item->item_expired_pkd) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->item_expired_jkn) }}
                            </td>


                            {{-- STOK AKHIR --}}
                            <td class="text-end">
                                {{ number_format($item->stok_akhir_program_pkd) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->stok_akhir_jkn) }}
                            </td>


                            {{-- PERMINTAAN --}}
                            <td class="text-end fw-bold">

                                {{ number_format($item->permintaan) }}

                            </td>


                            {{-- PEMBERIAN --}}
                            <td class="text-end fw-bold text-success">

                                {{ number_format($item->pemberian_program_pkd) }}

                            </td>



                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="21"
                                class="text-center text-muted py-4"
                            >

                                Tidak ada data item obat.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



   {{-- ==========================================================
    BUTTON
=========================================================== --}}
<div class="d-flex justify-content-end gap-2 mt-4">

    <a
        href="{{ route('newlplpo.arsip.index') }}"
        class="btn btn-secondary"
    >
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>


    <button
        type="button"
        class="btn btn-success"
        id="btnPrint"
    >
        <i class="bi bi-printer"></i>
        Cetak
    </button>


    <button
        type="button"
        class="btn btn-success"
        id="btnExportExcel"
        data-excel-url="{{ route('newlplpo.arsip.export.excel', $report->id) }}"
    >
        <i class="bi bi-file-earmark-excel"></i>
        Excel
    </button>


    <button
        type="button"
        class="btn btn-danger"
        id="btnExportPdf"
        data-pdf-url="{{ route('newlplpo.arsip.export.pdf', $report->id) }}"
    >
        <i class="bi bi-file-earmark-pdf"></i>
        PDF
    </button>

</div>
</div>

@endsection


@push('script')

    <script src="{{ asset('js/newlplpo/arsip/detail.js') }}"></script>

@endpush
