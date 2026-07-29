@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-md-6">
                    <h4>Master Posyandu</h4>
                </div>

                <div class="col-md-6 text-end">

                    <a href="/adminpanel/posyandu/create"
                       class="btn btn-primary">

                        Tambah Posyandu

                    </a>

                </div>

            </div>

        </div>

        <div class="card-body">

            <table id="tablePosyandu"
                   class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>Kode</th>
                        <th>Nama Posyandu</th>
                        <th>Provinsi</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                        <th>Fasyankes</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>

                    </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

$(function(){

    let table = $('#tablePosyandu').DataTable({

        processing:true,
        serverSide:true,

        ajax:{
            url:'/adminpanel/posyandu/data'
        },

        columns:[

            {
                data:'kodePosyandu'
            },

            {
                data:'namaPosyandu'
            },

            {
                data:'province_name'
            },

            {
                data:'city_name'
            },

            {
                data:'district_name'
            },

            {
                data:'village_name'
            },

            {
                data:'namaFaskes'
            },

            {
                data:'status',
                orderable:false,
                searchable:false
            },

            {
                data:'action',
                orderable:false,
                searchable:false
            }

        ]

    });



    $(document).on(
        'click',
        '.btn-delete',
        function(){

            let id = $(this).data('id');

            if(!confirm('Hapus data?')){
                return;
            }

            $.ajax({

                url:'/adminpanel/posyandu/delete/'+id,

                method:'DELETE',

                data:{
                    _token:'{{ csrf_token() }}'
                },

                success:function(res){

                    table.ajax.reload();

                }

            });

        }
    );

});

</script>

@endpush
