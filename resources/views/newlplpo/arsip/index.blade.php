@extends('newlplpo.layouts.master')

@section('title','Arsip LPLPO')

@section('content')

<div class="card shadow-sm">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">

            Arsip Laporan LPLPO

        </h4>

    </div>

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-2">

                <select
                    id="filter_bulan"
                    class="form-select">

                    <option value="">Semua Bulan</option>

                    @foreach([
                    1=>'Januari',
                    2=>'Februari',
                    3=>'Maret',
                    4=>'April',
                    5=>'Mei',
                    6=>'Juni',
                    7=>'Juli',
                    8=>'Agustus',
                    9=>'September',
                    10=>'Oktober',
                    11=>'November',
                    12=>'Desember'
                    ] as $id=>$nama)

                        <option value="{{ $id }}">

                            {{ $nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-2">

                <select
                    id="filter_tahun"
                    class="form-select">

                    <option value="">Semua Tahun</option>

                    @for($i=date('Y');$i>=2020;$i--)

                        <option value="{{ $i }}">

                            {{ $i }}

                        </option>

                    @endfor

                </select>

            </div>

        </div>

        <div class="table-responsive">

            <table
                id="tblArsip"
                class="table table-bordered table-hover">

                <thead class="table-success">

                <tr>

                    <th width="60">No</th>

                    <th>Nomor LPLPO</th>

                    <th>Tgl Laporan</th>

                    <th>Faskes</th>

                    <th>Bulan</th>

                    <th>Tahun</th>

                    <th>Total Item</th>

                    <th>Status</th>

                    <th width="120">Aksi</th>

                </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@endsection

@push('script')

<script>

let table = $('#tblArsip').DataTable({

    processing:true,

    serverSide:true,

    ajax:{

        url:"{{ route('newlplpo.arsip.datatable') }}",

        data:function(d){

            d.bulan = $('#filter_bulan').val();

            d.tahun = $('#filter_tahun').val();

        }

    },

    columns:[

        {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
        },

        {
            data:'nomor_lplpo'
        },

        {
            data:'created_at'
        },

        {
            data:'nama_faskes',
            defaultContent:'-'
        },

        {
            data:'bulan'
        },

        {
            data:'tahun'
        },

        {
            data:'items_count',
            className:'text-center'
        },

        {
            data:'status',
            orderable:false,
            searchable:false
        },

        {
            data:'aksi',
            orderable:false,
            searchable:false
        }

    ]

});

$('#filter_bulan,#filter_tahun').change(function(){

    table.ajax.reload();

});

</script>

@endpush
