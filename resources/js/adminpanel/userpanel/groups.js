let groupTable = null;
let groupModal = null;


/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    console.log('User Group JS loaded');


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE MODAL
    |--------------------------------------------------------------------------
    */

    const modalElement =
        document.getElementById('groupModal');

    if (!modalElement) {

        console.error(
            'Element #groupModal tidak ditemukan.'
        );

        return;
    }


    if (typeof bootstrap === 'undefined') {

        console.error(
            'Bootstrap JS tidak ditemukan.'
        );

        return;
    }


    groupModal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );


    /*
    |--------------------------------------------------------------------------
    | BUTTON TAMBAH GROUP
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '#btnAddGroup',
        function (e) {

            e.preventDefault();

            console.log(
                'Tombol Tambah Group diklik'
            );

            openGroupModal();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'submit',
        '#groupForm',
        function (e) {

            e.preventDefault();

            saveGroup();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-edit-group',
        function (e) {

            e.preventDefault();

            const id =
                $(this).data('id');

            editGroup(id);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-delete-group',
        function (e) {

            e.preventDefault();

            const id =
                $(this).data('id');

            deleteGroup(id);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    $('#searchGroup').on(
        'keyup',
        function () {

            if (groupTable) {

                groupTable.ajax.reload();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | LOAD DATATABLE
    |--------------------------------------------------------------------------
    */

    loadGroupTable();

});


/*
|--------------------------------------------------------------------------
| LOAD DATATABLE
|--------------------------------------------------------------------------
*/

