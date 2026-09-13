$(function () {

    'use strict';

    const tableBody = $('#monitoringTableBody');
    const filterTahun = $('#filterTahun');

    const loading = $('#monitoringLoading');
    const errorBox = $('#monitoringError');

    /*
     * URL data
     */
    const dataUrl = '/newlplpo/report-monitoring/data';

    /*
     * Nama bulan
     */
    const months = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Agt',
        'Sep',
        'Okt',
        'Nov',
        'Des'
    ];

    /*
     * Mapping status -> CSS class
     */
    const statusClass = {
        SUBMITED: 'status-submited',
        VERIFIED: 'status-verified',
        REJECTED: 'status-rejected',
        FINAL: 'status-final'
    };

    /*
     * Load data
     */
    function loadMonitoring() {

        const tahun = filterTahun.val();

        tableBody.empty();

        errorBox
            .addClass('d-none')
            .text('');

        loading.removeClass('d-none');

        $.ajax({

            url: dataUrl,

            method: 'GET',

            data: {
                tahun: tahun
            },

            dataType: 'json'

        })
        .done(function (response) {

            if (!response || response.success !== true) {

                showError(
                    'Data monitoring tidak dapat diproses.'
                );

                return;
            }

            renderTable(response.data || []);

        })
        .fail(function (xhr) {

            console.error(
                'Monitoring LPLPO error:',
                xhr
            );

            let message =
                'Terjadi kesalahan saat mengambil data monitoring.';

            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {
                message = xhr.responseJSON.message;
            }

            showError(message);

        })
        .always(function () {

            loading.addClass('d-none');

        });
    }


    /*
     * Render table
     */
    function renderTable(data) {

        tableBody.empty();

        if (!data.length) {

            const row = $('<tr>');

            const cell = $('<td>')
                .attr('colspan', 13)
                .addClass('text-center text-muted py-3')
                .text('Tidak ada data report untuk tahun tersebut.');

            row.append(cell);

            tableBody.append(row);

            return;
        }

        data.forEach(function (faskes) {

            const row = $('<tr>');

            /*
             * Nama faskes
             */
            const faskesCell = $('<td>')
                .text(faskes.nama_faskes || '-');

            row.append(faskesCell);

            /*
             * Januari - Desember
             */
            for (let month = 1; month <= 12; month++) {

                const report =
                    faskes.bulan &&
                    faskes.bulan[month]
                        ? faskes.bulan[month]
                        : null;

                const cell = $('<td>')
                    .addClass('report-cell');

                /*
                 * Tidak ada report
                 */
                if (!report) {

                    cell
                        .addClass('report-empty')
                        .text('-');

                } else {

                    const status =
                        report.status
                            ? report.status.toUpperCase()
                            : '';

                    const cssClass =
                        statusClass[status] ||
                        'report-empty';

                    cell
                        .addClass(cssClass)
                        .text(report.tanggal || '-');

                    /*
                     * Tooltip browser
                     */
                    cell.attr(
                        'title',
                        status
                            ? status
                            : 'Tidak ada status'
                    );

                }

                row.append(cell);
            }

            tableBody.append(row);

        });
    }


    /*
     * Error
     */
    function showError(message) {

        errorBox
            .removeClass('d-none')
            .text(message);
    }


    /*
     * Filter tahun
     */
    filterTahun.on('change', function () {

        loadMonitoring();

    });


    /*
     * Initial load
     */
    loadMonitoring();

});
