@extends('newlplpo.layouts.master')

@section('title','Detail LPLPO')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-success text-white">

            <div class="d-flex justify-content-between">

                <h4 class="mb-0">

                    Detail Laporan LPLPO

                </h4>

                @php

                    $badge = match($report->report_status){

                        'DRAFT'=>'secondary',

                        'SUBMITED'=>'info',

                        'VERIFIED'=>'primary',

                        'FINAL'=>'success',

                        'REJECTED'=>'danger',

                        default=>'dark'

                    };

                    $status = match($report->report_status){
                         'DRAFT'=>'DRAFT',

                        'SUBMITED'=>'TERKIRIM',

                        'VERIFIED'=>'TERVERIFIKASI',

                        'FINAL'=>'SELESAI',

                        'REJECTED'=>'DITOLAK',

                        default=>'NEW'

                    }

                @endphp

                <span class="badge bg-{{$badge}} fs-6">

                    {{ $status }}

                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

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

                                {{ $report->created_at->format('d-m-Y H:i') }}

                            </td>

                        </tr>

                    </table>

                </div>

                <div class="col-md-6">

                    <table class="table table-borderless">

                        <tr>

                            <th width="180">

                                Nama Faskes

                            </th>

                            <td>

                                {{ $faskes->namaFaskes }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Kecamatan

                            </th>

                            <td>

                                {{ $faskes->kecamatan->name }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Kabupaten

                            </th>

                            <td>

                                {{ $faskes->kota->name }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Provinsi

                            </th>

                            <td>

                                {{ $faskes->provinsi->name }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <!-- TABLE ITEM -->
  <div class="card shadow-sm mt-3">

    <div class="card-header bg-success text-white">

        <div class="d-flex justify-content-between">

            <h5 class="mb-0">

                Detail Item Obat

            </h5>

            <span class="badge bg-light text-dark">

                {{ $items->count() }} Item

            </span>

        </div>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-bordered table-hover table-sm mb-0">

                <thead>

                    <tr class="table-success text-center">

                        <th rowspan="2">No</th>
                        <th rowspan="2">Program</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Nama Obat</th>
                        <th rowspan="2">Sat</th>

                        <th colspan="2">Stok Awal</th>

                        <th colspan="2">Penerimaan</th>

                        <th colspan="2">Persediaan</th>

                        <th colspan="2">Pemakaian</th>

                        <th rowspan="2">Expired</th>

                        <th colspan="2">Stok Akhir</th>

                        <th rowspan="2">Permintaan</th>

                        <th rowspan="2">Pemberian</th>

                    </tr>

                    <tr class="table-success text-center">

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
                    $program = '';
                    $no = 1;
                @endphp

                @foreach($items as $item)

                   @php
    $programName = optional($item->program)->program_name ?? 'Non Program';
@endphp

@if($program != $programName)

    @if(strtolower($programName) != 'non program')

        <tr class="table-primary">

            <td colspan="18">

                <strong>{{ $programName }}</strong>

            </td>

        </tr>

    @endif

    @php
        $program = $programName;
    @endphp

@endif

                    <tr>

                        <td>{{ $no++ }}</td>

                        <td>{{ optional($item->program)->program_name }}</td>

                        <td>{{ $item->kode_obat }}</td>

                        <td>{{ $item->nama_obat }}</td>

                        <td>{{ $item->satuan }}</td>

                        <td class="text-end">{{ number_format($item->stok_awal_progam_pkd) }}</td>
                        <td class="text-end">{{ number_format($item->stok_awal_jkn) }}</td>

                        <td class="text-end">{{ number_format($item->penerimaan_program_pkd) }}</td>
                        <td class="text-end">{{ number_format($item->penerimaan_jkn) }}</td>

                        <td class="text-end">{{ number_format($item->persediaan_program_pkd) }}</td>
                        <td class="text-end">{{ number_format($item->persediaan_jkn) }}</td>

                        <td class="text-end">{{ number_format($item->pemakaian_program_pkd) }}</td>
                        <td class="text-end">{{ number_format($item->pemakaian_jkn) }}</td>

                        <td class="text-end">{{ number_format($item->item_expired) }}</td>

                        <td class="text-end">{{ number_format($item->stok_akhir_program_pkd) }}</td>
                        <td class="text-end">{{ number_format($item->stok_akhir_jkn) }}</td>

                        <td class="text-end fw-bold">
                            {{ number_format($item->permintaan) }}
                        </td>

                        <td class="text-end fw-bold text-success">
                            {{ number_format($item->pemberian) }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

<!--- BUTTON -->

<div class="text-end mt-4">

<a
    href="{{ route('newlplpo.laporan') }}"
    class="btn btn-secondary">

    <i class="bi bi-arrow-left"></i>

    Kembali

</a>

<button
    class="btn btn-success">

    <i class="bi bi-printer"></i>

    Cetak

</button>

</div>
@endsection
