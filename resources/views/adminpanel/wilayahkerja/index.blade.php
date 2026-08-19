@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">

            <h5>

                Wilayah Kerja Posyandu

            </h5>

            <button
                class="btn btn-primary"
                id="btnTambah">

                <i class="bi bi-plus-circle"></i>

                Tambah

            </button>

        </div>

        <div class="card-body">

            <table
                class="table table-bordered"
                id="datatable">

                <thead>

                <tr>

                    <th>No</th>

                    <th>Posyandu</th>

                    <th>Desa</th>

                    <th>Kecamatan</th>

                    <th>Kabupaten</th>

                    <th>Provinsi</th>

                    <th>RW</th>

                    <th width="120">Action</th>

                </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@include('adminpanel.wilayahkerja.form')

@endsection

@push('styles')
<style>
    .btn-action {
        width: 36px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 6px;
    }

    .btn-action i {
        font-size: 16px;
    }

    .action-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }
</style>

@endpush

@push('scripts')


<script>
window.WilayahKerjaPosyanduConfig = {

    storeUrl: "{{ route('wilayahkerja.store') }}",

    updateUrl: "{{ route('wilayahkerja.update', ['id' => '__ID__']) }}",

};
</script>

<script>

$(function(){

$('#datatable').DataTable({

processing:true,

serverSide:true,

ajax:"{{ route('wilayahkerja.datatable') }}",

columns:[

{data:'DT_RowIndex',searchable:false,orderable:false},

{data:'nama_posyandu'},

{data:'desa'},

{data:'kecamatan'},

{data:'kabupaten'},

{data:'provinsi'},

{data:'rw'},

{data:'aksi',searchable:false,orderable:false}

]

});

});

$('#btnTambah').click(function(){

    $('#formData')[0].reset();

    tagifyRW.removeAllTags();

    $('#id').val('');

    $('#kodePosyandu').val(null).trigger('change');

    new bootstrap.Offcanvas(
        '#offcanvasForm'
    ).show();

});
$('#formData').submit(function(e){

    e.preventDefault();

    let rw = tagifyRW.value.map(item => item.value).join(',');

    let formData = new FormData(this);
    formData.set('rw', rw);

    let id = $('#id').val();

    let url;
    let method = 'POST';

   if (id) {

    url =
        window.WilayahKerjaPosyanduConfig
            .updateUrl
            .replace('__ID__', id);

    formData.append('_method', 'PUT');

} else {

    url =
        window.WilayahKerjaPosyanduConfig
            .storeUrl;
}
    $.ajax({

        url:url,

        type:'POST',

        data:formData,

        processData:false,

        contentType:false,

        success:function(res){

            bootstrap.Offcanvas
                .getInstance(document.getElementById('offcanvasForm'))
                .hide();

            $('#datatable')
                .DataTable()
                .ajax.reload(null,false);

            Swal.fire(
                'Berhasil',
                res.message,
                'success'
            );

        },

        error:function(xhr){

            Swal.fire(
                'Error',
                xhr.responseJSON.message,
                'error'
            );

        }

    });

});

$(document).on(
'click',
'.btnEdit',
function(){

    let id=$(this).data('id');

 $.get($(this).data('url'),

        function(res){

            $('#id').val(res.id);

            let option=new Option(

                res.posyandu.namaPosyandu,

                res.kodePosyandu,

                true,

                true

            );

            $('#kodePosyandu')
                .append(option)
                .trigger('change');

            tagifyRW.removeAllTags();

            tagifyRW.addTags(
                res.rw.split(',')
            );

            new bootstrap.Offcanvas(
                '#offcanvasForm'
            ).show();

        }

    );

});

$(document).on('click','.btnDelete',function(){

    let id=$(this).data('id');

    Swal.fire({

        title:'Hapus data?',

        icon:'warning',

        showCancelButton:true

    }).then((result)=>{

        if(result.isConfirmed){

            $.ajax({

               url: $(this).data('url'),

                type:'POST',

                data:{
                    _method:'DELETE',
                    _token:$('meta[name="csrf-token"]').attr('content')
                },

                success:function(res){

                    $('#datatable')
                        .DataTable()
                        .ajax.reload(null,false);

                    Swal.fire(
                        'Berhasil',
                        res.message,
                        'success'
                    );

                }

            });

        }

    });

});
</script>

@endpush
