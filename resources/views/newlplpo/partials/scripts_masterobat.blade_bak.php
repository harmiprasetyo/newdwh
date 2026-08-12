@push('script')

<script>

$(function () {

    let tableObat = $('#tblMasterObat').DataTable({

        processing: true,
        serverSide: true,

        ajax: "{{ route('newlplpo.masterobat.datatable') }}",

        columns: [
            { data: 'kode_obat', name: 'kode_obat' },
            { data: 'nama_obat', name: 'nama_obat' },
            { data: 'satuan', name: 'satuan' },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false
            }
        ]

    });

    // pencarian manual
    $('#keywordObat').keyup(function () {

        tableObat.search($(this).val()).draw();

    });

    // pilih obat
    $(document).on('click', '.pilih-obat', function () {

        $('#kode_obat').val($(this).data('kode'));
        $('#nama_obat').val($(this).data('nama'));
        $('#satuan').val($(this).data('satuan'));

        $('#stok_minimum').val($(this).data('min'));
        $('#stok_optimum').val($(this).data('opt'));



    });


    $(document).on('click', '#btnSaveItem', function () {

    let btn = $(this);

    let form = $('#frmItem');

    let editId = form.data('edit-id');

    let isEdit = editId !== undefined &&
                 editId !== null &&
                 editId !== '';

    btn.prop('disabled', true);

    btn.html(
        '<span class="spinner-border spinner-border-sm"></span> Menyimpan...'
    );


    let url;

    let method;


    if (isEdit) {

        url =
            "{{ url('newlplpo/item') }}/" +
            editId;

        method = "PUT";

    } else {

        url =
            "{{ route('newlplpo.item.store') }}";

        method = "POST";

    }


    $.ajax({

        url: url,

        type: method,

        data: form.serialize(),

        headers: {

            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]').attr('content')

        },

        success: function (res) {

            const el =
                document.getElementById('offcanvasObat');

            const offcanvas =
                bootstrap.Offcanvas.getOrCreateInstance(el);

            offcanvas.hide();


            Swal.fire({

                icon: 'success',

                title: 'Berhasil',

                text:
                    res.message ??
                    (isEdit
                        ? 'Item berhasil diupdate'
                        : 'Item berhasil disimpan'),

                timer: 1200,

                showConfirmButton: false

            });


            el.addEventListener(
                'hidden.bs.offcanvas',
                function () {

                    /*
                    |--------------------------------------------------------------------------
                    | RESET MODE
                    |--------------------------------------------------------------------------
                    */

                    form.removeData('edit-id');

                    btn
                        .html('💾 Simpan Item')
                        .data('mode', 'create');


                    /*
                    |--------------------------------------------------------------------------
                    | REFRESH
                    |--------------------------------------------------------------------------
                    */

                    location.reload();

                },
                {
                    once: true
                }
            );

        },

        error: function (xhr) {

            console.error(xhr);

            let message =
                xhr.responseJSON?.message ??
                'Gagal menyimpan data.';


            if (xhr.responseJSON?.errors) {

                message =
                    Object.values(
                        xhr.responseJSON.errors
                    )
                    .flat()
                    .join('<br>');

            }


            Swal.fire({

                icon: 'error',

                title: 'Gagal',

                html: message

            });

        },

        complete: function () {

            btn.prop('disabled', false);

            btn.html(
                isEdit
                    ? '<i class="bi bi-save"></i> Update Item'
                    : '💾 Simpan Item'
            );

        }

    });

});


/**
 * =========================================================
 * EDIT ITEM
 * =========================================================
 */

$(document).on('click', '.btnEditItem', function (e) {

    e.preventDefault();

    console.log('================================');
    console.log('BUTTON EDIT DIKLIK');
    console.log('ID:', $(this).data('id'));
    console.log('================================');

});


});

</script>

@endpush
