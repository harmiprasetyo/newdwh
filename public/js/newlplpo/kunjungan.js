/******/ (() => { // webpackBootstrap
/*!********************************************!*\
  !*** ./resources/js/newlplpo/kunjungan.js ***!
  \********************************************/
(function ($) {
  'use strict';

  /**
   * ==========================================================
   * HITUNG KUNJUNGAN
   * ==========================================================
   */
  function hitungKunjungan() {
    var jkn = parseInt($('#kunjungan_jkn').val(), 10) || 0;
    var tunai = parseInt($('#kunjungan_tunai').val(), 10) || 0;
    var gratis = parseInt($('#kunjungan_gratis').val(), 10) || 0;
    var anak = parseInt($('#kunjungan_anak').val(), 10) || 0;
    var dewasa = parseInt($('#kunjungan_dewasa').val(), 10) || 0;
    var totalKategori = jkn + tunai + gratis;
    var totalGender = anak + dewasa;

    /*
     * Tampilkan total kategori
     */
    $('#total_kunjungan_perkategori').val(totalKategori);

    /*
     * Tampilkan total gender
     */
    $('#total_kunjungan_pergender').val(totalGender);

    /*
     * Validasi kesamaan total
     */
    if (totalKategori !== totalGender) {
      $('#warningTotal').removeClass('d-none');
      $('#btnSimpanKunjungan').prop('disabled', true);
    } else {
      $('#warningTotal').addClass('d-none');
      $('#btnSimpanKunjungan').prop('disabled', false);
    }
  }

  /**
   * ==========================================================
   * EVENT INPUT
   * ==========================================================
   */
  $(document).on('input', '.kunjungan-kategori, .kunjungan-gender', function () {
    hitungKunjungan();
  });

  /**
   * ==========================================================
   * DOCUMENT READY
   * ==========================================================
   */
  $(document).ready(function () {
    hitungKunjungan();
  });
})(jQuery);
/******/ })()
;