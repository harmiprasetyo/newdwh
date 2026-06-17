@extends('layouts.admin')
@section('content')
<div class="container mt-4">

    <button class="btn btn-primary mb-2" id="btnTambah">Tambah Data</button>

    <table id="tableObat" class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Satuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalObat">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="frmObat">
                @csrf

                <input type="hidden" id="id">

                <div class="modal-body">
                    <input type="text" name="kode_obat" class="form-control mb-2" placeholder="Kode Obat">
                    <input type="text" name="nama_obat" class="form-control mb-2" placeholder="Nama Obat">
                    <input type="text" name="satuan" class="form-control mb-2" placeholder="Satuan">
                     <input type="text" name="kategori_obat" class="form-control mb-2" placeholder="Kategori">
                      <input type="text" name="kelompok_obat" class="form-control mb-2" placeholder="Kelompok">
                       <input type="text" name="golongan_obat" class="form-control mb-2" placeholder="Golongan">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    window.API_KEY = '{{ config("app.api_key") }}';



    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-API-KEY': window.API_KEY
    }
});
    let table = $('#tableObat').DataTable({
    ajax: '/api/master-obat',
    columns: [
        { data: 'kode_obat' },
        { data: 'nama_obat' },
        { data: 'satuan' },
        {
            data: null,
            render: function(data){
                return `
                    <button onclick="edit(${data.id})">Edit</button>
                    <button onclick="hapus(${data.id})">Hapus</button>
                `;
            }
        }
    ]
});

// TAMBAH
$('#btnTambah').click(function(){
    $('#frmObat')[0].reset();
    $('#id').val('');
    $('#modalObat').modal('show');
});

// SUBMIT
$('#frmObat').submit(function(e){
    e.preventDefault();

    let id = $('#id').val();
    let url = id ? `/api/master-obat/${id}` : '/api/master-obat';
    let method = id ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        data: $(this).serialize(),

        success: function(res){
            Swal.fire('Success', res.message, 'success');
            $('#modalObat').modal('hide');
            table.ajax.reload();
        },

        error: function(err){
            let errors = err.responseJSON.errors;
            let msg = '';

            for (let key in errors) {
                msg += errors[key][0] + '\n';
            }

            Swal.fire('Error', msg, 'error');
        }
    });
});

// EDIT
function edit(id){
    $.get(`/api/master-obat/${id}`, function(data){

        $('#id').val(data.id);
        $('[name=kode_obat]').val(data.kode_obat);
        $('[name=nama_obat]').val(data.nama_obat);
        $('[name=satuan]').val(data.satuan);

        $('#modalObat').modal('show');
    });
}

// DELETE
function hapus(id){
    Swal.fire({
        title: 'Yakin?',
        icon: 'warning',
        showCancelButton: true
    }).then((result) => {

        if(result.isConfirmed){
            $.ajax({
                url: `/api/master-obat/${id}`,
                method: 'DELETE',
                success: function(res){
                    Swal.fire('Deleted', res.message, 'success');
                    table.ajax.reload();
                }
            });
        }

    });
}
</script>
@endsection
