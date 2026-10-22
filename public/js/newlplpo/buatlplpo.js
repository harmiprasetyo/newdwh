/******/ (() => { // webpackBootstrap
/*!********************************************!*\
  !*** ./resources/js/newlplpo/buatlplpo.js ***!
  \********************************************/
(function ($) {
  'use strict';

  var config = window.LplpoConfig || {};

  /*
  |--------------------------------------------------------------------------
  | DOCUMENT READY
  |--------------------------------------------------------------------------
  */

  $(function () {
    console.log('BUATLPLPO.JS BERHASIL DIMUAT');
    initSubmitLaporan();
    initHeaderEdit();
  });

  /*
  |--------------------------------------------------------------------------
  | SUBMIT LAPORAN
  |--------------------------------------------------------------------------
  */

  function initSubmitLaporan() {
    $(document).on('click', '#btnSubmitLaporan', function (e) {
      e.preventDefault();
      var button = $(this);
      if (button.prop('disabled')) {
        return;
      }
      Swal.fire({
        title: 'Submit Laporan?',
        text: 'Setelah disubmit laporan tidak dapat diubah lagi.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Submit',
        cancelButtonText: 'Batal'
      }).then(function (result) {
        if (!result.isConfirmed) {
          return;
        }
        submitLaporan(button);
      });
    });
  }
  function submitLaporan(button) {
    var _config$routes;
    if (!((_config$routes = config.routes) !== null && _config$routes !== void 0 && _config$routes.reportUpdate)) {
      Swal.fire('Error', 'URL update laporan tidak tersedia.', 'error');
      return;
    }
    var originalHtml = button.html();
    $.ajax({
      url: config.routes.reportUpdate,
      type: 'PUT',
      data: {
        _token: config.csrfToken,
        report_status: 'SUBMITED'
      },
      beforeSend: function beforeSend() {
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>' + 'Mengirim...');
      },
      success: function success(response) {
        console.log('SUBMIT RESPONSE:', response);
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: response.message || 'Laporan berhasil disubmit.',
          timer: 1500,
          showConfirmButton: false
        }).then(function () {
          window.location.reload();
        });
      },
      error: function error(xhr) {
        console.error('SUBMIT ERROR:', xhr.responseText);
        button.prop('disabled', false).html(originalHtml);
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: getErrorMessage(xhr, 'Gagal submit laporan.')
        });
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | EDIT HEADER
  |--------------------------------------------------------------------------
  */

  function initHeaderEdit() {
    $(document).on('click', '#btnEditHeader', function (e) {
      e.preventDefault();
      var button = $(this);
      $('select[name="bulan"]').prop('disabled', false);
      $('input[name="tahun"]').prop('readonly', false);
      button.removeClass('btn-warning').addClass('btn-success').html('<i class="bi bi-check-circle me-1"></i>' + 'Simpan Header').attr('id', 'btnSaveHeader');
    });
    $(document).on('click', '#btnSaveHeader', function (e) {
      e.preventDefault();
      saveHeader($(this));
    });
  }

  /*
  |--------------------------------------------------------------------------
  | SAVE HEADER
  |--------------------------------------------------------------------------
  */

  function saveHeader(button) {
    var _config$routes2;
    if (!((_config$routes2 = config.routes) !== null && _config$routes2 !== void 0 && _config$routes2.reportUpdate)) {
      Swal.fire('Error', 'URL update laporan tidak tersedia.', 'error');
      return;
    }
    var bulan = $('select[name="bulan"]').val();
    var tahun = $('input[name="tahun"]').val();
    var originalHtml = button.html();
    $.ajax({
      url: config.routes.reportUpdate,
      type: 'PUT',
      data: {
        _token: config.csrfToken,
        bulan: bulan,
        tahun: tahun
      },
      beforeSend: function beforeSend() {
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>' + 'Menyimpan...');
      },
      success: function success(response) {
        console.log('HEADER UPDATE:', response);
        $('select[name="bulan"]').prop('disabled', true);
        $('input[name="tahun"]').prop('readonly', true);
        button.prop('disabled', false).removeClass('btn-success').addClass('btn-warning').html('<i class="bi bi-pencil-square me-1"></i>' + 'Edit Header').attr('id', 'btnEditHeader');
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: response.message || 'Header berhasil diperbarui.',
          timer: 1500,
          showConfirmButton: false
        });
      },
      error: function error(xhr) {
        console.error('HEADER UPDATE ERROR:', xhr.responseText);
        button.prop('disabled', false).html(originalHtml);
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          html: getErrorMessage(xhr, 'Gagal menyimpan perubahan header.')
        });
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | ERROR MESSAGE
  |--------------------------------------------------------------------------
  */

  function getErrorMessage(xhr, fallback) {
    if (xhr.responseJSON && xhr.responseJSON.message) {
      return xhr.responseJSON.message;
    }
    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
      return Object.values(xhr.responseJSON.errors).flat().join('<br>');
    }
    return fallback;
  }
})(jQuery);
/******/ })()
;