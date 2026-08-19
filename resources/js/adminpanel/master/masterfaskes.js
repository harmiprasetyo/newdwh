let tableFaskes = null;
let modalFaskes = null;
let editMode = false;


/*
|--------------------------------------------------------------------------
| Document Ready
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    console.log('MASTER FASKES JS LOADED');

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Modal
    |--------------------------------------------------------------------------
    */

    const modalElement = document.getElementById('modalFaskes');

    if (
        modalElement &&
        typeof bootstrap !== 'undefined'
    ) {
        modalFaskes = new bootstrap.Modal(modalElement);
    }


    /*
    |--------------------------------------------------------------------------
    | Initialize
    |--------------------------------------------------------------------------
    */

    initDataTable();

    loadProvinces();

    loadTypes();

    registerEvents();

});


/*
|--------------------------------------------------------------------------
| DataTable
|--------------------------------------------------------------------------
*/

function initDataTable()
{
    tableFaskes = $('#tableFaskes').DataTable({

        processing: true,

        serverSide: true,

        ajax: {
            url: '/adminpanel/master/faskes/datatable',

            type: 'GET',

            error: function (xhr, status, error) {

                console.error('DataTable Error');

                console.error('Status:', xhr.status);

                console.error('Error:', error);

                console.error(
                    'Response:',
                    xhr.responseText
                );

            }
        },

        columns: [

            /*
            |--------------------------------------------------------------------------
            | No
            |--------------------------------------------------------------------------
            */

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },


            /*
            |--------------------------------------------------------------------------
            | Kode Faskes
            |--------------------------------------------------------------------------
            */

            {
                data: 'kodeFaskes',
                name: 'kodeFaskes'
            },


            /*
            |--------------------------------------------------------------------------
            | Nama Faskes
            |--------------------------------------------------------------------------
            */

            {
                data: 'namaFaskes',
                name: 'namaFaskes'
            },


            /*
            |--------------------------------------------------------------------------
            | Type Faskes
            |--------------------------------------------------------------------------
            */

            {
                data: 'type_faskes',
                name: 'typeFaskes'
            },


            /*
            |--------------------------------------------------------------------------
            | Provinsi
            |--------------------------------------------------------------------------
            */

            {
                data: 'provinsi',
                name: 'kodePropinsi'
            },


            /*
            |--------------------------------------------------------------------------
            | Kota / Kabupaten
            |--------------------------------------------------------------------------
            */

            {
                data: 'kota',
                name: 'kodeKabupaten'
            },


            /*
            |--------------------------------------------------------------------------
            | Kecamatan
            |--------------------------------------------------------------------------
            */

            {
                data: 'kecamatan',
                name: 'kodeKecamatan'
            },


            /*
            |--------------------------------------------------------------------------
            | Kepemilikan
            |--------------------------------------------------------------------------
            */

            {
                data: 'kepemilikan',
                name: 'kepemilikan'
            },


            /*
            |--------------------------------------------------------------------------
            | Action
            |--------------------------------------------------------------------------
            */

            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }

        ],

        order: [
            [2, 'asc']
        ],

        language: {

            processing: 'Memproses...',

            search: 'Cari:',

            lengthMenu:
                'Tampilkan _MENU_ data',

            info:
                'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

            infoEmpty:
                'Tidak ada data',

            zeroRecords:
                'Data tidak ditemukan',

            emptyTable:
                'Belum ada data faskes',

            paginate: {

                first: 'Pertama',

                last: 'Terakhir',

                next: 'Berikutnya',

                previous: 'Sebelumnya'

            }

        }

    });
}


/*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
*/

