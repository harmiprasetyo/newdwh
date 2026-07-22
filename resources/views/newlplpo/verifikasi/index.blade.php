@extends('newlplpo.layouts.master')

@section('title','Verifikasi LPLPO')

@section('content')

<div class="card shadow-sm">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">
            Verifikasi Laporan LPLPO
        </h4>

    </div>

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-2">

                <select id="bulan" class="form-select">

                    @for($i=1;$i<=12;$i++)
                        <option value="{{ $i }}"
                            {{ $i==date('n') ? 'selected' : '' }}>
                            {{ bulan($i) }}
                        </option>
                    @endfor

                </select>

            </div>

            <div class="col-md-2">

                <select id="tahun" class="form-select">

                    @for($i=date('Y');$i>=2023;$i--)
                        <option value="{{ $i }}"
                            {{ $i==date('Y') ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor

                </select>

            </div>

        </div>

        <div class="table-responsive">

            <table id="tblVerification"
                   class="table table-bordered table-hover table-striped w-100">

                <thead class="table-success">

                <tr>

                    <th width="20">No</th>
                    <th width="100">Tanggal</th>
                    <th width="100">Nama Faskes</th>
                    <th width="80">Bulan</th>
                    <th width="80">Tahun</th>
                    <th width="30">Total Item</th>
                    <th width="100">Status</th>
                    <th width="80">Aksi</th>

                </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@endsection

@push('script')

<script>

$(function(){

   let table = $('#tblVerification').DataTable({

    destroy:true,
    processing:true,
    serverSide:false,

    responsive:false,
    scrollX:true,
    scrollCollapse:true,
    autoWidth:false,
    pageLength:10,

    ajax:{
        url:"{{ route('newlplpo.verifikasi.datatable') }}",
        data:function(d){
            d.bulan = $('#bulan').val();
            d.tahun = $('#tahun').val();
        }
    },

    columnDefs:[
        {
            targets:0,
            width:"50px",
            className:"text-center"
        },
        {
            targets:1,
            width:"140px"
        },
        {
            targets:2,
            width:"400px"
        },
        {
            targets:3,
            width:"80px",
            className:"text-center"
        },
        {
            targets:4,
            width:"80px",
            className:"text-center"
        },
        {
            targets:5,
            width:"90px",
            className:"text-center"
        },
        {
            targets:6,
            width:"120px",
            className:"text-center"
        },
        {
            targets:7,
            width:"70px",
            orderable:false,
            searchable:false,
            className:"text-center"
        }
    ],

    columns:[
        {data:'DT_RowIndex'},
        {data:'created_at'},
        {data:'nama_faskes'},
        {data:'bulan'},
        {data:'tahun'},
        {data:'items_count'},
        {data:'status_badge'},
        {data:'action'}
    ]

});

    $('#bulan,#tahun').change(function(){

        table.ajax.reload();

    });

});

</script>

@endpush
