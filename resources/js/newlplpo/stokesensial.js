$(function () {

    'use strict';

    // =========================================================
    // CONFIG
    // =========================================================

    const config = window.StokEsensialConfig || {};

    const currentUser = config.currentUser || {};

    const groupId =
        parseInt(currentUser.groupid || 0, 10);

    const userKodeFaskes =
        currentUser.kodeFaskes || null;

    const isAdmin =
        [1, 2].includes(groupId);

    const dataUrl =
        config.dataUrl || '';

    const storeUrl =
        config.storeUrl || '';

    const kategoriUrl =
        config.kategoriUrl || '';

   const obatUrl = config.obatUrl || '';


    // =========================================================
    // STATE
    // =========================================================

    let table = null;

    let currentEditId = 0;
    


    // =========================================================
    // ELEMENT
    // =========================================================

    const modalData =
        $('#modalData');

    const formData =
        $('#formData');

    const kategoriWrapper =
        $('#kategoriWrapper');

    const kategori =
        $('#kategori');

    const obatEsensial =
        $('#obat_esensial');

    const tahun =
        $('#tahun');

    // IMPORTANT:
    // Ini adalah element jQuery, jadi gunakan $kodeFaskes
    // agar tidak bentrok dengan userKodeFaskes.

    const $kodeFaskes =
        $('#kodeFaskes');



// =========================================================
// SELECT2 OBAT
// =========================================================

const kodeObat = $('#kode_obat');

kodeObat.select2({

    theme: 'bootstrap-5',

    dropdownParent: modalData,

    width: '100%',

    placeholder: 'Pilih Obat',

    allowClear: true,

    ajax: {

        url: obatUrl,

        type: 'GET',

        dataType: 'json',

        delay: 250,

        data: function (params) {

            return {

                search: params.term || '',

                tahun: tahun.val(),

                kodeFaskes:
                    $kodeFaskes.val(),

                exclude_stok_setting: 1,

                edit_id:
                    currentEditId || ''

            };

        },

        processResults: function (response) {

            return {

                results: (response.data || [])
                    .map(function (item) {

                        return {

                            id:
                                item.kode_obat,

                            text:
                                item.kode_obat +
                                ' — ' +
                                item.nama_obat

                        };

                    })

            };

        },

        cache: true

    }

});





    // =========================================================
    // SELECT2 KATEGORI
    // =========================================================

    kategori.select2({

        theme: 'bootstrap-5',

        dropdownParent: modalData,

        width: '100%',

        placeholder: 'Pilih Kategori',

        allowClear: true

    });


    // =========================================================
    // RESET KATEGORI
    // =========================================================

    function resetKategori() {

        kategori
            .empty()
            .append(
                '<option value="">Pilih Kategori</option>'
            )
            .val(null)
            .trigger('change');

    }


    // =========================================================
    // LOAD KATEGORI
    // =========================================================

    function loadKategori(selectedValue = null) {

        const tahunValue =
            tahun.val();

        const faskesValue =
            $kodeFaskes.val();


        if (
            !tahunValue ||
            !faskesValue
        ) {

            resetKategori();

            return;

        }


        if (!kategoriUrl) {

            console.error(
                'StokEsensialConfig.kategoriUrl belum tersedia.'
            );

            resetKategori();

            return;

        }


        kategori
            .prop('disabled', true);


        $.ajax({

            url: kategoriUrl,

            type: 'GET',

            data: {

                tahun:
                    tahunValue,

                kodeFaskes:
                    faskesValue,

                edit_id:
                    currentEditId || 0

            },

            success: function (response) {

                resetKategori();


                if (
                    response &&
                    response.success &&
                    Array.isArray(response.data)
                ) {

                    response.data.forEach(
                        function (item) {

                            const itemKategori =
                                String(
                                    item.kategori || ''
                                ).trim();


                            if (!itemKategori) {
                                return;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | KATEGORI SUDAH DIGUNAKAN
                            |--------------------------------------------------------------------------
                            |
                            | Jangan tampilkan kategori yang sudah
                            | digunakan oleh obat lain.
                            |
                            | Tetapi kategori milik data yang sedang
                            | diedit tetap boleh ditampilkan.
                            |
                            */

                            if (
                                item.used === true &&
                                itemKategori !==
                                    String(
                                        selectedValue || ''
                                    ).trim()
                            ) {

                                return;

                            }


                            const option =
                                new Option(

                                    itemKategori,

                                    itemKategori,

                                    false,

                                    itemKategori ===
                                        String(
                                            selectedValue || ''
                                        ).trim()

                                );


                            kategori.append(option);

                        }
                    );

                }


                kategori
                    .val(
                        selectedValue || null
                    )
                    .trigger('change');

            },

            error: function (xhr) {

                console.error(
                    'Gagal memuat kategori:',
                    xhr
                );


                kategori
                    .empty()
                    .append(
                        '<option value="">Gagal memuat kategori</option>'
                    )
                    .val(null)
                    .trigger('change');

            },

            complete: function () {

                kategori
                    .prop('disabled', false);

            }

        });

    }


    // =========================================================
    // SHOW / HIDE KATEGORI
    // =========================================================

    function toggleKategori(
        selectedValue = null
    ) {

        const value =
            obatEsensial.val();


        if (value === 'oe') {

            kategoriWrapper
                .slideDown(150);

            kategori
                .prop('required', true);

            loadKategori(
                selectedValue
            );

        } else {

            kategoriWrapper
                .hide();

            kategori
                .prop('required', false);

            resetKategori();

        }

    }


    // =========================================================
    // OBAT ESENSIAL CHANGE
    // =========================================================

    obatEsensial.on(
        'change',
        function () {

            toggleKategori();

        }
    );


    // =========================================================
    // TAHUN CHANGE
    // =========================================================

    tahun.on(
        'change',
        function () {

            /*
            |--------------------------------------------------------------------------
            | Ketika tahun berubah, kategori lama jangan otomatis
            | dipertahankan karena kategori memiliki scope:
            |
            | kodeFaskes + tahun
            |--------------------------------------------------------------------------
            */

            if (
                obatEsensial.val() === 'oe'
            ) {

                loadKategori(null);

            }

        }
    );


    // =========================================================
    // FASKES CHANGE
    // =========================================================

    $kodeFaskes.on(
        'change',
        function () {

            /*
            |--------------------------------------------------------------------------
            | Ketika faskes berubah, kategori lama juga harus
            | dikosongkan karena kategori memiliki scope faskes.
            |--------------------------------------------------------------------------
            */

            if (
                obatEsensial.val() === 'oe'
            ) {

                loadKategori(null);

            }

        }
    );


    // =========================================================
    // DATATABLE
    // =========================================================

    table =
        $('#datatable').DataTable({

            processing: true,

            serverSide: true,

            ajax: {

                url: dataUrl,

                data: function (d) {

                    d.tahun =
                        $('#filterTahun').val();

                    d.kodeFaskes =
                        $('#filterFaskes').val();

                }

            },

            columns: [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'obat',
                    name: 'o.nama_obat'
                },

                {
                    data: 'faskes',
                    name: 'f.namaFaskes'
                },

                {
                    data: 'stok_minimal',
                    name: 's.stok_minimal'
                },

                {
                    data: 'stok_optimum',
                    name: 's.stok_optimum'
                },

                {
                    data: 'obat_esensial',
                    name: 's.obat_esensial'
                },

                {
                    data: 'kategori',
                    name: 's.kategori',
                    defaultContent: '-'
                },

                {
                    data:
                        'obat_formularium_puskesmas',
                    name:
                        's.obat_formularium_puskesmas'
                },

                {
                    data: 'tahun',
                    name: 's.tahun'
                },

                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                }

            ]

        });


    // =========================================================
    // FILTER
    // =========================================================

    $('#filterTahun, #filterFaskes')
        .on(
            'change',
            function () {

                table.ajax.reload();

            }
        );


    // =========================================================
    // ADD
    // =========================================================

    $('#btnTambah').on(
        'click',
        function () {

            currentEditId = 0;


            formData[0].reset();


            $('#data_id')
                .val('');


            $('#modalDataLabel')
                .text(
                    'Tambah Stok & Esensial'
                );


            resetKategori();


            kategoriWrapper
                .hide();


            kategori
                .prop('required', false);


            /*
            |--------------------------------------------------------------------------
            | Untuk user non-admin, faskes mengikuti
            | faskes user login.
            |--------------------------------------------------------------------------
            */

            if (!isAdmin && userKodeFaskes) {

                $kodeFaskes
                    .val(userKodeFaskes)
                    .trigger('change.select2');

            }


            modalData.modal('show');

        }
    );


    // =========================================================
    // EDIT
    // =========================================================

    $(document).on(
        'click',
        '.btn-edit',
        function () {

            const id =
                $(this).data('id');


            currentEditId = id;


            $.ajax({

                url:
                    '/newlplpo/stok-esensial/' +
                    id,

                type: 'GET',

                success: function (response) {

                    const data =
                        response.data;


                    $('#data_id')
                        .val(data.id);


                    /*
                    |--------------------------------------------------------------------------
                    | OBAT
                    |--------------------------------------------------------------------------
                    */

                    $('#kode_obat')
                        .empty()
                        .append(
                            new Option(

                                data.obat?.nama_obat ||
                                    data.kode_obat,

                                data.kode_obat,

                                true,

                                true

                            )
                        )
                        .trigger('change');


                    /*
                    |--------------------------------------------------------------------------
                    | FASKES
                    |--------------------------------------------------------------------------
                    */

                    $kodeFaskes
                        .val(data.kodeFaskes)
                        .trigger('change.select2');


                    /*
                    |--------------------------------------------------------------------------
                    | STOCK
                    |--------------------------------------------------------------------------
                    */

                    $('#stok_minimal')
                        .val(data.stok_minimal);


                    $('#stok_optimum')
                        .val(data.stok_optimum);


                    /*
                    |--------------------------------------------------------------------------
                    | ESENSIAL
                    |--------------------------------------------------------------------------
                    */

                    obatEsensial
                        .val(data.obat_esensial)
                        .trigger('change.select2');


                    /*
                    |--------------------------------------------------------------------------
                    | TAHUN
                    |--------------------------------------------------------------------------
                    */

                    tahun
                        .val(data.tahun)
                        .trigger('change.select2');


                    /*
                    |--------------------------------------------------------------------------
                    | FORMULARIUM
                    |--------------------------------------------------------------------------
                    */

                    $('#obat_formularium_puskesmas')
                        .val(
                            data.obat_formularium_puskesmas
                        )
                        .trigger('change.select2');


                    /*
                    |--------------------------------------------------------------------------
                    | KATEGORI
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data.obat_esensial === 'oe'
                    ) {

                        kategoriWrapper
                            .show();

                        kategori
                            .prop('required', true);


                        loadKategori(
                            data.kategori || null
                        );

                    } else {

                        kategoriWrapper
                            .hide();

                        kategori
                            .prop('required', false);

                        resetKategori();

                    }


                    $('#modalDataLabel')
                        .text(
                            'Edit Stok & Esensial'
                        );


                    modalData.modal('show');

                },

                error: function (xhr) {

                    console.error(
                        'Gagal mengambil data:',
                        xhr
                    );


                    Swal.fire(

                        'Error',

                        'Gagal mengambil data.',

                        'error'

                    );

                }

            });

        }
    );


    // =========================================================
    // SUBMIT
    // =========================================================

    formData.on(
        'submit',
        function (e) {

            e.preventDefault();


            const id =
                $('#data_id').val();


            const isEdit =
                !!id;


            const url =
                isEdit

                    ? '/newlplpo/stok-esensial/' +
                        id

                    : storeUrl;


            const method =
                isEdit
                    ? 'PUT'
                    : 'POST';


            /*
            |--------------------------------------------------------------------------
            | KATEGORI
            |--------------------------------------------------------------------------
            */

            let kategoriValue =
                kategori.val();


            if (
                obatEsensial.val() !== 'oe'
            ) {

                kategoriValue = '';

            }


            /*
            |--------------------------------------------------------------------------
            | PAYLOAD
            |--------------------------------------------------------------------------
            */

            const payload = {

                _token:
                    $('meta[name="csrf-token"]')
                        .attr('content'),

                kode_obat:
                    $('#kode_obat').val(),

                kodeFaskes:
                    $kodeFaskes.val(),

                stok_minimal:
                    $('#stok_minimal').val(),

                stok_optimum:
                    $('#stok_optimum').val(),

                obat_esensial:
                    obatEsensial.val(),

                kategori:
                    kategoriValue,

                obat_formularium_puskesmas:
                    $('#obat_formularium_puskesmas')
                        .val(),

                tahun:
                    tahun.val()

            };


            $.ajax({

                url: url,

                type: method,

                data: payload,

                beforeSend: function () {

                    $('#btnSimpan')
                        .prop(
                            'disabled',
                            true
                        );

                },

                success: function (response) {

                    modalData.modal('hide');


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

                        timer: 1800,

                        showConfirmButton: false

                    });

                },

                error: function (xhr) {

                    let message =
                        'Terjadi kesalahan.';


                    if (
                        xhr.responseJSON?.message
                    ) {

                        message =
                            xhr.responseJSON.message;

                    }


                    if (
                        xhr.responseJSON?.errors?.kategori
                    ) {

                        message =
                            xhr.responseJSON
                                .errors
                                .kategori[0];

                    }


                    Swal.fire({

                        icon: 'error',

                        title:
                            'Tidak dapat menyimpan',

                        text: message

                    });

                },

                complete: function () {

                    $('#btnSimpan')
                        .prop(
                            'disabled',
                            false
                        );

                }

            });

        }
    );


    // =========================================================
    // DELETE
    // =========================================================

    $(document).on(
        'click',
        '.btn-delete',
        function () {

            const id =
                $(this).data('id');


            Swal.fire({

                title: 'Hapus data?',

                text:
                    'Data yang dihapus tidak dapat dikembalikan.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText:
                    'Ya, hapus',

                cancelButtonText:
                    'Batal'

            }).then(
                function (result) {

                    if (
                        !result.isConfirmed
                    ) {

                        return;

                    }


                    $.ajax({

                        url:
                            '/newlplpo/stok-esensial/' +
                            id,

                        type: 'DELETE',

                        data: {

                            _token:
                                $('meta[name="csrf-token"]')
                                    .attr('content')

                        },

                        success: function (
                            response
                        ) {

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

                                showConfirmButton:
                                    false

                            });

                        },

                        error: function (xhr) {

                            Swal.fire(

                                'Error',

                                xhr.responseJSON?.message ||
                                'Data gagal dihapus.',

                                'error'

                            );

                        }

                    });

                }
            );

        }
    );

});

