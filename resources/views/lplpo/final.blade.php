@extends('layouts.lplpo')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5>Data Laporan LPLPO Final</h5>
        </div>

        <div class="card-body">
            <table id="table_lplpo_final" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Faskes</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>

$(function(){

    $('#table_lplpo_final').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("lplpo.final.data") }}',
        columns: [
            {
    data: 'DT_RowIndex',orderable: false, searchable: false},

            { data: 'namaFaskes', name: 'namaFaskes' },
            { data: 'bulan', name: 'bulan' },
            { data: 'tahun', name: 'tahun' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ]
    });

});
</script>

@endsection
