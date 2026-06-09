@extends('layouts.lplpo')
@section('content')
<!-- MODAL -->
<div class="modal fade" id="modalForm">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Form Baseline Pemakaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formData">

                    <input type="hidden" id="id">

                    <!-- FASKES -->


                    <!-- OBAT -->
                    <div class="mb-2">
                        <label>Kode Obat</label>
                        <input type="text" id="kode_obat" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Nama Obat</label>
                        <input type="text" id="nama_obat" class="form-control">
                    </div>

                    <!-- BULAN -->
                    <div class="mb-2">
                        <label>Bulan</label>
                        <input type="number" id="bulan" class="form-control">
                    </div>

                    <!-- TAHUN -->
                    <div class="mb-2">
                        <label>Tahun</label>
                        <input type="number" id="tahun" class="form-control">
                    </div>

                    <!-- DECIMAL -->
                    <div class="mb-2">
                        <label>Rerata Pemakaian</label>
                        <input type="number" step="0.01" id="rerata_pemakaian" class="form-control">
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" onclick="save()">💾 Simpan</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<button class="btn btn-primary mb-2" onclick="tambah()">+ Tambah Data</button>

<table id="tableData" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Faskes</th>
            <th>Kode Obat</th>
            <th>Nama Obat</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Rerata</th>
            <th>Aksi</th>
        </tr>
    </thead>
</table>


<script>
    let table;
window.API_KEY = '{{ config("app.api_key") }}';
$(function(){

 $.ajaxSetup({
    headers: {
        'X-API-KEY': window.API_KEY,
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

    table = $('#tableData').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/api/baseline',
        columns: [
            {data:'DT_RowIndex', orderable:false, searchable:false},
            {data:'namaFaskes'},
            {data:'kode_obat'},
            {data:'nama_obat'},
            {data:'bulan'},
            {data:'tahun'},
            {data:'rerata_pemakaian'},
            {
                data:'id',
                render: function(id){
                    return `
                        <button class="btn btn-sm btn-warning" onclick="edit(${id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="hapus(${id})">Hapus</button>
                    `;
                }
            }
        ]
    });

});

//Tambah Data
function tambah(){
    $('#formData')[0].reset();
    $('#id').val('');

    let modal = new bootstrap.Modal(document.getElementById('modalForm'));
    modal.show();
}
//simpan create & update
function save(){

    let id = $('#id').val();

    let url = id ? `/api/baseline/${id}` : '/api/baseline';
    let method = id ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        data: {
            kode_faskes: $('#kode_faskes').val(),
            kode_obat: $('#kode_obat').val(),
            nama_obat: $('#nama_obat').val(),
            bulan: $('#bulan').val(),
            tahun: $('#tahun').val(),
            rerata_pemakaian: $('#rerata_pemakaian').val()
        },
       success: function(){
    let modalEl = document.getElementById('modalForm');
    let modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();

    table.ajax.reload();
}
    });
}

//edit data
function edit(id){

    $.get(`/api/baseline/${id}`, function(res){

        let d = res.data;

        $('#id').val(d.id);
        $('#kode_obat').val(d.kode_obat);
        $('#nama_obat').val(d.nama_obat);
        $('#bulan').val(d.bulan);
        $('#tahun').val(d.tahun);
        $('#rerata_pemakaian').val(d.rerata_pemakaian);

        let modal = new bootstrap.Modal(document.getElementById('modalForm'));
        modal.show();
    });
}

//delete data
function hapus(id){

    if(confirm('Yakin hapus?')){

        $.ajax({
            url: `/api/baseline/${id}`,
            method: 'DELETE',
            success: function(){
                table.ajax.reload();
            }
        });

    }
}
    </script>
@endsection
