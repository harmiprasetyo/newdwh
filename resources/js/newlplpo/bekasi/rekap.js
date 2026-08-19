$(document).ready(function () {

    let isLoading = false;

    const config = window.LplpoBekasiRekapConfig;

    const $form = $('#formRekapFilter');
    const $startDate = $('#start_date');
    const $endDate = $('#end_date');

    const $tableBody = $('#tableBody');

    const $btnFilter = $('#btnFilter');
    const $btnReset = $('#btnReset');

    const $errorContainer = $('#errorContainer');
    const $errorMessage = $('#errorMessage');

    const $periodeLabel = $('#periodeLabel');


    // =========================================================
    // FORMAT ANGKA
    // =========================================================

    function formatNumber(value) {

        let number = parseFloat(value);

        if (isNaN(number)) {
            number = 0;
        }

        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(number);
    }


    // =========================================================
    // FORMAT TANGGAL
    // =========================================================

    function formatDate(dateString) {

        if (!dateString) {
            return '-';
        }

        const parts = dateString.split('-');

        if (parts.length !== 3) {
            return dateString;
        }

        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }


    // =========================================================
    // ERROR
    // =========================================================

    function showError(message) {

        $errorMessage.text(message);

        $errorContainer.removeClass('d-none');
    }


    function hideError() {

        $errorContainer.addClass('d-none');

        $errorMessage.text('');
    }


    // =========================================================
    // LOADING
    // =========================================================

    function showLoading() {

        $tableBody.html(`
            <tr>
                <td colspan="15" class="text-center py-5">

                    <div class="spinner-border text-primary"
                         role="status">

                        <span class="visually-hidden">
                            Loading...
                        </span>

                    </div>

                    <div class="mt-2 text-muted">
                        Mengambil dan merekap data LPLPO...
                    </div>

                </td>
            </tr>
        `);

        $btnFilter
            .prop('disabled', true)
            .html(`
                <span
                    class="spinner-border spinner-border-sm me-1"
                    role="status"
                ></span>
                Memproses...
            `);

        isLoading = true;
    }


    function hideLoading() {

        $btnFilter
            .prop('disabled', false)
            .html(`
                <i class="bi bi-search me-1"></i>
                Tampilkan
            `);

        isLoading = false;
    }


    // =========================================================
    // VALIDASI
    // =========================================================

    function validateFilter() {

        const startDate = $startDate.val();

        const endDate = $endDate.val();


        if (!startDate) {

            showError('Tanggal mulai wajib diisi.');

            $startDate.focus();

            return false;
        }


        if (!endDate) {

            showError('Tanggal akhir wajib diisi.');

            $endDate.focus();

            return false;
        }


        if (startDate > endDate) {

            showError(
                'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.'
            );

            $startDate.focus();

            return false;
        }


        return true;
    }


    // =========================================================
    // UPDATE LABEL PERIODE
    // =========================================================

    function updatePeriodLabel() {

        const startDate = $startDate.val();

        const endDate = $endDate.val();


        $periodeLabel.html(`
            ${formatDate(startDate)}
            -
            ${formatDate(endDate)}
        `);
    }


    // =========================================================
    // LOAD DATA
    // =========================================================

    function loadData() {

        if (isLoading) {
            return;
        }


        if (!validateFilter()) {
            return;
        }


        hideError();

        showLoading();


        const startDate = $startDate.val();

        const endDate = $endDate.val();


        $.ajax({

            url: config.dataUrl,

            method: 'GET',

            data: {

                start_date: startDate,

                end_date: endDate

            },

            dataType: 'json',

            success: function (response) {

                hideLoading();


                if (!response.success) {

                    showError(
                        response.message
                        || 'Gagal mengambil data LPLPO.'
                    );

                    renderEmpty();

                    return;
                }


                updatePeriodLabel();


                renderTable(
                    response.data || []
                );

            },


            error: function (xhr) {

                hideLoading();

                console.error(
                    'LPLPO Rekap Error:',
                    xhr
                );


                let message =
                    'Terjadi kesalahan saat mengambil data LPLPO.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                showError(message);

                renderEmpty();

            }

        });

    }


    // =========================================================
    // RENDER EMPTY
    // =========================================================

    function renderEmpty() {

        $tableBody.html(`
            <tr>

                <td
                    colspan="15"
                    class="text-center py-5 text-muted"
                >

                    <i
                        class="bi bi-inbox"
                        style="font-size: 35px;"
                    ></i>

                    <div class="mt-2">
                        Tidak ada data LPLPO.
                    </div>

                </td>

            </tr>
        `);

    }


    // =========================================================
    // RENDER TABLE
    // =========================================================

    function renderTable(data) {

        if (!data || data.length === 0) {

            renderEmpty();

            return;
        }


        let html = '';


        data.forEach(function (row, index) {

            html += `

                <tr>

                    <td class="text-center">
                        ${index + 1}
                    </td>


                    <td>
                        ${escapeHtml(row.nama_pkm)}
                    </td>


                    <td>
                        ${escapeHtml(row.kode_obat_kfa)}
                    </td>


                    <td>
                        ${escapeHtml(row.nama_obat)}
                    </td>


                    <td class="text-center">
                        ${escapeHtml(row.satuan)}
                    </td>


                    <!-- STOK AWAL -->

                    <td class="text-end">
                        ${formatNumber(row.stok_awal_rutin)}
                    </td>

                    <td class="text-end">
                        ${formatNumber(row.stok_awal_program)}
                    </td>

                    <td class="text-end">
                        ${formatNumber(row.stok_awal_jkn)}
                    </td>


                    <!-- PENERIMAAN -->

                    <td class="text-end">
                        ${formatNumber(row.penerimaan_rutin_pkd)}
                    </td>

                    <td class="text-end">
                        ${formatNumber(row.penerimaan_program)}
                    </td>

                    <td class="text-end">
                        ${formatNumber(row.penerimaan_jkn)}
                    </td>


                    <!-- PENGGUNAAN -->

                    <td class="text-end">
                        ${formatNumber(row.penggunaan_rutin)}
                    </td>

                    <td class="text-end">
                        ${formatNumber(row.penggunaan_program)}
                    </td>

                    <td class="text-end">
                        ${formatNumber(row.penggunaan_jkn)}
                    </td>

                    <!-- STOK AKHIR -->

<td class="text-end fw-semibold">
    ${formatNumber(row.stok_akhir_rutin)}
</td>

<td class="text-end fw-semibold">
    ${formatNumber(row.stok_akhir_program)}
</td>

<td class="text-end fw-semibold">
    ${formatNumber(row.stok_akhir_jkn)}
</td>
<td class="text-end fw-bold">
    ${formatNumber(row.stok_akhir)}
</td>


                    <td class="text-end">
                        ${formatNumber(row.stok_optimum)}
                    </td>


                    <td class="text-end">
                        ${formatNumber(row.permintaan)}
                    </td>

                </tr>

            `;

        });


        $tableBody.html(html);

    }


    // =========================================================
    // ESCAPE HTML
    // =========================================================

    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {

            return '-';

        }


        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    // =========================================================
    // SUBMIT FILTER
    // =========================================================

    $form.on('submit', function (e) {

        e.preventDefault();

        loadData();

    });


    // =========================================================
    // RESET
    // =========================================================

    $btnReset.on('click', function () {

        $startDate.val(
            config.defaultStartDate
        );

        $endDate.val(
            config.defaultEndDate
        );

        hideError();

        updatePeriodLabel();

        loadData();

    });


    // =========================================================
    // INITIAL LOAD
    // =========================================================

    updatePeriodLabel();

    loadData();

});