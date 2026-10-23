/******/ (() => { // webpackBootstrap
/*!****************************************************!*\
  !*** ./resources/js/adminpanel/posyandu/create.js ***!
  \****************************************************/
$(function () {
  var config = window.PosyanduConfig || {};
  var isGroup3 = !!config.isGroup3;
  var faskes = config.faskes || null;

  /*
  |--------------------------------------------------------------------------
  | HELPER
  |--------------------------------------------------------------------------
  */

  function escapeHtml(value) {
    if (value === null || value === undefined) {
      return '';
    }
    return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  }

  /*
  |--------------------------------------------------------------------------
  | GROUP 3
  |--------------------------------------------------------------------------
  */

  function initializeGroup3() {
    if (!isGroup3 || !faskes) {
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | PROVINSI
    |--------------------------------------------------------------------------
    */

    $('#provinsi').html("\n            <option value=\"".concat(escapeHtml(faskes.kodePropinsi), "\">\n                ").concat(escapeHtml(faskes.namaPropinsi), "\n            </option>\n        ")).val(faskes.kodePropinsi).prop('disabled', true);

    /*
    |--------------------------------------------------------------------------
    | KOTA
    |--------------------------------------------------------------------------
    */

    $('#kota').html("\n            <option value=\"".concat(escapeHtml(faskes.kodeKota), "\">\n                ").concat(escapeHtml(faskes.namaKota), "\n            </option>\n        ")).val(faskes.kodeKota).prop('disabled', true);

    /*
    |--------------------------------------------------------------------------
    | KECAMATAN
    |--------------------------------------------------------------------------
    */

    $('#kecamatan').html("\n            <option value=\"".concat(escapeHtml(faskes.kodeKecamatan), "\">\n                ").concat(escapeHtml(faskes.namaKecamatan), "\n            </option>\n        ")).val(faskes.kodeKecamatan).prop('disabled', true);

    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    $('#faskes').html("\n            <option value=\"".concat(escapeHtml(faskes.kodeFaskes), "\">\n                ").concat(escapeHtml(faskes.namaFaskes), "\n            </option>\n        ")).val(faskes.kodeFaskes).prop('disabled', true);

    /*
    |--------------------------------------------------------------------------
    | DESA
    |--------------------------------------------------------------------------
    |
    | Desa TETAP AKTIF.
    |
    | Data desa berasal dari:
    |
    | indonesia_villages
    |
    | WHERE district_code =
    | master_faskes.kodeKecamatan
    |
    */

    loadVillages(faskes.kodeKecamatan);
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD VILLAGES
  |--------------------------------------------------------------------------
  */

  function loadVillages(districtCode) {
    $('#desa').html("\n                <option value=\"\">\n                    Memuat Desa...\n                </option>\n            ").prop('disabled', true);
    if (!districtCode) {
      $('#desa').html("\n                    <option value=\"\">\n                        Pilih Desa\n                    </option>\n                ");
      return;
    }
    $.get(config.villagesUrl, {
      district_code: districtCode
    }).done(function (response) {
      var html = "\n                <option value=\"\">\n                    Pilih Desa\n                </option>\n            ";
      if (response && Array.isArray(response.data)) {
        response.data.forEach(function (item) {
          html += "\n                        <option value=\"".concat(escapeHtml(item.code), "\">\n                            ").concat(escapeHtml(item.name), "\n                        </option>\n                    ");
        });
      }
      $('#desa').html(html).prop('disabled', false);
    }).fail(function (xhr) {
      console.error('Gagal load desa:', xhr.responseText);
      $('#desa').html("\n                    <option value=\"\">\n                        Gagal memuat Desa\n                    </option>\n                ").prop('disabled', true);
    });
  }

  /*
  |--------------------------------------------------------------------------
  | NON GROUP 3
  |--------------------------------------------------------------------------
  */

  function loadProvinces() {
    $.get(config.provincesUrl).done(function (response) {
      var html = "\n                <option value=\"\">\n                    Pilih Provinsi\n                </option>\n            ";
      response.data.forEach(function (item) {
        html += "\n                    <option value=\"".concat(escapeHtml(item.code), "\">\n                        ").concat(escapeHtml(item.name), "\n                    </option>\n                ");
      });
      $('#provinsi').html(html).prop('disabled', false);
    });
  }

  /*
  |--------------------------------------------------------------------------
  | PROVINCE CHANGE
  |--------------------------------------------------------------------------
  */

  $('#provinsi').on('change', function () {
    if (isGroup3) {
      return;
    }
    var code = $(this).val();
    $('#kota').html('<option value="">Pilih Kota</option>').prop('disabled', true);
    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
    $('#desa').html('<option value="">Pilih Desa</option>').prop('disabled', true);
    $('#faskes').html('<option value="">Pilih Fasyankes</option>').prop('disabled', true);
    if (!code) {
      return;
    }
    $.get(config.citiesUrl, {
      province_code: code
    }).done(function (response) {
      var html = "\n                    <option value=\"\">\n                        Pilih Kota\n                    </option>\n                ";
      response.data.forEach(function (item) {
        html += "\n                        <option value=\"".concat(escapeHtml(item.code), "\">\n                            ").concat(escapeHtml(item.name), "\n                        </option>\n                    ");
      });
      $('#kota').html(html).prop('disabled', false);
    });
  });

  /*
  |--------------------------------------------------------------------------
  | CITY CHANGE
  |--------------------------------------------------------------------------
  */

  $('#kota').on('change', function () {
    if (isGroup3) {
      return;
    }
    var code = $(this).val();
    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
    $('#desa').html('<option value="">Pilih Desa</option>').prop('disabled', true);
    $('#faskes').html('<option value="">Pilih Fasyankes</option>').prop('disabled', true);
    if (!code) {
      return;
    }
    $.get(config.districtsUrl, {
      city_code: code
    }).done(function (response) {
      var html = "\n                    <option value=\"\">\n                        Pilih Kecamatan\n                    </option>\n                ";
      response.data.forEach(function (item) {
        html += "\n                        <option value=\"".concat(escapeHtml(item.code), "\">\n                            ").concat(escapeHtml(item.name), "\n                        </option>\n                    ");
      });
      $('#kecamatan').html(html).prop('disabled', false);
    });
  });

  /*
  |--------------------------------------------------------------------------
  | DISTRICT CHANGE
  |--------------------------------------------------------------------------
  */

  $('#kecamatan').on('change', function () {
    if (isGroup3) {
      return;
    }
    var code = $(this).val();
    loadVillages(code);
    $('#faskes').html('<option value="">Memuat Fasyankes...</option>').prop('disabled', true);
    if (!code) {
      return;
    }
    $.get(config.faskesUrl, {
      district_code: code
    }).done(function (response) {
      var html = "\n                    <option value=\"\">\n                        Pilih Fasyankes\n                    </option>\n                ";
      response.data.forEach(function (item) {
        html += "\n                        <option value=\"".concat(escapeHtml(item.kodeFaskes), "\">\n                            ").concat(escapeHtml(item.namaFaskes), "\n                        </option>\n                    ");
      });
      $('#faskes').html(html).prop('disabled', false);
    });
  });

  /*
  |--------------------------------------------------------------------------
  | SUBMIT
  |--------------------------------------------------------------------------
  */

  $('#frmPosyandu').on('submit', function (e) {
    e.preventDefault();
    var form = this;
    var data = $(form).serialize();
    $.ajax({
      url: config.storeUrl,
      type: 'POST',
      data: data,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(response) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: response.message || 'Posyandu berhasil disimpan.',
          timer: 1500,
          showConfirmButton: false
        });
        setTimeout(function () {
          window.location.href = config.indexUrl;
        }, 1500);
      },
      error: function error(xhr) {
        var message = 'Terjadi kesalahan saat menyimpan data.';
        if (xhr.responseJSON && xhr.responseJSON.errors) {
          message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        }
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          html: message
        });
      }
    });
  });

  /*
  |--------------------------------------------------------------------------
  | INITIALIZE
  |--------------------------------------------------------------------------
  */

  if (isGroup3) {
    initializeGroup3();
  } else {
    loadProvinces();
  }
});
/******/ })()
;