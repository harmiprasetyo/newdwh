/******/ (() => { // webpackBootstrap
/*!*****************************************************!*\
  !*** ./resources/js/adminpanel/userpanel/groups.js ***!
  \*****************************************************/
var groupTable = null;
var groupModal = null;

/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {
  console.log('User Group JS loaded');

  /*
  |--------------------------------------------------------------------------
  | INITIALIZE MODAL
  |--------------------------------------------------------------------------
  */

  var modalElement = document.getElementById('groupModal');
  if (!modalElement) {
    console.error('Element #groupModal tidak ditemukan.');
    return;
  }
  if (typeof bootstrap === 'undefined') {
    console.error('Bootstrap JS tidak ditemukan.');
    return;
  }
  groupModal = bootstrap.Modal.getOrCreateInstance(modalElement);

  /*
  |--------------------------------------------------------------------------
  | BUTTON TAMBAH GROUP
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '#btnAddGroup', function (e) {
    e.preventDefault();
    console.log('Tombol Tambah Group diklik');
    openGroupModal();
  });

  /*
  |--------------------------------------------------------------------------
  | FORM SUBMIT
  |--------------------------------------------------------------------------
  */

  $(document).on('submit', '#groupForm', function (e) {
    e.preventDefault();
    saveGroup();
  });

  /*
  |--------------------------------------------------------------------------
  | EDIT
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btn-edit-group', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    editGroup(id);
  });

  /*
  |--------------------------------------------------------------------------
  | DELETE
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btn-delete-group', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    deleteGroup(id);
  });

  /*
  |--------------------------------------------------------------------------
  | SEARCH
  |--------------------------------------------------------------------------
  */

  $('#searchGroup').on('keyup', function () {
    if (groupTable) {
      groupTable.ajax.reload();
    }
  });

  /*
  |--------------------------------------------------------------------------
  | LOAD DATATABLE
  |--------------------------------------------------------------------------
  */

  loadGroupTable();
});

/*
|--------------------------------------------------------------------------
| LOAD DATATABLE
|--------------------------------------------------------------------------
*/

function loadGroupTable() {
  groupTable = $('#groupTable').DataTable({
    processing: true,
    serverSide: false,
    searching: false,
    lengthChange: false,
    pageLength: 25,
    responsive: true,
    ajax: {
      url: window.UserPanelConfig.datatableUrl,
      data: function data(d) {
        d.search = $('#searchGroup').val();
      },
      dataSrc: function dataSrc(json) {
        var _json$total, _json$data, _json$data2;
        $('#totalGroup').text((_json$total = json.total) !== null && _json$total !== void 0 ? _json$total : 0);
        var totalRole = 0;
        var totalUser = 0;
        ((_json$data = json.data) !== null && _json$data !== void 0 ? _json$data : []).forEach(function (item) {
          var _item$roles_count, _item$users_count;
          totalRole += Number((_item$roles_count = item.roles_count) !== null && _item$roles_count !== void 0 ? _item$roles_count : 0);
          totalUser += Number((_item$users_count = item.users_count) !== null && _item$users_count !== void 0 ? _item$users_count : 0);
        });
        $('#totalRole').text(totalRole);
        $('#totalUser').text(totalUser);
        return (_json$data2 = json.data) !== null && _json$data2 !== void 0 ? _json$data2 : [];
      }
    },
    columns: [
    /*
    |--------------------------------------------------------------------------
    | NO
    |--------------------------------------------------------------------------
    */

    {
      data: null,
      className: 'text-center',
      render: function render(data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
      }
    },
    /*
    |--------------------------------------------------------------------------
    | GROUP
    |--------------------------------------------------------------------------
    */

    {
      data: 'group_name',
      render: function render(data, type, row) {
        return "\n                        <div class=\"d-flex align-items-center\">\n\n                            <div\n                                class=\"\n                                    rounded-circle\n                                    bg-primary\n                                    bg-opacity-10\n                                    text-primary\n                                    d-flex\n                                    align-items-center\n                                    justify-content-center\n                                    me-3\n                                \"\n                                style=\"\n                                    width:38px;\n                                    height:38px;\n                                \"\n                            >\n                                <i class=\"fas fa-users\"></i>\n                            </div>\n\n                            <div>\n\n                                <div class=\"fw-semibold\">\n                                    ".concat(escapeHtml(data), "\n                                </div>\n\n                                <small class=\"text-muted\">\n                                    Group ID #").concat(row.group_id, "\n                                </small>\n\n                            </div>\n\n                        </div>\n                    ");
      }
    },
    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    {
      data: 'roles_count',
      className: 'text-center',
      render: function render(data) {
        return "\n                        <span\n                            class=\"\n                                badge\n                                bg-success\n                                bg-opacity-10\n                                text-success\n                                px-3\n                                py-2\n                            \"\n                        >\n\n                            <i\n                                class=\"fas fa-user-tag me-1\"\n                            ></i>\n\n                            ".concat(data !== null && data !== void 0 ? data : 0, "\n\n                        </span>\n                    ");
      }
    },
    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    {
      data: 'users_count',
      className: 'text-center',
      render: function render(data) {
        return "\n                        <span\n                            class=\"\n                                badge\n                                bg-info\n                                bg-opacity-10\n                                text-info\n                                px-3\n                                py-2\n                            \"\n                        >\n\n                            <i\n                                class=\"fas fa-user me-1\"\n                            ></i>\n\n                            ".concat(data !== null && data !== void 0 ? data : 0, "\n\n                        </span>\n                    ");
      }
    },
    /*
    |--------------------------------------------------------------------------
    | ACTION
    |--------------------------------------------------------------------------
    */

    {
      data: 'group_id',
      className: 'text-center',
      orderable: false,
      searchable: false,
      render: function render(id) {
        return "\n\n                        <div\n                            class=\"\n                                d-flex\n                                justify-content-center\n                                gap-1\n                            \"\n                        >\n\n                            <button\n                                type=\"button\"\n                                class=\"\n                                    btn\n                                    btn-sm\n                                    btn-outline-primary\n                                    btn-edit-group\n                                \"\n                                data-id=\"".concat(id, "\"\n                                title=\"Edit\"\n                            >\n\n                                <i\n                                    class=\"fas fa-edit\"\n                                ></i>\n\n                            </button>\n\n\n                            <button\n                                type=\"button\"\n                                class=\"\n                                    btn\n                                    btn-sm\n                                    btn-outline-danger\n                                    btn-delete-group\n                                \"\n                                data-id=\"").concat(id, "\"\n                                title=\"Hapus\"\n                            >\n\n                                <i\n                                    class=\"fas fa-trash\"\n                                ></i>\n\n                            </button>\n\n                        </div>\n\n                    ");
      }
    }],
    language: {
      emptyTable: 'Belum ada user group.',
      zeroRecords: 'Data tidak ditemukan.',
      processing: 'Memuat data...',
      paginate: {
        previous: '<i class="fas fa-chevron-left"></i>',
        next: '<i class="fas fa-chevron-right"></i>'
      }
    }
  });
}

/*
|--------------------------------------------------------------------------
| OPEN MODAL
|--------------------------------------------------------------------------
*/

function openGroupModal() {
  console.log('openGroupModal() dijalankan');

  /*
  |--------------------------------------------------------------------------
  | RESET FORM
  |--------------------------------------------------------------------------
  */

  var form = document.getElementById('groupForm');
  if (form) {
    form.reset();
  }

  /*
  |--------------------------------------------------------------------------
  | RESET ID
  |--------------------------------------------------------------------------
  */

  $('#group_id').val('');

  /*
  |--------------------------------------------------------------------------
  | RESET VALIDATION
  |--------------------------------------------------------------------------
  */

  $('#group_name').removeClass('is-invalid');
  $('#groupNameError').text('');

  /*
  |--------------------------------------------------------------------------
  | TITLE
  |--------------------------------------------------------------------------
  */

  $('#groupModalTitle').text('Tambah User Group');

  /*
  |--------------------------------------------------------------------------
  | SHOW MODAL
  |--------------------------------------------------------------------------
  */

  if (!groupModal) {
    var modalElement = document.getElementById('groupModal');
    groupModal = bootstrap.Modal.getOrCreateInstance(modalElement);
  }
  groupModal.show();
}

/*
|--------------------------------------------------------------------------
| EDIT GROUP
|--------------------------------------------------------------------------
*/

function editGroup(id) {
  $.ajax({
    url: "".concat(window.UserPanelConfig.baseUrl, "/").concat(id),
    type: 'GET',
    success: function success(response) {
      var data = response.data;
      $('#group_id').val(data.group_id);
      $('#group_name').val(data.group_name);
      $('#groupModalTitle').text('Edit User Group');
      $('#group_name').removeClass('is-invalid');
      $('#groupNameError').text('');
      groupModal.show();
    },
    error: function error(xhr) {
      console.error(xhr.responseText);
      showError('Gagal mengambil data group.');
    }
  });
}

/*
|--------------------------------------------------------------------------
| SAVE GROUP
|--------------------------------------------------------------------------
*/

function saveGroup() {
  var id = $('#group_id').val();
  var groupName = $('#group_name').val().trim();
  $('#group_name').removeClass('is-invalid');
  $('#groupNameError').text('');

  /*
  |--------------------------------------------------------------------------
  | VALIDATION
  |--------------------------------------------------------------------------
  */

  if (!groupName) {
    $('#group_name').addClass('is-invalid');
    $('#groupNameError').text('Nama group wajib diisi.');
    return;
  }

  /*
  |--------------------------------------------------------------------------
  | URL
  |--------------------------------------------------------------------------
  */

  var url = id ? "".concat(window.UserPanelConfig.baseUrl, "/").concat(id) : window.UserPanelConfig.baseUrl;
  var method = id ? 'PUT' : 'POST';

  /*
  |--------------------------------------------------------------------------
  | BUTTON
  |--------------------------------------------------------------------------
  */

  var button = $('#btnSaveGroup');
  button.prop('disabled', true).html("\n            <span\n                class=\"\n                    spinner-border\n                    spinner-border-sm\n                    me-2\n                \"\n            ></span>\n\n            Menyimpan...\n        ");

  /*
  |--------------------------------------------------------------------------
  | AJAX
  |--------------------------------------------------------------------------
  */

  $.ajax({
    url: url,
    type: method,
    data: {
      group_name: groupName,
      _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function success(response) {
      var _response$message;
      groupModal.hide();
      if (groupTable) {
        groupTable.ajax.reload(null, false);
      }
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: (_response$message = response.message) !== null && _response$message !== void 0 ? _response$message : 'Data berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false
      });
    },
    error: function error(xhr) {
      var _xhr$responseJSON, _xhr$responseJSON$mes, _xhr$responseJSON2;
      console.error(xhr.responseText);
      if (xhr.status === 422 && (_xhr$responseJSON = xhr.responseJSON) !== null && _xhr$responseJSON !== void 0 && _xhr$responseJSON.errors) {
        var errors = xhr.responseJSON.errors;
        if (errors.group_name) {
          $('#group_name').addClass('is-invalid');
          $('#groupNameError').text(errors.group_name[0]);
        }
        return;
      }
      showError((_xhr$responseJSON$mes = (_xhr$responseJSON2 = xhr.responseJSON) === null || _xhr$responseJSON2 === void 0 ? void 0 : _xhr$responseJSON2.message) !== null && _xhr$responseJSON$mes !== void 0 ? _xhr$responseJSON$mes : 'Terjadi kesalahan.');
    },
    complete: function complete() {
      button.prop('disabled', false).html("\n                    <i\n                        class=\"fas fa-save me-2\"\n                    ></i>\n\n                    Simpan\n                ");
    }
  });
}