function registerEvents()
{

    /*
    |--------------------------------------------------------------------------
    | Tambah
    |--------------------------------------------------------------------------
    */

    $('#btnTambah').on(
        'click',
        function () {

            openCreate();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Provinsi
    |--------------------------------------------------------------------------
    */

    $('#kodePropinsi').on(
        'change',
        function () {

            const provinceCode = $(this).val();

            resetCity();

            resetDistrict();

            if (!provinceCode) {
                return;
            }

            loadCities(provinceCode);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Kota
    |--------------------------------------------------------------------------
    */

    $('#kodeKabupaten').on(
        'change',
        function () {

            const cityCode = $(this).val();

            resetDistrict();

            if (!cityCode) {
                return;
            }

            loadDistricts(cityCode);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    $('#formFaskes').on(
        'submit',
        function (e) {

            e.preventDefault();

            saveFaskes();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    $('#tableFaskes').on(
        'click',
        '.btn-edit',
        function () {

            const id = $(this).data('id');

            openEdit(id);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    $('#tableFaskes').on(
        'click',
        '.btn-delete',
        function () {

            const id = $(this).data('id');

            const name = $(this).data('name');

            deleteFaskes(id, name);

        }
    );

}


/*
|--------------------------------------------------------------------------
| Open Create
|--------------------------------------------------------------------------
*/

function openCreate()
{

    editMode = false;

    resetForm();

    $('#modalFaskesTitle')
        .text('Tambah Faskes');

    if (modalFaskes) {

        modalFaskes.show();

    }

}


/*
|--------------------------------------------------------------------------
| Open Edit
|--------------------------------------------------------------------------
*/

function openEdit(id)
{

    editMode = true;

    resetForm();

    $.ajax({

        url: window.routeFaskesShow
            .replace(':id', id),

        type: 'GET',

        success: function (response) {

            if (!response.success) {

                showError({
                    responseJSON: {
                        message:
                            response.message ||
                            'Data tidak ditemukan.'
                    }
                });

                return;

            }

            const data = response.data;


            /*
            |--------------------------------------------------------------------------
            | Basic Data
            |--------------------------------------------------------------------------
            */

            $('#faskes_id')
                .val(data.id);

            $('#kodeFaskes')
                .val(data.kodeFaskes);

            $('#namaFaskes')
                .val(data.namaFaskes);

            $('#typeFaskes')
                .val(data.typeFaskes);

            $('#kepemilikan')
                .val(data.kepemilikan);


            /*
            |--------------------------------------------------------------------------
            | Province
            |--------------------------------------------------------------------------
            */

            loadProvinces(function () {

                $('#kodePropinsi')
                    .val(data.kodePropinsi);


                /*
                |--------------------------------------------------------------------------
                | City
                |--------------------------------------------------------------------------
                */

                loadCities(
                    data.kodePropinsi,
                    function () {

                        $('#kodeKabupaten')
                            .val(data.kodeKabupaten)
                            .prop(
                                'disabled',
                                false
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | District
                        |--------------------------------------------------------------------------
                        */

                        loadDistricts(
                            data.kodeKabupaten,
                            function () {

                                $('#kodeKecamatan')
                                    .val(data.kodeKecamatan)
                                    .prop(
                                        'disabled',
                                        false
                                    );

                            }
                        );

                    }
                );

            });


            $('#modalFaskesTitle')
                .text('Edit Faskes');


            if (modalFaskes) {

                modalFaskes.show();

            }

        },

        error: function (xhr) {

            showError(xhr);

        }

    });

}


/*
|--------------------------------------------------------------------------
| Load Types
|--------------------------------------------------------------------------
*/

function loadTypes(callback = null)
{

    $.ajax({

        url: window.routeFaskesTypes,

        type: 'GET',

        success: function (response) {

            const select =
                $('#typeFaskes');

            select.empty();

            select.append(
                '<option value="">-- Pilih Type Faskes --</option>'
            );

            if (response.success) {

                response.data.forEach(
                    function (item) {

                        select.append(

                            $('<option>', {

                                value:
                                    item.id,

                                text:
                                    item.typeFaskes

                            })

                        );

                    }
                );

            }

            if (
                typeof callback ===
                'function'
            ) {

                callback();

            }

        },

        error: function (xhr) {

            showError(xhr);

        }

    });

}


/*
|--------------------------------------------------------------------------
| Load Provinces
|--------------------------------------------------------------------------
*/

function loadProvinces(callback = null)
{

    $.ajax({

        url: window.routeFaskesProvinces,

        type: 'GET',

        success: function (response) {

            const select =
                $('#kodePropinsi');

            select.empty();

            select.append(
                '<option value="">-- Pilih Provinsi --</option>'
            );

            if (response.success) {

                response.data.forEach(
                    function (item) {

                        select.append(

                            $('<option>', {

                                value:
                                    item.code,

                                text:
                                    item.name

                            })

                        );

                    }
                );

            }

            if (
                typeof callback ===
                'function'
            ) {

                callback();

            }

        },

        error: function (xhr) {

            showError(xhr);

        }

    });

}


/*
|--------------------------------------------------------------------------
| Load Cities
|--------------------------------------------------------------------------
*/

function loadCities(
    provinceCode,
    callback = null
)
{

    $.ajax({

        url: window.routeFaskesCities,

        type: 'GET',

        data: {

            province_code:
                provinceCode

        },

        success: function (response) {

            const select =
                $('#kodeKabupaten');

            select.empty();

            select.append(
                '<option value="">-- Pilih Kota/Kabupaten --</option>'
            );

            if (response.success) {

                response.data.forEach(
                    function (item) {

                        select.append(

                            $('<option>', {

                                value:
                                    item.code,

                                text:
                                    item.name

                            })

                        );

                    }
                );

                select.prop(
                    'disabled',
                    false
                );

            }

            if (
                typeof callback ===
                'function'
            ) {

                callback();

            }

        },

        error: function (xhr) {

            showError(xhr);

        }

    });

}


/*
|--------------------------------------------------------------------------
| Load Districts
|--------------------------------------------------------------------------
*/

function loadDistricts(
    cityCode,
    callback = null
)
{

    $.ajax({

        url: window.routeFaskesDistricts,

        type: 'GET',

        data: {

            city_code:
                cityCode

        },

        success: function (response) {

            const select =
                $('#kodeKecamatan');

            select.empty();

            select.append(
                '<option value="">-- Pilih Kecamatan --</option>'
            );

            if (response.success) {

                response.data.forEach(
                    function (item) {

                        select.append(

                            $('<option>', {

                                value:
                                    item.code,

                                text:
                                    item.name

                            })

                        );

                    }
                );

                select.prop(
                    'disabled',
                    false
                );

            }

            if (
                typeof callback ===
                'function'
            ) {

                callback();

            }

        },

        error: function (xhr) {

            showError(xhr);

        }

    });

}


/*
|--------------------------------------------------------------------------
| Save
|--------------------------------------------------------------------------
*/

function saveFaskes()
{

    clearErrors();

    const id =
        $('#faskes_id').val();

    let url =
        window.routeFaskesStore;

    let method =
        'POST';


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    if (editMode) {

        url =
            window.routeFaskesUpdate
                .replace(':id', id);

        method =
            'PUT';

    }


    const formData =
        $('#formFaskes').serialize();

    const button =
        $('#btnSimpan');


    button
        .prop(
            'disabled',
            true
        )
        .html(
            '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'
        );


    $.ajax({

        url: url,

        type: method,

        data: formData,

        success: function (response) {

            if (response.success) {

                if (modalFaskes) {

                    modalFaskes.hide();

                }

                if (tableFaskes) {

                    tableFaskes.ajax.reload(
                        null,
                        false
                    );

                }

                showSuccess(
                    response.message
                );

            }

        },

        error: function (xhr) {

            if (
                xhr.status === 422 &&
                xhr.responseJSON &&
                xhr.responseJSON.errors
            ) {

                showValidationErrors(
                    xhr.responseJSON.errors
                );

            } else {

                showError(xhr);

            }

        },

        complete: function () {

            button
                .prop(
                    'disabled',
                    false
                )
                .html(
                    '<i class="fas fa-save"></i> Simpan'
                );

        }

    });

}


/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

function deleteFaskes(
    id,
    name
)
{

    if (
        !confirm(
            'Apakah Anda yakin ingin menghapus faskes "' +
            name +
            '"?'
        )
    ) {

        return;

    }


    $.ajax({

        url:
            window.routeFaskesDelete
                .replace(':id', id),

        type:
            'DELETE',

        data: {

            _token:
                $('meta[name="csrf-token"]')
                    .attr('content')

        },

        success: function (response) {

            if (response.success) {

                if (tableFaskes) {

                    tableFaskes.ajax.reload(
                        null,
                        false
                    );

                }

                showSuccess(
                    response.message
                );

            }

        },

        error: function (xhr) {

            showError(xhr);

        }

    });

}


/*
|--------------------------------------------------------------------------
| Reset Form
|--------------------------------------------------------------------------
*/

function resetForm()
{

    $('#formFaskes')[0].reset();

    $('#faskes_id').val('');

    clearErrors();

    resetCity();

    resetDistrict();

}


/*
|--------------------------------------------------------------------------
| Reset City
|--------------------------------------------------------------------------
*/

function resetCity()
{

    $('#kodeKabupaten')
        .empty()
        .append(
            '<option value="">-- Pilih Kota/Kabupaten --</option>'
        )
        .prop(
            'disabled',
            true
        );

}


/*
|--------------------------------------------------------------------------
| Reset District
|--------------------------------------------------------------------------
*/

function resetDistrict()
{

    $('#kodeKecamatan')
        .empty()
        .append(
            '<option value="">-- Pilih Kecamatan --</option>'
        )
        .prop(
            'disabled',
            true
        );

}


/*
|--------------------------------------------------------------------------
| Validation Errors
|--------------------------------------------------------------------------
*/

function clearErrors()
{

    $('.is-invalid')
        .removeClass('is-invalid');

    $('.invalid-feedback')
        .text('');

}


function showValidationErrors(errors)
{

    clearErrors();

    $.each(
        errors,
        function (field, messages) {

            const input =
                $('#' + field);

            input.addClass(
                'is-invalid'
            );

            $('#error-' + field)
                .text(messages[0]);

        }
    );

}


/*
|--------------------------------------------------------------------------
| Notification
|--------------------------------------------------------------------------
*/

function showSuccess(message)
{

    if (
        typeof Swal !==
        'undefined'
    ) {

        Swal.fire({

            icon: 'success',

            title: 'Berhasil',

            text: message,

            timer: 1800,

            showConfirmButton: false

        });

    } else {

        alert(message);

    }

}


function showError(xhr)
{

    let message =
        'Terjadi kesalahan.';


    if (
        xhr &&
        xhr.responseJSON &&
        xhr.responseJSON.message
    ) {

        message =
            xhr.responseJSON.message;

    }


    if (
        typeof Swal !==
        'undefined'
    ) {

        Swal.fire({

            icon: 'error',

            title: 'Error',

            text: message

        });

    } else {

        alert(message);

    }

}
