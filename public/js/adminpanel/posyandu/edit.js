/******/ (() => { // webpackBootstrap
/*!**************************************************!*\
  !*** ./resources/js/adminpanel/posyandu/edit.js ***!
  \**************************************************/
$(function () {
  var config = window.PosyanduConfig || {};
  var isGroup3 = !!config.isGroup3;
  var faskes = config.faskes || null;
  var posyandu = config.posyandu || null;

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
  | LOAD DESA GROUP 3
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
      var html = "\n                <option value=\"\">\n                    -- Pilih Desa --\n                </option>\n            ";
      if (response && Array.isArray(response.data)) {
        response.data.forEach(function (item) {
          var selected = posyandu && String(posyandu.village_code) === String(item.code) ? 'selected' : '';
          html += "\n                            <option\n                                value=\"".concat(escapeHtml(item.code), "\"\n                                ").concat(selected, "\n                            >\n                                ").concat(escapeHtml(item.name), "\n                            </option>\n                        ");
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
  | GROUP 3 INITIALIZE
  |--------------------------------------------------------------------------
  */

  function initializeGroup3() {
    if (!isGroup3 || !faskes) {
      return;
    }
    loadVillages(faskes.kodeKecamatan);
  }

  /*
  |--------------------------------------------------------------------------
  | SUBMIT
  |--------------------------------------------------------------------------
  */

  $('#frmPosyanduEdit').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: config.updateUrl,
      type: 'PUT',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(response) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: response.message || 'Posyandu berhasil diperbarui.',
          timer: 1500,
          showConfirmButton: false
        });
        setTimeout(function () {
          window.location.href = config.indexUrl;
        }, 1500);
      },
      error: function error(xhr) {
        var message = 'Terjadi kesalahan saat memperbarui data.';
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
  }
});
/******/ })()
;