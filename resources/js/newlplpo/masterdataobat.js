$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | CONFIG
    |--------------------------------------------------------------------------
    */

    const config = window.masterDataObatConfig;

    const dataUrl = config.dataUrl;
    const storeUrl = config.storeUrl;

    let editMode = false;


    /*
    |--------------------------------------------------------------------------
    | PASTIKAN KODE OBAT ADALAH INPUT TEXT
    |--------------------------------------------------------------------------
    |
    | Tidak menggunakan Select2.
    |
    */

    const kodeObat = $('#kode_obat');

    if (kodeObat.length) {

        // Hapus kemungkinan instance Select2 lama
        if (kodeObat.hasClass('select2-hidden-accessible')) {

            kodeObat.select2('destroy');

        }

        // Pastikan elemen benar-benar input
        if (kodeObat.prop('tagName').toLowerCase() !== 'input') {

            console.error(
                'ERROR: #kode_obat bukan input text.'
            );

        }

        kodeObat.attr('type', 'text');

    }


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

            searchPlaceholder:
                'Cari kode / nama obat...',

            processing:
                'Memuat data...',

            emptyTable:
                'Belum ada data.',

            zeroRecords:
                'Data tidak ditemukan.'

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TAMBAH OBAT
    |--------------------------------------------------------------------------
    */

    $('#btnTambahObat').on(
        'click',
        function () {

            editMode = false;

            clearForm();

            $('#modalObatTitle').html(`
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Obat
            `);

            $('#saveTextObat')
                .text('Simpan');


            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    document.getElementById('modalObat')
                );

            modal.show();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT OBAT
    |--------------------------------------------------------------------------
    */

    $('#datatableObat').on(
        'click',
        '.btn-edit-obat',
        function () {

            const id =
                $(this).data('id');

            editMode = true;

            clearForm();


            $('#modalObatTitle').html(`
                <i class="bi bi-pencil-square me-2"></i>
                Edit Obat
            `);

            $('#saveTextObat')
                .text('Update');


            $.ajax({

                url:
                    config.baseUrl + '/' + id,

                type:
                    'GET',

                dataType:
                    'json',

                success:
                    function (response) {

                        const data =
                            response.data;


                        $('#obat_id')
                            .val(data.id);


                        /*
                        |--------------------------------------------------------------------------
                        | KODE OBAT
                        |--------------------------------------------------------------------------
                        */

                        $('#kode_obat')
                            .val(data.kode_obat);


                        /*
                        |--------------------------------------------------------------------------
                        | NAMA
                        |--------------------------------------------------------------------------
                        */

                        $('#nama_obat')
                            .val(data.nama_obat);


                        /*
                        |--------------------------------------------------------------------------
                        | SATUAN
                        |--------------------------------------------------------------------------
                        */

                        $('#satuan')
                            .val(data.satuan);


                        /*
                        |--------------------------------------------------------------------------
                        | NAPZA
                        |--------------------------------------------------------------------------
                        */

                        $('#obat_napza')
                            .val(
                                data.obat_napza ?? 'tidak'
                            );


                        const modal =
                            bootstrap.Modal
                                .getOrCreateInstance(
                                    document.getElementById(
                                        'modalObat'
                                    )
                                );

                        modal.show();

                    },

                error:
                    function (xhr) {

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

                title:
                    'Hapus Obat?',

                html: `
                    Data obat
                    <strong>
                        ${escapeHtml(nama)}
                    </strong>
                    akan dihapus.
                `,

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

            }).then(
                function (result) {

                    if (!result.isConfirmed) {

                        return;

                    }


                    $.ajax({

                        url:
                            config.baseUrl + '/' + id,

                        type:
                            'DELETE',

                        data: {

                            _token:
                                config.csrfToken

                        },

                        success:
                            function (response) {

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

                        error:
                            function (xhr) {

                                Swal.fire(
                                    'Tidak dapat dihapus',
                                    getErrorMessage(xhr),
                                    'error'
                                );

                            }

                    });

                }
            );

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
                $('#obat_id').val();


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


            /*
            |--------------------------------------------------------------------------
            | PAYLOAD
            |--------------------------------------------------------------------------
            */

            const payload = {

                kode_obat:
                    $('#kode_obat').val().trim(),

                nama_obat:
                    $('#nama_obat').val().trim(),

                satuan:
                    $('#satuan').val().trim(),

                obat_napza:
                    $('#obat_napza').val(),

                _token:
                    config.csrfToken

            };


            setLoading(true);


            $.ajax({

                url:
                    url,

                type:
                    method,

                data:
                    payload,

                dataType:
                    'json',

                success:
                    function (response) {

                        const modal =
                            bootstrap.Modal.getInstance(
                                document.getElementById(
                                    'modalObat'
                                )
                            );


                        if (modal) {

                            modal.hide();

                        }


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

                error:
                    function (xhr) {

                        handleError(xhr);

                    },

                complete:
                    function () {

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

        const form =
            $('#formObat')[0];


        if (form) {

            form.reset();

        }


        /*
        |--------------------------------------------------------------------------
        | ID EDIT
        |--------------------------------------------------------------------------
        */

        $('#obat_id')
            .val('');


        /*
        |--------------------------------------------------------------------------
        | KODE OBAT
        |--------------------------------------------------------------------------
        | Input text biasa.
        |--------------------------------------------------------------------------
        */

        $('#kode_obat')
            .val('')
            .removeClass('is-invalid');


        /*
        |--------------------------------------------------------------------------
        | NAPZA
        |--------------------------------------------------------------------------
        */

        $('#obat_napza')
            .val('tidak');


        clearValidation();

    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR VALIDATION
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
    | HANDLE VALIDATION ERROR
    |--------------------------------------------------------------------------
    */

    function handleError(xhr)
    {

        console.error(
            xhr.responseText
        );


        if (
            xhr.status === 422 &&
            xhr.responseJSON?.errors
        ) {

            const errors =
                xhr.responseJSON.errors;


            Object.keys(errors)
                .forEach(
                    function (field) {

                        const input =
                            $('#' + field);


                        input.addClass(
                            'is-invalid'
                        );


                        $('#' + field + '_error')
                            .text(
                                errors[field][0]
                            );

                    }
                );


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

        $('#btnSimpanObat')
            .prop(
                'disabled',
                status
            );


        if (status) {

            $('#spinnerObat')
                .removeClass('d-none');


            $('#saveIconObat')
                .addClass('d-none');


            $('#saveTextObat')
                .text(
                    'Menyimpan...'
                );

        }
        else {

            $('#spinnerObat')
                .addClass('d-none');


            $('#saveIconObat')
                .removeClass('d-none');


            $('#saveTextObat')
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
