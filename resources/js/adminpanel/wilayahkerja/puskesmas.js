$(function () {

    const config =
        window.WilayahKerjaPuskesmasConfig || {};

    const groupId =
        parseInt(config.groupId || 0);

    const isGroup3 =
        groupId === 3;


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    const table =
        $('#wilayahPuskesmasTable').DataTable({

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
                    data: 'namaFaskes'
                },

                {
                    data: 'namaDesa'
                },

                {
                    data: 'kecamatan'
                },

                {
                    data: 'kota'
                },

                {
                    data: 'provinsi'
                },

                {
                    data: 'action',
                    searchable: false,
                    orderable: false
                }

            ]

        });


    /*
    |--------------------------------------------------------------------------
    | SELECT2 FASKES
    |--------------------------------------------------------------------------
    */

    $('#kodeFaskes').select2({

        dropdownParent:
            $('#wilayahPuskesmasModal'),

        width: '100%',

        placeholder:
            'Pilih Puskesmas',

        allowClear: true,

        ajax: {

            url:
                config.faskesUrl,

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

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | SELECT2 DESA
    |--------------------------------------------------------------------------
    */

    $('#kodeDesa').select2({

        dropdownParent:
            $('#wilayahPuskesmasModal'),

        width: '100%',

        placeholder:
            'Pilih Desa / Kelurahan',

        allowClear: true

    });


    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if (isGroup3) {

        $('#faskesContainer')
            .hide();

        $('#wilayahFaskesContainer')
            .hide();

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD DESA
    |--------------------------------------------------------------------------
    */

    function loadDesa(kodeFaskes, selected = null) {

        $('#kodeDesa')
            .empty()
            .append(
                new Option(
                    'Memuat desa...',
                    '',
                    true,
                    true
                )
            )
            .trigger('change');


        if (!kodeFaskes) {

            $('#kodeDesa')
                .empty()
                .append(
                    new Option(
                        'Pilih Desa / Kelurahan',
                        '',
                        true,
                        true
                    )
                )
                .trigger('change');

            return;
        }


        $.ajax({

            url:
                config.desaByFaskesUrl,

            type:
                'GET',

            data: {
                kodeFaskes:
                    kodeFaskes
            },

            success: function (data) {

                $('#kodeDesa')
                    .empty()
                    .append(
                        new Option(
                            'Pilih Desa / Kelurahan',
                            '',
                            true,
                            true
                        )
                    );


                $.each(
                    data,
                    function (_, item) {

                        const option =
                            new Option(
                                item.text,
                                item.id,
                                false,
                                String(item.id) ===
                                    String(selected)
                            );

                        $('#kodeDesa')
                            .append(option);
                    }
                );


                $('#kodeDesa')
                    .trigger('change');

            },

            error: function () {

                $('#kodeDesa')
                    .empty()
                    .append(
                        new Option(
                            'Gagal memuat desa',
                            '',
                            true,
                            true
                        )
                    )
                    .trigger('change');

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FASKES CHANGE
    |--------------------------------------------------------------------------
    */

    $('#kodeFaskes').on(
        'change',
        function () {

            const selected =
                $(this)
                    .find(':selected');


            if (!isGroup3) {

                $('#kodePropinsi')
                    .val(
                        selected.data('propinsi') || ''
                    );

            }

            loadDesa(
                $(this).val()
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    $('#btnAddWilayah').on(
        'click',
        function () {

            $('#wilayahPuskesmasForm')[0].reset();

            $('#wilayahId')
                .val('');

            $('#kodeFaskes')
                .val(null)
                .trigger('change');

            $('#kodeDesa')
                .empty()
                .append(
                    new Option(
                        'Pilih Desa / Kelurahan',
                        '',
                        true,
                        true
                    )
                )
                .trigger('change');


            if (isGroup3) {

                /*
                |--------------------------------------------------------------------------
                | Group 3:
                | kodeFaskes otomatis dari user
                |--------------------------------------------------------------------------
                */

                loadDesa(
                    config.userKodeFaskes
                );

            }


            $('#wilayahPuskesmasModalTitle')
                .text(
                    'Tambah Wilayah Kerja Puskesmas'
                );


            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    document.getElementById(
                        'wilayahPuskesmasModal'
                    )
                );


            modal.show();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-edit',
        function () {

            const id =
                $(this).data('id');


            const url =
                config.showUrl
                    .replace(
                        '__ID__',
                        id
                    );


            $.get(url)

                .done(function (response) {

                    const data =
                        response.data;


                    $('#wilayahId')
                        .val(data.id);


                    /*
                    |--------------------------------------------------------------------------
                    | GROUP 3
                    |--------------------------------------------------------------------------
                    */

                    if (isGroup3) {

                        loadDesa(
                            config.userKodeFaskes,
                            data.kodeDesa
                        );

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | GROUP 1 / 2
                        |--------------------------------------------------------------------------
                        */

                        const option =
                            new Option(

                                data.faskes?.namaFaskes ||
                                    data.kodeFaskes,

                                data.kodeFaskes,

                                true,

                                true

                            );


                        $('#kodeFaskes')
                            .empty()
                            .append(option)
                            .trigger('change');


                        loadDesa(
                            data.kodeFaskes,
                            data.kodeDesa
                        );

                    }


                    $('#wilayahPuskesmasModalTitle')
                        .text(
                            'Edit Wilayah Kerja Puskesmas'
                        );


                    bootstrap.Modal
                        .getOrCreateInstance(
                            document.getElementById(
                                'wilayahPuskesmasModal'
                            )
                        )
                        .show();

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
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#wilayahPuskesmasForm').on(
        'submit',
        function (e) {

            e.preventDefault();


            const id =
                $('#wilayahId').val();


            let url =
                config.storeUrl;

            let method =
                'POST';


            if (id) {

                url =
                    config.updateUrl
                        .replace(
                            '__ID__',
                            id
                        );

                method =
                    'PUT';
            }


            let kodeFaskes =
                $('#kodeFaskes').val();


            /*
            |--------------------------------------------------------------------------
            | GROUP 3
            |--------------------------------------------------------------------------
            */

            if (isGroup3) {

                kodeFaskes =
                    config.userKodeFaskes;
            }


            $.ajax({

                url: url,

                type: method,

                data: {

                    kodeFaskes:
                        kodeFaskes,

                    kodeDesa:
                        $('#kodeDesa').val(),

                    _token:
                        $('meta[name="csrf-token"]')
                            .attr('content')

                },

                success: function (response) {

                    bootstrap.Modal
                        .getInstance(
                            document.getElementById(
                                'wilayahPuskesmasModal'
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
                            response.message,

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
    | DELETE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-delete',
        function () {

            const id =
                $(this).data('id');


            Swal.fire({

                title:
                    'Hapus data?',

                text:
                    'Wilayah kerja Puskesmas akan dihapus.',

                icon:
                    'warning',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Ya, hapus',

                cancelButtonText:
                    'Batal'

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                $.ajax({

                    url:
                        config.deleteUrl
                            .replace(
                                '__ID__',
                                id
                            ),

                    type:
                        'DELETE',

                    data: {

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

                    },

                    error: function (xhr) {

                        Swal.fire({

                            icon:
                                'error',

                            title:
                                'Gagal',

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
