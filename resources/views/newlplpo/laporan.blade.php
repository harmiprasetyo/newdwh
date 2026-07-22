@extends('newlplpo.layouts.master')

@section('title','Daftar LPLPO')

@section('content')

<div class="card shadow-sm">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">

            Daftar Laporan LPLPO

        </h4>

    </div>

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-2">

                <select
                    id="bulan"
                    class="form-select">

                    @for($i=1;$i<=12;$i++)

                        <option
                            value="{{ $i }}"
                            {{ $i==date('n')?'selected':'' }}>

                            {{ DateTime::createFromFormat('!m',$i)->format('F') }}

                        </option>

                    @endfor

                </select>

            </div>

            <div class="col-md-2">

                <select
                    id="tahun"
                    class="form-select">

                    @for($i=date('Y');$i>=2020;$i--)

                        <option>{{ $i }}</option>

                    @endfor

                </select>

            </div>

        </div>

        <div class="table-responsive">

            <table
                id="tblReport"
                class="table table-bordered table-striped table-hover">

                <thead class="table-success">

                <tr>

                    <th width="50">No</th>

                    <th width="180">

                        Tgl Laporan

                    </th>

                    <th width="120">

                        Bulan

                    </th>

                    <th width="100">

                        Tahun

                    </th>

                    <th width="120">

                        Total Item

                    </th>

                    <th width="140">

                        Status

                    </th>

                    <th width="80">

                        Aksi

                    </th>

                </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@endsection

@push('script')

<script>

    $(document).on('click','.btnDelete',function(){

    let id = $(this).data('id');

    Swal.fire({

        title:'Hapus Laporan?',

        text:'Seluruh item obat akan ikut dihapus.',

        icon:'warning',

        showCancelButton:true,

        confirmButtonText:'Ya, Hapus',

        cancelButtonText:'Batal'

    }).then((result)=>{

        if(result.isConfirmed){

            $.ajax({

                url:'/newlplpo/'+id,

                type:'POST',

                data:{
                    _method:'DELETE',
                    _token:$('meta[name=csrf-token]').attr('content')
                },

                success:function(){

                    Swal.fire({

                        icon:'success',

                        title:'Berhasil',

                        text:'Laporan berhasil dihapus.'

                    }).then(()=>{

                        $('#tblReport').DataTable().ajax.reload(null,false);

                    });

                },

                error:function(xhr){

                    Swal.fire(

                        'Gagal',

                        xhr.responseJSON?.message ?? 'Terjadi kesalahan',

                        'error'

                    );

                }

            });

        }

    });

});

$(function(){

    let table = $('#tblReport').DataTable({

        processing:true,

        serverSide:false,

        pageLength:10,

        ajax:{

            url:"{{ route('newlplpo.laporan.datatable') }}",

            data:function(d){

                d.bulan=$('#bulan').val();

                d.tahun=$('#tahun').val();

            }

        },

        columns:[

            {data:'DT_RowIndex'},

            {data:'created_at'},

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
