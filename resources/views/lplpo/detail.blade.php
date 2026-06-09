@extends('layouts.lplpo')

@section('content')
<!--

   <div class="card shadow" style="width: 100%;">
        <div class="card-header bg-info text-white">
            <h5>Detail LPLPO</h5>
        </div>
        <div class="card-body">
            <p><b>Faskes:</b> {{ $header->namaFaskes }}</p>
            <p><b>Bulan:</b> {{ $header->bulan }}</p>
            <p><b>Tahun:</b> {{ $header->tahun }}</p>
        </div>
    </div>

    <div class="card shadow mt-3" style="width: 100%;">
        <div class="card-body">
             <div class="table-container">
            <table id="table_detail" class="table table-bordered table-sm">
                <thead>
<tr>
    <th rowspan="2">No</th>
    <th rowspan="2">Nama Obat</th>
    <th rowspan="2">Satuan</th>
    <th rowspan="2">Kode Obat</th>
    <th rowspan="2">Bulan</th>
    <th rowspan="2">Tahun</th>

    <th colspan="3">Stok Awal</th>
    <th colspan="3">Penerimaan</th>
    <th colspan="3">Persediaan</th>
    <th colspan="3">Pemakaian</th>

    <th rowspan="2">Kadaluarsa</th>
    <th rowspan="2">Pengembalian</th>

    <th colspan="3">Stok Akhir</th>

    <th rowspan="2">RKO</th>
    <th rowspan="2">Stok Optimum</th>
    <th rowspan="2">Permintaan</th>
    <th rowspan="2">Pemberian</th>

</tr>

<tr>
    <th>PKD</th><th>Program</th><th>JKN</th>
    <th>PKD</th><th>Program</th><th>JKN</th>
    <th>PKD</th><th>Program</th><th>JKN</th>
    <th>PKD</th><th>Program</th><th>JKN</th>
    <th>PKD</th><th>Program</th><th>JKN</th>
</tr>
</thead>
            </table>
             </div>
        </div>
    </div>

-->
 <div class="card shadow" style="width: 100%;">
        <div class="card-header bg-info text-white">
            <h5>Detail LPLPO</h5>
        </div>
        <div class="card-body">
            <p><b>Faskes:</b> {{ $header->namaFaskes }}</p>
            <p><b>Bulan:</b> {{ $header->bulan }}</p>
            <p><b>Tahun:</b> {{ $header->tahun }}</p>
        </div>
    </div>

 <div class="card shadow mt-3" style="width: 100%;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data LPLPO Final</h5>
        </div>

        <div class="card-body">


          <div class="table-container">
    <table id="table_lplpo" class="table table-bordered table-sm">
               <thead>
<tr>
    <th rowspan="2">No</th>
    <th rowspan="2">Nama Obat</th>
    <th rowspan="2">Satuan</th>
    <th rowspan="2">Kode Obat</th>

    <th rowspan="2">Bulan</th>
    <th rowspan="2">Tahun</th>

    <th colspan="3">Stok Awal</th>
    <th colspan="3">Penerimaan</th>
    <th colspan="3">Persediaan</th>
    <th colspan="3">Pemakaian</th>

    <th rowspan="2">Kadaluarsa</th>
    <th rowspan="2">Pengembalian</th>

    <th colspan="3">Stok Akhir</th>

    <th rowspan="2">RKO</th>
    <th rowspan="2">Stok Optimum</th>
    <th rowspan="2">Permintaan</th>

        <th rowspan="2">Pemberian</th>

</tr>

<tr>
    <th>PKD</th><th>Program</th><th>JKN</th>
    <th>PKD</th><th>Program</th><th>JKN</th>
    <th>PKD</th><th>Program</th><th>JKN</th>
    <th>PKD</th><th>Program</th><th>JKN</th>
    <th>PKD</th><th>Program</th><th>JKN</th>
</tr>
</thead>
            </table>
            @if(session('group') == 2)
<div class="mb-3 text-end">
    <button id="btn_submit_pemberian" class="btn btn-success">
        Submit Verifikasi
    </button>
</div>
@endif
           </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function(){
/*
    $('#table_detail').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("lplpo.final.detail.data", $header->id) }}',
        columns: [
           {
    data: 'DT_RowIndex',
    orderable: false,
    searchable: false
},
            { data: 'nama_obat' },
            { data: 'satuan' },
            { data: 'kode_obat' },

            { data: 'stok_awal_field1' },
            { data: 'stok_awal_field2' },
            { data: 'stok_awal_field3' },

            { data: 'pemakaian_field1' },
            { data: 'pemakaian_field2' },
            { data: 'pemakaian_field3' },

            { data: 'stok_akhir_field1' },
            { data: 'stok_akhir_field2' },
            { data: 'stok_akhir_field3' },

            { data: 'pemberian' }
        ]
    });
    */
let table = $('#table_lplpo').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '/lplpo-final/detail-data/{{ $header->id }}',
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
     { data: 'satuan' },
      { data: 'kode_obat' },

    { data: 'bulan' },
    { data: 'tahun' },

    { data: 'stok_awal_field1' },
    { data: 'stok_awal_field2' },
     { data: 'stok_awal_field3' },

    { data: 'penerimaan_field1' },
    { data: 'penerimaan_field2' },
    { data: 'penerimaan_field3' },

    { data: 'persediaan_field1' },
    { data: 'persediaan_field2' },
      { data: 'persediaan_field3' },

    { data: 'pemakaian_field1' },
    { data: 'pemakaian_field2' },
     { data: 'pemakaian_field3' },

    { data: 'kadaluarsa' },
    { data: 'pengembalian' },

    { data: 'stok_akhir_field1' },
    { data: 'stok_akhir_field2' },
        { data: 'stok_akhir_field3' },

    { data: 'rko' },
    { data: 'stok_optimum' },
    { data: 'permintaan' },
   {data: 'pemberian' }
],
    order: [[1, 'asc']]
});



});
</script>
@endsection
