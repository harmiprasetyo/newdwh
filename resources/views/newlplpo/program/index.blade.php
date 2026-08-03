@extends('newlplpo.layouts.master')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
         HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="bi bi-diagram-3 me-2"></i>

                Master Program

            </h4>

            <div class="text-muted">

                Kelola daftar program LPLPO

            </div>

        </div>


       <!-- <button
            type="button"
            class="btn btn-primary"
            id="btnTambah">

            <i class="bi bi-plus-lg me-1"></i>

            Tambah Program

        </button>-->

        <button
    type="button"
    class="btn btn-primary"
    id="btnTambah">

    <i class="bi bi-plus-lg me-1"></i>
    Tambah Program

</button>

    </div>


    {{-- ==========================================================
         CARD
    =========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="datatable"
                    class="table table-hover align-middle w-100">

                    <thead>

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Nama Program
                            </th>

                            <th width="180">
                                Dibuat
                            </th>

                            <th width="100"
                                class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ==============================================================
     MODAL FORM
================================================================ --}}

<div
    class="modal fade"
    id="modalProgram"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="modalTitle">

                    <i class="bi bi-plus-circle me-2"></i>

                    Tambah Program

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form id="formProgram">

                @csrf

                <input
                    type="hidden"
                    id="program_id">


                <div class="modal-body">

                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Nama Program

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            id="program_name"
                            name="program_name"
                            class="form-control"
                            placeholder="Masukkan nama program"
                            autocomplete="off">

                        <div
                            class="invalid-feedback"
                            id="program_name_error">
                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnSimpan">

                        <span
                            class="spinner-border spinner-border-sm d-none"
                            id="spinner">
                        </span>

                        <i
                            class="bi bi-check-lg me-1"
                            id="saveIcon">
                        </i>

                        <span id="saveText">
                            Simpan
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@endsection


