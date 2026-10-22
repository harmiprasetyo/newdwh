/******/ (() => { // webpackBootstrap
/*!***************************************************!*\
  !*** ./resources/js/adminpanel/posyandu/index.js ***!
  \***************************************************/
$(function () {
  var config = window.PosyanduConfig || {};
  var table = $('#tablePosyandu').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: config.dataUrl,
      type: 'GET'
    },
    columns: [{
      data: 'kodePosyandu'
    }, {
      data: 'namaPosyandu'
    }, {
      data: 'province_name'
    }, {
      data: 'city_name'
    }, {
      data: 'district_name'
    }, {
      data: 'village_name'
    }, {
      data: 'namaFaskes'
    }, {
      data: 'status',
      orderable: false,
      searchable: false
    }, {
      data: 'action',
      orderable: false,
      searchable: false
    }]
  });

  /*
  |--------------------------------------------------------------------------
  | DELETE
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btn-delete', function () {
    var id = $(this).data('id');
    Swal.fire({
      title: 'Hapus data?',
      text: 'Data Posyandu akan dihapus.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then(function (result) {
      if (!result.isConfirmed) {
        return;
      }
      $.ajax({
        url: "".concat(config.deleteUrl, "/").concat(id),
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function success(response) {
          table.ajax.reload(null, false);
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: response.message || 'Data berhasil dihapus.',
            timer: 1500,
            showConfirmButton: false
          });
        },
        error: function error(xhr) {
          var _xhr$responseJSON;
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: ((_xhr$responseJSON = xhr.responseJSON) === null || _xhr$responseJSON === void 0 ? void 0 : _xhr$responseJSON.message) || 'Data tidak dapat dihapus.'
          });
        }
      });
    });
  });
});
/******/ })()
;