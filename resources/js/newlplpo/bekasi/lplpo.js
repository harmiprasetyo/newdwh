/**
 * LPLPO Bekasi
 *
 * resources/js/newlplpo/bekasi/lplpo.js
 */

$(document).ready(function () {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | CONFIG
    |--------------------------------------------------------------------------
    */

    const config = window.LplpoBekasiConfig;


    if (!config) {

        console.error(
            'LplpoBekasiConfig tidak ditemukan.'
        );

        return;
    }


    const DATA_URL = config.dataUrl;

    const DEFAULT_START_DATE =
        config.defaultStartDate;

    const DEFAULT_END_DATE =
        config.defaultEndDate;

    const DEFAULT_LIMIT =
        parseInt(config.defaultLimit || 50, 10);

    const ALLOWED_LIMITS =
        Array.isArray(config.allowedLimits)
            ? config.allowedLimits.map(
                value => parseInt(value, 10)
            )
            : [25, 50, 100];


    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    let currentPage = 1;

    let currentLimit = DEFAULT_LIMIT;

    let hasNext = false;

    let isLoading = false;


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const $form =
        $('#formFilter');

    const $startDate =
        $('#start_date');

    const $endDate =
        $('#end_date');

    const $limit =
        $('#limit');

    const $tableBody =
        $('#tableBody');

    const $btnFilter =
        $('#btnFilter');

    const $btnReset =
        $('#btnReset');

    const $btnPrevious =
        $('#btnPrevious');

    const $btnNext =
        $('#btnNext');

    const $pageInfo =
        $('#pageInfo');

    const $loading =
        $('#loadingIndicator');

    const $error =
        $('#errorContainer');

    const $errorMessage =
        $('#errorMessage');

    const $periodeLabel =
        $('#periodeLabel');


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE LIMIT
    |--------------------------------------------------------------------------
    */

    currentLimit = normalizeLimit(
        $limit.val()
    );

    $limit.val(
        String(currentLimit)
    );


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE LIMIT
    |--------------------------------------------------------------------------
    */

    function normalizeLimit(value)
    {
        const limit =
            parseInt(value, 10);


        if (
            ALLOWED_LIMITS.includes(limit)
        ) {
            return limit;
        }


        return DEFAULT_LIMIT;
    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value)
    {
        if (
            value === null ||
            value === undefined
        ) {
            return '';
        }


        return $('<div>')
            .text(String(value))
            .html();
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT DATE
    |--------------------------------------------------------------------------
    */

    function formatDate(value)
    {
        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return '-';
        }


        /*
         * Hindari timezone conversion
         * dengan membuat tanggal lokal.
         */
        const parts =
            String(value).split('-');


        if (parts.length === 3) {

            return (
                parts[2] +
                '/' +
                parts[1] +
                '/' +
                parts[0]
            );
        }


        return escapeHtml(value);
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT NUMBER
    |--------------------------------------------------------------------------
    */

    function formatNumber(value)
    {
        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return '0';
        }


        const number =
            Number(value);


        if (Number.isNaN(number)) {
            return escapeHtml(value);
        }


        return number.toLocaleString(
            'id-ID',
            {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PERIOD LABEL
    |--------------------------------------------------------------------------
    */

    function updatePeriodLabel()
    {
        const start =
            $startDate.val();

        const end =
            $endDate.val();


        if (!start || !end) {

            $periodeLabel.text('-');

            return;
        }


        $periodeLabel.text(
            formatDate(start) +
            ' s/d ' +
            formatDate(end)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE FILTER
    |--------------------------------------------------------------------------
    */

    function validateFilter()
    {
        const start =
            $startDate.val();

        const end =
            $endDate.val();


        if (!start) {

            showError(
                'Tanggal mulai wajib diisi.'
            );

            $startDate.focus();

            return false;
        }


        if (!end) {

            showError(
                'Tanggal akhir wajib diisi.'
            );

            $endDate.focus();

            return false;
        }


        if (start > end) {

            showError(
                'Tanggal mulai tidak boleh lebih besar ' +
                'dari tanggal akhir.'
            );

            $startDate.focus();

            return false;
        }


        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW ERROR
    |--------------------------------------------------------------------------
    */

    function showError(message)
    {
        $errorMessage.text(
            message || 'Terjadi kesalahan.'
        );

        $error.removeClass('d-none');
    }


    /*
    |--------------------------------------------------------------------------
    | HIDE ERROR
    |--------------------------------------------------------------------------
    */

    function hideError()
    {
        $error
            .addClass('d-none');

        $errorMessage.text('');
    }


    /*
    |--------------------------------------------------------------------------
    | SET LOADING
    |--------------------------------------------------------------------------
    */

    function setLoading(status)
    {
        isLoading = status;


        if (status) {

            $loading.removeClass('d-none');


            $btnFilter
                .prop('disabled', true)
                .html(
                    '<span class="spinner-border ' +
                    'spinner-border-sm me-1" ' +
                    'role="status"></span>' +
                    'Memuat...'
                );


            $btnReset
                .prop('disabled', true);


            $limit
                .prop('disabled', true);


            /*
             * Pagination dinonaktifkan selama AJAX.
             */
            $btnPrevious
                .prop('disabled', true);


            $btnNext
                .prop('disabled', true);

        } else {

            $loading.addClass('d-none');


            $btnFilter
                .prop('disabled', false)
                .html(
                    '<i class="bi bi-search me-1"></i>' +
                    'Tampilkan'
                );


            $btnReset
                .prop('disabled', false);


            $limit
                .prop('disabled', false);


            updatePagination();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW EMPTY DATA
    |--------------------------------------------------------------------------
    */

    function showEmptyData()
    {
        $tableBody.html(`

            <tr class="empty-row">

                <td
                    colspan="21"
                    class="text-center text-muted"
                >

                    <div class="py-5">

                        <i
                            class="bi bi-inbox fs-1 d-block mb-3"
                        ></i>

                        <div class="fw-semibold mb-1">
                            Tidak ada data LPLPO
                        </div>

                        <div class="small">
                            Tidak ditemukan data pada
                            periode yang dipilih.
                        </div>

                    </div>

                </td>

            </tr>

        `);
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW LOADING ROW
    |--------------------------------------------------------------------------
    */

    function showLoadingRow()
    {
        $tableBody.html(`

            <tr>

                <td
                    colspan="21"
                    class="text-center py-5"
                >

                    <div
                        class="spinner-border text-primary"
                        role="status"
                    ></div>

                    <div class="small text-muted mt-2">
                        Memuat data LPLPO...
                    </div>

                </td>

            </tr>

        `);
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER TABLE
    |--------------------------------------------------------------------------
    */

    function renderTable(data)
    {
        $tableBody.empty();


        if (
            !Array.isArray(data) ||
            data.length === 0
        ) {

            showEmptyData();

            return;
        }


        let html = '';


        data.forEach(function (row, index) {

            const no =
                (
                    (currentPage - 1) *
                    currentLimit
                ) +
                index +
                1;


            html += `

                <tr>

                    <!-- NO -->
                    <td class="text-center">
                        ${no}
                    </td>


                    <!-- KODE SARANA -->
                    <td>
                        ${escapeHtml(
                            row.smiley_kode_sarana
                        )}
                    </td>


                    <!-- NAMA SARANA -->
                    <td>
                        ${escapeHtml(
                            row.smiley_nama_fasyankes
                        )}
                    </td>


                    <!-- NAMA PUSKESMAS -->
                    <td>
                        ${escapeHtml(
                            row.nama_pkm
                        )}
                    </td>


                    <!-- TANGGAL -->
                    <td>
                        ${formatDate(
                            row.tanggal
                        )}
                    </td>


                    <!-- NOMOR LPLPO -->
                    <td>
                        ${escapeHtml(
                            row.nomor_lplpo
                        )}
                    </td>


                    <!-- KODE KFA -->
                    <td>
                        ${escapeHtml(
                            row.kode_obat_kfa
                        )}
                    </td>


                    <!-- NAMA OBAT -->
                    <td>
                        ${escapeHtml(
                            row.nama_obat
                        )}
                    </td>


                    <!-- SATUAN -->
                    <td>
                        ${escapeHtml(
                            row.satuan
                        )}
                    </td>


                    <!-- STOK AWAL RUTIN -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.stok_awal_rutin
                        )}
                    </td>


                    <!-- STOK AWAL PROGRAM -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.stok_awal_program
                        )}
                    </td>


                    <!-- STOK AWAL JKN -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.stok_awal_jkn
                        )}
                    </td>


                    <!-- PENERIMAAN RUTIN -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.penerimaan_rutin_pkd
                        )}
                    </td>


                    <!-- PENERIMAAN PROGRAM -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.penerimaan_program
                        )}
                    </td>


                    <!-- PENERIMAAN JKN -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.penerimaan_jkn
                        )}
                    </td>


                    <!-- PENGGUNAAN RUTIN -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.penggunaan_rutin
                        )}
                    </td>


                    <!-- PENGGUNAAN PROGRAM -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.penggunaan_program
                        )}
                    </td>


                    <!-- PENGGUNAAN JKN -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.penggunaan_jkn
                        )}
                    </td>


                    <!-- EXPIRED DATE -->
                    <td>
                        ${formatDate(
                            row.expired_date
                        )}
                    </td>


                    <!-- STOK OPTIMUM -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.stok_optimum
                        )}
                    </td>


                    <!-- PERMINTAAN -->
                    <td class="number-cell">
                        ${formatNumber(
                            row.permintaan
                        )}
                    </td>

                </tr>

            `;
        });


        $tableBody.html(html);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PAGINATION
    |--------------------------------------------------------------------------
    */

    function updatePagination()
    {
        $pageInfo.text(
            'Halaman ' +
            currentPage +
            ' • ' +
            currentLimit +
            ' data per halaman'
        );


        if (isLoading) {
            return;
        }


        $btnPrevious.prop(
            'disabled',
            currentPage <= 1
        );


        $btnNext.prop(
            'disabled',
            !hasNext
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD DATA
    |--------------------------------------------------------------------------
    */

    function loadData(page)
    {
        page = parseInt(page, 10) || 1;


        if (page < 1) {
            page = 1;
        }


        if (!validateFilter()) {
            return;
        }


        /*
         * Jangan menjalankan request kedua
         * ketika request sebelumnya masih berjalan.
         */
        if (isLoading) {
            return;
        }


        hideError();


        currentPage = page;


        currentLimit =
            normalizeLimit(
                $limit.val()
            );


        $limit.val(
            String(currentLimit)
        );


        setLoading(true);

        showLoadingRow();


        $.ajax({

            url: DATA_URL,

            method: 'GET',

            dataType: 'json',

            data: {

                page: currentPage,

                limit: currentLimit,

                start_date:
                    $startDate.val(),

                end_date:
                    $endDate.val()

            },


            success: function (response) {

                if (
                    !response ||
                    response.success !== true
                ) {

                    showError(
                        response &&
                        response.message
                            ? response.message
                            : 'Gagal mengambil data LPLPO.'
                    );


                    showEmptyData();

                    hasNext = false;

                    return;
                }


                /*
                 * Ambil informasi pagination
                 * dari response controller.
                 */
                currentPage =
                    parseInt(
                        response.page ||
                        page,
                        10
                    );


                currentLimit =
                    normalizeLimit(
                        response.limit ||
                        currentLimit
                    );


                hasNext =
                    response.hasNext === true;


                /*
                 * Sinkronkan combo limit.
                 */
                $limit.val(
                    String(currentLimit)
                );


                renderTable(
                    response.data || []
                );


                updatePagination();

            },


            error: function (xhr) {

                let message =
                    'Tidak dapat mengambil data LPLPO.';


                /*
                 * Validation error 422
                 */
                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                /*
                 * HTTP 502 dari controller
                 */
                if (
                    xhr.status === 502 &&
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;
                }


                /*
                 * HTTP 500
                 */
                if (xhr.status === 500) {

                    message =
                        'Terjadi kesalahan pada server. ' +
                        'Silakan coba lagi.';

                }


                showError(message);

                showEmptyData();

                hasNext = false;

            },


            complete: function () {

                setLoading(false);

                updatePagination();

            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER SUBMIT
    |--------------------------------------------------------------------------
    */

    $form.on(
        'submit',
        function (event) {

            event.preventDefault();


            if (!validateFilter()) {
                return;
            }


            currentPage = 1;

            hasNext = false;


            currentLimit =
                normalizeLimit(
                    $limit.val()
                );


            updatePeriodLabel();


            loadData(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | LIMIT CHANGE
    |--------------------------------------------------------------------------
    */

    $limit.on(
        'change',
        function () {

            const newLimit =
                normalizeLimit(
                    $(this).val()
                );


            /*
             * Jika limit berubah,
             * selalu kembali ke halaman 1.
             */
            currentLimit = newLimit;

            currentPage = 1;

            hasNext = false;


            $limit.val(
                String(currentLimit)
            );


            loadData(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    $btnReset.on(
        'click',
        function () {

            if (isLoading) {
                return;
            }


            $startDate.val(
                DEFAULT_START_DATE
            );


            $endDate.val(
                DEFAULT_END_DATE
            );


            $limit.val(
                String(DEFAULT_LIMIT)
            );


            currentLimit =
                DEFAULT_LIMIT;

            currentPage = 1;

            hasNext = false;


            hideError();

            updatePeriodLabel();


            loadData(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PREVIOUS
    |--------------------------------------------------------------------------
    */

    $btnPrevious.on(
        'click',
        function () {

            if (isLoading) {
                return;
            }


            if (currentPage <= 1) {
                return;
            }


            loadData(
                currentPage - 1
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | NEXT
    |--------------------------------------------------------------------------
    */

    $btnNext.on(
        'click',
        function () {

            if (isLoading) {
                return;
            }


            if (!hasNext) {
                return;
            }


            loadData(
                currentPage + 1
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL STATE
    |--------------------------------------------------------------------------
    */

    updatePeriodLabel();

    updatePagination();


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    loadData(1);

});
