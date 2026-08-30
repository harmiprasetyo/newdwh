$(function () {

    /*
    |--------------------------------------------------------------------------
    | CONFIG
    |--------------------------------------------------------------------------
    */

    const config =
        window.lplpoStokEsensialConfig;

    const groupId =
        parseInt(
            config.groupId,
            10
        );

    let currentRequest = null;


    /*
    |--------------------------------------------------------------------------
    | LOAD DATA
    |--------------------------------------------------------------------------
    */

    function loadData()
    {
        const params = {};


        /*
        |--------------------------------------------------------------------------
        | GROUP 3,4,5
        |--------------------------------------------------------------------------
        */

        if (
            [3, 4, 5].includes(groupId)
        ) {

            params.bulan_mulai =
                parseInt(
                    $('#bulan_mulai').val(),
                    10
                );

            params.tahun_mulai =
                parseInt(
                    $('#tahun_mulai').val(),
                    10
                );

            params.bulan_sampai =
                parseInt(
                    $('#bulan_sampai').val(),
                    10
                );

            params.tahun_sampai =
                parseInt(
                    $('#tahun_sampai').val(),
                    10
                );


            if (
                !validateRange(
                    params
                )
            ) {

                return;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | GROUP 1,2
        |--------------------------------------------------------------------------
        */

        if (
            [1, 2].includes(groupId)
        ) {

            params.bulan =
                parseInt(
                    $('#bulan').val(),
                    10
                );

            params.tahun =
                parseInt(
                    $('#tahun').val(),
                    10
                );

            params.kode_faskes =
                $('#kode_faskes').length
                    ? $('#kode_faskes').val()
                    : '';

        }


        /*
        |--------------------------------------------------------------------------
        | ABORT REQUEST
        |--------------------------------------------------------------------------
        */

        if (currentRequest) {

            currentRequest.abort();

        }


        setLoading(true);


        /*
        |--------------------------------------------------------------------------
        | LOADING
        |--------------------------------------------------------------------------
        */

        renderLoading();


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        currentRequest =
            $.ajax({

                url: config.dataUrl,

                type: 'GET',

                data: params,

                cache: false,

                dataType: 'json',

                success: function (response) {

                    if (
                        !response.success
                    ) {

                        showError(
                            response.message ||
                            'Data tidak dapat diproses.'
                        );

                        return;

                    }


                    if (
                        response.mode === 'periode'
                    ) {

                        renderPeriode(
                            response.data
                        );

                    } else {

                        renderFaskes(
                            response.data
                        );

                    }

                },

                error: function (
                    xhr,
                    status
                ) {

                    if (
                        status === 'abort'
                    ) {

                        return;

                    }


                    console.error(
                        xhr.responseText
                    );


                    showError(
                        xhr.responseJSON?.message ||
                        'Gagal mengambil data heatmap.'
                    );

                },

                complete: function () {

                    setLoading(false);

                    currentRequest = null;

                }

            });

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE RANGE
    |--------------------------------------------------------------------------
    */

    function validateRange(params)
    {

        const mulai =
            (
                params.tahun_mulai *
                100
            ) +
            params.bulan_mulai;

        const sampai =
            (
                params.tahun_sampai *
                100
            ) +
            params.bulan_sampai;


        if (mulai > sampai) {

            Swal.fire({

                icon: 'warning',

                title: 'Periode Tidak Valid',

                text:
                    'Periode mulai tidak boleh lebih besar dari periode sampai.'

            });

            return false;

        }

        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER PERIODE
    |--------------------------------------------------------------------------
    */

    function renderPeriode(data)
    {

        const periods =
            data.periods || [];

        const rows =
            data.rows || [];

        const faskes =
            data.faskes || null;


        /*
        |--------------------------------------------------------------------------
        | INFO
        |--------------------------------------------------------------------------
        */

        $('#infoPeriode')
            .text(
                formatPeriode(
                    config.bulanMulai,
                    config.tahunMulai,
                    config.bulanSampai,
                    config.tahunSampai
                )
            );


        $('#infoFaskes')
            .text(
                faskes
                    ? (
                        faskes.kodeFaskes +
                        ' - ' +
                        faskes.namaFaskes
                    )
                    : '-'
            );


        $('#jumlahObat')
            .text(
                number(
                    rows.length
                ) +
                ' Obat'
            );


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        let head = `

            <tr>

                <th
                    rowspan="2"
                    class="text-center">

                    No

                </th>

                <th
                    rowspan="2"
                    class="obat-code">

                    Kode

                </th>

                <th
                    rowspan="2"
                    class="obat-name">

                    Nama Obat

                </th>

                <th
                    rowspan="2"
                    class="obat-satuan">

                    Sat

                </th>

        `;


        periods.forEach(function (period) {

            head += `

                <th
                    rowspan="2"
                    class="text-center">

                    ${escapeHtml(
                        period.label
                    )}

                </th>

            `;

        });


        head += `

            </tr>

        `;


        $('#heatmapHead')
            .html(head);


        /*
        |--------------------------------------------------------------------------
        | BODY
        |--------------------------------------------------------------------------
        */

        const tbody =
            $('#heatmapBody');

        tbody.empty();


        if (!rows.length) {

            tbody.html(`

                <tr>

                    <td
                        colspan="${4 + periods.length}"
                        class="text-center text-muted py-5">

                        <i
                            class="bi bi-inbox fs-1 d-block mb-2">
                        </i>

                        Tidak ada obat esensial.

                    </td>

                </tr>

            `);

            return;

        }


        rows.forEach(function (
            row,
            index
        ) {

            const napza =
                row.obat_napza === 'ya';


            tbody.append(`

                <tr>

                    <td class="text-center">

                        ${index + 1}

                    </td>

                    <td
                        class="${napza ? 'heat-napza' : ''}">

                        ${escapeHtml(
                            row.kode_obat
                        )}

                    </td>

                    <td
                        class="obat-name ${napza ? 'heat-napza' : ''}">

                        ${escapeHtml(
                            row.nama_obat
                        )}

                        ${
                            napza
                                ? `
                                    <span class="napza-label">
                                        NAPZA
                                    </span>
                                  `
                                : ''
                        }

                    </td>

                    <td
                        class="text-center ${napza ? 'heat-napza' : ''}">

                        ${escapeHtml(
                            row.satuan || '-'
                        )}

                    </td>

                    ${renderPeriodCells(
                        row,
                        periods
                    )}

                </tr>

            `);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER FASKES
    |--------------------------------------------------------------------------
    */

    function renderFaskes(data)
    {

        const faskes =
            data.faskes || [];

        const rows =
            data.rows || [];


        /*
        |--------------------------------------------------------------------------
        | INFO
        |--------------------------------------------------------------------------
        */

        $('#infoPeriode')
            .text(
                formatMonthYear(
                    data.bulan,
                    data.tahun
                )
            );


        $('#infoFaskes')
            .text(
                faskes.length +
                ' Faskes'
            );


        $('#jumlahObat')
            .text(
                number(
                    rows.length
                ) +
                ' Obat'
            );


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        let head = `

            <tr>

                <th
                    rowspan="2"
                    class="text-center">

                    No

                </th>

                <th
                    rowspan="2"
                    class="obat-code">

                    Kode

                </th>

                <th
                    rowspan="2"
                    class="obat-name">

                    Nama Obat

                </th>

                <th
                    rowspan="2"
                    class="obat-satuan">

                    Sat

                </th>

        `;


        faskes.forEach(function (f) {

            head += `

                <th
                    class="text-center"
                    title="${escapeHtml(
                        f.kodeFaskes
                    )}">

                    ${escapeHtml(
                        f.namaFaskes
                    )}

                </th>

            `;

        });


        head += `

            </tr>

        `;


        $('#heatmapHead')
            .html(head);


        /*
        |--------------------------------------------------------------------------
        | BODY
        |--------------------------------------------------------------------------
        */

        const tbody =
            $('#heatmapBody');

        tbody.empty();


        if (!rows.length) {

            tbody.html(`

                <tr>

                    <td
                        colspan="${4 + faskes.length}"
                        class="text-center text-muted py-5">

                        <i
                            class="bi bi-inbox fs-1 d-block mb-2">
                        </i>

                        Tidak ada obat esensial.

                    </td>

                </tr>

            `);

            return;

        }


        rows.forEach(function (
            row,
            index
        ) {

            const napza =
                row.obat_napza === 'ya';


            let html = `

                <tr>

                    <td class="text-center">

                        ${index + 1}

                    </td>

                    <td
                        class="${napza ? 'heat-napza' : ''}">

                        ${escapeHtml(
                            row.kode_obat
                        )}

                    </td>

                    <td
                        class="obat-name ${napza ? 'heat-napza' : ''}">

                        ${escapeHtml(
                            row.nama_obat
                        )}

                        ${
                            napza
                                ? `
                                    <span class="napza-label">
                                        NAPZA
                                    </span>
                                  `
                                : ''
                        }

                    </td>

                    <td
                        class="text-center ${napza ? 'heat-napza' : ''}">

                        ${escapeHtml(
                            row.satuan || '-'
                        )}

                    </td>

            `;


            faskes.forEach(function (f) {

                const cell =
                    row.cells[
                        f.kodeFaskes
                    ] || null;


                html +=
                    renderStockCell(
                        cell
                    );

            });


            html += `

                </tr>

            `;


            tbody.append(html);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | PERIOD CELLS
    |--------------------------------------------------------------------------
    */

    function renderPeriodCells(
        row,
        periods
    ) {

        let html = '';


        periods.forEach(function (
            period
        ) {

            const key =
                period.tahun +
                '-' +
                String(
                    period.bulan
                ).padStart(
                    2,
                    '0'
                );


            const cell =
                row.cells[key]
                || null;


            html +=
                renderStockCell(
                    cell
                );

        });


        return html;

    }


    /*
    |--------------------------------------------------------------------------
    | STOCK CELL
    |--------------------------------------------------------------------------
    */

    function renderStockCell(cell)
    {

        if (!cell) {

            return `

                <td
                    class="stock-cell heat-nodata">

                    <span>
                        -
                    </span>

                </td>

            `;

        }


        /*
        |--------------------------------------------------------------------------
        | NO DATA
        |--------------------------------------------------------------------------
        */

        if (
            !cell.available ||
            cell.stok_akhir === null
        ) {

            return `

                <td
                    class="stock-cell heat-nodata"
                    title="${escapeHtml(
                        buildTooltip(cell)
                    )}">

                    <span>
                        -
                    </span>

                </td>

            `;

        }


        /*
        |--------------------------------------------------------------------------
        | CLASS
        |--------------------------------------------------------------------------
        */

        let cssClass =
            'heat-' +
            (
                cell.level ||
                'nodata'
            );


        /*
        |--------------------------------------------------------------------------
        | PERCENTAGE
        |--------------------------------------------------------------------------
        */

        let percentage =
            cell.percentage !== null
                ? numberDecimal(
                    cell.percentage
                ) + '%'
                : '-';


        /*
        |--------------------------------------------------------------------------
        | FORMULARIUM
        |--------------------------------------------------------------------------
        */

        let formText = '';

        if (
            cell.formularium === 'true'
        ) {

            formText =
                '<div class="small formularium-ya">Form PKM</div>';

        } else if (
            cell.formularium === 'false'
        ) {

            formText =
                '<div class="small formularium-tidak">Non Form</div>';

        }


        return `

            <td
                class="stock-cell ${cssClass}"
                title="${escapeHtml(
                    buildTooltip(cell)
                )}">

                <div class="stock-value">

                    ${number(
                        cell.stok_akhir
                    )}

                </div>

                <div class="stock-percent">

                    ${percentage}

                </div>

                ${formText}

            </td>

        `;

    }


    /*
    |--------------------------------------------------------------------------
    | TOOLTIP
    |--------------------------------------------------------------------------
    */

    function buildTooltip(cell)
    {

        if (!cell) {

            return 'Tidak ada data';

        }


        let text =
            'Stok Akhir: ' +
            (
                cell.stok_akhir !== null
                    ? number(cell.stok_akhir)
                    : '-'
            );


        text +=
            '\nStok Minimal: ' +
            (
                cell.stok_minimal !== null
                    ? number(cell.stok_minimal)
                    : '-'
            );


        text +=
            '\nStok Optimum: ' +
            (
                cell.stok_optimum !== null
                    ? number(cell.stok_optimum)
                    : '-'
            );


        text +=
            '\nPersentase: ' +
            (
                cell.percentage !== null
                    ? numberDecimal(
                        cell.percentage
                    ) + '%'
                    : '-'
            );


        text +=
            '\nFormularium PKM: ' +
            (
                cell.formularium === 'true'
                    ? 'Ya'
                    : cell.formularium === 'false'
                        ? 'Tidak'
                        : '-'
            );


        return text;

    }


    /*
    |--------------------------------------------------------------------------
    | LOADING
    |--------------------------------------------------------------------------
    */

    function setLoading(status)
    {

        $('#btnFilter')
            .prop(
                'disabled',
                status
            );


        if (status) {

            $('#btnFilter')
                .html(`

                    <span
                        class="spinner-border spinner-border-sm me-1">
                    </span>

                    Memuat...

                `);

        } else {

            $('#btnFilter')
                .html(`

                    <i
                        class="bi bi-search me-1">
                    </i>

                    Tampilkan

                `);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOADING TABLE
    |--------------------------------------------------------------------------
    */

    function renderLoading()
    {

        $('#heatmapHead')
            .html(`

                <tr>

                    <th
                        colspan="10"
                        class="text-center py-3">

                        Memuat data...

                    </th>

                </tr>

            `);


        $('#heatmapBody')
            .html(`

                <tr>

                    <td
                        colspan="10"
                        class="text-center text-muted py-5">

                        <div
                            class="spinner-border text-success mb-2">
                        </div>

                        <div>
                            Mengambil data heatmap...
                        </div>

                    </td>

                </tr>

            `);

    }


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    function showError(message)
    {

        $('#heatmapBody')
            .html(`

                <tr>

                    <td
                        colspan="20"
                        class="text-center text-danger py-5">

                        <i
                            class="bi bi-exclamation-triangle fs-2 d-block mb-2">
                        </i>

                        ${escapeHtml(message)}

                    </td>

                </tr>

            `);


        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text: message

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT MONTH YEAR
    |--------------------------------------------------------------------------
    */

    function formatMonthYear(
        bulan,
        tahun
    )
    {

        const names = [

            '',
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
            names[bulan] ||
            bulan
        ) +
        ' ' +
        tahun;

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT PERIOD
    |--------------------------------------------------------------------------
    */

    function formatPeriode(
        bulanMulai,
        tahunMulai,
        bulanSampai,
        tahunSampai
    )
    {

        const mulai =
            formatMonthYear(
                bulanMulai,
                tahunMulai
            );

        const sampai =
            formatMonthYear(
                bulanSampai,
                tahunSampai
            );


        if (
            bulanMulai === bulanSampai &&
            tahunMulai === tahunSampai
        ) {

            return mulai;

        }


        return mulai +
            ' s/d ' +
            sampai;

    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER
    |--------------------------------------------------------------------------
    */

    function number(value)
    {

        const numeric =
            Number(value ?? 0);


        if (
            Number.isNaN(numeric)
        ) {

            return '0';

        }


        return new Intl.NumberFormat(
            'id-ID'
        ).format(numeric);

    }


    /*
    |--------------------------------------------------------------------------
    | DECIMAL
    |--------------------------------------------------------------------------
    */

    function numberDecimal(value)
    {

        const numeric =
            Number(value ?? 0);


        if (
            Number.isNaN(numeric)
        ) {

            return '0';

        }


        return new Intl.NumberFormat(
            'id-ID',
            {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }
        ).format(numeric);

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value)
    {

        return $('<div>')
            .text(
                value ?? ''
            )
            .html();

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    $('#btnFilter').on(
        'click',
        function () {

            loadData();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    loadData();

});
