$(function () {

    const config =
        window.WilayahKerjaPosyanduConfig || {};

            /*
    |--------------------------------------------------------------------------
    | TAGIFY
    |--------------------------------------------------------------------------
    */

    const tagifyRW =
        new Tagify(
            document.querySelector('#rw'),
            {
                duplicates: false,

                maxTags: 30,

                pattern: /^[0-9]{1,3}$/,

                dropdown: {
                    enabled: 0
                }
            }
        );


    /*
    |--------------------------------------------------------------------------
    | SELECT2 POSYANDU
    |--------------------------------------------------------------------------
    */

    $('#kodePosyandu').select2({

        dropdownParent:
            $('#offcanvasForm'),

        width: '100%',

        placeholder:
            'Pilih Posyandu',

        allowClear: true,

        ajax: {

            url:
                config.selectPosyanduUrl,

            dataType: 'json',

            delay: 250,

            data: function (params) {

                return {
                    q: params.term || ''
                };

            },

            processResults: function (data) {

                return {
                    results: data
                };

            },

            cache: true

        }

    });

    const table =
        $('#datatable').DataTable({

            processing: true,

            serverSide: true,

            ajax: {
                url: config.datatableUrl,
                type: 'GET'
            },

            columns: [

                {
                    data: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },

                {
                    data: 'nama_posyandu'
                },

                {
                    data: 'desa'
                },

                {
                    data: 'kecamatan'
                },

                {
                    data: 'kabupaten'
                },

                {
                    data: 'provinsi'
                },

                {
                    data: 'rw'
                },

                {
                    data: 'aksi',
                    searchable: false,
                    orderable: false
                }

            ]

        });


    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    $('#btnTambah').on(
        'click',
        function () {

            $('#formData')[0].reset();

            $('#id').val('');

            tagifyRW.removeAllTags();

            $('#kodePosyandu')
                .val(null)
                .trigger('change');


            const offcanvas =
                bootstrap.Offcanvas.getOrCreateInstance(
                    document.getElementById(
                        'offcanvasForm'
                    )
                );

            offcanvas.show();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#formData').on(
        'submit',
        function (e) {

            e.preventDefault();


            const id =
                $('#id').val();


            const formData =
                new FormData(this);


            const rw =
                tagifyRW.value
                    .map(item => item.value)
                    .join(',');


            formData.set(
                'rw',
                rw
            );


            let url =
                config.storeUrl;


            if (id) {

                url =
                    config.updateUrl
                        .replace(
                            '__ID__',
                            id
                        );

                formData.append(
                    '_method',
                    'PUT'
                );
            }


            $.ajax({

                url: url,

                type: 'POST',

                data: formData,

                processData: false,

                contentType: false,

                success: function (response) {

                    bootstrap.Offcanvas
                        .getInstance(
                            document.getElementById(
                                'offcanvasForm'
                            )
                        )
                        .hide();


                    table.ajax.reload(
                        null,
                        false
                    );


                    Swal.fire({

                        icon: 'success',

                        title: 'Berhasil',

                        text:
                            response.message ||
                            'Data berhasil disimpan.',

                        timer: 1500,

                        showConfirmButton: false

                    });

                },


                error: function (xhr) {

                    let message =
                        'Terjadi kesalahan.';


                    if (
                        xhr.responseJSON?.errors
                    ) {

                        message =
                            Object.values(
                                xhr.responseJSON.errors
                            )
                            .flat()
                            .join('<br>');

                    } else if (
                        xhr.responseJSON?.message
                    ) {

                        message =
                            xhr.responseJSON.message;
                    }


                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal',

                        html: message

                    });
                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btnEdit',
        function () {

            const button =
                $(this);


            $.get(
                button.data('url')
            )

            .done(function (response) {

                $('#id')
                    .val(response.id);


                /*
                |--------------------------------------------------------------------------
                | SELECT POSYANDU
                |--------------------------------------------------------------------------
                */

                const option =
                    new Option(

                        response.namaPosyandu,

                        response.kodePosyandu,

                        true,

                        true

                    );


                $('#kodePosyandu')
                    .empty()
                    .append(option)
                    .trigger('change');


                /*
                |--------------------------------------------------------------------------
                | RW
                |--------------------------------------------------------------------------
                */

                tagifyRW.removeAllTags();


                if (response.rw) {

                    tagifyRW.addTags(
                        response.rw.split(',')
                    );
                }


                const offcanvas =
                    bootstrap.Offcanvas.getOrCreateInstance(
                        document.getElementById(
                            'offcanvasForm'
                        )
                    );


                offcanvas.show();

            })

            .fail(function (xhr) {

                Swal.fire({

                    icon: 'error',

                    title: 'Gagal',

                    text:
                        xhr.responseJSON?.message ||
                        'Data tidak dapat dimuat.'

                });

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btnDelete',
        function () {

            const button =
                $(this);


            Swal.fire({

                title: 'Hapus data?',

                text:
                    'Wilayah kerja Posyandu akan dihapus.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText:
                    'Ya, hapus',

                cancelButtonText:
                    'Batal'

            })

            .then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                $.ajax({

                    url:
                        button.data('url'),

                    type: 'POST',

                    data: {

                        _method: 'DELETE',

                        _token:
                            $('meta[name="csrf-token"]')
                                .attr('content')

                    },


                    success: function (response) {

                        table.ajax.reload(
                            null,
                            false
                        );


                        Swal.fire({

                            icon: 'success',

                            title: 'Berhasil',

                            text:
                                response.message ||
                                'Data berhasil dihapus.',

                            timer: 1500,

                            showConfirmButton: false

                        });

                    },


                    error: function (xhr) {

                        Swal.fire({

                            icon: 'error',

                            title: 'Gagal',

                            text:
                                xhr.responseJSON?.message ||
                                'Data tidak dapat dihapus.'

                        });

                    }

                });

            });

        }
    );

});
