let roleTable = null;

let roleModal = null;


/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    console.log(
        'User Role JS loaded'
    );


    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    const modalElement =
        document.getElementById(
            'roleModal'
        );


    if (
        modalElement &&
        typeof bootstrap !== 'undefined'
    ) {

        roleModal =
            bootstrap.Modal.getOrCreateInstance(
                modalElement
            );

    }


    /*
    |--------------------------------------------------------------------------
    | ADD
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '#btnAddRole',
        function (e) {

            e.preventDefault();

            openRoleModal();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'submit',
        '#roleForm',
        function (e) {

            e.preventDefault();

            saveRole();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-edit-role',
        function () {

            editRole(
                $(this).data('id')
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-delete-role',
        function () {

            deleteRole(
                $(this).data('id')
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    $('#searchRole').on(
        'keyup',
        function () {

            if (roleTable) {

                roleTable.ajax.reload();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | LOAD GROUP
    |--------------------------------------------------------------------------
    */

    loadGroups();


    /*
    |--------------------------------------------------------------------------
    | LOAD TABLE
    |--------------------------------------------------------------------------
    */

    loadRoleTable();

});


/*
|--------------------------------------------------------------------------
| LOAD GROUPS
|--------------------------------------------------------------------------
*/

function loadGroups(
    selected = null
)
{

    $.ajax({

        url:
            window.UserRoleConfig.groupsUrl,

        type:
            'GET',

        success: function (response) {

            let html = `
                <option value="">
                    Pilih User Group
                </option>
            `;


            (response.data ?? [])
                .forEach(function (group) {

                    html += `
                        <option
                            value="${group.group_id}"
                        >
                            ${escapeHtml(
                                group.group_name
                            )}
                        </option>
                    `;

                });


            $('#groupId')
                .html(html);


            if (selected) {

                $('#groupId')
                    .val(selected);

            }

        },

        error: function (xhr) {

            console.error(
                xhr.responseText
            );

            showError(
                'Gagal memuat User Group.'
            );

        }

    });

}


/*
|--------------------------------------------------------------------------
| DATATABLE
|--------------------------------------------------------------------------
*/

