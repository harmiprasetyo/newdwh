<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <style>

        @page {
            size: A4 landscape;
            margin: 7mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 6px;
        }

        h2 {
            text-align: center;
            margin: 0 0 8px;
        }

        .periode {
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 0.5px solid #555;
            padding: 3px;
            vertical-align: middle;
        }

        th {
            background: #198754;
            color: white;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .program-row td {
            background: #cfe2ff;
            font-weight: bold;
            text-align: left;
        }

        .napza-row td {
            background: #f8d7da;
        }

        .oe {
            color: #198754;
            font-weight: bold;
        }

        .noe {
            color: #6c757d;
            font-weight: bold;
        }

        .formularium {
            color: #0d6efd;
            font-weight: bold;
        }

        .badge-napza {
            color: #dc3545;
            font-weight: bold;
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

    <h2>
        REKAPITULASI LPLPO
    </h2>

    <div class="periode">
        <strong>Periode:</strong>

        {{ sprintf(
            '%02d/%04d - %02d/%04d',
            $bulanMulai,
            $tahunMulai,
            $bulanSampai,
            $tahunSampai
        ) }}
    </div>

    <table>

        <thead>

            <tr>

                <th rowspan="2">No</th>
                <th rowspan="2">Kode</th>
                <th rowspan="2">Nama Obat</th>
                <th rowspan="2">Sat</th>
                <th rowspan="2">Esensial</th>
                <th rowspan="2">Formularium PKM</th>

                <th colspan="2">Stok Awal</th>
                <th colspan="2">Penerimaan</th>
                <th colspan="2">Persediaan</th>
                <th colspan="2">Pemakaian</th>
                <th colspan="2">Expired</th>
                <th colspan="2">Stok Akhir</th>

                <th rowspan="2">Permintaan</th>
                <th rowspan="2">Pemberian</th>

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

            @php
                $no = 1;
                $lastProgramId = null;
                $lastProgramName = null;
            @endphp

            @foreach($items as $item)

                @php

                    $programName = trim(
                        (string) (
                            $item->program_name
                            ?? 'Non Program'
                        )
                    );

                    if ($programName === '') {
                        $programName = 'Non Program';
                    }

                    $isNonProgram =
                        (int) $item->program_id === 1 ||
                        strtolower($programName) === 'non program';

                    $isNapza =
                        strtolower(
                            trim(
                                (string) $item->obat_napza
                            )
                        ) === 'ya';

                    $programChanged =
                        $lastProgramId !== $item->program_id ||
                        $lastProgramName !== $programName;

                @endphp

                @if(!$isNonProgram && $programChanged)

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

                <tr class="{{ $isNapza ? 'napza-row' : '' }}">

                    <td class="center">
                        {{ $no++ }}
                    </td>

                    <td>
                        {{ $item->kode_obat }}
                    </td>

                    <td>
                        {{ $item->nama_obat }}

                        @if($isNapza)
                            <span class="badge-napza">
                                [NAPZA]
                            </span>
                        @endif
                    </td>

                    <td class="center">
                        {{ $item->satuan }}
                    </td>

                    <td class="center">

                        @if(strtolower($item->obat_esensial) === 'oe')
                            <span class="oe">OE</span>
                        @else
                            <span class="noe">NOE</span>
                        @endif

                    </td>

                    <td class="center">

                        @if(
                            strtolower(
                                $item->obat_formularium_puskesmas
                            ) === 'true'
                        )
                            <span class="formularium">
                                Ya
                            </span>
                        @else
                            Tidak
                        @endif

                    </td>

                    <td class="right">
                        {{ number_format($item->stok_awal_program_pkd) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->stok_awal_jkn) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->penerimaan_program_pkd) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->penerimaan_jkn) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->persediaan_program_pkd) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->persediaan_jkn) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->pemakaian_program_pkd) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->pemakaian_jkn) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->item_expired_pkd) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->item_expired_jkn) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->stok_akhir_program_pkd) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->stok_akhir_jkn) }}
                    </td>

                    <td class="right">
                        {{ number_format($item->permintaan) }}
                    </td>

                    <td class="right">
                        {{
                            number_format(
                                (int) $item->pemberian_program_pkd +
                                (int) $item->pemberian_jkn
                            )
                        }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>
