/******/ (() => { // webpackBootstrap
/*!***********************************************************!*\
  !*** ./resources/js/adminpanel/wilayahkerja/puskesmas.js ***!
  \***********************************************************/
$(function () {
  var config = window.WilayahKerjaPuskesmasConfig || {};
  var groupId = parseInt(config.groupId || 0);
  var isGroup3 = groupId === 3;

  /*
  |--------------------------------------------------------------------------
  | DATATABLE
  |--------------------------------------------------------------------------
  */

  var table = $('#wilayahPuskesmasTable').DataTable({
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
      data: 'namaFaskes'
    }, {
      data: 'namaDesa'
    }, {
      data: 'kecamatan'
    }, {
      data: 'kota'
    }, {
      data: 'provinsi'
    }, {
      data: 'action',
      searchable: false,
      orderable: false
    }]
  });

  /*
  |--------------------------------------------------------------------------
  | SELECT2 FASKES
  |--------------------------------------------------------------------------
  */

  $('#kodeFaskes').select2({
    dropdownParent: $('#wilayahPuskesmasModal'),
    width: '100%',
    placeholder: 'Pilih Puskesmas',
    allowClear: true,
    ajax: {
      url: config.faskesUrl,
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
      }
    }
  });

  /*
  |--------------------------------------------------------------------------
  | SELECT2 DESA
  |--------------------------------------------------------------------------
  */

  $('#kodeDesa').select2({
    dropdownParent: $('#wilayahPuskesmasModal'),
    width: '100%',
    placeholder: 'Pilih Desa / Kelurahan',
    allowClear: true
  });

  /*
  |--------------------------------------------------------------------------
  | GROUP 3
  |--------------------------------------------------------------------------
  */

  if (isGroup3) {
    $('#faskesContainer').hide();
    $('#wilayahFaskesContainer').hide();
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD DESA
  |--------------------------------------------------------------------------
  */

  function loadDesa(kodeFaskes) {
    var selected = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    $('#kodeDesa').empty().append(new Option('Memuat desa...', '', true, true)).trigger('change');
    if (!kodeFaskes) {
      $('#kodeDesa').empty().append(new Option('Pilih Desa / Kelurahan', '', true, true)).trigger('change');
      return;
    }
    $.ajax({
      url: config.desaByFaskesUrl,
      type: 'GET',
      data: {
        kodeFaskes: kodeFaskes
      },
      success: function success(data) {
        $('#kodeDesa').empty().append(new Option('Pilih Desa / Kelurahan', '', true, true));
        $.each(data, function (_, item) {
          var option = new Option(item.text, item.id, false, String(item.id) === String(selected));
          $('#kodeDesa').append(option);
        });
        $('#kodeDesa').trigger('change');
      },
      error: function error() {
        $('#kodeDesa').empty().append(new Option('Gagal memuat desa', '', true, true)).trigger('change');
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | FASKES CHANGE
  |--------------------------------------------------------------------------
  */

  $('#kodeFaskes').on('change', function () {
    var selected = $(this).find(':selected');
    if (!isGroup3) {
      $('#kodePropinsi').val(selected.data('propinsi') || '');
    }
    loadDesa($(this).val());
  });

  /*
  |--------------------------------------------------------------------------
  | TAMBAH
  |--------------------------------------------------------------------------
  */

  $('#btnAddWilayah').on('click', function () {
    $('#wilayahPuskesmasForm')[0].reset();
    $('#wilayahId').val('');
    $('#kodeFaskes').val(null).trigger('change');
    $('#kodeDesa').empty().append(new Option('Pilih Desa / Kelurahan', '', true, true)).trigger('change');
    if (isGroup3) {
      /*
      |--------------------------------------------------------------------------
      | Group 3:
      | kodeFaskes otomatis dari user
      |--------------------------------------------------------------------------
      */

      loadDesa(config.userKodeFaskes);
    }
    $('#wilayahPuskesmasModalTitle').text('Tambah Wilayah Kerja Puskesmas');
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('wilayahPuskesmasModal'));
    modal.show();
  });

  /*
  |--------------------------------------------------------------------------
  | EDIT
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btn-edit', function () {
    var id = $(this).data('id');
    var url = config.showUrl.replace('__ID__', id);
    $.get(url).done(function (response) {
      var data = response.data;
      $('#wilayahId').val(data.id);

      /*
      |--------------------------------------------------------------------------
      | GROUP 3
      |--------------------------------------------------------------------------
      */

      if (isGroup3) {
        loadDesa(config.userKodeFaskes, data.kodeDesa);
      } else {
        var _data$faskes;
        /*
        |--------------------------------------------------------------------------
        | GROUP 1 / 2
        |--------------------------------------------------------------------------
        */

        var option = new Option(((_data$faskes = data.faskes) === null || _data$faskes === void 0 ? void 0 : _data$faskes.namaFaskes) || data.kodeFaskes, data.kodeFaskes, true, true);
        $('#kodeFaskes').empty().append(option).trigger('change');
        loadDesa(data.kodeFaskes, data.kodeDesa);
      }
      $('#wilayahPuskesmasModalTitle').text('Edit Wilayah Kerja Puskesmas');
      bootstrap.Modal.getOrCreateInstance(document.getElementById('wilayahPuskesmasModal')).show();
    }).fail(function (xhr) {
      var _xhr$responseJSON;
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: ((_xhr$responseJSON = xhr.responseJSON) === null || _xhr$responseJSON === void 0 ? void 0 : _xhr$responseJSON.message) || 'Data tidak dapat dimuat.'
      });
    });
  });

  /*
  |--------------------------------------------------------------------------
  | SUBMIT
  |--------------------------------------------------------------------------
  */

  $('#wilayahPuskesmasForm').on('submit', function (e) {
    e.preventDefault();
    var id = $('#wilayahId').val();
    var url = config.storeUrl;
    var method = 'POST';
    if (id) {
      url = config.updateUrl.replace('__ID__', id);
      method = 'PUT';
    }
    var kodeFaskes = $('#kodeFaskes').val();

    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if (isGroup3) {
      kodeFaskes = config.userKodeFaskes;
    }
    $.ajax({
      url: url,
      type: method,
      data: {
        kodeFaskes: kodeFaskes,
        kodeDesa: $('#kodeDesa').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(response) {
        bootstrap.Modal.getInstance(document.getElementById('wilayahPuskesmasModal')).hide();
        table.ajax.reload(null, false);
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: response.message,
          timer: 1500,
          showConfirmButton: false
        });
      },
      error: function error(xhr) {
        var _xhr$responseJSON2, _xhr$responseJSON3;
        var message = 'Terjadi kesalahan.';
        if ((_xhr$responseJSON2 = xhr.responseJSON) !== null && _xhr$responseJSON2 !== void 0 && _xhr$responseJSON2.errors) {
          message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
        } else if ((_xhr$responseJSON3 = xhr.responseJSON) !== null && _xhr$responseJSON3 !== void 0 && _xhr$responseJSON3.message) {
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
  | DELETE
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btn-delete', function () {
    var id = $(this).data('id');
    Swal.fire({
      title: 'Hapus data?',
      text: 'Wilayah kerja Puskesmas akan dihapus.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then(function (result) {
      if (!result.isConfirmed) {
        return;
      }
      $.ajax({
        url: config.deleteUrl.replace('__ID__', id),
        type: 'DELETE',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function success(response) {
          table.ajax.reload(null, false);
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: response.message,
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