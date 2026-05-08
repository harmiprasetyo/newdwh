@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <h4 class="mb-3">User Group Management</h4>

    <button class="btn btn-primary mb-3" onclick="openModal()">
        + Tambah Group
    </button>

    <table id="groupTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Group</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
    </table>

</div>

{{-- MODAL --}}
<div class="modal fade" id="groupModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="groupForm">
                <div class="modal-header">
                    <h5 class="modal-title">Form Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="group_id">

                    <div class="mb-3">
                        <label>Nama Group</label>
                        <input type="text" id="group_name" class="form-control" required>
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
let saveMethod = "create";

$(document).ready(function () {

    // ======================
    // DATATABLE
    // ======================
    table = $('#groupTable').DataTable({
        ajax: {
            url: "/api/usergroups",
            dataSrc: "data"
        },
        columns: [
            { data: "group_id" },
            { data: "group_name" },
            {
                data: null,
                render: function (data) {
                    return `
                        <button class="btn btn-sm btn-warning"
                            onclick="editGroup(${data.group_id}, '${data.group_name}')">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger"
                            onclick="deleteGroup(${data.group_id})">
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
    $('#groupForm').submit(function (e) {
        e.preventDefault();

        let id = $('#group_id').val();
        let url = "/api/usergroups";
        let type = "POST";

        if (saveMethod === "update") {
            url = `/api/usergroups/${id}`;
            type = "PUT";
        }

        $.ajax({
            url: url,
            type: type,
            data: {
                group_name: $('#group_name').val()
            },
            success: function () {

                $('#groupModal').modal('hide');
                $('#groupForm')[0].reset();
                table.ajax.reload();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan',
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
// OPEN MODAL CREATE
// ======================
function openModal() {
    saveMethod = "create";
    $('#groupForm')[0].reset();
    $('#group_id').val('');
    $('#groupModal').modal('show');
}

// ======================
// EDIT
// ======================
function editGroup(id, name) {
    saveMethod = "update";

    $('#group_id').val(id);
    $('#group_name').val(name);

    $('#groupModal').modal('show');
}

// ======================
// DELETE (SWEETALERT CONFIRM)
// ======================
function deleteGroup(id) {

    Swal.fire({
        title: 'Yakin hapus data?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: `/api/usergroups/${id}`,
                type: "DELETE",
                success: function () {

                    table.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: 'Data berhasil dihapus',
                        timer: 1500,
                        showConfirmButton: false
                    });

                },
                error: function () {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Data tidak bisa dihapus'
                    });

                }
            });

        }

    });

}
</script>

@endsection
