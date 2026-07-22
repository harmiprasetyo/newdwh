@extends('newlplpo.layouts.master')

@section('title','Pemberian Obat')

@section('content')

<div class="card shadow-sm">

    <div class="card-header bg-primary text-white">

        <h4 class="mb-0">

            Pemberian Obat LPLPO

        </h4>

    </div>

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-2">

                <select id="bulan" class="form-select">

                    @for($i=1;$i<=12;$i++)

                        <option value="{{$i}}"
                            {{$i==date('n')?'selected':''}}>

                            {{ bulan($i) }}

                        </option>

                    @endfor

                </select>

            </div>

            <div class="col-md-2">

                <select id="tahun" class="form-select">

                    @for($i=date('Y');$i>=2023;$i--)

                        <option
                            {{$i==date('Y')?'selected':''}}>

                            {{$i}}

                        </option>

                    @endfor

                </select>

            </div>

        </div>

        <div class="table-responsive">

            <table
                id="tblPemberian"
                class="table table-bordered table-hover table-striped w-100">

                <thead class="table-primary">

                    <tr>

                        <th style="width:60px">No</th>

                        <th style="width:120px">Tgl Laporan</th>

                        <th>Nama Faskes</th>

                        <th style="width:80px">Bulan</th>

                        <th style="width:80px">Tahun</th>

                        <th style="width:100px">Total Item</th>

                        <th style="width:120px">Status</th>

                        <th style="width:70px">Aksi</th>

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

    let table = $('#tblPemberian').DataTable({

        processing:true,

        serverSide:false,

        responsive:false,

        scrollX:true,

        autoWidth:false,

        pageLength:10,

        ajax:{

            url:"{{ route('newlplpo.pemberian.datatable') }}",

            data:function(d){

                d.bulan = $('#bulan').val();

                d.tahun = $('#tahun').val();

            }

        },

        columns:[

            {
                data:'DT_RowIndex',
                width:'60px',
                className:'text-center'
            },

            {
                data:'created_at',
                width:'120px'
            },

            {
                data:'nama_faskes'
            },

            {
                data:'bulan',
                width:'80px',
                className:'text-center'
            },

            {
                data:'tahun',
                width:'80px',
                className:'text-center'
            },

            {
                data:'items_count',
                width:'100px',
                className:'text-center'
            },

            {
                data:'status_badge',
                width:'120px',
                className:'text-center',
                orderable:false,
                searchable:false
            },

            {
                data:'action',
                width:'70px',
                className:'text-center',
                orderable:false,
                searchable:false
            }

        ],

        language:{
            emptyTable:'Belum ada laporan yang siap diberikan obat.'
        }

    });

    $('#bulan,#tahun').change(function(){

        table.ajax.reload();

    });

});

</script>

@endpush
