@extends('layouts.admin')

@section('title','Target Sasaran')

@section('content')

<div class="container-fluid">

    <div class="row mb-3">

        <div class="col-md-8">

            <h4 class="mb-0">
                Target Sasaran Ibu Hamil & Bayi
            </h4>

            <small class="text-muted">
                Penginputan Target Sasaran per Posyandu
            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('master.target-sasaran.create') }}"
               class="btn btn-primary">

                <i class="fas fa-plus"></i>

                Tambah Data

            </a>

        </div>

    </div>


    <div class="card shadow-sm">

        <div class="card-header">

            <div class="row mb-3">

    <div class="col-md-3">
        <select id="filter_posyandu" class="form-select select2">
            <option value="">Semua Posyandu</option>
            @foreach($posyandu as $item)
                <option value="{{ $item->id }}">
                    {{ $item->namaPosyandu }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select id="filter_bulan" class="form-select">
            <option value="">Semua Bulan</option>
            @foreach([
                1=>"Januari",2=>"Februari",3=>"Maret",4=>"April",
                5=>"Mei",6=>"Juni",7=>"Juli",8=>"Agustus",
                9=>"September",10=>"Oktober",11=>"November",12=>"Desember"
            ] as $k=>$v)
                <option value="{{ $k }}">{{ $v }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <input type="number"
               id="filter_tahun"
               class="form-control"
               placeholder="Tahun"
               value="{{ date('Y') }}">
    </div>

    <div class="col-md-3">

        <button id="btnFilter"
                class="btn btn-primary">

            <i class="fas fa-search"></i>
            Filter

        </button>

        <button id="btnReset"
                class="btn btn-secondary">

            <i class="fas fa-rotate-left"></i>
            Reset

        </button>

    </div>

</div>

        </div>

        <div class="card-body">

            <table
                class="table table-bordered table-striped"
                id="datatable">

                <thead>

                <tr>

                    <th width="5%">No</th>

                    <th>Posyandu</th>

                    <th>Bulan</th>

                    <th>Tahun</th>

                    <th>RW</th>

                    <th>RT</th>

                    <th>Ibu Hamil</th>

                    <th>Ibu Melahirkan</th>

                    <th>Bayi Baru Lahir</th>

                    <th width="12%">Aksi</th>

                </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

let table = $('#datatable').DataTable({

    processing:true,

    serverSide:true,

    responsive:true,

    autoWidth:false,

    ajax:{

        url:"{{ route('master.target-sasaran.datatable') }}",

        data:function(d){

            d.bulan=$('#filter_bulan').val();

            d.tahun=$('#filter_tahun').val();

            d.posyandu=$('#filter_posyandu').val();

        }

    },

    columns:[
        {
    data:'DT_RowIndex',
    name:'id',
    searchable:false,
    orderable:false
},



        {data:'posyandu',name:'posyandu.namaPosyandu'},

        {data:'bulan',name:'bulan'},

        {data:'tahun',name:'tahun'},

        {data:'rw',name:'rw'},

        {data:'rt',name:'rt'},

        {data:'sasaran_ibu_hamil',name:'sasaran_ibu_hamil'},

        {data:'sasaran_ibu_melahirkan',name:'sasaran_ibu_melahirkan'},

        {data:'sasaran_bayi_baru_lahir',name:'sasaran_bayi_baru_lahir'},

        {
            data:'action',
            name:'action',
            searchable:false,
            orderable:false
        }

    ]

});

$('#btnFilter').click(function(){

    table.ajax.reload();

});


$('#btnReset').click(function () {

    $('#filter_posyandu').val('').trigger('change');
    $('#filter_bulan').val('');
    $('#filter_tahun').val('');

    table.ajax.reload();

});


</script>

@endpush
