(function ($) {

    'use strict';

    const config =
        window.lplpoStokEsensialConfig || {};

    const groupId =
        parseInt(config.groupId || 0, 10);

    let xhr = null;
    let loading = false;

    /*
    |--------------------------------------------------------------------------
    | DOCUMENT READY
    |--------------------------------------------------------------------------
    */

    $(function () {

        console.log(
            '[Stok Esensial] JS loaded',
            {
                groupId: groupId,
                dataUrl: config.dataUrl
            }
        );

        init();

    });

    /*
    |--------------------------------------------------------------------------
    | INIT
    |--------------------------------------------------------------------------
    */

    function init() {

        bindEvents();

        loadData();
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    */

    function bindEvents() {

        $('#btnFilter')
            .off('click.stokEsensial')
            .on(
                'click.stokEsensial',
                function () {

                    loadData();
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD DATA
    |--------------------------------------------------------------------------
    */

    function loadData() {

        /*
        |--------------------------------------------------------------------------
        | JANGAN BIARKAN REQUEST MENUMPUK
        |--------------------------------------------------------------------------
        */

        if (xhr) {

            xhr.abort();

            xhr = null;
        }

        setLoading(true);

        const params =
            buildParams();

        console.log(
            '[Stok Esensial] Request',
            params
        );

        xhr = $.ajax({

            url: config.dataUrl,

            method: 'GET',

            data: params,

            dataType: 'json',

            timeout: 60000,

            headers: {
                'X-CSRF-TOKEN':
                    config.csrfToken || ''
            }

        });

        xhr.done(function (response) {

            console.log(
                '[Stok Esensial] Response',
                response
            );

            if (
                !response ||
                response.success !== true
            ) {

                showError(
                    response?.message ||
                    'Data tidak dapat dimuat.'
                );

                return;
            }

            renderResponse(response);

        });

        xhr.fail(function (
            jqXHR,
            textStatus,
            errorThrown
        ) {

            if (textStatus === 'abort') {
                return;
            }

            console.error(
                '[Stok Esensial] AJAX ERROR',
                {
                    status: jqXHR.status,
                    textStatus: textStatus,
                    error: errorThrown,
                    response: jqXHR.responseText
                }
            );

            let message =
                'Gagal memuat data.';

            if (
                textStatus === 'timeout'
            ) {

                message =
                    'Server terlalu lama merespons.';

            } else if (
                jqXHR.status === 403
            ) {

                message =
                    'Anda tidak memiliki akses.';

            } else if (
                jqXHR.status === 419
            ) {

                message =
                    'Session telah berakhir. Silakan login kembali.';

            } else if (
                jqXHR.responseJSON &&
                jqXHR.responseJSON.message
            ) {

                message =
                    jqXHR.responseJSON.message;
            }

            showError(message);

        });

        xhr.always(function () {

            xhr = null;

            setLoading(false);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | BUILD PARAMS
    |--------------------------------------------------------------------------
    */

    function buildParams() {

        /*
        |--------------------------------------------------------------------------
        | GROUP 3,4,5
        |--------------------------------------------------------------------------
        */

        if (
            [3, 4, 5].includes(groupId)
        ) {

            return {

                bulan_mulai:
                    $('#bulan_mulai').val()
                    || config.bulanMulai,

                tahun_mulai:
                    $('#tahun_mulai').val()
                    || config.tahunMulai,

                bulan_sampai:
                    $('#bulan_sampai').val()
                    || config.bulanSampai,

                tahun_sampai:
                    $('#tahun_sampai').val()
                    || config.tahunSampai
            };
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP 1,2
        |--------------------------------------------------------------------------
        */

        return {

            bulan:
                $('#bulan').val()
                || config.bulan,

            tahun:
                $('#tahun').val()
                || config.tahun,

            kode_faskes:
                $('#kode_faskes').val()
                || ''
        };
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER RESPONSE
    |--------------------------------------------------------------------------
    */

    function renderResponse(response) {

        const data =
            response.data || {};

        if (
            response.mode === 'periode'
        ) {

            renderPeriode(data);

            return;
        }

        if (
            response.mode === 'kategori'
        ) {

            renderKategori(data);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP 1
        |--------------------------------------------------------------------------
        */

        renderObat(data);
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP 1
    |--------------------------------------------------------------------------
    */

    function renderObat(data) {

        updateInfo(
            formatMonth(
                data.bulan,
                data.tahun
            ),
            formatFaskes(
                data.faskes
            )
        );

        renderFaskesHeader(
            data.faskes || []
        );

        renderRows(
            data.rows || [],
            data.faskes || [],
            'obat'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP 2
    |--------------------------------------------------------------------------
    */

    function renderKategori(data) {

        updateInfo(
            formatMonth(
                data.bulan,
                data.tahun
            ),
            formatFaskes(
                data.faskes
            )
        );

        renderFaskesHeader(
            data.faskes || []
        );

        renderRows(
            data.rows || [],
            data.faskes || [],
            'kategori'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP 3,4,5
    |--------------------------------------------------------------------------
    */

    function renderPeriode(data) {

        const periods =
            data.periods || [];

        const faskes =
            data.faskes || null;

        updateInfo(
            formatPeriods(periods),
            formatFaskes(faskes)
        );

        renderPeriodHeader(
            periods
        );

        renderPeriodRows(
            data.rows || [],
            periods
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER FASKES
    |--------------------------------------------------------------------------
    */

  function renderFaskesHeader(faskes) {

    let html = '<tr>';

    html += `
        <th class="sticky-column obat-name">
            ${groupId === 2
                ? 'Kategori Obat'
                : 'Nama Obat'}
        </th>
    `;

    faskes.forEach(function (faskesItem) {

        html += `
            <th
                class="text-center"
                title="${escapeHtml(
                    faskesItem.kodeFaskes
                )}">
                ${escapeHtml(
                    faskesItem.namaFaskes
                )}
            </th>
        `;
    });

    html += '</tr>';

    $('#heatmapHead').html(html);
}
    /*
    |--------------------------------------------------------------------------
    | HEADER PERIOD
    |--------------------------------------------------------------------------
    */

    function renderPeriodHeader(periods) {

        let html = '<tr>';

        html += `
            <th class="obat-name">
                Nama Obat
            </th>
        `;

        html += `
            <th class="obat-code">
                Kode
            </th>
        `;

        html += `
            <th class="obat-satuan">
                Satuan
            </th>
        `;

        periods.forEach(function (period) {

            html += `
                <th class="text-center">
                    ${escapeHtml(
                        period.label
                    )}
                </th>
            `;
        });

        html += '</tr>';

        $('#heatmapHead').html(html);
    }

    /*
    |--------------------------------------------------------------------------
    | ROWS GROUP 1 / 2
    |--------------------------------------------------------------------------
    */

    function renderRows(
    rows,
    faskes,
    mode
) {

    if (!rows.length) {

        showEmpty(
            faskes.length + 1
        );

        updateJumlah(0);

        return;
    }

    let html = '';

    rows.forEach(function (row) {

        html += '<tr>';

        if (mode === 'kategori') {

            html += `
                <td class="fw-semibold obat-name">
                    ${escapeHtml(
                        row.kategori ||
                        row.nama_obat ||
                        '-'
                    )}
                </td>
            `;

        } else {

            html += `
                <td class="obat-name">
                    ${escapeHtml(
                        row.nama_obat || '-'
                    )}
                </td>
            `;
        }

        faskes.forEach(function (f) {

            const cell =
                row.cells?.[
                    f.kodeFaskes
                ] || null;

            html += renderCell(cell);
        });

        html += '</tr>';
    });

    $('#heatmapBody').html(html);

    updateJumlah(rows.length);
}
    /*
    |--------------------------------------------------------------------------
    | ROWS PERIODE
    |--------------------------------------------------------------------------
    */

    function renderPeriodRows(
        rows,
        periods
    ) {

        if (!rows.length) {

            showEmpty(
                periods.length + 3
            );

            updateJumlah(0);

            return;
        }

        let html = '';

        rows.forEach(function (row) {

            html += '<tr>';

            html += `
                <td class="obat-name">
                    ${escapeHtml(
                        row.nama_obat || '-'
                    )}
                </td>
            `;

            html += `
                <td class="obat-code">
                    ${escapeHtml(
                        row.kode_obat || '-'
                    )}
                </td>
            `;

            html += `
                <td class="obat-satuan">
                    ${escapeHtml(
                        row.satuan || '-'
                    )}
                </td>
            `;

            periods.forEach(function (period) {

                const key =
                    period.tahun +
                    '-' +
                    String(
                        period.bulan
                    ).padStart(2, '0');

                const cell =
                    row.cells?.[key]
                    || null;

                html +=
                    renderCell(cell);
            });

            html += '</tr>';
        });

        $('#heatmapBody').html(html);

        updateJumlah(
            rows.length
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CELL
    |--------------------------------------------------------------------------
    */

    function renderCell(cell) {

        if (!cell) {

            return `
                <td class="stock-cell heat-nodata">
                    <span class="stock-value">
                        -
                    </span>
                </td>
            `;
        }

        const level =
            cell.level || 'nodata';

        let value = '-';

        if (
            cell.stok_akhir !== null &&
            cell.stok_akhir !== undefined
        ) {

            value =
                formatNumber(
                    cell.stok_akhir
                );
        }

        let percent = '';

        if (
            cell.percentage !== null &&
            cell.percentage !== undefined
        ) {

            percent = `
                <div class="stock-percent">
                    ${formatNumber(
                        cell.percentage,
                        2
                    )}%
                </div>
            `;
        }

        let title = '';

        if (
            cell.stok_minimal !== null &&
            cell.stok_minimal !== undefined
        ) {

            title =
                `Stok: ${formatNumber(
                    cell.stok_akhir
                )} | Minimal: ${formatNumber(
                    cell.stok_minimal
                )} | Optimum: ${formatNumber(
                    cell.stok_optimum
                )}`;
        }

        return `
            <td
                class="stock-cell heat-${escapeHtml(
                    level
                )}"
                title="${escapeHtml(title)}">

                <div class="stock-value">
                    ${value}
                </div>

                ${percent}

            </td>
        `;
    }

    /*
    |--------------------------------------------------------------------------
    | INFO
    |--------------------------------------------------------------------------
    */

    function updateInfo(
        periode,
        faskes
    ) {

        $('#infoPeriode')
            .text(periode || '-');

        $('#infoFaskes')
            .text(faskes || '-');
    }

    /*
    |--------------------------------------------------------------------------
    | FASKES FORMAT
    |--------------------------------------------------------------------------
    */

    function formatFaskes(faskes) {

        if (!faskes) {
            return '-';
        }

        if (!Array.isArray(faskes)) {

            return (
                faskes.kodeFaskes
                ? (
                    faskes.kodeFaskes +
                    ' - ' +
                    faskes.namaFaskes
                )
                : '-'
            );
        }

        if (!faskes.length) {
            return 'Tidak ada faskes';
        }

        if (faskes.length === 1) {

            return (
                faskes[0].kodeFaskes +
                ' - ' +
                faskes[0].namaFaskes
            );
        }

        return faskes.length +
            ' Faskes';
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT MONTH
    |--------------------------------------------------------------------------
    */

    function formatMonth(
        bulan,
        tahun
    ) {

        if (!bulan || !tahun) {
            return '-';
        }

        const months = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        return (
            months[
                parseInt(bulan, 10) - 1
            ] +
            ' ' +
            tahun
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT PERIOD
    |--------------------------------------------------------------------------
    */

    function formatPeriods(periods) {

        if (!periods.length) {
            return '-';
        }

        if (periods.length === 1) {
            return periods[0].label;
        }

        return (
            periods[0].label +
            ' s/d ' +
            periods[
                periods.length - 1
            ].label
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NUMBER
    |--------------------------------------------------------------------------
    */

    function formatNumber(
        value,
        decimal = 0
    ) {

        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {

            return '-';
        }

        return Number(value)
            .toLocaleString(
                'id-ID',
                {
                    minimumFractionDigits:
                        decimal,
                    maximumFractionDigits:
                        decimal
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | LOADING
    |--------------------------------------------------------------------------
    */

    function setLoading(state) {

        loading = state;

        const button =
            $('#btnFilter');

        if (!button.length) {
            return;
        }

        if (state) {

            button
                .prop('disabled', true)
                .data(
                    'original-html',
                    button.html()
                )
                .html(`
                    <span
                        class="spinner-border spinner-border-sm me-1">
                    </span>
                    Memuat...
                `);

        } else {

            button
                .prop('disabled', false)
                .html(
                    button.data(
                        'original-html'
                    ) ||
                    `
                        <i class="bi bi-search me-1"></i>
                        Tampilkan
                    `
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    function showError(message) {

        $('#heatmapHead').html(`
            <tr>
                <th class="text-center py-4">
                    Gagal memuat data
                </th>
            </tr>
        `);

        $('#heatmapBody').html(`
            <tr>
                <td class="text-center text-danger py-5">
                    <i class="bi bi-exclamation-triangle fs-3"></i>

                    <div class="mt-2">
                        ${escapeHtml(message)}
                    </div>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-success mt-3"
                        id="btnRetry">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Coba Lagi
                    </button>
                </td>
            </tr>
        `);

        $('#btnRetry')
            .off('click')
            .on('click', function () {

                loadData();
            });

        updateJumlah(0);
    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY
    |--------------------------------------------------------------------------
    */

    function showEmpty(colspan) {

        $('#heatmapBody').html(`
            <tr>
                <td
                    colspan="${colspan}"
                    class="text-center text-muted py-5">

                    <i class="bi bi-inbox fs-2"></i>

                    <div class="mt-2">
                        Tidak ada data untuk periode yang dipilih.
                    </div>

                </td>
            </tr>
        `);
    }

    /*
    |--------------------------------------------------------------------------
    | JUMLAH
    |--------------------------------------------------------------------------
    */

    function updateJumlah(total) {

        $('#jumlahObat')
            .text(
                formatNumber(total) +
                ' Data'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return $('<div>')
            .text(
                value ?? ''
            )
            .html();
    }

})(jQuery);