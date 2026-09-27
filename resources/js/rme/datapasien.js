$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Sidebar Active
    |--------------------------------------------------------------------------
    */

    $('#home').removeClass('active');
    $('#anak').removeClass('active');
    $('#rme').addClass('active');


    /*
    |--------------------------------------------------------------------------
    | DataTables
    |--------------------------------------------------------------------------
    */

    if ($('#riwayatKunjungan').length) {

        new DataTable('#riwayatKunjungan', {

            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],

            ordering: true,

            searching: true,

            paging: true,

            info: true,

            language: {

                search: 'Cari:',

                lengthMenu: 'Tampilkan _MENU_ data',

                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',

                zeroRecords: 'Data tidak ditemukan',

                emptyTable: 'Belum ada data kunjungan',

                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: '›',
                    previous: '‹'
                }

            }

        });

    }

        // =========================================================
    // TAB RIWAYAT / ANC
    // =========================================================

    $('.rme-tab').on('click', function () {

        const target = $(this).data('target');

        $('.rme-tab').removeClass('active');
        $(this).addClass('active');

        $('.rme-tab-content').hide();
        $('#' + target).show();

        // DataTables perlu menyesuaikan ukuran ketika
        // tabel ditampilkan kembali dari tab tersembunyi.
        if (target === 'history' && $.fn.dataTable) {
            $('#riwayatKunjungan')
                .DataTable()
                .columns.adjust();
        }
    });


    /*
    |--------------------------------------------------------------------------
    | Detail Encounter
    |--------------------------------------------------------------------------
    */

   $(document).on('click', '.btn-detail', function () {

    const patientId = $(this).data('patient-id');
    const encounterId = $(this).data('encounter-id');

    const params = new URLSearchParams();

    params.set('patient_id', patientId);
    params.set('idencounter', encounterId);

    window.location.href = '/datarme/detail?' + params.toString();
});


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    $('#btnlogout').on('click', function () {

        Swal.fire({

            title: 'Logout?',

            text: 'Apakah Anda yakin ingin keluar?',

            icon: 'question',

            showCancelButton: true,

            confirmButtonText: 'Ya, Logout',

            cancelButtonText: 'Batal',

            reverseButtons: true

        }).then(function (result) {

            if (!result.isConfirmed) {
                return;
            }

            $.ajax({

                url: '/logout',

                type: 'POST',

                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                beforeSend: function () {
                    NProgress.start();
                },

                success: function () {

                    window.location.href = '/login';

                },

                error: function () {

                    NProgress.done();

                    Swal.fire({

                        icon: 'error',

                        title: 'Logout gagal',

                        text: 'Terjadi kesalahan saat logout.'

                    });

                },

                complete: function () {
                    NProgress.done();
                }

            });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | NProgress
    |--------------------------------------------------------------------------
    */

    $(document).ajaxStart(function () {
        NProgress.start();
    });


    $(document).ajaxStop(function () {
        NProgress.done();
    });


    $(window).on('load', function () {
        NProgress.done();
    });

});
