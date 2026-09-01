(function ($) {

    'use strict';


    const config =
        window.LplpoConfig || {};


    let editId = null;


    /*
    |--------------------------------------------------------------------------
    | READY
    |--------------------------------------------------------------------------
    */

    $(function () {

        console.log(
            'ITEM.JS BERHASIL DIMUAT'
        );


        initTambahItem();

        initSaveItem();

        initEditItem();

        initDeleteItem();

        initSearchItem();

        initOffcanvasReset();

    });


    /*
    |--------------------------------------------------------------------------
    | TAMBAH ITEM
    |--------------------------------------------------------------------------
    */

    function initTambahItem() {

        $(document).on(
            'click',
            '#btnTambah',
            function () {

                resetItemForm();

                editId = null;

                const form =
                    document.getElementById(
                        'frmItem'
                    );

                if (form) {

                    delete form.dataset.editId;

                }


                setSaveButtonCreate();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SAVE ITEM
    |--------------------------------------------------------------------------
    */

    function initSaveItem() {

        $(document).on(
            'click',
            '#btnSaveItem',
            function (e) {

                e.preventDefault();

                const button =
                    $(this);


                /*
                |--------------------------------------------------------------------------
                | CEK MODE
                |--------------------------------------------------------------------------
                */

                const form =
                    document.getElementById(
                        'frmItem'
                    );


                if (form?.dataset.editId) {

                    editId =
                        form.dataset.editId;

                }


                console.log(
                    'ITEM SAVE MODE:',
                    editId
                        ? 'UPDATE'
                        : 'CREATE'
                );


                if (editId) {

                    updateItem(
                        button
                    );

                } else {

                    createItem(
                        button
                    );

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    function createItem(button) {

        const form =
            document.getElementById(
                'frmItem'
            );


        if (!form) {

            showError(
                'Form item tidak ditemukan.'
            );

            return;

        }


        const data =
            $(form).serialize();


        console.log(
            'CREATE ITEM:',
            data
        );


        $.ajax({

            url:
                config.routes.itemStore,

            type:
                'POST',

            data:
                data,

            beforeSend: function () {

                setButtonLoading(
                    button,
                    'Menyimpan...'
                );

            },

            success: function (response) {

                console.log(
                    'CREATE RESPONSE:',
                    response
                );


                if (
                    response.success === false
                ) {

                    showError(
                        response.message ||
                        'Gagal menambahkan item.'
                    );

                    return;

                }


                showSuccess(
                    response.message ||
                    'Item berhasil ditambahkan.'
                );


                closeOffcanvas();


                setTimeout(
                    function () {

                        window.location.reload();

                    },
                    800
                );

            },

            error: function (xhr) {

                console.error(
                    'CREATE ERROR:',
                    xhr.responseText
                );


                resetButton(
                    button,
                    'Simpan Item'
                );


                showAjaxError(
                    xhr,
                    'Gagal menambahkan item.'
                );

            },

            complete: function () {

                resetButton(
                    button,
                    'Simpan Item'
                );

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | EDIT ITEM
    |--------------------------------------------------------------------------
    */

    function initEditItem() {

        $(document).on(
            'click',
            '.btnEditItem',
            function (e) {

                e.preventDefault();

                const button =
                    $(this);

                const id =
                    button.data('id');


                if (!id) {

                    showError(
                        'ID item tidak ditemukan.'
                    );

                    return;

                }


                loadItemForEdit(
                    id
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD EDIT
    |--------------------------------------------------------------------------
    */

    function loadItemForEdit(id) {

        console.log(
            'EDIT ITEM ID:',
            id
        );


        const url =
            `${config.routes.itemEditBase}/${id}/edit`;


        $.ajax({

            url:
                url,

            type:
                'GET',

            headers: {

                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'

            },

            beforeSend: function () {

                console.log(
                    'Mengambil data item...'
                );

            },

            success: function (response) {

                console.log(
                    'EDIT RESPONSE:',
                    response
                );


                if (!response.success) {

                    showError(
                        response.message ||
                        'Data item tidak ditemukan.'
                    );

                    return;

                }


                const item =
                    response.data;


                /*
                |--------------------------------------------------------------------------
                | SET EDIT STATE
                |--------------------------------------------------------------------------
                */

                editId =
                    item.id;


                const form =
                    document.getElementById(
                        'frmItem'
                    );


                if (form) {

                    form.dataset.editId =
                        item.id;

                }


                /*
                |--------------------------------------------------------------------------
                | ISI FORM
                |--------------------------------------------------------------------------
                */

                setValue(
                    'program_id',
                    item.program_id
                );

                setValue(
                    'kode_obat',
                    item.kode_obat
                );

                setValue(
                    'nama_obat',
                    item.nama_obat
                );

                setValue(
                    'satuan',
                    item.satuan
                );

                setValue(
                    'stok_awal_progam_pkd',
                    item.stok_awal_progam_pkd
                );

                setValue(
                    'stok_awal_jkn',
                    item.stok_awal_jkn
                );

                setValue(
                    'penerimaan_program_pkd',
                    item.penerimaan_program_pkd
                );

                setValue(
                    'penerimaan_jkn',
                    item.penerimaan_jkn
                );

                setValue(
                    'persediaan_program_pkd',
                    item.persediaan_program_pkd
                );

                setValue(
                    'persediaan_jkn',
                    item.persediaan_jkn
                );

                setValue(
                    'pemakaian_program_pkd',
                    item.pemakaian_program_pkd
                );

                setValue(
                    'pemakaian_jkn',
                    item.pemakaian_jkn
                );

                setValue(
                    'item_expired_pkd',
                    item.item_expired_pkd
                );

                setValue(
                    'item_expired_jkn',
                    item.item_expired_jkn
                );

                setValue(
                    'stok_akhir_program_pkd',
                    item.stok_akhir_program_pkd
                );

                setValue(
                    'stok_akhir_jkn',
                    item.stok_akhir_jkn
                );

                setValue(
                    'stok_minimum',
                    item.stok_minimum
                );

                setValue(
                    'stok_optimum',
                    item.stok_optimum
                );

                setValue(
                    'permintaan',
                    item.permintaan
                );

                setValue(
                    'pemberian_program_pkd',
                    item.pemberian_program_pkd
                );

                setValue(
                    'pemberian_jkn',
                    item.pemberian_jkn
                );


                /*
                |--------------------------------------------------------------------------
                | PROGRAM SELECT
                |--------------------------------------------------------------------------
                */

                const program =
                    $('#program_id');


                if (program.length) {

                    program
                        .val(
                            item.program_id ?? ''
                        )
                        .trigger(
                            'change'
                        );

                }


                /*
                |--------------------------------------------------------------------------
                | BUTTON
                |--------------------------------------------------------------------------
                */

                setSaveButtonEdit();


                /*
                |--------------------------------------------------------------------------
                | SHOW OFFCANVAS
                |--------------------------------------------------------------------------
                */

                openOffcanvas();

            },

            error: function (xhr) {

                console.error(
                    'EDIT ITEM ERROR:',
                    xhr.responseText
                );


                showAjaxError(
                    xhr,
                    'Gagal mengambil data item.'
                );

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    function updateItem(button) {

        if (!editId) {

            showError(
                'ID item untuk update tidak ditemukan.'
            );

            return;

        }


        const form =
            document.getElementById(
                'frmItem'
            );


        if (!form) {

            showError(
                'Form item tidak ditemukan.'
            );

            return;

        }


        const data =
            $(form).serialize();


        console.log(
            'UPDATE ITEM ID:',
            editId
        );

        console.log(
            'UPDATE DATA:',
            data
        );


        const url =
            `${config.routes.itemUpdateBase}/${editId}`;


        $.ajax({

            url:
                url,

            type:
                'PUT',

            data:
                data,

            headers: {

                'X-CSRF-TOKEN':
                    config.csrfToken,

                'X-Requested-With':
                    'XMLHttpRequest'

            },

            beforeSend: function () {

                setButtonLoading(
                    button,
                    'Memperbarui...'
                );

            },

            success: function (response) {

                console.log(
                    'UPDATE RESPONSE:',
                    response
                );


                if (
                    response.success === false
                ) {

                    showError(
                        response.message ||
                        'Gagal memperbarui item.'
                    );

                    return;

                }


                showSuccess(
                    response.message ||
                    'Item berhasil diperbarui.'
                );


                closeOffcanvas();


                /*
                |--------------------------------------------------------------------------
                | RESET STATE
                |--------------------------------------------------------------------------
                */

                editId =
                    null;


                if (form) {

                    delete form.dataset.editId;

                }


                setTimeout(
                    function () {

                        window.location.reload();

                    },
                    800
                );

            },

            error: function (xhr) {

                console.error(
                    'UPDATE ERROR:',
                    xhr.responseText
                );


                showAjaxError(
                    xhr,
                    'Gagal memperbarui item.'
                );

            },

            complete: function () {

                resetButton(
                    button,
                    'Update Item'
                );

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    function initDeleteItem() {

        $(document).on(
            'click',
            '.btnDeleteItem, .btn-delete',
            function (e) {

                e.preventDefault();

                const button =
                    $(this);

                const id =
                    button.data('id');


                if (!id) {

                    showError(
                        'ID item tidak ditemukan.'
                    );

                    return;

                }


                Swal.fire({

                    title:
                        'Hapus Item?',

                    text:
                        'Data item obat akan dihapus.',

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


                    deleteItem(
                        id,
                        button
                    );

                });

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DELETE REQUEST
    |--------------------------------------------------------------------------
    */

    function deleteItem(
        id,
        button
    ) {

        const url =
            `${config.routes.itemDeleteBase}/${id}`;


        $.ajax({

            url:
                url,

            type:
                'DELETE',

            headers: {

                'X-CSRF-TOKEN':
                    config.csrfToken,

                'X-Requested-With':
                    'XMLHttpRequest'

            },

            beforeSend: function () {

                button
                    .prop(
                        'disabled',
                        true
                    );

            },

            success: function (response) {

                console.log(
                    'DELETE RESPONSE:',
                    response
                );


                if (
                    response.success === false
                ) {

                    showError(
                        response.message ||
                        'Gagal menghapus item.'
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | REMOVE ROW LANGSUNG
                |--------------------------------------------------------------------------
                */

                const row =
                    button.closest('tr');


                if (row.length) {

                    row.remove();

                }


                updateItemCount();


                Swal.fire({

                    icon:
                        'success',

                    title:
                        'Berhasil',

                    text:
                        response.message ||
                        'Item berhasil dihapus.',

                    timer:
                        1200,

                    showConfirmButton:
                        false

                });

            },

            error: function (xhr) {

                console.error(
                    'DELETE ERROR:',
                    xhr.responseText
                );


                showAjaxError(
                    xhr,
                    'Gagal menghapus item.'
                );

            },

            complete: function () {

                button
                    .prop(
                        'disabled',
                        false
                    );

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH ITEM
    |--------------------------------------------------------------------------
    */

    function initSearchItem() {

        $(document).on(
            'input',
            '#searchItem',
            function () {

                const keyword =
                    $(this)
                        .val()
                        .toLowerCase()
                        .trim();


                $('#tblItem tbody tr')
                    .each(function () {

                        const text =
                            $(this)
                                .text()
                                .toLowerCase();


                        $(this).toggle(
                            text.indexOf(
                                keyword
                            ) !== -1
                        );

                    });

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | RESET FORM
    |--------------------------------------------------------------------------
    */

    function resetItemForm() {

        const form =
            document.getElementById(
                'frmItem'
            );


        if (!form) {
            return;
        }


        form.reset();


        editId =
            null;


        delete form.dataset.editId;


        /*
        |--------------------------------------------------------------------------
        | RESET SELECT2
        |--------------------------------------------------------------------------
        */

        $('#program_id')
            .val(null)
            .trigger('change');


        /*
        |--------------------------------------------------------------------------
        | CLEAR HIDDEN VALUE
        |--------------------------------------------------------------------------
        */

        $('#kode_obat')
            .val('');

        $('#nama_obat')
            .val('');

        $('#satuan')
            .val('');

    }


    /*
    |--------------------------------------------------------------------------
    | OFFCANVAS RESET
    |--------------------------------------------------------------------------
    */

    function initOffcanvasReset() {

        const element =
            document.getElementById(
                'offcanvasObat'
            );


        if (!element) {
            return;
        }


        element.addEventListener(
            'hidden.bs.offcanvas',
            function () {

                resetItemForm();

                setSaveButtonCreate();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SET BUTTON CREATE
    |--------------------------------------------------------------------------
    */

    function setSaveButtonCreate() {

        const button =
            $('#btnSaveItem');


        if (!button.length) {
            return;
        }


        button
            .prop(
                'disabled',
                false
            )
            .removeAttr(
                'data-mode'
            )
            .html(
                '<i class="bi bi-save me-1"></i>' +
                'Simpan Item'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | SET BUTTON EDIT
    |--------------------------------------------------------------------------
    */

    function setSaveButtonEdit() {

        const button =
            $('#btnSaveItem');


        if (!button.length) {
            return;
        }


        button
            .prop(
                'disabled',
                false
            )
            .attr(
                'data-mode',
                'edit'
            )
            .html(
                '<i class="bi bi-save me-1"></i>' +
                'Update Item'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | SET BUTTON LOADING
    |--------------------------------------------------------------------------
    */

    function setButtonLoading(
        button,
        text
    ) {

        button
            .prop(
                'disabled',
                true
            )
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span>' +
                text
            );

    }


    /*
    |--------------------------------------------------------------------------
    | RESET BUTTON
    |--------------------------------------------------------------------------
    */

    function resetButton(
        button,
        text
    ) {

        button
            .prop(
                'disabled',
                false
            )
            .html(
                '<i class="bi bi-save me-1"></i>' +
                text
            );

    }


    /*
    |--------------------------------------------------------------------------
    | SET VALUE
    |--------------------------------------------------------------------------
    */

    function setValue(
        id,
        value
    ) {

        const element =
            document.getElementById(
                id
            );


        if (!element) {

            console.warn(
                `Element #${id} tidak ditemukan`
            );

            return;

        }


        element.value =
            value ?? '';

    }


    /*
    |--------------------------------------------------------------------------
    | OPEN OFFCANVAS
    |--------------------------------------------------------------------------
    */

    function openOffcanvas() {

        const element =
            document.getElementById(
                'offcanvasObat'
            );


        if (!element) {

            console.error(
                '#offcanvasObat tidak ditemukan.'
            );

            return;

        }


        const offcanvas =
            bootstrap.Offcanvas
                .getOrCreateInstance(
                    element
                );


        offcanvas.show();

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE OFFCANVAS
    |--------------------------------------------------------------------------
    */

    function closeOffcanvas() {

        const element =
            document.getElementById(
                'offcanvasObat'
            );


        if (!element) {
            return;
        }


        const offcanvas =
            bootstrap.Offcanvas
                .getInstance(
                    element
                );


        if (offcanvas) {

            offcanvas.hide();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE ITEM COUNT
    |--------------------------------------------------------------------------
    */

    function updateItemCount() {

        const count =
            $('#tblItem tbody tr')
                .length;


        $('#itemCount')
            .text(
                `${count} Item`
            );

    }


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    function showSuccess(
        message
    ) {

        Swal.fire({

            icon:
                'success',

            title:
                'Berhasil',

            text:
                message,

            timer:
                1200,

            showConfirmButton:
                false

        });

    }


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    function showError(
        message
    ) {

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
    | AJAX ERROR
    |--------------------------------------------------------------------------
    */

    function showAjaxError(
        xhr,
        fallback
    ) {

        let message =
            fallback;


        if (
            xhr.responseJSON?.message
        ) {

            message =
                xhr.responseJSON.message;

        }


        if (
            xhr.status === 422 &&
            xhr.responseJSON?.errors
        ) {

            message =
                Object
                    .values(
                        xhr.responseJSON.errors
                    )
                    .flat()
                    .join('<br>');

        }


        Swal.fire({

            icon:
                'error',

            title:
                'Gagal',

            html:
                message

        });

    }

})(jQuery);