/*
|--------------------------------------------------------------------------
| DELETE GROUP
|--------------------------------------------------------------------------
*/

function deleteGroup(id) {
  Swal.fire({
    title: 'Hapus User Group?',
    text: 'Data yang sudah dihapus tidak dapat dikembalikan.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus',
    cancelButtonText: 'Batal',
    reverseButtons: true
  }).then(function (result) {
    if (!result.isConfirmed) {
      return;
    }
    $.ajax({
      url: "".concat(window.UserPanelConfig.baseUrl, "/").concat(id),
      type: 'DELETE',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(response) {
        var _response$message2;
        if (groupTable) {
          groupTable.ajax.reload(null, false);
        }
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: (_response$message2 = response.message) !== null && _response$message2 !== void 0 ? _response$message2 : 'Group berhasil dihapus.',
          timer: 1500,
          showConfirmButton: false
        });
      },
      error: function error(xhr) {
        var _xhr$responseJSON$mes2, _xhr$responseJSON3;
        console.error(xhr.responseText);
        Swal.fire({
          icon: 'error',
          title: 'Tidak dapat dihapus',
          text: (_xhr$responseJSON$mes2 = (_xhr$responseJSON3 = xhr.responseJSON) === null || _xhr$responseJSON3 === void 0 ? void 0 : _xhr$responseJSON3.message) !== null && _xhr$responseJSON$mes2 !== void 0 ? _xhr$responseJSON$mes2 : 'Terjadi kesalahan.'
        });
      }
    });
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
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {
  return $('<div>').text(value !== null && value !== void 0 ? value : '').html();
}
/******/ })()
;