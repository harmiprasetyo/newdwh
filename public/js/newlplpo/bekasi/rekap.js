/******/ (() => { // webpackBootstrap
/*!***********************************************!*\
  !*** ./resources/js/newlplpo/bekasi/rekap.js ***!
  \***********************************************/
$(document).ready(function () {
  var isLoading = false;
  var config = window.LplpoBekasiRekapConfig;
  var $form = $('#formRekapFilter');
  var $startDate = $('#start_date');
  var $endDate = $('#end_date');
  var $tableBody = $('#tableBody');
  var $btnFilter = $('#btnFilter');
  var $btnReset = $('#btnReset');
  var $errorContainer = $('#errorContainer');
  var $errorMessage = $('#errorMessage');
  var $periodeLabel = $('#periodeLabel');

  // =========================================================
  // FORMAT ANGKA
  // =========================================================

  function formatNumber(value) {
    var number = parseFloat(value);
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
    var parts = dateString.split('-');
    if (parts.length !== 3) {
      return dateString;
    }
    return "".concat(parts[2], "/").concat(parts[1], "/").concat(parts[0]);
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
    $tableBody.html("\n            <tr>\n                <td colspan=\"15\" class=\"text-center py-5\">\n\n                    <div class=\"spinner-border text-primary\"\n                         role=\"status\">\n\n                        <span class=\"visually-hidden\">\n                            Loading...\n                        </span>\n\n                    </div>\n\n                    <div class=\"mt-2 text-muted\">\n                        Mengambil dan merekap data LPLPO...\n                    </div>\n\n                </td>\n            </tr>\n        ");
    $btnFilter.prop('disabled', true).html("\n                <span\n                    class=\"spinner-border spinner-border-sm me-1\"\n                    role=\"status\"\n                ></span>\n                Memproses...\n            ");
    isLoading = true;
  }
  function hideLoading() {
    $btnFilter.prop('disabled', false).html("\n                <i class=\"bi bi-search me-1\"></i>\n                Tampilkan\n            ");
    isLoading = false;
  }

  // =========================================================
  // VALIDASI
  // =========================================================

  function validateFilter() {
    var startDate = $startDate.val();
    var endDate = $endDate.val();
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
      showError('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
      $startDate.focus();
      return false;
    }
    return true;
  }

  // =========================================================
  // UPDATE LABEL PERIODE
  // =========================================================

  function updatePeriodLabel() {
    var startDate = $startDate.val();
    var endDate = $endDate.val();
    $periodeLabel.html("\n            ".concat(formatDate(startDate), "\n            -\n            ").concat(formatDate(endDate), "\n        "));
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
    var startDate = $startDate.val();
    var endDate = $endDate.val();
    $.ajax({
      url: config.dataUrl,
      method: 'GET',
      data: {
        start_date: startDate,
        end_date: endDate
      },
      dataType: 'json',
      success: function success(response) {
        hideLoading();
        if (!response.success) {
          showError(response.message || 'Gagal mengambil data LPLPO.');
          renderEmpty();
          return;
        }
        updatePeriodLabel();
        renderTable(response.data || []);
      },
      error: function error(xhr) {
        hideLoading();
        console.error('LPLPO Rekap Error:', xhr);
        var message = 'Terjadi kesalahan saat mengambil data LPLPO.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
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
    $tableBody.html("\n            <tr>\n\n                <td\n                    colspan=\"15\"\n                    class=\"text-center py-5 text-muted\"\n                >\n\n                    <i\n                        class=\"bi bi-inbox\"\n                        style=\"font-size: 35px;\"\n                    ></i>\n\n                    <div class=\"mt-2\">\n                        Tidak ada data LPLPO.\n                    </div>\n\n                </td>\n\n            </tr>\n        ");
  }

  // =========================================================
  // RENDER TABLE
  // =========================================================

  function renderTable(data) {
    if (!data || data.length === 0) {
      renderEmpty();
      return;
    }
    var html = '';
    data.forEach(function (row, index) {
      html += "\n\n                <tr>\n\n                    <td class=\"text-center\">\n                        ".concat(index + 1, "\n                    </td>\n\n\n                    <td>\n                        ").concat(escapeHtml(row.nama_pkm), "\n                    </td>\n\n\n                    <td>\n                        ").concat(escapeHtml(row.kode_obat_kfa), "\n                    </td>\n\n\n                    <td>\n                        ").concat(escapeHtml(row.nama_obat), "\n                    </td>\n\n\n                    <td class=\"text-center\">\n                        ").concat(escapeHtml(row.satuan), "\n                    </td>\n\n\n                    <!-- STOK AWAL -->\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.stok_awal_rutin), "\n                    </td>\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.stok_awal_program), "\n                    </td>\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.stok_awal_jkn), "\n                    </td>\n\n\n                    <!-- PENERIMAAN -->\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.penerimaan_rutin_pkd), "\n                    </td>\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.penerimaan_program), "\n                    </td>\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.penerimaan_jkn), "\n                    </td>\n\n\n                    <!-- PENGGUNAAN -->\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.penggunaan_rutin), "\n                    </td>\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.penggunaan_program), "\n                    </td>\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.penggunaan_jkn), "\n                    </td>\n\n                    <!-- STOK AKHIR -->\n\n<td class=\"text-end fw-semibold\">\n    ").concat(formatNumber(row.stok_akhir_rutin), "\n</td>\n\n<td class=\"text-end fw-semibold\">\n    ").concat(formatNumber(row.stok_akhir_program), "\n</td>\n\n<td class=\"text-end fw-semibold\">\n    ").concat(formatNumber(row.stok_akhir_jkn), "\n</td>\n<td class=\"text-end fw-bold\">\n    ").concat(formatNumber(row.stok_akhir), "\n</td>\n\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.stok_optimum), "\n                    </td>\n\n\n                    <td class=\"text-end\">\n                        ").concat(formatNumber(row.permintaan), "\n                    </td>\n\n                </tr>\n\n            ");
    });
    $tableBody.html(html);
  }

  // =========================================================
  // ESCAPE HTML
  // =========================================================

  function escapeHtml(value) {
    if (value === null || value === undefined) {
      return '-';
    }
    return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
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
    $startDate.val(config.defaultStartDate);
    $endDate.val(config.defaultEndDate);
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
/******/ })()
;