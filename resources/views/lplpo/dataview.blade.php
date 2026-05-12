<!-- <!DOCTYPE html>
<html>
<head>
    <title>Data LPLPO</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>-->

@extends('layouts.lplpo')

@section('content')
<body class="bg-light">

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data LPLPO</h5>
        </div>

        <div class="card-body">

            <!-- FILTER -->
            <div class="row mb-3">

                <div class="col-md-3">
                    <select id="filter_bulan" class="form-control">
                        <option value="">Semua Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="number" id="filter_tahun" class="form-control" placeholder="Tahun">
                </div>

                <div class="col-md-3">
    <select id="filter_faskes" class="form-control">
        <option value="">Semua Faskes</option>
        @foreach($faskes as $f)
            <option value="{{ $f->kodeFaskes }}">
                {{ $f->namaFaskes }}
            </option>
        @endforeach
    </select>
</div>

                <div class="col-md-3">
                    <button id="btn_filter" class="btn btn-primary w-100">Filter</button>
                </div>

            </div>

          <div class="table-container">
    <table id="table_lplpo" class="table table-bordered table-sm">
               <thead>
<tr>
    <th rowspan="2">No</th>
    <th rowspan="2">Nama Obat</th>
    <th rowspan="2">Faskes</th>
    <th rowspan="2">Bulan</th>
    <th rowspan="2">Tahun</th>

    <th colspan="2">Stok Awal</th>
    <th colspan="2">Penerimaan</th>
    <th colspan="2">Persediaan</th>
    <th colspan="2">Pemakaian</th>

    <th rowspan="2">Kadaluarsa</th>
    <th rowspan="2">Pengembalian</th>

    <th colspan="2">Stok Akhir</th>

    <th rowspan="2">RKO</th>
    <th rowspan="2">Stok Optimum</th>
    <th rowspan="2">Permintaan</th>
</tr>

<tr>
    <th>PKD</th><th>Program</th>
    <th>PKD</th><th>Program</th>
    <th>PKD</th><th>Program</th>
    <th>PKD</th><th>Program</th>
    <th>PKD</th><th>Program</th>
</tr>
</thead>
            </table>
           </div>

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
let table = $('#table_lplpo').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '/lplpo/data',
        data: function (d) {
            d.bulan = $('#filter_bulan').val();
            d.tahun = $('#filter_tahun').val();
            d.faskes = $('#filter_faskes').val();
        },
    processing: true,
    serverSide: true,
    scrollX: true, // 🔥 WAJIB
    autoWidth: false, // 🔥 penting
    },
    columnDefs: [
    { width: "50px", targets: 0 },  // No
    { width: "200px", targets: 1 }, // Nama Obat
],
columns: [
    { data: 'DT_RowIndex', orderable:false, searchable:false },
    { data: 'nama_obat' },
    { data: 'namaFaskes' }, // ✅ ganti ini
    { data: 'bulan' },
    { data: 'tahun' },

    { data: 'stok_awal_pkd' },
    { data: 'stok_awal_program' },

    { data: 'penerimaan_pkd' },
    { data: 'penerimaan_program' },

    { data: 'persediaan_pkd' },
    { data: 'persediaan_program' },

    { data: 'pemakaian_pkd' },
    { data: 'pemakaian_program' },

    { data: 'kadaluarsa' },
    { data: 'pengembalian' },

    { data: 'stok_akhir_pkd' },
    { data: 'stok_akhir_program' },

    { data: 'rko' },
    { data: 'stok_optimum' },
    { data: 'permintaan' }
],
    order: [[1, 'asc']]
});

// trigger filter
$('#btn_filter').click(function () {
    table.ajax.reload();
});
</script>

</body>
@endsection