function loadGroupTable()
{

    groupTable = $('#groupTable').DataTable({

        processing: true,

        serverSide: false,

        searching: false,

        lengthChange: false,

        pageLength: 25,

        responsive: true,

        ajax: {

            url:
                window.UserPanelConfig
                    .datatableUrl,

            data: function (d) {

                d.search =
                    $('#searchGroup').val();

            },

            dataSrc: function (json) {

                $('#totalGroup')
                    .text(json.total ?? 0);


                let totalRole = 0;

                let totalUser = 0;


                (json.data ?? []).forEach(
                    function (item) {

                        totalRole += Number(
                            item.roles_count ?? 0
                        );

                        totalUser += Number(
                            item.users_count ?? 0
                        );

                    }
                );


                $('#totalRole')
                    .text(totalRole);

                $('#totalUser')
                    .text(totalUser);


                return json.data ?? [];

            }

        },


        columns: [

            /*
            |--------------------------------------------------------------------------
            | NO
            |--------------------------------------------------------------------------
            */

            {
                data: null,

                className:
                    'text-center',

                render: function (
                    data,
                    type,
                    row,
                    meta
                ) {

                    return (
                        meta.row +
                        meta.settings
                            ._iDisplayStart +
                        1
                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | GROUP
            |--------------------------------------------------------------------------
            */

            {
                data: 'group_name',

                render: function (
                    data,
                    type,
                    row
                ) {

                    return `
                        <div class="d-flex align-items-center">

                            <div
                                class="
                                    rounded-circle
                                    bg-primary
                                    bg-opacity-10
                                    text-primary
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                    me-3
                                "
                                style="
                                    width:38px;
                                    height:38px;
                                "
                            >
                                <i class="fas fa-users"></i>
                            </div>

                            <div>

                                <div class="fw-semibold">
                                    ${escapeHtml(data)}
                                </div>

                                <small class="text-muted">
                                    Group ID #${row.group_id}
                                </small>

                            </div>

                        </div>
                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | ROLE
            |--------------------------------------------------------------------------
            */

            {
                data: 'roles_count',

                className:
                    'text-center',

                render: function (data) {

                    return `
                        <span
                            class="
                                badge
                                bg-success
                                bg-opacity-10
                                text-success
                                px-3
                                py-2
                            "
                        >

                            <i
                                class="fas fa-user-tag me-1"
                            ></i>

                            ${data ?? 0}

                        </span>
                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            {
                data: 'users_count',

                className:
                    'text-center',

                render: function (data) {

                    return `
                        <span
                            class="
                                badge
                                bg-info
                                bg-opacity-10
                                text-info
                                px-3
                                py-2
                            "
                        >

                            <i
                                class="fas fa-user me-1"
                            ></i>

                            ${data ?? 0}

                        </span>
                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */

            {
                data: 'group_id',

                className:
                    'text-center',

                orderable: false,

                searchable: false,

                render: function (id) {

                    return `

                        <div
                            class="
                                d-flex
                                justify-content-center
                                gap-1
                            "
                        >

                            <button
                                type="button"
                                class="
                                    btn
                                    btn-sm
                                    btn-outline-primary
                                    btn-edit-group
                                "
                                data-id="${id}"
                                title="Edit"
                            >

                                <i
                                    class="fas fa-edit"
                                ></i>

                            </button>


                            <button
                                type="button"
                                class="
                                    btn
                                    btn-sm
                                    btn-outline-danger
                                    btn-delete-group
                                "
                                data-id="${id}"
                                title="Hapus"
                            >

                                <i
                                    class="fas fa-trash"
                                ></i>

                            </button>

                        </div>

                    `;

                }

            }

        ],


        language: {

            emptyTable:
                'Belum ada user group.',

            zeroRecords:
                'Data tidak ditemukan.',

            processing:
                'Memuat data...',

            paginate: {

                previous:
                    '<i class="fas fa-chevron-left"></i>',

                next:
                    '<i class="fas fa-chevron-right"></i>'

            }

        }

    });

}


/*
|--------------------------------------------------------------------------
| OPEN MODAL
|--------------------------------------------------------------------------
*/

function openGroupModal()
{

    console.log(
        'openGroupModal() dijalankan'
    );


    /*
    |--------------------------------------------------------------------------
    | RESET FORM
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById(
            'groupForm'
        );

    if (form) {

        form.reset();

    }


    /*
    |--------------------------------------------------------------------------
    | RESET ID
    |--------------------------------------------------------------------------
    */

    $('#group_id')
        .val('');


    /*
    |--------------------------------------------------------------------------
    | RESET VALIDATION
    |--------------------------------------------------------------------------
    */

    $('#group_name')
        .removeClass('is-invalid');

    $('#groupNameError')
        .text('');


    /*
    |--------------------------------------------------------------------------
    | TITLE
    |--------------------------------------------------------------------------
    */

    $('#groupModalTitle')
        .text('Tambah User Group');


    /*
    |--------------------------------------------------------------------------
    | SHOW MODAL
    |--------------------------------------------------------------------------
    */

    if (!groupModal) {

        const modalElement =
            document.getElementById(
                'groupModal'
            );

        groupModal =
            bootstrap.Modal.getOrCreateInstance(
                modalElement
            );

    }


    groupModal.show();

}


/*
|--------------------------------------------------------------------------
| EDIT GROUP
|--------------------------------------------------------------------------
*/

function editGroup(id)
{

    $.ajax({

        url:
            `${window.UserPanelConfig.baseUrl}/${id}`,

        type: 'GET',

        success: function (response) {

            const data =
                response.data;


            $('#group_id')
                .val(data.group_id);


            $('#group_name')
                .val(data.group_name);


            $('#groupModalTitle')
                .text(
                    'Edit User Group'
                );


            $('#group_name')
                .removeClass(
                    'is-invalid'
                );


            $('#groupNameError')
                .text('');


            groupModal.show();

        },


        error: function (xhr) {

            console.error(
                xhr.responseText
            );

            showError(
                'Gagal mengambil data group.'
            );

        }

    });

}


/*
|--------------------------------------------------------------------------
| SAVE GROUP
|--------------------------------------------------------------------------
*/

function saveGroup()
{

    const id =
        $('#group_id').val();


    const groupName =
        $('#group_name')
            .val()
            .trim();


    $('#group_name')
        .removeClass(
            'is-invalid'
        );


    $('#groupNameError')
        .text('');


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    if (!groupName) {

        $('#group_name')
            .addClass(
                'is-invalid'
            );

        $('#groupNameError')
            .text(
                'Nama group wajib diisi.'
            );

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    */

    const url = id

        ? `${window.UserPanelConfig.baseUrl}/${id}`

        : window.UserPanelConfig.baseUrl;


    const method =
        id
            ? 'PUT'
            : 'POST';


    /*
    |--------------------------------------------------------------------------
    | BUTTON
    |--------------------------------------------------------------------------
    */

    const button =
        $('#btnSaveGroup');


    button
        .prop(
            'disabled',
            true
        )
        .html(`
            <span
                class="
                    spinner-border
                    spinner-border-sm
                    me-2
                "
            ></span>

            Menyimpan...
        `);


    /*
    |--------------------------------------------------------------------------
    | AJAX
    |--------------------------------------------------------------------------
    */

    $.ajax({

        url: url,

        type: method,

        data: {

            group_name:
                groupName,

            _token:
                $('meta[name="csrf-token"]')
                    .attr('content')

        },


        success: function (response) {

            groupModal.hide();


            if (groupTable) {

                groupTable.ajax.reload(
                    null,
                    false
                );

            }


            Swal.fire({

                icon: 'success',

                title: 'Berhasil',

                text:
                    response.message ??
                    'Data berhasil disimpan.',

                timer: 1500,

                showConfirmButton:
                    false

            });

        },


        error: function (xhr) {

            console.error(
                xhr.responseText
            );


            if (
                xhr.status === 422 &&
                xhr.responseJSON?.errors
            ) {

                const errors =
                    xhr.responseJSON.errors;


                if (
                    errors.group_name
                ) {

                    $('#group_name')
                        .addClass(
                            'is-invalid'
                        );


                    $('#groupNameError')
                        .text(
                            errors.group_name[0]
                        );

                }

                return;

            }


            showError(
                xhr.responseJSON?.message ??
                'Terjadi kesalahan.'
            );

        },


        complete: function () {

            button
                .prop(
                    'disabled',
                    false
                )
                .html(`
                    <i
                        class="fas fa-save me-2"
                    ></i>

                    Simpan
                `);

        }

    });

}


/*
|--------------------------------------------------------------------------
| DELETE GROUP
|--------------------------------------------------------------------------
*/

function deleteGroup(id)
{

    Swal.fire({

        title:
            'Hapus User Group?',

        text:
            'Data yang sudah dihapus tidak dapat dikembalikan.',

        icon:
            'warning',

        showCancelButton:
            true,

        confirmButtonText:
            'Ya, Hapus',

        cancelButtonText:
            'Batal',

        reverseButtons:
            true

    }).then(function (result) {

        if (
            !result.isConfirmed
        ) {

            return;

        }


        $.ajax({

            url:
                `${window.UserPanelConfig.baseUrl}/${id}`,

            type:
                'DELETE',

            data: {

                _token:
                    $('meta[name="csrf-token"]')
                        .attr('content')

            },


            success: function (response) {

                if (groupTable) {

                    groupTable.ajax.reload(
                        null,
                        false
                    );

                }


                Swal.fire({

                    icon:
                        'success',

                    title:
                        'Berhasil',

                    text:
                        response.message ??
                        'Group berhasil dihapus.',

                    timer:
                        1500,

                    showConfirmButton:
                        false

                });

            },


            error: function (xhr) {

                console.error(
                    xhr.responseText
                );


                Swal.fire({

                    icon:
                        'error',

                    title:
                        'Tidak dapat dihapus',

                    text:
                        xhr.responseJSON?.message ??
                        'Terjadi kesalahan.'

                });

            }

        });

    });

}


/*
|--------------------------------------------------------------------------
| ERROR
|--------------------------------------------------------------------------
*/

function showError(message)
{

    Swal.fire({

        icon:
            'error',

        title:
            'Gagal',

        text:
            message

    });

}


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value)
{

    return $('<div>')
        .text(
            value ?? ''
        )
        .html();

}