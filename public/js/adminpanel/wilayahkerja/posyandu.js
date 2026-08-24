/******/ (() => { // webpackBootstrap
/*!**********************************************************!*\
  !*** ./resources/js/adminpanel/wilayahkerja/posyandu.js ***!
  \**********************************************************/
$(function () {
  var config = window.WilayahKerjaPosyanduConfig || {};

  /*
  |--------------------------------------------------------------------------
  | TAGIFY
  |--------------------------------------------------------------------------
  */

  var tagifyRW = new Tagify(document.querySelector('#rw'), {
    duplicates: false,
    maxTags: 30,
    pattern: /^[0-9]{1,3}$/,
    dropdown: {
      enabled: 0
    }
  });

  /*
  |--------------------------------------------------------------------------
  | SELECT2 POSYANDU
  |--------------------------------------------------------------------------
  */

  $('#kodePosyandu').select2({
    dropdownParent: $('#offcanvasForm'),
    width: '100%',
    placeholder: 'Pilih Posyandu',
    allowClear: true,
    ajax: {
      url: config.selectPosyanduUrl,
      dataType: 'json',
      delay: 250,
      data: function data(params) {
        return {
          q: params.term || ''
        };
      },
      processResults: function processResults(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });
  var table = $('#datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: config.datatableUrl,
      type: 'GET'
    },
    columns: [{
      data: 'DT_RowIndex',
      searchable: false,
      orderable: false
    }, {
      data: 'nama_posyandu'
    }, {
      data: 'desa'
    }, {
      data: 'kecamatan'
    }, {
      data: 'kabupaten'
    }, {
      data: 'provinsi'
    }, {
      data: 'rw'
    }, {
      data: 'aksi',
      searchable: false,
      orderable: false
    }]
  });

  /*
  |--------------------------------------------------------------------------
  | TAMBAH
  |--------------------------------------------------------------------------
  */

  $('#btnTambah').on('click', function () {
    $('#formData')[0].reset();
    $('#id').val('');
    tagifyRW.removeAllTags();
    $('#kodePosyandu').val(null).trigger('change');
    var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('offcanvasForm'));
    offcanvas.show();
  });

  /*
  |--------------------------------------------------------------------------
  | SUBMIT
  |--------------------------------------------------------------------------
  */

  $('#formData').on('submit', function (e) {
    e.preventDefault();
    var id = $('#id').val();
    var formData = new FormData(this);
    var rw = tagifyRW.value.map(function (item) {
      return item.value;
    }).join(',');
    formData.set('rw', rw);
    var url = config.storeUrl;
    if (id) {
      url = config.updateUrl.replace('__ID__', id);
      formData.append('_method', 'PUT');
    }
    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function success(response) {
        bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasForm')).hide();
        table.ajax.reload(null, false);
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: response.message || 'Data berhasil disimpan.',
          timer: 1500,
          showConfirmButton: false
        });
      },
      error: function error(xhr) {
        var _xhr$responseJSON, _xhr$responseJSON2;
        var message = 'Terjadi kesalahan.';
        if ((_xhr$responseJSON = xhr.responseJSON) !== null && _xhr$responseJSON !== void 0 && _xhr$responseJSON.errors) {
          message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
        } else if ((_xhr$responseJSON2 = xhr.responseJSON) !== null && _xhr$responseJSON2 !== void 0 && _xhr$responseJSON2.message) {
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
  | EDIT
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btnEdit', function () {
    var button = $(this);
    $.get(button.data('url')).done(function (response) {
      $('#id').val(response.id);

      /*
      |--------------------------------------------------------------------------
      | SELECT POSYANDU
      |--------------------------------------------------------------------------
      */

      var option = new Option(response.namaPosyandu, response.kodePosyandu, true, true);
      $('#kodePosyandu').empty().append(option).trigger('change');

      /*
      |--------------------------------------------------------------------------
      | RW
      |--------------------------------------------------------------------------
      */

      tagifyRW.removeAllTags();
      if (response.rw) {
        tagifyRW.addTags(response.rw.split(','));
      }
      var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('offcanvasForm'));
      offcanvas.show();
    }).fail(function (xhr) {
      var _xhr$responseJSON3;
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: ((_xhr$responseJSON3 = xhr.responseJSON) === null || _xhr$responseJSON3 === void 0 ? void 0 : _xhr$responseJSON3.message) || 'Data tidak dapat dimuat.'
      });
    });
  });

  /*
  |--------------------------------------------------------------------------
  | DELETE
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btnDelete', function () {
    var button = $(this);
    Swal.fire({
      title: 'Hapus data?',
      text: 'Wilayah kerja Posyandu akan dihapus.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then(function (result) {
      if (!result.isConfirmed) {
        return;
      }
      $.ajax({
        url: button.data('url'),
        type: 'POST',
        data: {
          _method: 'DELETE',
          _token: $('meta[name="csrf-token"]').attr('content')
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
          var _xhr$responseJSON4;
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: ((_xhr$responseJSON4 = xhr.responseJSON) === null || _xhr$responseJSON4 === void 0 ? void 0 : _xhr$responseJSON4.message) || 'Data tidak dapat dihapus.'
          });
        }
      });
    });
  });
});
/******/ })()
;