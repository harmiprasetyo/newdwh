/******/ (() => { // webpackBootstrap
/*!***********************************************!*\
  !*** ./resources/js/newlplpo/bekasi/lplpo.js ***!
  \***********************************************/
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
  var config = window.LplpoBekasiConfig;
  if (!config) {
    console.error('LplpoBekasiConfig tidak ditemukan.');
    return;
  }
  var DATA_URL = config.dataUrl;
  var DEFAULT_START_DATE = config.defaultStartDate;
  var DEFAULT_END_DATE = config.defaultEndDate;
  var DEFAULT_LIMIT = parseInt(config.defaultLimit || 50, 10);
  var ALLOWED_LIMITS = Array.isArray(config.allowedLimits) ? config.allowedLimits.map(function (value) {
    return parseInt(value, 10);
  }) : [25, 50, 100];

  /*
  |--------------------------------------------------------------------------
  | STATE
  |--------------------------------------------------------------------------
  */

  var currentPage = 1;
  var currentLimit = DEFAULT_LIMIT;
  var hasNext = false;
  var isLoading = false;

  /*
  |--------------------------------------------------------------------------
  | ELEMENTS
  |--------------------------------------------------------------------------
  */

  var $form = $('#formFilter');
  var $startDate = $('#start_date');
  var $endDate = $('#end_date');
  var $limit = $('#limit');
  var $tableBody = $('#tableBody');
  var $btnFilter = $('#btnFilter');
  var $btnReset = $('#btnReset');
  var $btnPrevious = $('#btnPrevious');
  var $btnNext = $('#btnNext');
  var $pageInfo = $('#pageInfo');
  var $loading = $('#loadingIndicator');
  var $error = $('#errorContainer');
  var $errorMessage = $('#errorMessage');
  var $periodeLabel = $('#periodeLabel');

  /*
  |--------------------------------------------------------------------------
  | INITIALIZE LIMIT
  |--------------------------------------------------------------------------
  */

  currentLimit = normalizeLimit($limit.val());
  $limit.val(String(currentLimit));

  /*
  |--------------------------------------------------------------------------
  | NORMALIZE LIMIT
  |--------------------------------------------------------------------------
  */

  function normalizeLimit(value) {
    var limit = parseInt(value, 10);
    if (ALLOWED_LIMITS.includes(limit)) {
      return limit;
    }
    return DEFAULT_LIMIT;
  }

  /*
  |--------------------------------------------------------------------------
  | ESCAPE HTML
  |--------------------------------------------------------------------------
  */

  function escapeHtml(value) {
    if (value === null || value === undefined) {
      return '';
    }
    return $('<div>').text(String(value)).html();
  }

  /*
  |--------------------------------------------------------------------------
  | FORMAT DATE
  |--------------------------------------------------------------------------
  */

  function formatDate(value) {
    if (value === null || value === undefined || value === '') {
      return '-';
    }

    /*
     * Hindari timezone conversion
     * dengan membuat tanggal lokal.
     */
    var parts = String(value).split('-');
    if (parts.length === 3) {
      return parts[2] + '/' + parts[1] + '/' + parts[0];
    }
    return escapeHtml(value);
  }

  /*
  |--------------------------------------------------------------------------
  | FORMAT NUMBER
  |--------------------------------------------------------------------------
  */

  function formatNumber(value) {
    if (value === null || value === undefined || value === '') {
      return '0';
    }
    var number = Number(value);
    if (Number.isNaN(number)) {
      return escapeHtml(value);
    }
    return number.toLocaleString('id-ID', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 2
    });
  }

  /*
  |--------------------------------------------------------------------------
  | UPDATE PERIOD LABEL
  |--------------------------------------------------------------------------
  */

  function updatePeriodLabel() {
    var start = $startDate.val();
    var end = $endDate.val();
    if (!start || !end) {
      $periodeLabel.text('-');
      return;
    }
    $periodeLabel.text(formatDate(start) + ' s/d ' + formatDate(end));
  }

  /*
  |--------------------------------------------------------------------------
  | VALIDATE FILTER
  |--------------------------------------------------------------------------
  */

  function validateFilter() {
    var start = $startDate.val();
    var end = $endDate.val();
    if (!start) {
      showError('Tanggal mulai wajib diisi.');
      $startDate.focus();
      return false;
    }
    if (!end) {
      showError('Tanggal akhir wajib diisi.');
      $endDate.focus();
      return false;
    }
    if (start > end) {
      showError('Tanggal mulai tidak boleh lebih besar ' + 'dari tanggal akhir.');
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

  function showError(message) {
    $errorMessage.text(message || 'Terjadi kesalahan.');
    $error.removeClass('d-none');
  }

  /*
  |--------------------------------------------------------------------------
  | HIDE ERROR
  |--------------------------------------------------------------------------
  */

  function hideError() {
    $error.addClass('d-none');
    $errorMessage.text('');
  }

  /*
  |--------------------------------------------------------------------------
  | SET LOADING
  |--------------------------------------------------------------------------
  */

  function setLoading(status) {
    isLoading = status;
    if (status) {
      $loading.removeClass('d-none');
      $btnFilter.prop('disabled', true).html('<span class="spinner-border ' + 'spinner-border-sm me-1" ' + 'role="status"></span>' + 'Memuat...');
      $btnReset.prop('disabled', true);
      $limit.prop('disabled', true);

      /*
       * Pagination dinonaktifkan selama AJAX.
       */
      $btnPrevious.prop('disabled', true);
      $btnNext.prop('disabled', true);
    } else {
      $loading.addClass('d-none');
      $btnFilter.prop('disabled', false).html('<i class="bi bi-search me-1"></i>' + 'Tampilkan');
      $btnReset.prop('disabled', false);
      $limit.prop('disabled', false);
      updatePagination();
    }
  }

  /*
  |--------------------------------------------------------------------------
  | SHOW EMPTY DATA
  |--------------------------------------------------------------------------
  */

  function showEmptyData() {
    $tableBody.html("\n\n            <tr class=\"empty-row\">\n\n                <td\n                    colspan=\"21\"\n                    class=\"text-center text-muted\"\n                >\n\n                    <div class=\"py-5\">\n\n                        <i\n                            class=\"bi bi-inbox fs-1 d-block mb-3\"\n                        ></i>\n\n                        <div class=\"fw-semibold mb-1\">\n                            Tidak ada data LPLPO\n                        </div>\n\n                        <div class=\"small\">\n                            Tidak ditemukan data pada\n                            periode yang dipilih.\n                        </div>\n\n                    </div>\n\n                </td>\n\n            </tr>\n\n        ");
  }

  /*
  |--------------------------------------------------------------------------
  | SHOW LOADING ROW
  |--------------------------------------------------------------------------
  */

  function showLoadingRow() {
    $tableBody.html("\n\n            <tr>\n\n                <td\n                    colspan=\"21\"\n                    class=\"text-center py-5\"\n                >\n\n                    <div\n                        class=\"spinner-border text-primary\"\n                        role=\"status\"\n                    ></div>\n\n                    <div class=\"small text-muted mt-2\">\n                        Memuat data LPLPO...\n                    </div>\n\n                </td>\n\n            </tr>\n\n        ");
  }

  /*
  |--------------------------------------------------------------------------
  | RENDER TABLE
  |--------------------------------------------------------------------------
  */

  function renderTable(data) {
    $tableBody.empty();
    if (!Array.isArray(data) || data.length === 0) {
      showEmptyData();
      return;
    }
    var html = '';
    data.forEach(function (row, index) {
      var no = (currentPage - 1) * currentLimit + index + 1;
      html += "\n\n                <tr>\n\n                    <!-- NO -->\n                    <td class=\"text-center\">\n                        ".concat(no, "\n                    </td>\n\n\n                    <!-- KODE SARANA -->\n                    <td>\n                        ").concat(escapeHtml(row.smiley_kode_sarana), "\n                    </td>\n\n\n                    <!-- NAMA SARANA -->\n                    <td>\n                        ").concat(escapeHtml(row.smiley_nama_fasyankes), "\n                    </td>\n\n\n                    <!-- NAMA PUSKESMAS -->\n                    <td>\n                        ").concat(escapeHtml(row.nama_pkm), "\n                    </td>\n\n\n                    <!-- TANGGAL -->\n                    <td>\n                        ").concat(formatDate(row.tanggal), "\n                    </td>\n\n\n                    <!-- NOMOR LPLPO -->\n                    <td>\n                        ").concat(escapeHtml(row.nomor_lplpo), "\n                    </td>\n\n\n                    <!-- KODE KFA -->\n                    <td>\n                        ").concat(escapeHtml(row.kode_obat_kfa), "\n                    </td>\n\n\n                    <!-- NAMA OBAT -->\n                    <td>\n                        ").concat(escapeHtml(row.nama_obat), "\n                    </td>\n\n\n                    <!-- SATUAN -->\n                    <td>\n                        ").concat(escapeHtml(row.satuan), "\n                    </td>\n\n\n                    <!-- STOK AWAL RUTIN -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.stok_awal_rutin), "\n                    </td>\n\n\n                    <!-- STOK AWAL PROGRAM -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.stok_awal_program), "\n                    </td>\n\n\n                    <!-- STOK AWAL JKN -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.stok_awal_jkn), "\n                    </td>\n\n\n                    <!-- PENERIMAAN RUTIN -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.penerimaan_rutin_pkd), "\n                    </td>\n\n\n                    <!-- PENERIMAAN PROGRAM -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.penerimaan_program), "\n                    </td>\n\n\n                    <!-- PENERIMAAN JKN -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.penerimaan_jkn), "\n                    </td>\n\n\n                    <!-- PENGGUNAAN RUTIN -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.penggunaan_rutin), "\n                    </td>\n\n\n                    <!-- PENGGUNAAN PROGRAM -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.penggunaan_program), "\n                    </td>\n\n\n                    <!-- PENGGUNAAN JKN -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.penggunaan_jkn), "\n                    </td>\n\n\n                    <!-- EXPIRED DATE -->\n                    <td>\n                        ").concat(formatDate(row.expired_date), "\n                    </td>\n\n\n                    <!-- STOK OPTIMUM -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.stok_optimum), "\n                    </td>\n\n\n                    <!-- PERMINTAAN -->\n                    <td class=\"number-cell\">\n                        ").concat(formatNumber(row.permintaan), "\n                    </td>\n\n                </tr>\n\n            ");
    });
    $tableBody.html(html);
  }

  /*
  |--------------------------------------------------------------------------
  | UPDATE PAGINATION
  |--------------------------------------------------------------------------
  */

  function updatePagination() {
    $pageInfo.text('Halaman ' + currentPage + ' • ' + currentLimit + ' data per halaman');
    if (isLoading) {
      return;
    }
    $btnPrevious.prop('disabled', currentPage <= 1);
    $btnNext.prop('disabled', !hasNext);
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD DATA
  |--------------------------------------------------------------------------
  */

  function loadData(page) {
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
    currentLimit = normalizeLimit($limit.val());
    $limit.val(String(currentLimit));
    setLoading(true);
    showLoadingRow();
    $.ajax({
      url: DATA_URL,
      method: 'GET',
      dataType: 'json',
      data: {
        page: currentPage,
        limit: currentLimit,
        start_date: $startDate.val(),
        end_date: $endDate.val()
      },
      success: function success(response) {
        if (!response || response.success !== true) {
          showError(response && response.message ? response.message : 'Gagal mengambil data LPLPO.');
          showEmptyData();
          hasNext = false;
          return;
        }

        /*
         * Ambil informasi pagination
         * dari response controller.
         */
        currentPage = parseInt(response.page || page, 10);
        currentLimit = normalizeLimit(response.limit || currentLimit);
        hasNext = response.hasNext === true;

        /*
         * Sinkronkan combo limit.
         */
        $limit.val(String(currentLimit));
        renderTable(response.data || []);
        updatePagination();
      },
      error: function error(xhr) {
        var message = 'Tidak dapat mengambil data LPLPO.';

        /*
         * Validation error 422
         */
        if (xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        }

        /*
         * HTTP 502 dari controller
         */
        if (xhr.status === 502 && xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        }

        /*
         * HTTP 500
         */
        if (xhr.status === 500) {
          message = 'Terjadi kesalahan pada server. ' + 'Silakan coba lagi.';
        }
        showError(message);
        showEmptyData();
        hasNext = false;
      },
      complete: function complete() {
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

  $form.on('submit', function (event) {
    event.preventDefault();
    if (!validateFilter()) {
      return;
    }
    currentPage = 1;
    hasNext = false;
    currentLimit = normalizeLimit($limit.val());
    updatePeriodLabel();
    loadData(1);
  });

  /*
  |--------------------------------------------------------------------------
  | LIMIT CHANGE
  |--------------------------------------------------------------------------
  */

  $limit.on('change', function () {
    var newLimit = normalizeLimit($(this).val());

    /*
     * Jika limit berubah,
     * selalu kembali ke halaman 1.
     */
    currentLimit = newLimit;
    currentPage = 1;
    hasNext = false;
    $limit.val(String(currentLimit));
    loadData(1);
  });

  /*
  |--------------------------------------------------------------------------
  | RESET
  |--------------------------------------------------------------------------
  */

  $btnReset.on('click', function () {
    if (isLoading) {
      return;
    }
    $startDate.val(DEFAULT_START_DATE);
    $endDate.val(DEFAULT_END_DATE);
    $limit.val(String(DEFAULT_LIMIT));
    currentLimit = DEFAULT_LIMIT;
    currentPage = 1;
    hasNext = false;
    hideError();
    updatePeriodLabel();
    loadData(1);
  });

  /*
  |--------------------------------------------------------------------------
  | PREVIOUS
  |--------------------------------------------------------------------------
  */

  $btnPrevious.on('click', function () {
    if (isLoading) {
      return;
    }
    if (currentPage <= 1) {
      return;
    }
    loadData(currentPage - 1);
  });

  /*
  |--------------------------------------------------------------------------
  | NEXT
  |--------------------------------------------------------------------------
  */

  $btnNext.on('click', function () {
    if (isLoading) {
      return;
    }
    if (!hasNext) {
      return;
    }
    loadData(currentPage + 1);
  });

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
/******/ })()
;