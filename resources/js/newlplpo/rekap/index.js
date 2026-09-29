/**

* ==========================================================
* REKAP LPLPO
* ==========================================================
  */

$(function () {


/*
|--------------------------------------------------------------------------
| URL
|--------------------------------------------------------------------------
*/

const dataUrl =
    window.lplpoRekap.dataUrl;

const exportExcelUrl =
    window.lplpoRekap.exportExcelUrl;

const exportPdfUrl =
    window.lplpoRekap.exportPdfUrl;


/*
|--------------------------------------------------------------------------
| STATUS REQUEST
|--------------------------------------------------------------------------
*/

let currentRequest = null;


/*
|--------------------------------------------------------------------------
| AMBIL FILTER
|--------------------------------------------------------------------------
*/

function getFilter()
{
    return {

        bulan_mulai:
            parseInt(
                $('#bulan_mulai').val(),
                10
            ),

        tahun_mulai:
            parseInt(
                $('#tahun_mulai').val(),
                10
            ),

        bulan_sampai:
            parseInt(
                $('#bulan_sampai').val(),
                10
            ),

        tahun_sampai:
            parseInt(
                $('#tahun_sampai').val(),
                10
            ),

        kode_faskes:
            $('#kode_faskes').length
                ? $('#kode_faskes').val()
                : ''

    };
}


/*
|--------------------------------------------------------------------------
| VALIDASI PERIODE
|--------------------------------------------------------------------------
*/

function validatePeriod(filter)
{
    const periodeMulai =
        (filter.tahun_mulai * 100) +
        filter.bulan_mulai;

    const periodeSampai =
        (filter.tahun_sampai * 100) +
        filter.bulan_sampai;

    if (
        periodeMulai >
        periodeSampai
    ) {

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
| LOADING
|--------------------------------------------------------------------------
*/

function setLoading(status)
{
    const button =
        $('#btnFilter');

    if (status) {

        button
            .prop(
                'disabled',
                true
            )
            .html(`
                <span
                    class="spinner-border spinner-border-sm me-1">
                </span>
                Memuat...
            `);

        $('#tableRekap')
            .addClass(
                'rekap-loading'
            );

    } else {

        button
            .prop(
                'disabled',
                false
            )
            .html(`
                <i class="bi bi-search me-1"></i>
                Tampilkan
            `);

        $('#tableRekap')
            .removeClass(
                'rekap-loading'
            );
    }
}


/*
|--------------------------------------------------------------------------
| LOAD DATA
|--------------------------------------------------------------------------
*/

function loadData()
{
    const filter =
        getFilter();

    console.log(
        'FILTER REKAP LPLPO:',
        filter
    );

    if (
        !validatePeriod(filter)
    ) {
        return;
    }

    if (currentRequest) {
        currentRequest.abort();
    }

    setLoading(true);

    $('#tableRekap tbody').html(`

        <tr>

            <td
                colspan="20"
                class="text-center py-5 text-muted">

                <div
                    class="spinner-border text-success mb-2">
                </div>

                <div>
                    Mengambil data rekap...
                </div>

            </td>

        </tr>

    `);

    currentRequest = $.ajax({

        url: dataUrl,

        method: 'GET',

        data: {

            bulan_mulai:
                filter.bulan_mulai,

            tahun_mulai:
                filter.tahun_mulai,

            bulan_sampai:
                filter.bulan_sampai,

            tahun_sampai:
                filter.tahun_sampai,

            kode_faskes:
                filter.kode_faskes,

            _: new Date().getTime()

        },

        cache: false,

        dataType: 'json',

        success: function (response) {

            console.log(
                'RESPONSE REKAP LPLPO:',
                response
            );

            if (
                !response.success
            ) {

                showError(
                    response.message ??
                    'Data tidak dapat diproses.'
                );

                return;
            }

            renderHeader(
                response
            );

            renderTable(
                response.items ?? []
            );
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
                'ERROR REKAP:',
                xhr.responseText
            );

            let message =
                'Gagal mengambil data rekap LPLPO.';

            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message =
                    xhr.responseJSON.message;
            }

            showError(
                message
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
| HEADER INFO
|--------------------------------------------------------------------------
*/

function renderHeader(response)
{
    const bulanMulaiText =
        $('#bulan_mulai option:selected')
            .text()
            .trim();

    const tahunMulai =
        $('#tahun_mulai')
            .val();

    const bulanSampaiText =
        $('#bulan_sampai option:selected')
            .text()
            .trim();

    const tahunSampai =
        $('#tahun_sampai')
            .val();

    $('#infoPeriode').html(`

        ${escapeHtml(
            bulanMulaiText
        )}

        ${escapeHtml(
            tahunMulai
        )}

        <span class="mx-2 text-muted">
            s/d
        </span>

        ${escapeHtml(
            bulanSampaiText
        )}

        ${escapeHtml(
            tahunSampai
        )}

    `);

    $('#infoJumlahLaporan')
        .text(
            number(
                response.jumlah_laporan
            )
        );

    $('#infoJumlahItem')
        .text(
            number(
                response.jumlah_item
            )
        );

    $('#jumlahItem')
        .text(
            number(
                response.jumlah_item
            ) +
            ' Item'
        );
}


/*
|--------------------------------------------------------------------------
| RENDER TABLE
|--------------------------------------------------------------------------
*/

function renderTable(items)
{
    const tbody =
        $('#tableRekap tbody');

    tbody.empty();

    /*
    |--------------------------------------------------------------------------
    | EMPTY
    |--------------------------------------------------------------------------
    */

    if (
        !items ||
        !items.length
    ) {

        tbody.html(`

            <tr>

                <td
                    colspan="20"
                    class="text-center text-muted py-5">

                    <i
                        class="bi bi-inbox fs-1 d-block mb-2">
                    </i>

                    <strong>
                        Tidak ada data
                    </strong>

                    <div class="small mt-1">

                        Tidak ditemukan LPLPO FINAL
                        pada periode yang dipilih.

                    </div>

                </td>

            </tr>

        `);

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | NOMOR
    |--------------------------------------------------------------------------
    */

    let no = 1;

    let lastProgramId = null;

    let lastProgramName = null;


    /*
    |--------------------------------------------------------------------------
    | LOOP
    |--------------------------------------------------------------------------
    */

    items.forEach(function (item) {

        const programName =
            String(
                item.program_name ??
                'Non Program'
            ).trim() ||
            'Non Program';

        const isNonProgram =
            Number(
                item.program_id
            ) === 1 ||
            programName.toLowerCase() ===
            'non program';


        /*
        |--------------------------------------------------------------------------
        | PROGRAM SEPARATOR
        |--------------------------------------------------------------------------
        |
        | Non Program / program_id = 1:
        | tidak menampilkan separator.
        |
        */

        if (!isNonProgram) {

            const programChanged =
                lastProgramId !==
                    item.program_id ||
                lastProgramName !==
                    programName;

            if (programChanged) {

                tbody.append(`

                    <tr class="table-primary">

                        <td
                            colspan="20"
                            class="fw-bold text-start">

                            <i
                                class="bi bi-folder2-open me-1">
                            </i>

                            ${escapeHtml(
                                programName
                            )}

                        </td>

                    </tr>

                `);

                lastProgramId =
                    item.program_id;

                lastProgramName =
                    programName;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | NAPZA
        |--------------------------------------------------------------------------
        */

        const isNapza =
            String(
                item.obat_napza ??
                'tidak'
            )
            .toLowerCase() === 'ya';

        const rowClass =
            isNapza
                ? 'table-napza'
                : '';


        /*
        |--------------------------------------------------------------------------
        | ESENSIAL
        |--------------------------------------------------------------------------
        */

        const essentialValue =
            String(
                item.obat_esensial ??
                'noe'
            )
            .toLowerCase();

        let essentialHtml;

        if (
            essentialValue === 'oe'
        ) {

            essentialHtml = `
                <span class="badge bg-success">
                    OE
                </span>
            `;

        } else {

            essentialHtml = `
                <span class="badge bg-secondary">
                    NOE
                </span>
            `;
        }


        /*
        |--------------------------------------------------------------------------
        | FORMULARIUM PKM
        |--------------------------------------------------------------------------
        */

        const formulariumValue =
            String(
                item.obat_formularium_puskesmas ??
                'false'
            )
            .toLowerCase();

        let formulariumHtml;

        if (
            formulariumValue === 'true'
        ) {

            formulariumHtml = `
                <span class="badge bg-primary">
                    Ya
                </span>
            `;

        } else {

            formulariumHtml = `
                <span class="text-muted">
                    Tidak
                </span>
            `;
        }


        /*
        |--------------------------------------------------------------------------
        | PEMBERIAN
        |--------------------------------------------------------------------------
        */

        const pemberian =
            Number(
                item.pemberian_program_pkd ?? 0
            ) +
            Number(
                item.pemberian_jkn ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | ROW
        |--------------------------------------------------------------------------
        */

        tbody.append(`

            <tr class="${rowClass}">

                <td
                    class="text-center fw-semibold">

                    ${no++}

                </td>

                <td>

                    ${escapeHtml(
                        item.kode_obat ??
                        '-'
                    )}

                </td>

                <td>

                    ${escapeHtml(
                        item.nama_obat ??
                        '-'
                    )}

                    ${
                        isNapza
                            ? `
                                <span
                                    class="badge bg-danger ms-1">
                                    NAPZA
                                </span>
                            `
                            : ''
                    }

                </td>

                <td class="text-center">

                    ${escapeHtml(
                        item.satuan ??
                        '-'
                    )}

                </td>

                <td class="text-center">

                    ${essentialHtml}

                </td>

                <td class="text-center">

                    ${formulariumHtml}

                </td>


                <!-- STOK AWAL -->

                <td class="text-end">
                    ${number(
                        item.stok_awal_program_pkd
                    )}
                </td>

                <td class="text-end">
                    ${number(
                        item.stok_awal_jkn
                    )}
                </td>


                <!-- PENERIMAAN -->

                <td class="text-end">
                    ${number(
                        item.penerimaan_program_pkd
                    )}
                </td>

                <td class="text-end">
                    ${number(
                        item.penerimaan_jkn
                    )}
                </td>


                <!-- PERSEDIAAN -->

                <td class="text-end">
                    ${number(
                        item.persediaan_program_pkd
                    )}
                </td>

                <td class="text-end">
                    ${number(
                        item.persediaan_jkn
                    )}
                </td>


                <!-- PEMAKAIAN -->

                <td class="text-end">
                    ${number(
                        item.pemakaian_program_pkd
                    )}
                </td>

                <td class="text-end">
                    ${number(
                        item.pemakaian_jkn
                    )}
                </td>


                <!-- EXPIRED -->

                <td class="text-end">
                    ${number(
                        item.item_expired_pkd
                    )}
                </td>

                <td class="text-end">
                    ${number(
                        item.item_expired_jkn
                    )}
                </td>


                <!-- STOK AKHIR -->

                <td class="text-end">
                    ${number(
                        item.stok_akhir_program_pkd
                    )}
                </td>

                <td class="text-end">
                    ${number(
                        item.stok_akhir_jkn
                    )}
                </td>


                <!-- PERMINTAAN -->

                <td
                    class="text-end fw-semibold">

                    ${number(
                        item.permintaan
                    )}

                </td>


                <!-- PEMBERIAN = PKD + JKN -->

                <td
                    class="text-end fw-semibold">

                    ${number(
                        pemberian
                    )}

                </td>

            </tr>

        `);

    });
}


/*
|--------------------------------------------------------------------------
| EXPORT QUERY
|--------------------------------------------------------------------------
*/

function buildExportUrl(baseUrl)
{
    const filter =
        getFilter();

    if (
        !validatePeriod(filter)
    ) {
        return null;
    }

    const params =
        new URLSearchParams({

            bulan_mulai:
                filter.bulan_mulai,

            tahun_mulai:
                filter.tahun_mulai,

            bulan_sampai:
                filter.bulan_sampai,

            tahun_sampai:
                filter.tahun_sampai,

            kode_faskes:
                filter.kode_faskes || ''

        });

    return (
        baseUrl +
        '?' +
        params.toString()
    );
}


/*
|--------------------------------------------------------------------------
| EXPORT EXCEL
|--------------------------------------------------------------------------
*/

$('#btnExportExcel').on(
    'click',
    function () {

        const url =
            buildExportUrl(
                exportExcelUrl
            );

        if (!url) {
            return;
        }

        window.location.href =
            url;
    }
);


/*
|--------------------------------------------------------------------------
| EXPORT PDF
|--------------------------------------------------------------------------
*/

$('#btnExportPdf').on(
    'click',
    function () {

        const url =
            buildExportUrl(
                exportPdfUrl
            );

        if (!url) {
            return;
        }

        window.location.href =
            url;
    }
);


/*
|--------------------------------------------------------------------------
| FILTER BUTTON
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
| SELECT CHANGE
|--------------------------------------------------------------------------
|
| Tidak otomatis load.
|
*/

$(
    '#bulan_mulai, ' +
    '#tahun_mulai, ' +
    '#bulan_sampai, ' +
    '#tahun_sampai, ' +
    '#kode_faskes'
).on(
    'change',
    function () {

        // User harus klik Tampilkan.

    }
);


/*
|--------------------------------------------------------------------------
| NUMBER FORMAT
|--------------------------------------------------------------------------
*/

function number(value)
{
    const numeric =
        Number(
            value ?? 0
        );

    if (
        Number.isNaN(numeric)
    ) {
        return '0';
    }

    return new Intl.NumberFormat(
        'id-ID'
    ).format(
        numeric
    );
}


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
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
| ERROR
|--------------------------------------------------------------------------
*/

function showError(message)
{
    $('#tableRekap tbody').html(`

        <tr>

            <td
                colspan="20"
                class="text-center text-danger py-5">

                <i
                    class="bi bi-exclamation-triangle fs-2 d-block mb-2">
                </i>

                ${escapeHtml(
                    message
                )}

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
| INITIAL LOAD
|--------------------------------------------------------------------------
*/

loadData();


});
