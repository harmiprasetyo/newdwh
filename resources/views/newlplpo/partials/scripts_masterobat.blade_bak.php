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

    btn.prop('disabled', true).text('Menyimpan...');

    $.ajax({

        url: "{{ route('newlplpo.item.store') }}",

        type: "POST",

        data: $('#frmItem').serialize(),

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

       success: function (res) {

    // Tutup offcanvas dulu
    const el = document.getElementById('offcanvasObat');

    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);

    offcanvas.hide();

    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: res.message,
        timer: 1200,
        showConfirmButton: false
    });

    // Tunggu animasi offcanvas selesai
    el.addEventListener('hidden.bs.offcanvas', function () {

        location.reload();

    }, { once: true });

},

        error: function (xhr) {

            Swal.fire(
                'Error',
                xhr.responseJSON?.message ?? 'Gagal menyimpan',
                'error'
            );
        bootstrap.Offcanvas
        .getInstance(document.getElementById('offcanvasObat'))
        .hide();

        },

        complete: function(){

            btn.prop('disabled', false)
               .text('💾 Simpan Item');

        }

    });

});

});

</script>

@endpush
