$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | DEFAULT DATE
    |--------------------------------------------------------------------------
    */

    const today = new Date();

    const firstDay = new Date(
        today.getFullYear(),
        today.getMonth(),
        1
    );


    function formatDateInput(date) {

        const year =
            date.getFullYear();

        const month =
            String(date.getMonth() + 1)
                .padStart(2, '0');

        const day =
            String(date.getDate())
                .padStart(2, '0');


        return `${year}-${month}-${day}`;
    }


    $('#start_date').val(
        formatDateInput(firstDay)
    );


    $('#end_date').val(
        formatDateInput(today)
    );



    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    const table = $('#activityLogTable').DataTable({

        processing: true,

        serverSide: true,

        searching: false,

        pageLength: 25,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],

        ajax: {

            url:
                window.ActivityLogConfig.datatableUrl,

            data: function (d) {

                d.start_date =
                    $('#start_date').val();

                d.end_date =
                    $('#end_date').val();

                d.action =
                    $('#action').val();

                d.module =
                    $('#module').val();

            }

        },


        order: [
            [1, 'desc']
        ],


        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },


            {
                data: 'waktu',
                name: 'created_at'
            },


            {
                data: 'user_label',
                name: 'user_id'
            },


            {
                data: 'action_badge',
                name: 'action',
                className: 'text-center'
            },


            {
                data: 'module_label',
                name: 'module'
            },


            {
                data: 'description_label',
                name: 'description'
            },


            {
                data: 'method_badge',
                name: 'method',
                className: 'text-center'
            },


            {
                data: 'ip_address',
                name: 'ip_address'
            },


            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }

        ],


        language: {

            processing:
                'Memproses...',

            search:
                'Cari:',

            lengthMenu:
                'Tampilkan _MENU_ data',

            info:
                'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

            infoEmpty:
                'Tidak ada data',

            zeroRecords:
                'Data tidak ditemukan',

            paginate: {

                first:
                    'Pertama',

                last:
                    'Terakhir',

                next:
                    'Next',

                previous:
                    'Previous'

            }

        }

    });



    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    $('#formFilter').on(
        'submit',
        function (e) {

            e.preventDefault();

            table.ajax.reload();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    $('#btnReset').on(
        'click',
        function () {

            $('#action').val('');

            $('#module').val('');

            $('#start_date').val(
                formatDateInput(firstDay)
            );

            $('#end_date').val(
                formatDateInput(today)
            );


            table.ajax.reload();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    $('#activityLogTable').on(
        'click',
        '.btnDetail',
        function () {

            const button = $(this);


            $('#detailAction')
                .html(
                    `<span class="badge bg-primary">
                        ${escapeHtml(
                            button.data('action')
                        )}
                    </span>`
                );


            $('#detailModule').text(
                button.data('module')
            );


            $('#detailDescription').text(
                button.data('description')
            );


            $('#detailIp').text(
                button.data('ip')
            );


            $('#detailSubject').text(

                `${button.data('subject-type')} #${button.data('subject-id')}`

            );


            $('#detailUrl').text(
                button.data('url')
            );


            $('#detailUserAgent').text(
                button.data('user-agent')
            );


            /*
            |--------------------------------------------------------------------------
            | OLD VALUES
            |--------------------------------------------------------------------------
            */

            let oldValues =
                button.attr('data-old');


            try {

                oldValues =
                    JSON.stringify(
                        JSON.parse(oldValues),
                        null,
                        4
                    );

            } catch (e) {

                // gunakan data asli

            }


            $('#detailOld').text(
                oldValues || '{}'
            );


            /*
            |--------------------------------------------------------------------------
            | NEW VALUES
            |--------------------------------------------------------------------------
            */

            let newValues =
                button.attr('data-new');


            try {

                newValues =
                    JSON.stringify(
                        JSON.parse(newValues),
                        null,
                        4
                    );

            } catch (e) {

                // gunakan data asli

            }


            $('#detailNew').text(
                newValues || '{}'
            );


            /*
            |--------------------------------------------------------------------------
            | SHOW MODAL
            |--------------------------------------------------------------------------
            */

            const modal =
                new bootstrap.Modal(
                    document.getElementById(
                        'modalActivityDetail'
                    )
                );


            modal.show();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {

            return '';

        }


        return String(value)

            .replace(
                /&/g,
                '&amp;'
            )

            .replace(
                /</g,
                '&lt;'
            )

            .replace(
                />/g,
                '&gt;'
            )

            .replace(
                /"/g,
                '&quot;'
            )

            .replace(
                /'/g,
                '&#039;'
            );

    }

});