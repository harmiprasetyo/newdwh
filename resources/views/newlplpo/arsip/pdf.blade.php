<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>
        LPLPO -
        {{ $report->nomor_lplpo ?: $report->id }}
    </title>

    <style>

        @page {
            size: A4 landscape;
            margin: 8mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 6.5px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .metadata {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .metadata td {
            padding: 2px 3px;
            vertical-align: top;
        }

        .metadata .label {
            width: 100px;
            font-weight: bold;
        }

        .legend {
            width: 100%;
            margin-bottom: 5px;
        }

        .legend td {
            padding: 2px 4px;
        }

        .table-detail {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table-detail th,
        .table-detail td {
            border: 0.5px solid #000;
            padding: 2px;
            vertical-align: middle;
        }

        .table-detail th {
            background: #4472C4;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .table-detail td {
            text-align: right;
        }

        .table-detail td:nth-child(1),
        .table-detail td:nth-child(2),
        .table-detail td:nth-child(4),
        .table-detail td:nth-child(5),
        .table-detail td:nth-child(6) {
            text-align: center;
        }

        .table-detail td:nth-child(3) {
            text-align: left;
        }

        .program-row td {
            background: #CFE2FF;
            font-weight: bold;
            text-align: left !important;
            font-size: 7px;
            padding: 3px;
        }

        .napza-row td {
            background: #F8D7DA;
        }

        .oe {
            color: #198754;
            font-weight: bold;
        }

        .noe {
            color: #6C757D;
            font-weight: bold;
        }

        .formularium {
            color: #0D6EFD;
            font-weight: bold;
        }

        .legend-box {
            display: inline-block;
            width: 10px;
            height: 8px;
            background: #F8D7DA;
            border: 0.5px solid #E5AEB3;
        }

        .page-break {
            page-break-after: always;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

    </style>
</head>

<body>

    {{-- ==========================================================
         TITLE
    =========================================================== --}}

    <div class="title">
        LPLPO - LAPORAN PEMAKAIAN DAN LEMBAR PERMINTAAN OBAT
    </div>


    {{-- ==========================================================
         METADATA
    =========================================================== --}}

    <table class="metadata">

        <tr>
            <td class="label">
                Nomor LPLPO
            </td>

            <td>
                {{ $report->nomor_lplpo }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Fasilitas Kesehatan
            </td>

            <td>
                {{ $report->nama_faskes ?? optional($faskes)->namaFaskes }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Periode
            </td>

            <td>
                {{ sprintf('%02d/%d', $report->bulan, $report->tahun) }}
            </td>
        </tr>

    </table>


    {{-- ==========================================================
         LEGEND
    =========================================================== --}}

    <table class="legend">

        <tr>

            <td>
                <strong>Keterangan:</strong>
            </td>

            <td>
                <span class="oe">OE</span>
                = Obat Esensial
            </td>

            <td>
                <span class="noe">NOE</span>
                = Non Obat Esensial
            </td>

            <td>
                <span class="formularium">Ya</span>
                = Formularium PKM
            </td>

            <td>
                <strong>NAPZA</strong>
                = Obat NAPZA
            </td>

            <td>
                <span class="legend-box"></span>
                Baris obat NAPZA
            </td>

        </tr>

    </table>


    {{-- ==========================================================
         DETAIL
    =========================================================== --}}

    <table class="table-detail">

        <thead>

            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Obat</th>
                <th>Sat</th>
                <th>Esensial</th>
                <th>Formularium PKM</th>

                <th>Stok Awal PKD</th>
                <th>Stok Awal JKN</th>

                <th>Penerimaan PKD</th>
                <th>Penerimaan JKN</th>

                <th>Persediaan PKD</th>
                <th>Persediaan JKN</th>

                <th>Pemakaian PKD</th>
                <th>Pemakaian JKN</th>

                <th>Expired PKD</th>
                <th>Expired JKN</th>

                <th>Stok Akhir PKD</th>
                <th>Stok Akhir JKN</th>

                <th>Permintaan</th>
                <th>Pemberian</th>
            </tr>

        </thead>

        <tbody>

            @php
                $lastProgramId = null;
                $lastProgramName = null;
                $itemNumber = 0;
            @endphp

            @foreach ($items as $item)

                @php

                    $programName = trim(
                        (string) (
                            $item->program_name
                            ?? optional($item->program)->program_name
                            ?? 'Non Program'
                        )
                    );

                    if ($programName === '') {
                        $programName = 'Non Program';
                    }

                    $isNonProgram =
                        (int) $item->program_id === 1
                        ||
                        strtolower($programName) === 'non program';

                    $isNapza =
                        strtolower(
                            trim(
                                (string) (
                                    $item->obat_napza ?? 'tidak'
                                )
                            )
                        ) === 'ya';

                    $isEssential =
                        strtolower(
                            trim(
                                (string) (
                                    $item->obat_esensial ?? 'noe'
                                )
                            )
                        ) === 'oe';

                    $isFormularium =
                        strtolower(
                            trim(
                                (string) (
                                    $item->obat_formularium_puskesmas
                                    ?? 'false'
                                )
                            )
                        ) === 'true';

                @endphp


                {{-- ==================================================
                     PROGRAM SEPARATOR
                =================================================== --}}

                @if (!$isNonProgram)

                    @php
                        $programChanged =
                            $lastProgramId !== $item->program_id
                            ||
                            $lastProgramName !== $programName;
                    @endphp

                    @if ($programChanged)

                        <tr class="program-row">

                            <td colspan="20">
                                {{ $programName }}
                            </td>

                        </tr>

                        @php
                            $lastProgramId = $item->program_id;
                            $lastProgramName = $programName;
                        @endphp

                    @endif

                @endif


                {{-- ==================================================
                     ITEM
                =================================================== --}}

                @php
                    $itemNumber++;

                    $namaObat = $item->nama_obat;

                    if ($isNapza) {
                        $namaObat .= ' [NAPZA]';
                    }
                @endphp

                <tr class="{{ $isNapza ? 'napza-row' : '' }}">

                    <td>
                        {{ $itemNumber }}
                    </td>

                    <td>
                        {{ $item->kode_obat }}
                    </td>

                    <td>
                        {{ $namaObat }}
                    </td>

                    <td>
                        {{ $item->satuan }}
                    </td>

                    <td class="{{ $isEssential ? 'oe' : 'noe' }}">
                        {{ strtoupper($item->obat_esensial ?: 'NOE') }}
                    </td>

                    <td class="{{ $isFormularium ? 'formularium' : '' }}">
                        {{ $isFormularium ? 'Ya' : 'Tidak' }}
                    </td>

                    <td>
                        {{ number_format((int) $item->stok_awal_program_pkd) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->stok_awal_jkn) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->penerimaan_program_pkd) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->penerimaan_jkn) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->persediaan_program_pkd) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->persediaan_jkn) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->pemakaian_program_pkd) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->pemakaian_jkn) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->expired_program_pkd) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->expired_jkn) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->stok_akhir_program_pkd) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->stok_akhir_jkn) }}
                    </td>

                    <td>
                        {{ number_format((int) $item->permintaan) }}
                    </td>

                    <td>
                        {{ number_format(
                            (int) $item->pemberian_program_pkd
                            +
                            (int) $item->pemberian_jkn
                        ) }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</body>

</html>