function loadRoleTable()
{

    roleTable =
        $('#roleTable').DataTable({

            processing: true,

            serverSide: false,

            searching: false,

            lengthChange: false,

            pageLength: 25,

            responsive: true,

            ajax: {

                url:
                    window.UserRoleConfig
                        .datatableUrl,

                data: function (d) {

                    d.search =
                        $('#searchRole')
                            .val();

                },

                dataSrc: function (json) {

                    const data =
                        json.data ?? [];


                    $('#totalRole')
                        .text(
                            json.total ??
                            data.length
                        );


                    const groups =
                        new Set(
                            data
                                .map(
                                    item =>
                                        item.groupId
                                )
                        );


                    $('#totalGroup')
                        .text(
                            groups.size
                        );


                    const users =
                        data.reduce(
                            function (
                                total,
                                item
                            ) {

                                return total +
                                    Number(
                                        item.users_count ??
                                        0
                                    );

                            },
                            0
                        );


                    $('#totalUser')
                        .text(users);


                    return data;

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

                    render:
                        function (
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
                | ROLE
                |--------------------------------------------------------------------------
                */

                {

                    data:
                        'role_name',

                    render:
                        function (
                            data,
                            type,
                            row
                        ) {

                            return `

                                <div
                                    class="
                                        d-flex
                                        align-items-center
                                    "
                                >

                                    <div
                                        class="
                                            rounded-circle
                                            bg-success
                                            bg-opacity-10
                                            text-success
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

                                        <i
                                            class="
                                                fas
                                                fa-user-tag
                                            "
                                        ></i>

                                    </div>


                                    <div>

                                        <div
                                            class="fw-semibold"
                                        >
                                            ${escapeHtml(
                                                data
                                            )}
                                        </div>

                                        <small
                                            class="text-muted"
                                        >
                                            Role ID #${row.id}
                                        </small>

                                    </div>

                                </div>

                            `;

                        }

                },


                /*
                |--------------------------------------------------------------------------
                | GROUP
                |--------------------------------------------------------------------------
                */

                {

                    data:
                        'group',

                    render:
                        function (
                            data
                        ) {

                            if (!data) {

                                return `
                                    <span
                                        class="
                                            badge
                                            bg-secondary
                                        "
                                    >
                                        -
                                    </span>
                                `;

                            }


                            return `

                                <span
                                    class="
                                        badge
                                        bg-primary
                                        bg-opacity-10
                                        text-primary
                                        px-3
                                        py-2
                                    "
                                >

                                    <i
                                        class="
                                            fas
                                            fa-users
                                            me-1
                                        "
                                    ></i>

                                    ${escapeHtml(
                                        data.group_name
                                    )}

                                </span>

                            `;

                        }

                },


                /*
                |--------------------------------------------------------------------------
                | USERS
                |--------------------------------------------------------------------------
                */

                {

                    data:
                        'users_count',

                    className:
                        'text-center',

                    render:
                        function (
                            data
                        ) {

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
                                        class="
                                            fas
                                            fa-user
                                            me-1
                                        "
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

                    data:
                        'id',

                    className:
                        'text-center',

                    orderable:
                        false,

                    searchable:
                        false,

                    render:
                        function (
                            id
                        ) {

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
                                            btn-edit-role
                                        "
                                        data-id="${id}"
                                        title="Edit"
                                    >

                                        <i
                                            class="
                                                fas
                                                fa-edit
                                            "
                                        ></i>

                                    </button>


                                    <button
                                        type="button"
                                        class="
                                            btn
                                            btn-sm
                                            btn-outline-danger
                                            btn-delete-role
                                        "
                                        data-id="${id}"
                                        title="Hapus"
                                    >

                                        <i
                                            class="
                                                fas
                                                fa-trash
                                            "
                                        ></i>

                                    </button>

                                </div>

                            `;

                        }

                }

            ],


            language: {

                emptyTable:
                    'Belum ada user role.',

                zeroRecords:
                    'Role tidak ditemukan.',

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

function openRoleModal()
{

    $('#roleForm')[0].reset();

    $('#role_id')
        .val('');


    $('#role_name')
        .removeClass(
            'is-invalid'
        );


    $('#groupId')
        .removeClass(
            'is-invalid'
        );


    $('#roleNameError')
        .text('');


    $('#groupIdError')
        .text('');


    $('#roleModalTitle')
        .text(
            'Tambah User Role'
        );


    loadGroups();


    if (!roleModal) {

        roleModal =
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById(
                    'roleModal'
                )
            );

    }


    roleModal.show();

}


/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

function editRole(id)
{

    $.ajax({

        url:
            `${window.UserRoleConfig.baseUrl}/${id}`,

        type:
            'GET',

        success:
            function (response) {

                const data =
                    response.data;


                $('#role_id')
                    .val(data.id);


                $('#role_name')
                    .val(data.role_name);


                $('#roleModalTitle')
                    .text(
                        'Edit User Role'
                    );


                loadGroups(
                    data.groupId
                );


                $('#role_name')
                    .removeClass(
                        'is-invalid'
                    );


                $('#groupId')
                    .removeClass(
                        'is-invalid'
                    );


                if (!roleModal) {

                    roleModal =
                        bootstrap.Modal
                            .getOrCreateInstance(
                                document.getElementById(
                                    'roleModal'
                                )
                            );

                }


                roleModal.show();

            },


        error:
            function (xhr) {

                console.error(
                    xhr.responseText
                );

                showError(
                    'Gagal mengambil data role.'
                );

            }

    });

}


/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/

function saveRole()
{

    const id =
        $('#role_id')
            .val();


    const roleName =
        $('#role_name')
            .val()
            .trim();


    const groupId =
        $('#groupId')
            .val();


    /*
    |--------------------------------------------------------------------------
    | RESET ERROR
    |--------------------------------------------------------------------------
    */

    $('#role_name')
        .removeClass(
            'is-invalid'
        );

    $('#groupId')
        .removeClass(
            'is-invalid'
        );


    $('#roleNameError')
        .text('');

    $('#groupIdError')
        .text('');


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    let valid = true;


    if (!groupId) {

        $('#groupId')
            .addClass(
                'is-invalid'
            );

        $('#groupIdError')
            .text(
                'User Group wajib dipilih.'
            );

        valid = false;

    }


    if (!roleName) {

        $('#role_name')
            .addClass(
                'is-invalid'
            );

        $('#roleNameError')
            .text(
                'Nama role wajib diisi.'
            );

        valid = false;

    }


    if (!valid) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    */

    const url = id

        ? `${window.UserRoleConfig.baseUrl}/${id}`

        : window.UserRoleConfig.baseUrl;


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
        $('#btnSaveRole');


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

        url:
            url,

        type:
            method,

        data: {

            role_name:
                roleName,

            groupId:
                groupId,

            _token:
                $('meta[name="csrf-token"]')
                    .attr('content')

        },


        success:
            function (response) {

                roleModal.hide();


                roleTable.ajax.reload(
                    null,
                    false
                );


                Swal.fire({

                    icon:
                        'success',

                    title:
                        'Berhasil',

                    text:
                        response.message ??
                        'Role berhasil disimpan.',

                    timer:
                        1500,

                    showConfirmButton:
                        false

                });

            },


        error:
            function (xhr) {

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
                        errors.role_name
                    ) {

                        $('#role_name')
                            .addClass(
                                'is-invalid'
                            );

                        $('#roleNameError')
                            .text(
                                errors.role_name[0]
                            );

                    }


                    if (
                        errors.groupId
                    ) {

                        $('#groupId')
                            .addClass(
                                'is-invalid'
                            );

                        $('#groupIdError')
                            .text(
                                errors.groupId[0]
                            );

                    }

                    return;

                }


                showError(
                    xhr.responseJSON?.message ??
                    'Terjadi kesalahan.'
                );

            },


        complete:
            function () {

                button
                    .prop(
                        'disabled',
                        false
                    )
                    .html(`
                        <i
                            class="
                                fas
                                fa-save
                                me-2
                            "
                        ></i>

                        Simpan
                    `);

            }

    });

}


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

function deleteRole(id)
{

    Swal.fire({

        title:
            'Hapus User Role?',

        text:
            'Role yang masih digunakan oleh user tidak dapat dihapus.',

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

    }).then(
        function (result) {

            if (
                !result.isConfirmed
            ) {

                return;

            }


            $.ajax({

                url:
                    `${window.UserRoleConfig.baseUrl}/${id}`,

                type:
                    'DELETE',

                data: {

                    _token:
                        $('meta[name="csrf-token"]')
                            .attr('content')

                },


                success:
                    function (response) {

                        roleTable.ajax.reload(
                            null,
                            false
                        );


                        Swal.fire({

                            icon:
                                'success',

                            title:
                                'Berhasil',

                            text:
                                response.message ??
                                'Role berhasil dihapus.',

                            timer:
                                1500,

                            showConfirmButton:
                                false

                        });

                    },


                error:
                    function (xhr) {

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

        }
    );

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