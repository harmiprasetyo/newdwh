let userTable = null;
let userModal = null;

/*
|--------------------------------------------------------------------------
| CSRF CONFIGURATION
|--------------------------------------------------------------------------
*/

$.ajaxSetup({

    headers: {

        'X-CSRF-TOKEN':
            $('meta[name="csrf-token"]').attr('content')

    }

});
/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    userModal = new bootstrap.Modal(
        document.getElementById('userModal')
    );

    initSelect2();

    loadGroups();

    loadProvinces();

    loadUserTable();

    bindEvents();

});


/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/
function bindEvents()
{
    /*
    |--------------------------------------------------------------------------
    | ADD USER
    |--------------------------------------------------------------------------
    */

    $('#btnAddUser').on('click', function () {

        openUserModal();

    });


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    $('#searchUser').on(
        'keyup',
        debounce(function () {

            if (userTable) {
                userTable.ajax.reload();
            }

        }, 400)
    );


    /*
    |--------------------------------------------------------------------------
    | FILTER GROUP
    |--------------------------------------------------------------------------
    */

    $('#filterGroup').on('change', function () {

        const groupId = $(this).val();

        loadFilterRoles(groupId);

        if (userTable) {
            userTable.ajax.reload();
        }

    });


    /*
    |--------------------------------------------------------------------------
    | FILTER LAIN
    |--------------------------------------------------------------------------
    */

    $('#filterRole, #filterProvinsi, #filterKota')
        .on('change', function () {

            if (userTable) {
                userTable.ajax.reload();
            }

        });


    /*
    |--------------------------------------------------------------------------
    | GROUP → ROLE
    |--------------------------------------------------------------------------
    */

    $('#groupid').on('change', function () {

        const groupId = $(this).val();

        loadRoles(groupId);

    });


    /*
    |--------------------------------------------------------------------------
    | PROVINSI → KOTA
    |--------------------------------------------------------------------------
    */

    $('#kodePropinsi').on('change', function () {

        const province = $(this).val();

        loadCities(province);

    });


    /*
    |--------------------------------------------------------------------------
    | KOTA → KECAMATAN
    |--------------------------------------------------------------------------
    */

    $('#kodeKota').on('change', function () {

        const city = $(this).val();

        loadDistricts(city);

    });


    /*
    |--------------------------------------------------------------------------
    | KECAMATAN → FASKES
    |--------------------------------------------------------------------------
    */

    $('#kodeKecamatan').on('change', function () {

        const district = $(this).val();

        loadFaskes(district);

    });


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#userForm').on('submit', function (e) {

        e.preventDefault();

        saveUser();

    });


    /*
    |--------------------------------------------------------------------------
    | RESET FILTER
    |--------------------------------------------------------------------------
    */

    $('#btnResetFilter').on('click', function () {

        $('#searchUser').val('');

        $('#filterGroup')
            .val('')
            .trigger('change');

        $('#filterRole')
            .html('<option value="">Semua Role</option>')
            .val('')
            .trigger('change');

        $('#filterProvinsi')
            .val('')
            .trigger('change');

        $('#filterKota')
            .html('<option value="">Semua Kota</option>')
            .val('')
            .trigger('change');

        if (userTable) {
            userTable.ajax.reload();
        }

    });
}

/*
|--------------------------------------------------------------------------
| DATATABLE
|--------------------------------------------------------------------------
*/