@push('script')

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | CONFIG
    |--------------------------------------------------------------------------
    */

    const tableUrl =
        "{{ route('newlplpo.program.datatable') }}";

    const storeUrl =
        "{{ route('newlplpo.program.store') }}";


    let editMode = false;


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    const table = $('#datatable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        pageLength: 10,

        ajax: {

            url: tableUrl,

            type: 'GET'

        },

        columns: [

            {
                data: 'DT_RowIndex',

                name: 'DT_RowIndex',

                orderable: false,

                searchable: false,

                className: 'text-center'

            },


            {

                data: 'program_name',

                name: 'program_name',

                render: function (data) {

                    return `

                        <div class="fw-semibold">

                            <i class="
                                bi bi-folder2-open
                                text-primary
                                me-2
                            "></i>

                            ${escapeHtml(data)}

                        </div>

                    `;

                }

            },


            {

                data: 'created_at',

                name: 'created_at',

                className: 'text-muted'

            },


            {

                data: 'action',

                name: 'action',

                orderable: false,

                searchable: false,

                className: 'text-center'

            }

        ],

        order: [

            [1, 'asc']

        ],

        language: {

            processing:
                'Memuat data...',

            search:
                'Cari:',

            searchPlaceholder:
                'Cari program...',

            lengthMenu:
                '_MENU_ data',

            info:
                'Menampilkan _START_ - _END_ dari _TOTAL_ program',

            infoEmpty:
                'Tidak ada program',

            zeroRecords:
                'Program tidak ditemukan',

            emptyTable:
                'Belum ada program',

            paginate: {

                previous:
                    '<i class="bi bi-chevron-left"></i>',

                next:
                    '<i class="bi bi-chevron-right"></i>'

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    $('#btnTambah').on('click', function () {

        editMode = false;

        clearForm();

        $('#modalTitle').html(`

            <i class="bi bi-plus-circle me-2"></i>

            Tambah Program

        `);

        $('#saveText').text('Simpan');

        //$('#modalProgram').modal('show');

        const modalElement = document.getElementById('modalProgram');

const modal = bootstrap.Modal.getOrCreateInstance(
    modalElement
);

modal.show();

    });


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $('#datatable').on(
        'click',
        '.btn-edit',
        function () {

            const id =
                $(this).data('id');

            editMode = true;

            clearForm();

            $('#modalTitle').html(`

                <i class="bi bi-pencil-square me-2"></i>

                Edit Program

            `);

            $('#saveText').text('Update');


            $.ajax({

                url:
                    "{{ url('/newlplpo/program') }}/" +
                    id,

                type: 'GET',

                beforeSend: function () {

                    $('#program_name')
                        .prop('disabled', true);

                },

               success: function (response) {
                $('#program_id')
                .val(response.data.id);
                $('#program_name')
        .val(response.data.program_name)
        .prop('disabled', false);


    const modalElement =
        document.getElementById('modalProgram');

    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );

    modal.show();

},

                error: function (xhr) {

                    $('#program_name')
                        .prop('disabled', false);

                    Swal.fire(
                        'Error',
                        getErrorMessage(xhr),
                        'error'
                    );

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $('#datatable').on(
        'click',
        '.btn-delete',
        function () {

            const id =
                $(this).data('id');

            const name =
                $(this).data('name');


            Swal.fire({

                title:
                    'Hapus Program?',

                html:

                    `Program
                    <strong>${escapeHtml(name)}</strong>
                    akan dihapus.`,

                icon:
                    'warning',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Ya, Hapus',

                cancelButtonText:
                    'Batal',

                confirmButtonColor:
                    '#dc3545'

            }).then(function (result) {

                if (!result.isConfirmed) {

                    return;

                }


                $.ajax({

                    url:
                        "{{ url('/newlplpo/program') }}/" +
                        id,

                    type:
                        'DELETE',

                    data: {

                        _token:
                            "{{ csrf_token() }}"

                    },

                    beforeSend: function () {

                        Swal.fire({

                            title:
                                'Menghapus...',

                            allowOutsideClick:
                                false,

                            didOpen: function () {

                                Swal.showLoading();

                            }

                        });

                    },

                    success: function (response) {

                        Swal.fire({

                            icon:
                                'success',

                            title:
                                'Berhasil',

                            text:
                                response.message ??
                                'Program berhasil dihapus.',

                            timer:
                                1500,

                            showConfirmButton:
                                false

                        });

                        table.ajax.reload(
                            null,
                            false
                        );

                    },

                    error: function (xhr) {

                        Swal.fire(

                            'Tidak dapat dihapus',

                            getErrorMessage(xhr),

                            'error'

                        );

                    }

                });

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#formProgram').on(
        'submit',
        function (e) {

            e.preventDefault();


            clearValidation();


            const name =
                $('#program_name')
                    .val()
                    .trim();


            if (!name) {

                showError(
                    'Nama program wajib diisi.'
                );

                return;

            }


            const id =
                $('#program_id').val();


            let url =
                storeUrl;

            let method =
                'POST';


            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            if (editMode) {

                url =
                    "{{ url('/newlplpo/program') }}/" +
                    id;

                method =
                    'PUT';

            }


            const payload = {

                program_name:
                    name,

                _token:
                    "{{ csrf_token() }}"

            };


            setLoading(true);


            $.ajax({

                url:
                    url,

                type:
                    method,

                data:
                    payload,

                success: function (response) {

                    $('#modalProgram')
                        .modal('hide');


                    Swal.fire({

                        icon:
                            'success',

                        title:
                            'Berhasil',

                        text:
                            response.message,

                        timer:
                            1500,

                        showConfirmButton:
                            false

                    });


                    table.ajax.reload(
                        null,
                        false
                    );

                },

                error: function (xhr) {

                    handleError(xhr);

                },

                complete: function () {

                    setLoading(false);

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLEAR FORM
    |--------------------------------------------------------------------------
    */

    function clearForm()
    {

        $('#formProgram')[0].reset();

        $('#program_id').val('');

        $('#program_name')
            .prop('disabled', false);

        clearValidation();

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    function clearValidation()
    {

        $('#program_name')
            .removeClass('is-invalid');

        $('#program_name_error')
            .text('');

    }


    function showError(message)
    {

        $('#program_name')
            .addClass('is-invalid');

        $('#program_name_error')
            .text(message);

        $('#program_name')
            .focus();

    }


    /*
    |--------------------------------------------------------------------------
    | API ERROR
    |--------------------------------------------------------------------------
    */

    function handleError(xhr)
    {

        console.error(
            'Program Error:',
            xhr.responseText
        );


        if (
            xhr.status === 422 &&
            xhr.responseJSON?.errors
        ) {

            const errors =
                xhr.responseJSON.errors;


            if (
                errors.program_name
            ) {

                showError(
                    errors.program_name[0]
                );

            }

            return;

        }


        Swal.fire(

            'Error',

            getErrorMessage(xhr),

            'error'

        );

    }


    function getErrorMessage(xhr)
    {

        return (

            xhr.responseJSON?.message ??

            'Terjadi kesalahan pada server.'

        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOADING
    |--------------------------------------------------------------------------
    */

    function setLoading(status)
    {

        $('#btnSimpan')
            .prop('disabled', status);


        if (status) {

            $('#spinner')
                .removeClass('d-none');

            $('#saveIcon')
                .addClass('d-none');

            $('#saveText')
                .text('Menyimpan...');

        } else {

            $('#spinner')
                .addClass('d-none');

            $('#saveIcon')
                .removeClass('d-none');

            $('#saveText')
                .text(
                    editMode
                        ? 'Update'
                        : 'Simpan'
                );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value)
    {

        return $('<div>')
            .text(value ?? '')
            .html();

    }

});

</script>

@endpush
