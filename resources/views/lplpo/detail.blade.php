@extends('layouts.lplpo')

@section('content')
<div class="container mt-4">

    <div class="card shadow mb-3">
        <div class="card-header bg-info text-white">
            <h5>Detail LPLPO</h5>
        </div>
        <div class="card-body">
            <p><b>Faskes:</b> {{ $header->namaFaskes }}</p>
            <p><b>Bulan:</b> {{ $header->bulan }}</p>
            <p><b>Tahun:</b> {{ $header->tahun }}</p>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table id="table_detail" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Satuan</th>
                        <th>Kode Obat</th>

                        <th>Stok Awal PKD</th>
                        <th>Stok Awal Program</th>
                        <th>Stok Awal JKN</th>

                        <th>Pemakaian PKD</th>
                        <th>Pemakaian Program</th>
                        <th>Pemakaian JKN</th>

                        <th>Stok Akhir PKD</th>
                        <th>Stok Akhir Program</th>
                        <th>Stok Akhir JKN</th>

                        <th>Pemberian</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>

<script>
$(function(){

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

});
</script>
@endsection
