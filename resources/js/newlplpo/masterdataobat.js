$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    */

    const config = window.masterDataObatConfig;

    const dataUrl = config.dataUrl;
    const storeUrl = config.storeUrl;


    let editMode = false;


    /*
    |--------------------------------------------------------------------------
    | SELECT2 OBAT
    |--------------------------------------------------------------------------
    */

    $('#kode_obat').select2({

        dropdownParent: $('#modalObat'),

        width: '100%',

        placeholder: '-- Pilih Obat --',

        allowClear: true

    });


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    const table = $('#datatableObat').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        pageLength: 10,

        ajax: {

            url: dataUrl,

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
                data: 'kode_obat',

                name: 'kode_obat'

            },

            {
                data: 'nama_obat',

                name: 'nama_obat'

            },

            {
                data: 'satuan',

                name: 'satuan'

            },

            {
                data: 'obat_napza',

                name: 'obat_napza',

                className: 'text-center'

            },

            {
                data: 'aksi',

                name: 'aksi',

                orderable: false,

                searchable: false,

                className: 'text-center'

            }

        ],

        order: [

            [
                2,
                'asc'
            ]

        ],

        language: {

            search: 'Cari:',

            searchPlaceholder: 'Cari kode / nama obat...',

            processing: 'Memuat data...',

            emptyTable: 'Belum ada data.',

            zeroRecords: 'Data tidak ditemukan.'

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TAMBAH OBAT
    |--------------------------------------------------------------------------
    */

    $('#btnTambahObat').on('click', function () {

        editMode = false;

        clearForm();

        $('#modalTitle').html(`
            <i class="bi bi-plus-circle me-2"></i>
            Tambah Obat
        `);

        $('#saveText').text('Simpan');

        const modal =
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById('modalObat')
            );

        modal.show();

    });


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $('#datatableObat').on(
        'click',
        '.btn-edit-obat',
        function () {

            const id = $(this).data('id');

            editMode = true;

            clearForm();

            $('#modalTitle').html(`
                <i class="bi bi-pencil-square me-2"></i>
                Edit Obat
            `);

            $('#saveText').text('Update');


            $.ajax({

                url: config.baseUrl + '/' + id,

                type: 'GET',

                success: function (response) {

                    const data = response.data;


                    $('#data_id')
                        .val(data.id);


                    $('#kode_obat')
                        .val(data.kode_obat);


                    $('#nama_obat')
                        .val(data.nama_obat);


                    $('#satuan')
                        .val(data.satuan);


                    $('#obat_napza')
                        .val(data.obat_napza);


                    const modal =
                        bootstrap.Modal.getOrCreateInstance(
                            document.getElementById('modalObat')
                        );

                    modal.show();

                },

                error: function (xhr) {

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

    $('#datatableObat').on(
        'click',
        '.btn-delete-obat',
        function () {

            const id =
                $(this).data('id');

            const nama =
                $(this).data('nama');


            Swal.fire({

                title: 'Hapus Obat?',

                html: `
                    Data obat
                    <strong>${escapeHtml(nama)}</strong>
                    akan dihapus.
                `,

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Ya, Hapus',

                cancelButtonText: 'Batal',

                confirmButtonColor: '#dc3545'

            }).then(function (result) {

                if (!result.isConfirmed) {

                    return;

                }


                $.ajax({

                    url:
                        config.baseUrl + '/' + id,

                    type: 'DELETE',

                    data: {

                        _token: config.csrfToken

                    },

                    success: function (response) {

                        Swal.fire({

                            icon: 'success',

                            title: 'Berhasil',

                            text: response.message,

                            timer: 1500,

                            showConfirmButton: false

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
    | SUBMIT FORM
    |--------------------------------------------------------------------------
    */

    $('#formObat').on(
        'submit',
        function (e) {

            e.preventDefault();

            clearValidation();


            const id =
                $('#data_id').val();


            let url =
                storeUrl;

            let method =
                'POST';


            if (editMode) {

                url =
                    config.baseUrl + '/' + id;

                method =
                    'PUT';

            }


            const payload = {

                kode_obat:
                    $('#kode_obat').val(),

                nama_obat:
                    $('#nama_obat').val(),

                satuan:
                    $('#satuan').val(),

                obat_napza:
                    $('#obat_napza').val(),

                _token:
                    config.csrfToken

            };


            setLoading(true);


            $.ajax({

                url: url,

                type: method,

                data: payload,

                success: function (response) {

                    const modal =
                        bootstrap.Modal.getInstance(
                            document.getElementById('modalObat')
                        );

                    if (modal) {

                        modal.hide();

                    }


                    Swal.fire({

                        icon: 'success',

                        title: 'Berhasil',

                        text: response.message,

                        timer: 1500,

                        showConfirmButton: false

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

        $('#formObat')[0].reset();

        $('#data_id').val('');

        $('#kode_obat')
            .val(null)
            .trigger('change');

        $('#obat_napza')
            .val('tidak');

        clearValidation();

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    function clearValidation()
    {

        $('#formObat')
            .find('.is-invalid')
            .removeClass('is-invalid');

        $('#formObat')
            .find('.invalid-feedback')
            .text('');

    }


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    function handleError(xhr)
    {

        console.error(xhr.responseText);


        if (
            xhr.status === 422 &&
            xhr.responseJSON?.errors
        ) {

            const errors =
                xhr.responseJSON.errors;


            Object.keys(errors)
                .forEach(function (field) {

                    const input =
                        $('#' + field);

                    input.addClass(
                        'is-invalid'
                    );


                    $('#' + field + '_error')
                        .text(
                            errors[field][0]
                        );

                });


            Swal.fire(
                'Validasi',
                'Periksa kembali data yang dimasukkan.',
                'warning'
            );

            return;

        }


        Swal.fire(
            'Error',
            getErrorMessage(xhr),
            'error'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ERROR MESSAGE
    |--------------------------------------------------------------------------
    */

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
