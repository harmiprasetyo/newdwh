/******/ (() => { // webpackBootstrap
/*!***********************************************!*\
  !*** ./resources/js/newlplpo/kategoriobat.js ***!
  \***********************************************/
$(function () {
  'use strict';

  // =========================================================
  // CONFIG
  // =========================================================
  var config = window.KategoriObatConfig || {};
  var datatableUrl = config.datatableUrl || '';
  var storeUrl = config.storeUrl || '';
  var baseUrl = config.baseUrl || '';
  var csrfToken = config.csrfToken || '';

  // =========================================================
  // AJAX DEFAULT
  // =========================================================

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    }
  });

  // =========================================================
  // VARIABLE
  // =========================================================

  var table = null;
  var modalElement = document.getElementById('modalKategoriObat');
  var modal = null;
  if (modalElement) {
    modal = new bootstrap.Modal(modalElement);
  }

  // =========================================================
  // DATATABLE
  // =========================================================

  function initTable() {
    if ($.fn.DataTable.isDataTable('#tableKategoriObat')) {
      $('#tableKategoriObat').DataTable().destroy();
    }
    table = $('#tableKategoriObat').DataTable({
      processing: true,
      ajax: {
        url: datatableUrl,
        type: 'GET',
        dataSrc: function dataSrc(json) {
          if (!json.success) {
            showError(json.message || 'Gagal mengambil data kategori obat.');
            return [];
          }
          return json.data || [];
        },
        error: function error(xhr) {
          console.error('DATATABLE ERROR:', xhr.responseText);
          showError(getErrorMessage(xhr));
        }
      },
      columns: [{
        data: 'no',
        className: 'text-center',
        orderable: false,
        searchable: false
      }, {
        data: 'kategori',
        defaultContent: '-'
      }, {
        data: null,
        className: 'text-center',
        orderable: false,
        searchable: false,
        render: function render(data, type, row) {
          return "\n                            <div class=\"btn-group btn-group-sm\">\n\n                                <button\n                                    type=\"button\"\n                                    class=\"btn btn-warning btn-edit-kategori\"\n                                    data-id=\"".concat(row.id, "\"\n                                    title=\"Edit\"\n                                >\n                                    <i class=\"bi bi-pencil\"></i>\n                                </button>\n\n                                <button\n                                    type=\"button\"\n                                    class=\"btn btn-danger btn-delete-kategori\"\n                                    data-id=\"").concat(row.id, "\"\n                                    data-kategori=\"").concat(escapeHtml(row.kategori), "\"\n                                    title=\"Hapus\"\n                                >\n                                    <i class=\"bi bi-trash\"></i>\n                                </button>\n\n                            </div>\n                        ");
        }
      }],
      order: [[1, 'asc']],
      pageLength: 25,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      language: {
        processing: 'Memproses...',
        search: 'Cari:',
        lengthMenu: 'Tampilkan _MENU_ data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
        infoEmpty: 'Tidak ada data',
        zeroRecords: 'Data tidak ditemukan',
        emptyTable: 'Belum ada data kategori obat',
        paginate: {
          first: 'Pertama',
          last: 'Terakhir',
          next: '›',
          previous: '‹'
        }
      }
    });
  }

  // =========================================================
  // TAMBAH
  // =========================================================

  $('#btnTambahKategori').on('click', function () {
    resetForm();
    $('#modalKategoriTitle').text('Tambah Kategori Obat');
    $('#textSimpanKategori').text('Simpan');
    modal.show();
    setTimeout(function () {
      $('#kategori').trigger('focus');
    }, 300);
  });

  // =========================================================
  // EDIT
  // =========================================================

  $('#tableKategoriObat').on('click', '.btn-edit-kategori', function () {
    var id = $(this).data('id');
    if (!id) {
      showError('ID kategori obat tidak ditemukan.');
      return;
    }
    getKategori(id);
  });
  function getKategori(id) {
    setButtonLoading(true);
    $.ajax({
      url: "".concat(baseUrl, "/").concat(id),
      type: 'GET',
      success: function success(response) {
        if (!response.success) {
          showError(response.message || 'Gagal mengambil data.');
          return;
        }
        var data = response.data;
        resetForm();
        $('#kategoriId').val(data.id);
        $('#kategori').val(data.kategori);
        $('#modalKategoriTitle').text('Edit Kategori Obat');
        $('#textSimpanKategori').text('Update');
        modal.show();
        setTimeout(function () {
          $('#kategori').trigger('focus');
        }, 300);
      },
      error: function error(xhr) {
        console.error('GET KATEGORI ERROR:', xhr.responseText);
        showError(getErrorMessage(xhr));
      },
      complete: function complete() {
        setButtonLoading(false);
      }
    });
  }

  // =========================================================
  // SUBMIT FORM
  // =========================================================

  $('#formKategoriObat').on('submit', function (e) {
    e.preventDefault();
    clearValidation();
    var id = $('#kategoriId').val();
    var isEdit = id !== '';
    var url = isEdit ? "".concat(baseUrl, "/").concat(id) : storeUrl;
    var method = isEdit ? 'PUT' : 'POST';
    var kategori = $.trim($('#kategori').val());

    // -------------------------------------------------
    // CLIENT VALIDATION
    // -------------------------------------------------

    if (!kategori) {
      showValidation('kategori', 'Kategori obat wajib diisi.');
      $('#kategori').trigger('focus');
      return;
    }
    if (kategori.length > 100) {
      showValidation('kategori', 'Kategori obat maksimal 100 karakter.');
      $('#kategori').trigger('focus');
      return;
    }

    // -------------------------------------------------
    // LOADING
    // -------------------------------------------------

    setButtonLoading(true);

    // -------------------------------------------------
    // AJAX
    // -------------------------------------------------

    $.ajax({
      url: url,
      type: method,
      data: {
        kategori: kategori
      },
      success: function success(response) {
        if (!response.success) {
          showError(response.message || 'Gagal menyimpan data.');
          return;
        }

        // -----------------------------------------
        // CLOSE MODAL
        // -----------------------------------------

        modal.hide();

        // -----------------------------------------
        // RELOAD DATATABLE
        // -----------------------------------------

        if (table) {
          table.ajax.reload(null, false);
        }

        // -----------------------------------------
        // SUCCESS
        // -----------------------------------------

        showSuccess(response.message || 'Data berhasil disimpan.');
      },
      error: function error(xhr) {
        console.error('SAVE KATEGORI ERROR:', xhr.responseText);

        // -----------------------------------------
        // VALIDATION ERROR
        // -----------------------------------------

        if (xhr.status === 422) {
          var _xhr$responseJSON;
          var errors = ((_xhr$responseJSON = xhr.responseJSON) === null || _xhr$responseJSON === void 0 ? void 0 : _xhr$responseJSON.errors) || {};
          if (errors.kategori) {
            showValidation('kategori', errors.kategori[0]);
            $('#kategori').trigger('focus');
          }
          return;
        }
        showError(getErrorMessage(xhr));
      },
      complete: function complete() {
        setButtonLoading(false);
      }
    });
  });

  // =========================================================
  // DELETE
  // =========================================================

  $('#tableKategoriObat').on('click', '.btn-delete-kategori', function () {
    var id = $(this).data('id');
    var kategori = $(this).data('kategori') || '';
    if (!id) {
      showError('ID kategori obat tidak ditemukan.');
      return;
    }
    var confirmed = confirm("Apakah Anda yakin ingin menghapus kategori \"".concat(kategori, "\"?"));
    if (!confirmed) {
      return;
    }
    deleteKategori(id);
  });
  function deleteKategori(id) {
    $.ajax({
      url: "".concat(baseUrl, "/").concat(id),
      type: 'DELETE',
      beforeSend: function beforeSend() {
        // Optional loading indicator
        $('#tableKategoriObat').addClass('opacity-50');
      },
      success: function success(response) {
        if (!response.success) {
          showError(response.message || 'Gagal menghapus data.');
          return;
        }
        if (table) {
          table.ajax.reload(null, false);
        }
        showSuccess(response.message || 'Kategori obat berhasil dihapus.');
      },
      error: function error(xhr) {
        console.error('DELETE KATEGORI ERROR:', xhr.responseText);
        showError(getErrorMessage(xhr));
      },
      complete: function complete() {
        $('#tableKategoriObat').removeClass('opacity-50');
      }
    });
  }

  // =========================================================
  // RESET FORM
  // =========================================================

  function resetForm() {
    $('#formKategoriObat')[0].reset();
    $('#kategoriId').val('');
    $('#kategori').val('');
    clearValidation();
    setButtonLoading(false);
  }

  // =========================================================
  // VALIDATION
  // =========================================================

  function clearValidation() {
    $('#kategori').removeClass('is-invalid');
    $('#kategoriError').text('');
  }
  function showValidation(field, message) {
    var input = $("#".concat(field));
    input.addClass('is-invalid');
    $("#".concat(field, "Error")).text(message);
  }

  // =========================================================
  // BUTTON LOADING
  // =========================================================

  function setButtonLoading(loading) {
    var button = $('#btnSimpanKategori');
    var spinner = $('#spinnerKategori');
    var text = $('#textSimpanKategori');
    if (loading) {
      button.prop('disabled', true);
      spinner.removeClass('d-none');
      text.text('Menyimpan...');
    } else {
      button.prop('disabled', false);
      spinner.addClass('d-none');
      var isEdit = $('#kategoriId').val() !== '';
      text.text(isEdit ? 'Update' : 'Simpan');
    }
  }

  // =========================================================
  // ERROR MESSAGE
  // =========================================================

  function getErrorMessage(xhr) {
    if (xhr.responseJSON && xhr.responseJSON.message) {
      return xhr.responseJSON.message;
    }
    if (xhr.status === 419) {
      return 'Session telah berakhir. Silakan refresh halaman.';
    }
    if (xhr.status === 403) {
      return 'Anda tidak memiliki izin untuk melakukan tindakan ini.';
    }
    if (xhr.status === 404) {
      return 'Data atau URL tidak ditemukan.';
    }
    if (xhr.status === 500) {
      return 'Terjadi kesalahan pada server.';
    }
    return 'Terjadi kesalahan. Silakan coba lagi.';
  }

  // =========================================================
  // SUCCESS MESSAGE
  // =========================================================

  function showSuccess(message) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: message,
        timer: 1800,
        showConfirmButton: false
      });
    } else {
      alert(message);
    }
  }

  // =========================================================
  // ERROR MESSAGE
  // =========================================================

  function showError(message) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: message
      });
    } else {
      alert(message);
    }
  }

  // =========================================================
  // ESCAPE HTML
  // =========================================================

  function escapeHtml(value) {
    return String(value !== null && value !== void 0 ? value : '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  }

  // =========================================================
  // INIT
  // =========================================================

  initTable();
});
/******/ })()
;