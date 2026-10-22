/******/ (() => { // webpackBootstrap
/*!***************************************!*\
  !*** ./resources/js/newlplpo/item.js ***!
  \***************************************/
(function ($) {
  'use strict';

  var config = window.LplpoConfig || {};
  var editId = null;

  /*
  |--------------------------------------------------------------------------
  | READY
  |--------------------------------------------------------------------------
  */

  $(function () {
    console.log('ITEM.JS BERHASIL DIMUAT');
    initTambahItem();
    initSaveItem();
    initEditItem();
    initDeleteItem();
    initSearchItem();
    initOffcanvasReset();
  });

  /*
  |--------------------------------------------------------------------------
  | TAMBAH ITEM
  |--------------------------------------------------------------------------
  */

  function initTambahItem() {
    $(document).on('click', '#btnTambah', function () {
      resetItemForm();
      editId = null;
      var form = document.getElementById('frmItem');
      if (form) {
        delete form.dataset.editId;
      }
      setSaveButtonCreate();
    });
  }

  /*
  |--------------------------------------------------------------------------
  | SAVE ITEM
  |--------------------------------------------------------------------------
  */

  function initSaveItem() {
    $(document).on('click', '#btnSaveItem', function (e) {
      e.preventDefault();
      var button = $(this);

      /*
      |--------------------------------------------------------------------------
      | CEK MODE
      |--------------------------------------------------------------------------
      */

      var form = document.getElementById('frmItem');
      if (form !== null && form !== void 0 && form.dataset.editId) {
        editId = form.dataset.editId;
      }
      console.log('ITEM SAVE MODE:', editId ? 'UPDATE' : 'CREATE');
      if (editId) {
        updateItem(button);
      } else {
        createItem(button);
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | CREATE
  |--------------------------------------------------------------------------
  */

  function createItem(button) {
    var form = document.getElementById('frmItem');
    if (!form) {
      showError('Form item tidak ditemukan.');
      return;
    }
    var data = $(form).serialize();
    console.log('CREATE ITEM:', data);
    $.ajax({
      url: config.routes.itemStore,
      type: 'POST',
      data: data,
      beforeSend: function beforeSend() {
        setButtonLoading(button, 'Menyimpan...');
      },
      success: function success(response) {
        console.log('CREATE RESPONSE:', response);
        if (response.success === false) {
          showError(response.message || 'Gagal menambahkan item.');
          return;
        }
        showSuccess(response.message || 'Item berhasil ditambahkan.');
        closeOffcanvas();
        setTimeout(function () {
          window.location.reload();
        }, 800);
      },
      error: function error(xhr) {
        console.error('CREATE ERROR:', xhr.responseText);
        resetButton(button, 'Simpan Item');
        showAjaxError(xhr, 'Gagal menambahkan item.');
      },
      complete: function complete() {
        resetButton(button, 'Simpan Item');
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | EDIT ITEM
  |--------------------------------------------------------------------------
  */

  function initEditItem() {
    $(document).on('click', '.btnEditItem', function (e) {
      e.preventDefault();
      var button = $(this);
      var id = button.data('id');
      if (!id) {
        showError('ID item tidak ditemukan.');
        return;
      }
      loadItemForEdit(id);
    });
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD EDIT
  |--------------------------------------------------------------------------
  */

  function loadItemForEdit(id) {
    console.log('EDIT ITEM ID:', id);
    var url = "".concat(config.routes.itemEditBase, "/").concat(id, "/edit");
    $.ajax({
      url: url,
      type: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      beforeSend: function beforeSend() {
        console.log('Mengambil data item...');
      },
      success: function success(response) {
        console.log('EDIT RESPONSE:', response);
        if (!response.success) {
          showError(response.message || 'Data item tidak ditemukan.');
          return;
        }
        var item = response.data;

        /*
        |--------------------------------------------------------------------------
        | SET EDIT STATE
        |--------------------------------------------------------------------------
        */

        editId = item.id;
        var form = document.getElementById('frmItem');
        if (form) {
          form.dataset.editId = item.id;
        }

        /*
        |--------------------------------------------------------------------------
        | ISI FORM
        |--------------------------------------------------------------------------
        */

        setValue('program_id', item.program_id);
        setValue('kode_obat', item.kode_obat);
        setValue('nama_obat', item.nama_obat);
        setValue('satuan', item.satuan);
        setValue('stok_awal_progam_pkd', item.stok_awal_progam_pkd);
        setValue('stok_awal_jkn', item.stok_awal_jkn);
        setValue('penerimaan_program_pkd', item.penerimaan_program_pkd);
        setValue('penerimaan_jkn', item.penerimaan_jkn);
        setValue('persediaan_program_pkd', item.persediaan_program_pkd);
        setValue('persediaan_jkn', item.persediaan_jkn);
        setValue('pemakaian_program_pkd', item.pemakaian_program_pkd);
        setValue('pemakaian_jkn', item.pemakaian_jkn);
        setValue('item_expired_pkd', item.item_expired_pkd);
        setValue('item_expired_jkn', item.item_expired_jkn);
        setValue('stok_akhir_program_pkd', item.stok_akhir_program_pkd);
        setValue('stok_akhir_jkn', item.stok_akhir_jkn);
        setValue('stok_minimum', item.stok_minimum);
        setValue('stok_optimum', item.stok_optimum);
        setValue('permintaan', item.permintaan);
        setValue('pemberian_program_pkd', item.pemberian_program_pkd);
        setValue('pemberian_jkn', item.pemberian_jkn);

        /*
        |--------------------------------------------------------------------------
        | PROGRAM SELECT
        |--------------------------------------------------------------------------
        */

        var program = $('#program_id');
        if (program.length) {
          var _item$program_id;
          program.val((_item$program_id = item.program_id) !== null && _item$program_id !== void 0 ? _item$program_id : '').trigger('change');
        }

        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */

        setSaveButtonEdit();

        /*
        |--------------------------------------------------------------------------
        | SHOW OFFCANVAS
        |--------------------------------------------------------------------------
        */

        openOffcanvas();
      },
      error: function error(xhr) {
        console.error('EDIT ITEM ERROR:', xhr.responseText);
        showAjaxError(xhr, 'Gagal mengambil data item.');
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | UPDATE
  |--------------------------------------------------------------------------
  */

  function updateItem(button) {
    if (!editId) {
      showError('ID item untuk update tidak ditemukan.');
      return;
    }
    var form = document.getElementById('frmItem');
    if (!form) {
      showError('Form item tidak ditemukan.');
      return;
    }
    var data = $(form).serialize();
    console.log('UPDATE ITEM ID:', editId);
    console.log('UPDATE DATA:', data);
    var url = "".concat(config.routes.itemUpdateBase, "/").concat(editId);
    $.ajax({
      url: url,
      type: 'PUT',
      data: data,
      headers: {
        'X-CSRF-TOKEN': config.csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      },
      beforeSend: function beforeSend() {
        setButtonLoading(button, 'Memperbarui...');
      },
      success: function success(response) {
        console.log('UPDATE RESPONSE:', response);
        if (response.success === false) {
          showError(response.message || 'Gagal memperbarui item.');
          return;
        }
        showSuccess(response.message || 'Item berhasil diperbarui.');
        closeOffcanvas();

        /*
        |--------------------------------------------------------------------------
        | RESET STATE
        |--------------------------------------------------------------------------
        */

        editId = null;
        if (form) {
          delete form.dataset.editId;
        }
        setTimeout(function () {
          window.location.reload();
        }, 800);
      },
      error: function error(xhr) {
        console.error('UPDATE ERROR:', xhr.responseText);
        showAjaxError(xhr, 'Gagal memperbarui item.');
      },
      complete: function complete() {
        resetButton(button, 'Update Item');
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | DELETE
  |--------------------------------------------------------------------------
  */

  function initDeleteItem() {
    $(document).on('click', '.btnDeleteItem, .btn-delete', function (e) {
      e.preventDefault();
      var button = $(this);
      var id = button.data('id');
      if (!id) {
        showError('ID item tidak ditemukan.');
        return;
      }
      Swal.fire({
        title: 'Hapus Item?',
        text: 'Data item obat akan dihapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then(function (result) {
        if (!result.isConfirmed) {
          return;
        }
        deleteItem(id, button);
      });
    });
  }

  /*
  |--------------------------------------------------------------------------
  | DELETE REQUEST
  |--------------------------------------------------------------------------
  */

  function deleteItem(id, button) {
    var url = "".concat(config.routes.itemDeleteBase, "/").concat(id);
    $.ajax({
      url: url,
      type: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': config.csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      },
      beforeSend: function beforeSend() {
        button.prop('disabled', true);
      },
      success: function success(response) {
        console.log('DELETE RESPONSE:', response);
        if (response.success === false) {
          showError(response.message || 'Gagal menghapus item.');
          return;
        }

        /*
        |--------------------------------------------------------------------------
        | REMOVE ROW LANGSUNG
        |--------------------------------------------------------------------------
        */

        var row = button.closest('tr');
        if (row.length) {
          row.remove();
        }
        updateItemCount();
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: response.message || 'Item berhasil dihapus.',
          timer: 1200,
          showConfirmButton: false
        });
      },
      error: function error(xhr) {
        console.error('DELETE ERROR:', xhr.responseText);
        showAjaxError(xhr, 'Gagal menghapus item.');
      },
      complete: function complete() {
        button.prop('disabled', false);
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | SEARCH ITEM
  |--------------------------------------------------------------------------
  */

  function initSearchItem() {
    $(document).on('input', '#searchItem', function () {
      var keyword = $(this).val().toLowerCase().trim();
      $('#tblItem tbody tr').each(function () {
        var text = $(this).text().toLowerCase();
        $(this).toggle(text.indexOf(keyword) !== -1);
      });
    });
  }

  /*
  |--------------------------------------------------------------------------
  | RESET FORM
  |--------------------------------------------------------------------------
  */

  function resetItemForm() {
    var form = document.getElementById('frmItem');
    if (!form) {
      return;
    }
    form.reset();
    editId = null;
    delete form.dataset.editId;

    /*
    |--------------------------------------------------------------------------
    | RESET SELECT2
    |--------------------------------------------------------------------------
    */

    $('#program_id').val(null).trigger('change');

    /*
    |--------------------------------------------------------------------------
    | CLEAR HIDDEN VALUE
    |--------------------------------------------------------------------------
    */

    $('#kode_obat').val('');
    $('#nama_obat').val('');
    $('#satuan').val('');
  }

  /*
  |--------------------------------------------------------------------------
  | OFFCANVAS RESET
  |--------------------------------------------------------------------------
  */

  function initOffcanvasReset() {
    var element = document.getElementById('offcanvasObat');
    if (!element) {
      return;
    }
    element.addEventListener('hidden.bs.offcanvas', function () {
      resetItemForm();
      setSaveButtonCreate();
    });
  }

  /*
  |--------------------------------------------------------------------------
  | SET BUTTON CREATE
  |--------------------------------------------------------------------------
  */

  function setSaveButtonCreate() {
    var button = $('#btnSaveItem');
    if (!button.length) {
      return;
    }
    button.prop('disabled', false).removeAttr('data-mode').html('<i class="bi bi-save me-1"></i>' + 'Simpan Item');
  }

  /*
  |--------------------------------------------------------------------------
  | SET BUTTON EDIT
  |--------------------------------------------------------------------------
  */

  function setSaveButtonEdit() {
    var button = $('#btnSaveItem');
    if (!button.length) {
      return;
    }
    button.prop('disabled', false).attr('data-mode', 'edit').html('<i class="bi bi-save me-1"></i>' + 'Update Item');
  }

  /*
  |--------------------------------------------------------------------------
  | SET BUTTON LOADING
  |--------------------------------------------------------------------------
  */

  function setButtonLoading(button, text) {
    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>' + text);
  }

  /*
  |--------------------------------------------------------------------------
  | RESET BUTTON
  |--------------------------------------------------------------------------
  */

  function resetButton(button, text) {
    button.prop('disabled', false).html('<i class="bi bi-save me-1"></i>' + text);
  }

  /*
  |--------------------------------------------------------------------------
  | SET VALUE
  |--------------------------------------------------------------------------
  */

  function setValue(id, value) {
    var element = document.getElementById(id);
    if (!element) {
      console.warn("Element #".concat(id, " tidak ditemukan"));
      return;
    }
    element.value = value !== null && value !== void 0 ? value : '';
  }

  /*
  |--------------------------------------------------------------------------
  | OPEN OFFCANVAS
  |--------------------------------------------------------------------------
  */

  function openOffcanvas() {
    var element = document.getElementById('offcanvasObat');
    if (!element) {
      console.error('#offcanvasObat tidak ditemukan.');
      return;
    }
    var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(element);
    offcanvas.show();
  }

  /*
  |--------------------------------------------------------------------------
  | CLOSE OFFCANVAS
  |--------------------------------------------------------------------------
  */

  function closeOffcanvas() {
    var element = document.getElementById('offcanvasObat');
    if (!element) {
      return;
    }
    var offcanvas = bootstrap.Offcanvas.getInstance(element);
    if (offcanvas) {
      offcanvas.hide();
    }
  }

  /*
  |--------------------------------------------------------------------------
  | UPDATE ITEM COUNT
  |--------------------------------------------------------------------------
  */

  function updateItemCount() {
    var count = $('#tblItem tbody tr').length;
    $('#itemCount').text("".concat(count, " Item"));
  }

  /*
  |--------------------------------------------------------------------------
  | SUCCESS
  |--------------------------------------------------------------------------
  */

  function showSuccess(message) {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: message,
      timer: 1200,
      showConfirmButton: false
    });
  }

  /*
  |--------------------------------------------------------------------------
  | ERROR
  |--------------------------------------------------------------------------
  */

  function showError(message) {
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: message
    });
  }

  /*
  |--------------------------------------------------------------------------
  | AJAX ERROR
  |--------------------------------------------------------------------------
  */

  function showAjaxError(xhr, fallback) {
    var _xhr$responseJSON, _xhr$responseJSON2;
    var message = fallback;
    if ((_xhr$responseJSON = xhr.responseJSON) !== null && _xhr$responseJSON !== void 0 && _xhr$responseJSON.message) {
      message = xhr.responseJSON.message;
    }
    if (xhr.status === 422 && (_xhr$responseJSON2 = xhr.responseJSON) !== null && _xhr$responseJSON2 !== void 0 && _xhr$responseJSON2.errors) {
      message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
    }
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      html: message
    });
  }
})(jQuery);
/******/ })()
;