function loadUserTable()
{

    userTable = $('#userTable').DataTable({

        processing: true,

        serverSide: false,

        searching: false,

        lengthChange: true,

        pageLength: 25,

        responsive: true,

        ajax: {

            url:
                window.UserPanelConfig.datatableUrl,

            data: function (d) {

                d.search =
                    $('#searchUser').val();

                d.groupid =
                    $('#filterGroup').val();

                d.role_id =
                    $('#filterRole').val();

                d.kodePropinsi =
                    $('#filterProvinsi').val();

                d.kodeKota =
                    $('#filterKota').val();

            },

            dataSrc: function (json) {

                const data =
                    json.data ?? [];


                $('#totalUser')
                    .text(data.length);


                $('#totalAdmin')
                    .text(
                        data.filter(
                            x =>
                                x.role ===
                                'administrator'
                        ).length
                    );


                $('#totalFaskes')
                    .text(
                        data.filter(
                            x =>
                                x.role ===
                                'faskes'
                        ).length
                    );


                return data;

            }

        },


        columns: [

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

                    return meta.row + 1;

                }

            },


            {
                data: null,

                render: function (data) {

                    return `

                        <div class="d-flex align-items-center">

                            <div
                                class="rounded-circle
                                       bg-primary
                                       bg-opacity-10
                                       text-primary
                                       d-flex
                                       align-items-center
                                       justify-content-center
                                       me-3"
                                style="
                                    width:42px;
                                    height:42px;
                                "
                            >

                                <i class="fas fa-user"></i>

                            </div>

                            <div>

                                <div class="fw-semibold">
                                    ${escapeHtml(
                                        data.namalengkap
                                    )}
                                </div>

                                <small class="text-muted">
                                    @${escapeHtml(
                                        data.username
                                    )}
                                </small>

                                <br>

                                <small class="text-muted">
                                    ${escapeHtml(
                                        data.email
                                    )}
                                </small>

                            </div>

                        </div>

                    `;

                }

            },


            {
                data: 'group_name',

                defaultContent: '-',

                render: function (data) {

                    return data
                        ? `
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                <i class="fas fa-users me-1"></i>
                                ${escapeHtml(data)}
                            </span>
                        `
                        : '-';

                }

            },


            {
                data: 'role_name',

                defaultContent: '-',

                render: function (data) {

                    return data
                        ? `
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                <i class="fas fa-user-tag me-1"></i>
                                ${escapeHtml(data)}
                            </span>
                        `
                        : `
                            <span class="text-muted">
                                -
                            </span>
                        `;

                }

            },


            {
                data: null,

                render: function (data) {

                    if (!data.namaFaskes) {

                        return `
                            <span class="text-muted">
                                Tidak ada faskes
                            </span>
                        `;

                    }


                    return `

                        <div>

                            <div class="fw-semibold">

                                ${escapeHtml(
                                    data.namaFaskes
                                )}

                            </div>

                            <small class="text-muted">

                                ${escapeHtml(
                                    data.kodeFaskes ??
                                    ''
                                )}

                            </small>

                        </div>

                    `;

                }

            },


            {
    data: null,

    render: function (data) {

        const province =
            data.provinsi_name ?? data.kodePropinsi ?? '-';

        const city =
            data.kota_name ?? data.kodeKota ?? '-';

        const district =
            data.kecamatan_name ?? data.kodeKecamatan ?? '-';

        return `

            <div>

                <div class="fw-semibold">
                    ${escapeHtml(city)}
                </div>

                <small class="text-muted">

                    ${escapeHtml(province)}

                    ${
                        district !== '-'
                            ? ' • ' + escapeHtml(district)
                            : ''
                    }

                </small>

            </div>

        `;

    }

},


            {
                data: 'userid',

                className:
                    'text-center',

                orderable: false,

                render: function (id) {

                    return `

                        <div class="d-flex justify-content-center gap-1">

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                onclick="editUser('${id}')"
                                title="Edit"
                            >
                                <i class="fas fa-edit"></i>
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"
                                onclick="deleteUser('${id}')"
                                title="Hapus"
                            >
                                <i class="fas fa-trash"></i>
                            </button>

                        </div>

                    `;

                }

            }

        ],


        order: [
            [1, 'asc']
        ],


        language: {

            emptyTable:
                'Belum ada user.',

            zeroRecords:
                'Data tidak ditemukan.',

            processing:
                'Memuat data...',

            lengthMenu:
                '_MENU_ data',

            info:
                'Menampilkan _START_ - _END_ dari _TOTAL_ user',

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
| OPEN
|--------------------------------------------------------------------------
*/

function openUserModal()
{
    $('#userForm')[0].reset();

    $('#userid').val('');

    clearValidation();


    $('#userModalTitle')
        .text('Tambah User');


    $('#password')
        .val('')
        .prop('required', true);


    $('#passwordHelp')
        .text(
            'Password wajib diisi. Minimal 6 karakter.'
        );


    /*
    |--------------------------------------------------------------------------
    | GROUP
    |--------------------------------------------------------------------------
    */

    $('#groupid')
        .val('')
        .trigger('change.select2');


    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    $('#role_id')
        .html(
            '<option value="">Pilih Role</option>'
        )
        .val('')
        .trigger('change.select2');


    /*
    |--------------------------------------------------------------------------
    | LOCATION
    |--------------------------------------------------------------------------
    */

    $('#kodePropinsi')
        .val('')
        .trigger('change.select2');


    $('#kodeKota')
        .html(
            '<option value="">Pilih Kota / Kabupaten</option>'
        )
        .val('')
        .trigger('change.select2');


    $('#kodeKecamatan')
        .html(
            '<option value="">Pilih Kecamatan</option>'
        )
        .val('')
        .trigger('change.select2');


    $('#kodeFaskes')
        .html(
            '<option value="">Pilih Faskes</option>'
        )
        .val('')
        .trigger('change.select2');


    userModal.show();
}


/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

function editUser(id)
{
    clearValidation();

    $.ajax({
        url: `${window.UserPanelConfig.baseUrl}/${id}`,
        type: 'GET',

        beforeSend: function () {

            Swal.fire({
                title: 'Memuat data user...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: function () {
                    Swal.showLoading();
                }
            });

        },

        success: async function (response) {

            const data = response.data;

            /*
            |--------------------------------------------------------------------------
            | BASIC DATA
            |--------------------------------------------------------------------------
            */

            $('#userid').val(data.userid);

            $('#username').val(data.username);

            $('#namalengkap').val(data.namalengkap);

            $('#email').val(data.email);

            $('#role').val(data.role);


            /*
            |--------------------------------------------------------------------------
            | PASSWORD
            |--------------------------------------------------------------------------
            */

            $('#password')
                .val('')
                .prop('required', false);

            $('#passwordHelp').text(
                'Kosongkan jika password tidak ingin diubah.'
            );


            /*
            |--------------------------------------------------------------------------
            | TITLE
            |--------------------------------------------------------------------------
            */

            $('#userModalTitle')
                .text('Edit User');


            /*
            |--------------------------------------------------------------------------
            | GROUP → ROLE
            |--------------------------------------------------------------------------
            */

            await loadEditGroupAndRole(data);


            /*
            |--------------------------------------------------------------------------
            | PROVINSI → KOTA → KECAMATAN → FASKES
            |--------------------------------------------------------------------------
            */

            await loadEditLocation(data);


            /*
            |--------------------------------------------------------------------------
            | TUTUP LOADING
            |--------------------------------------------------------------------------
            */

            Swal.close();


            /*
            |--------------------------------------------------------------------------
            | SHOW MODAL
            |--------------------------------------------------------------------------
            */

            userModal.show();

        },

        error: function (xhr) {

            Swal.close();

            showError(
                xhr.responseJSON?.message ??
                'Gagal mengambil data user.'
            );

        }

    });
}

/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/

function saveUser()
{

    const id =
        $('#userid').val();


    const data = {

        username:
            $('#username').val(),

        namalengkap:
            $('#namalengkap').val(),

        email:
            $('#email').val(),

        password:
            $('#password').val(),

        groupid:
            $('#groupid').val(),

        role_id:
            $('#role_id').val(),

        role:
            $('#role').val(),

        kodePropinsi:
            $('#kodePropinsi').val(),

        kodeKota:
            $('#kodeKota').val(),

        kodeKecamatan:
            $('#kodeKecamatan').val(),

        kodeFaskes:
            $('#kodeFaskes').val(),

        namaFaskes:
            $('#kodeFaskes option:selected').text()

    };


    clearValidation();


    const url = id

        ? `${window.UserPanelConfig.baseUrl}/${id}`

        : window.UserPanelConfig.baseUrl;


    const method =
        id ? 'PUT' : 'POST';


    const button =
        $('#btnSaveUser');


    button
        .prop('disabled', true)
        .html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Menyimpan...
        `);


    $.ajax({

        url:

            url,

        type:

            method,

        data:

            data,


        success:
            function (response) {

                userModal.hide();


                userTable.ajax.reload(
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
                        'User berhasil disimpan.',

                    timer:
                        1500,

                    showConfirmButton:
                        false

                });

            },


        error:
            function (xhr) {

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    showValidationErrors(
                        xhr.responseJSON.errors
                    );

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
                    .prop('disabled', false)
                    .html(`
                        <i class="fas fa-save me-2"></i>
                        Simpan User
                    `);

            }

    });

}


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

function deleteUser(id)
{

    Swal.fire({

        title:
            'Hapus User?',

        text:
            'Data user akan dihapus secara permanen.',

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

            if (!result.isConfirmed) {
                return;
            }


            $.ajax({

                url:
                    `${window.UserPanelConfig.baseUrl}/${id}`,

                type:
                    'DELETE',

                success:
                    function (response) {

                        userTable.ajax.reload(
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
                                'User berhasil dihapus.',

                            timer:
                                1500,

                            showConfirmButton:
                                false

                        });

                    },

                error:
                    function (xhr) {

                        showError(
                            xhr.responseJSON?.message ??
                            'User tidak dapat dihapus.'
                        );

                    }

            });

        }
    );

}


/*
|--------------------------------------------------------------------------
| GROUP
|--------------------------------------------------------------------------
*/

function loadGroups()
{

    $.get(
        '/adminpanel/userpanel/groups/datatable',
        function (response) {

            let filter =
                '<option value="">Semua Group</option>';

            let modal =
                '<option value="">Pilih Group</option>';


            (response.data ?? [])
                .forEach(
                    function (item) {

                        filter += `
                            <option value="${item.group_id}">
                                ${escapeHtml(item.group_name)}
                            </option>
                        `;


                        modal += `
                            <option value="${item.group_id}">
                                ${escapeHtml(item.group_name)}
                            </option>
                        `;

                    }
                );


            $('#filterGroup')
                .html(filter)
                .trigger('change.select2');


            $('#groupid')
                .html(modal)
                .trigger('change.select2');

        }
    );

}


/*
|--------------------------------------------------------------------------
| ROLE
|--------------------------------------------------------------------------
*/
function loadRoles(groupId)
{
    const $role = $('#role_id');

    $role
        .html('<option value="">Memuat role...</option>')
        .trigger('change.select2');

    if (!groupId) {

        $role
            .html('<option value="">Pilih Role</option>')
            .trigger('change.select2');

        return;
    }

    $.ajax({

        url: '/adminpanel/userpanel/roles/bygroup',

        type: 'GET',

        data: {
            groupId: groupId
        },

        success: function (response) {

            let option =
                '<option value="">Pilih Role</option>';

            (response.data ?? []).forEach(function (item) {

                option += `
                    <option value="${item.id}">
                        ${escapeHtml(item.role_name)}
                    </option>
                `;

            });

            $role
                .html(option)
                .trigger('change.select2');

        },

        error: function (xhr) {

            console.error(
                'Gagal mengambil role:',
                xhr.responseJSON ?? xhr.responseText
            );

            $role
                .html(
                    '<option value="">Role tidak tersedia</option>'
                )
                .trigger('change.select2');

            showError(
                xhr.responseJSON?.message ??
                'Gagal mengambil data role.'
            );

        }

    });
}

/*
|--------------------------------------------------------------------------
| FILTER ROLE
|--------------------------------------------------------------------------
*/

function loadFilterRoles(groupId)
{

    let option =
        '<option value="">Semua Role</option>';


    if (!groupId) {

        $('#filterRole')
            .html(option)
            .trigger('change.select2');

        return;

    }


    $.get(
        '/adminpanel/userpanel/roles/datatable',
        {
            groupId: groupId
        },
        function (response) {

            (response.data ?? [])
                .forEach(
                    function (item) {

                        option += `
                            <option value="${item.id}">
                                ${escapeHtml(item.role_name)}
                            </option>
                        `;

                    }
                );


            $('#filterRole')
                .html(option)
                .trigger('change.select2');

        }
    );

}


/*
|--------------------------------------------------------------------------
| PROVINCE
|--------------------------------------------------------------------------
*/

function loadProvinces()
{
    $.ajax({

        url: '/adminpanel/wilayah/listpropinsi',

        type: 'GET',

        success: function (response) {

            const provinces = response.data ?? [];

            let filter =
                '<option value="">Semua Provinsi</option>';

            let modal =
                '<option value="">Pilih Provinsi</option>';

            provinces.forEach(function (item) {

                filter += `
                    <option value="${item.code}">
                        ${escapeHtml(item.name)}
                    </option>
                `;

                modal += `
                    <option value="${item.code}">
                        ${escapeHtml(item.name)}
                    </option>
                `;

            });

            $('#filterProvinsi')
                .html(filter)
                .trigger('change');

            $('#kodePropinsi')
                .html(modal)
                .trigger('change');

        },

        error: function (xhr) {

            console.error(
                'LOAD PROVINCE ERROR:',
                xhr.responseJSON ?? xhr.responseText
            );

            showError(
                'Gagal mengambil data provinsi.'
            );

        }

    });
}


/*
|--------------------------------------------------------------------------
| CITY
|--------------------------------------------------------------------------
*/

function loadCities(province, selectedCity = null, callback = null)
{
    const select = $('#kodeKota');

    select.html(
        '<option value="">Pilih Kota / Kabupaten</option>'
    );

    select.val('').trigger('change');

    if (!province) {

        if (typeof callback === 'function') {
            callback();
        }

        return;
    }

    select.prop('disabled', true);

    $.ajax({

        url: '/adminpanel/wilayah/listkota',

        type: 'GET',

        data: {
            province_code: province
        },

        success: function (response) {

            const cities = response.data ?? [];

            cities.forEach(function (item) {

                select.append(`
                    <option value="${item.code}">
                        ${escapeHtml(item.name)}
                    </option>
                `);

            });

            if (selectedCity) {

                select
                    .val(selectedCity)
                    .trigger('change');

            }

            if (typeof callback === 'function') {
                callback();
            }

        },

        error: function (xhr) {

            console.error(
                'LOAD CITY ERROR:',
                xhr.responseJSON ?? xhr.responseText
            );

            showError(
                'Gagal mengambil data kota/kabupaten.'
            );

        },

        complete: function () {

            select.prop('disabled', false);

        }

    });
}

/*
|--------------------------------------------------------------------------
| DISTRICT
|--------------------------------------------------------------------------
*/
function loadDistricts(city, selectedDistrict = null, callback = null)
{
    const select = $('#kodeKecamatan');

    select.html(
        '<option value="">Pilih Kecamatan</option>'
    );

    select.val('').trigger('change');

    if (!city) {

        if (typeof callback === 'function') {
            callback();
        }

        return;
    }

    select.prop('disabled', true);

    $.ajax({

        url: '/adminpanel/wilayah/listkecamatan',

        type: 'GET',

        data: {
            city_code: city
        },

        success: function (response) {

            const districts = response.data ?? [];

            districts.forEach(function (item) {

                select.append(`
                    <option value="${item.code}">
                        ${escapeHtml(item.name)}
                    </option>
                `);

            });

            if (selectedDistrict) {

                select
                    .val(selectedDistrict)
                    .trigger('change');

            }

            if (typeof callback === 'function') {
                callback();
            }

        },

        error: function (xhr) {

            console.error(
                'LOAD DISTRICT ERROR:',
                xhr.responseJSON ?? xhr.responseText
            );

            showError(
                'Gagal mengambil data kecamatan.'
            );

        },

        complete: function () {

            select.prop('disabled', false);

        }

    });
}

/*
|--------------------------------------------------------------------------
| FASKES
|--------------------------------------------------------------------------
*/
function loadFaskes(district)
{
    const select = $('#kodeFaskes');

    select
        .html(
            '<option value="">Memuat Faskes...</option>'
        )
        .prop('disabled', true)
        .trigger('change.select2');


    if (!district) {

        select
            .html(
                '<option value="">Pilih Faskes</option>'
            )
            .prop('disabled', true)
            .trigger('change.select2');

        return;
    }


    $.ajax({

        url:
            window.UserPanelConfig.faskesUrl,

        type:
            'GET',

        data: {
            kecamatan: district
        },

        success: function (response) {

            let option =
                '<option value="">Pilih Faskes</option>';


            (response.data ?? [])
                .forEach(function (item) {

                    option += `
                        <option value="${escapeHtml(item.kodeFaskes)}">
                            ${escapeHtml(item.namaFaskes)}
                        </option>
                    `;

                });


            select
                .html(option)
                .prop('disabled', false)
                .trigger('change.select2');

        },

        error: function (xhr) {

            console.error(
                'Gagal mengambil faskes:',
                xhr.responseText
            );


            select
                .html(
                    '<option value="">Gagal memuat faskes</option>'
                )
                .prop('disabled', true)
                .trigger('change.select2');


            showError(
                xhr.responseJSON?.message ??
                'Gagal mengambil data faskes.'
            );

        }

    });
}


/*
|--------------------------------------------------------------------------
| EDIT LOCATION
|--------------------------------------------------------------------------
*/
async function loadEditLocation(data)
{
    /*
    |--------------------------------------------------------------------------
    | GET LOCATION VALUES
    |--------------------------------------------------------------------------
    */

    const provinceCode =
        data.kodePropinsi ??
        data.kode_propinsi ??
        '';

    const cityCode =
        data.kodeKota ??
        data.kode_kota ??
        '';

    const districtCode =
        data.kodeKecamatan ??
        data.kode_kecamatan ??
        '';

    const faskesCode =
        data.kodeFaskes ??
        data.kode_faskes ??
        '';


    console.log(
        'EDIT LOCATION:',
        {
            provinceCode: provinceCode,
            cityCode: cityCode,
            districtCode: districtCode,
            faskesCode: faskesCode
        }
    );


    /*
    |--------------------------------------------------------------------------
    | PROVINSI
    |--------------------------------------------------------------------------
    */

    $('#kodePropinsi')
        .val(String(provinceCode))
        .trigger('change.select2');


    /*
    |--------------------------------------------------------------------------
    | KOTA
    |--------------------------------------------------------------------------
    */

    await loadEditCities(
        provinceCode,
        cityCode
    );


    /*
    |--------------------------------------------------------------------------
    | KECAMATAN
    |--------------------------------------------------------------------------
    */

    await loadEditDistricts(
        cityCode,
        districtCode
    );


    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    await loadEditFaskes(
        districtCode,
        faskesCode
    );
}

function loadEditDistricts(cityCode, selectedDistrict)
{
    return new Promise(function (resolve, reject) {

        const select =
            $('#kodeKecamatan');


        select
            .html(
                '<option value="">Memuat kecamatan...</option>'
            )
            .prop('disabled', true)
            .trigger('change.select2');


        if (!cityCode) {

            select
                .html(
                    '<option value="">Pilih Kecamatan</option>'
                )
                .prop('disabled', false)
                .val('')
                .trigger('change.select2');

            resolve();

            return;
        }


        $.ajax({

            url:
                '/adminpanel/wilayah/listkecamatan',

            type:
                'GET',

            data: {
                city_code: cityCode
            },

            success: function (response) {

                let options =
                    '<option value="">Pilih Kecamatan</option>';


                (response.data ?? []).forEach(function (item) {

                    options += `
                        <option value="${item.code}">
                            ${escapeHtml(item.name)}
                        </option>
                    `;

                });


                select
                    .html(options)
                    .prop('disabled', false);


                /*
                |--------------------------------------------------------------------------
                | SELECT KECAMATAN
                |--------------------------------------------------------------------------
                */

                if (
                    selectedDistrict !== null &&
                    selectedDistrict !== undefined
                ) {

                    select.val(
                        String(selectedDistrict)
                    );

                }


                select
                    .trigger('change.select2');


                resolve();

            },

            error: function (xhr) {

                select
                    .html(
                        '<option value="">Gagal memuat kecamatan</option>'
                    )
                    .prop('disabled', false)
                    .trigger('change.select2');

                reject(xhr);

            }

        });

    });
}


function loadEditCities(provinceCode, selectedCity)
{
    return new Promise(function (resolve, reject) {

        const select = $('#kodeKota');


        select
            .html(
                '<option value="">Memuat kota/kabupaten...</option>'
            )
            .prop('disabled', true)
            .trigger('change.select2');


        if (!provinceCode) {

            select
                .html(
                    '<option value="">Pilih Kota</option>'
                )
                .prop('disabled', false)
                .val('')
                .trigger('change.select2');

            resolve();

            return;
        }


        $.ajax({

            url:
                '/adminpanel/wilayah/listkota',

            type:
                'GET',

            data: {
                province_code: provinceCode
            },

            success: function (response) {

                let options =
                    '<option value="">Pilih Kota</option>';


                (response.data ?? []).forEach(function (item) {

                    options += `
                        <option value="${item.code}">
                            ${escapeHtml(item.name)}
                        </option>
                    `;

                });


                select
                    .html(options)
                    .prop('disabled', false);


                /*
                |--------------------------------------------------------------------------
                | SELECT KOTA
                |--------------------------------------------------------------------------
                */

                if (
                    selectedCity !== null &&
                    selectedCity !== undefined
                ) {

                    select.val(
                        String(selectedCity)
                    );

                }


                select
                    .trigger('change.select2');


                resolve();

            },

            error: function (xhr) {

                select
                    .html(
                        '<option value="">Gagal memuat kota</option>'
                    )
                    .prop('disabled', false)
                    .trigger('change.select2');

                reject(xhr);

            }

        });

    });
}



function loadFilterCities(province)
{

    $('#filterKota')
        .html(
            '<option value="">Semua Kota</option>'
        );


    if (!province) {

        $('#filterKota')
            .trigger('change.select2');

        return;

    }


    $.get(
        '/adminpanel/wilayah/listkota',
        {
            province_code:
                province
        },
        function (response) {

            (response.data ?? [])
                .forEach(
                    function (item) {

                        $('#filterKota')
                            .append(`
                                <option value="${item.code}">
                                    ${escapeHtml(item.name)}
                                </option>
                            `);

                    }
                );


            $('#filterKota')
                .trigger('change.select2');

        }
    );

}


/*
|--------------------------------------------------------------------------
| RESET LOCATION
|--------------------------------------------------------------------------
*/

function resetLocationFields()
{

    $('#kodePropinsi')
        .val('')
        .trigger('change.select2');


    $('#kodeKota')
        .html(
            '<option value="">Pilih Kota</option>'
        )
        .trigger('change.select2');


    $('#kodeKecamatan')
        .html(
            '<option value="">Pilih Kecamatan</option>'
        )
        .trigger('change.select2');


    $('#kodeFaskes')
        .html(
            '<option value="">Pilih Faskes</option>'
        )
        .trigger('change.select2');

}


/*
|--------------------------------------------------------------------------
| SELECT2
|--------------------------------------------------------------------------
*/

function initSelect2()
{
    $('#groupid, #role_id, #kodePropinsi, #kodeKota, #kodeKecamatan, #kodeFaskes')
        .select2({

            dropdownParent: $('#userModal'),

            width: '100%'

        });


    $('#filterGroup, #filterRole, #filterProvinsi, #filterKota')
        .select2({

            width: '100%'

        });
}


/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
*/

function clearValidation()
{

    $('.is-invalid')
        .removeClass('is-invalid');

    $('.invalid-feedback')
        .text('');

}


function showValidationErrors(errors)
{

    Object.keys(errors)
        .forEach(
            function (field) {

                const input =
                    $('#' + field);

                input.addClass(
                    'is-invalid'
                );


                $('#' + field + 'Error')
                    .text(
                        errors[field][0]
                    );

            }
        );

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
| DEBOUNCE
|--------------------------------------------------------------------------
*/

function debounce(func, wait)
{

    let timeout;


    return function () {

        const context =
            this;

        const args =
            arguments;


        clearTimeout(
            timeout
        );


        timeout =
            setTimeout(
                function () {

                    func.apply(
                        context,
                        args
                    );

                },
                wait
            );

    };

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




async function loadEditGroupAndRole(data)
{
    /*
    |--------------------------------------------------------------------------
    | SET GROUP
    |--------------------------------------------------------------------------
    */

    const groupId = data.groupid ?? data.group_id ?? '';

    $('#groupid')
        .val(String(groupId))
        .trigger('change.select2');


    /*
    |--------------------------------------------------------------------------
    | LOAD ROLE
    |--------------------------------------------------------------------------
    */

    const roleId =
        data.role_id ??
        data.roleid ??
        data.role ??
        '';

    await loadRolesForEdit(
        groupId,
        roleId
    );
}



function loadRolesForEdit(groupId, selectedRoleId)
{
    return new Promise(function (resolve, reject) {

        const select = $('#role_id');


        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        select
            .prop('disabled', true)
            .html(
                '<option value="">Memuat role...</option>'
            )
            .trigger('change.select2');


        /*
        |--------------------------------------------------------------------------
        | GROUP KOSONG
        |--------------------------------------------------------------------------
        */

        if (!groupId) {

            select
                .html(
                    '<option value="">Pilih Role</option>'
                )
                .prop('disabled', false)
                .val('')
                .trigger('change.select2');

            resolve();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD ROLE
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url:
                window.UserPanelConfig.rolesByGroupUrl ??
                '/adminpanel/userpanel/roles/bygroup',

            type:
                'GET',

            data: {

                /*
                | Gunakan nama parameter yang sama dengan
                | fungsi loadRoles()
                */

                groupId: groupId

            },

            success: function (response) {

                const roles =
                    response.data ?? [];


                let options =
                    '<option value="">Pilih Role</option>';


                /*
                |--------------------------------------------------------------------------
                | BUILD OPTIONS
                |--------------------------------------------------------------------------
                */

                roles.forEach(function (item) {

                    const roleId =
                        item.id ??
                        item.role_id;


                    const roleName =
                        item.role_name ??
                        item.name ??
                        '-';


                    if (
                        roleId !== null &&
                        roleId !== undefined
                    ) {

                        options += `
                            <option value="${escapeHtml(String(roleId))}">
                                ${escapeHtml(roleName)}
                            </option>
                        `;

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | INSERT OPTIONS
                |--------------------------------------------------------------------------
                */

                select
                    .html(options)
                    .prop('disabled', false);


                /*
                |--------------------------------------------------------------------------
                | SET SELECTED ROLE
                |--------------------------------------------------------------------------
                */

                const selectedValue =
                    selectedRoleId !== null &&
                    selectedRoleId !== undefined
                        ? String(selectedRoleId)
                        : '';


                /*
                |--------------------------------------------------------------------------
                | CEK OPTION
                |--------------------------------------------------------------------------
                */

                const optionExists =
                    select
                        .find('option')
                        .filter(function () {

                            return String(
                                $(this).val()
                            ) === selectedValue;

                        })
                        .length > 0;


                console.log(
                    'EDIT ROLE',
                    {
                        groupId: groupId,
                        selectedRoleId: selectedRoleId,
                        selectedValue: selectedValue,
                        optionExists: optionExists
                    }
                );


                if (optionExists) {

                    select
                        .val(selectedValue);

                } else {

                    console.warn(
                        'Role tidak ditemukan:',
                        selectedValue
                    );

                    select.val('');

                }


                /*
                |--------------------------------------------------------------------------
                | REFRESH SELECT2
                |--------------------------------------------------------------------------
                */

                select.trigger('change.select2');


                resolve();

            },

            error: function (xhr) {

                console.error(
                    'LOAD EDIT ROLE ERROR:',
                    xhr.responseJSON ??
                    xhr.responseText
                );


                select
                    .html(
                        '<option value="">Role tidak tersedia</option>'
                    )
                    .prop('disabled', false)
                    .val('')
                    .trigger('change.select2');


                reject(xhr);

            }

        });

    });
}


/*
|--------------------------------------------------------------------------
| GLOBAL FUNCTIONS
|--------------------------------------------------------------------------
|
| Dibutuhkan karena tombol pada Blade menggunakan onclick=""
|
|--------------------------------------------------------------------------
*/

window.openUserModal = openUserModal;
window.editUser = editUser;
window.deleteUser = deleteUser;
