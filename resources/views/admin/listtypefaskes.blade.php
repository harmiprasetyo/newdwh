@extends('layouts.admin')
@section('content')

<div class="container-fluid">

    <h4 class="mb-3">List Type Faskes</h4>

    <button class="btn btn-primary mb-3" onclick="openModal()">
        + Tambah Type
    </button>

    <table id="typeTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type Faskes</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
    </table>

</div>

<div class="modal fade" id="typeModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="typeForm">

                <div class="modal-header">
                    <h5 class="modal-title">Form Type Faskes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="id">

                    <div class="mb-3">
                        <label>Type Faskes</label>
                        <input type="text" id="typeFaskes" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
let table;
let mode = "create";

window.API_KEY = '{{ config("app.api_key") }}';
$(document).ready(function () {


    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-API-KEY': window.API_KEY
    }
});

    // ======================
    // DATATABLE
    // ======================
    table = $('#typeTable').DataTable({
        ajax: {
            url: "/api/master/typefaskes",
            dataSrc: "data"
        },
        columns: [
            { data: "id" },
            { data: "typeFaskes" },
            {
                data: null,
                render: function (data) {
                    return `
                        <button class="btn btn-warning btn-sm"
                            onclick="edit(${data.id}, '${data.typeFaskes}')">
                            Edit
                        </button>

                        <button class="btn btn-danger btn-sm"
                            onclick="hapus(${data.id})">
                            Hapus
                        </button>
                    `;
                }
            }
        ]
    });

    // ======================
    // SUBMIT FORM
    // ======================
    $('#typeForm').submit(function (e) {
        e.preventDefault();

        let id = $('#id').val();
        let url = "/api/master/typefaskes";
        let type = "POST";

        if (mode === "update") {
            url = `/api/master/typefaskes/${id}`;
            type = "PUT";
        }

        $.ajax({
            url: url,
            type: type,
            data: {
                typeFaskes: $('#typeFaskes').val()
            },
            success: function () {

                $('#typeModal').modal('hide');
                $('#typeForm')[0].reset();
                table.ajax.reload();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data tersimpan',
                    timer: 1500,
                    showConfirmButton: false
                });

            },
            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat menyimpan data'
                });

            }
        });

    });

});

// ======================
// OPEN CREATE
// ======================
function openModal() {
    mode = "create";
    $('#typeForm')[0].reset();
    $('#id').val('');
    $('#typeModal').modal('show');
}

// ======================
// EDIT
// ======================
function edit(id, name) {
    mode = "update";

    $('#id').val(id);
    $('#typeFaskes').val(name);

    $('#typeModal').modal('show');
}

// ======================
// DELETE
// ======================
function hapus(id) {

    Swal.fire({
        title: 'Hapus data?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: `/api/master/typefaskes/${id}`,
                type: "DELETE",
                success: function () {

                    table.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        timer: 1200,
                        showConfirmButton: false
                    });

                },
                error: function () {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal hapus data'
                    });

                }
            });

        }

    });

}
</script>
@endsection
