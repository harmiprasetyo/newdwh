/******/ (() => { // webpackBootstrap
/*!****************************************************!*\
  !*** ./resources/js/adminpanel/userpanel/roles.js ***!
  \****************************************************/
var roleTable = null;
var roleModal = null;

/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {
  console.log('User Role JS loaded');

  /*
  |--------------------------------------------------------------------------
  | MODAL
  |--------------------------------------------------------------------------
  */

  var modalElement = document.getElementById('roleModal');
  if (modalElement && typeof bootstrap !== 'undefined') {
    roleModal = bootstrap.Modal.getOrCreateInstance(modalElement);
  }

  /*
  |--------------------------------------------------------------------------
  | ADD
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '#btnAddRole', function (e) {
    e.preventDefault();
    openRoleModal();
  });

  /*
  |--------------------------------------------------------------------------
  | FORM
  |--------------------------------------------------------------------------
  */

  $(document).on('submit', '#roleForm', function (e) {
    e.preventDefault();
    saveRole();
  });

  /*
  |--------------------------------------------------------------------------
  | EDIT
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btn-edit-role', function () {
    editRole($(this).data('id'));
  });

  /*
  |--------------------------------------------------------------------------
  | DELETE
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btn-delete-role', function () {
    deleteRole($(this).data('id'));
  });

  /*
  |--------------------------------------------------------------------------
  | SEARCH
  |--------------------------------------------------------------------------
  */

  $('#searchRole').on('keyup', function () {
    if (roleTable) {
      roleTable.ajax.reload();
    }
  });

  /*
  |--------------------------------------------------------------------------
  | LOAD GROUP
  |--------------------------------------------------------------------------
  */

  loadGroups();

  /*
  |--------------------------------------------------------------------------
  | LOAD TABLE
  |--------------------------------------------------------------------------
  */

  loadRoleTable();
});

/*
|--------------------------------------------------------------------------
| LOAD GROUPS
|--------------------------------------------------------------------------
*/

function loadGroups() {
  var selected = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  $.ajax({
    url: window.UserRoleConfig.groupsUrl,
    type: 'GET',
    success: function success(response) {
      var _response$data;
      var html = "\n                <option value=\"\">\n                    Pilih User Group\n                </option>\n            ";
      ((_response$data = response.data) !== null && _response$data !== void 0 ? _response$data : []).forEach(function (group) {
        html += "\n                        <option\n                            value=\"".concat(group.group_id, "\"\n                        >\n                            ").concat(escapeHtml(group.group_name), "\n                        </option>\n                    ");
      });
      $('#groupId').html(html);
      if (selected) {
        $('#groupId').val(selected);
      }
    },
    error: function error(xhr) {
      console.error(xhr.responseText);
      showError('Gagal memuat User Group.');
    }
  });
}

/*
|--------------------------------------------------------------------------
| DATATABLE
|--------------------------------------------------------------------------
*/

function loadRoleTable() {
  roleTable = $('#roleTable').DataTable({
    processing: true,
    serverSide: false,
    searching: false,
    lengthChange: false,
    pageLength: 25,
    responsive: true,
    ajax: {
      url: window.UserRoleConfig.datatableUrl,
      data: function data(d) {
        d.search = $('#searchRole').val();
      },
      dataSrc: function dataSrc(json) {
        var _json$data, _json$total;
        var data = (_json$data = json.data) !== null && _json$data !== void 0 ? _json$data : [];
        $('#totalRole').text((_json$total = json.total) !== null && _json$total !== void 0 ? _json$total : data.length);
        var groups = new Set(data.map(function (item) {
          return item.groupId;
        }));
        $('#totalGroup').text(groups.size);
        var users = data.reduce(function (total, item) {
          var _item$users_count;
          return total + Number((_item$users_count = item.users_count) !== null && _item$users_count !== void 0 ? _item$users_count : 0);
        }, 0);
        $('#totalUser').text(users);
        return data;
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
    | ROLE
    |--------------------------------------------------------------------------
    */

    {
      data: 'role_name',
      render: function render(data, type, row) {
        return "\n\n                                <div\n                                    class=\"\n                                        d-flex\n                                        align-items-center\n                                    \"\n                                >\n\n                                    <div\n                                        class=\"\n                                            rounded-circle\n                                            bg-success\n                                            bg-opacity-10\n                                            text-success\n                                            d-flex\n                                            align-items-center\n                                            justify-content-center\n                                            me-3\n                                        \"\n                                        style=\"\n                                            width:38px;\n                                            height:38px;\n                                        \"\n                                    >\n\n                                        <i\n                                            class=\"\n                                                fas\n                                                fa-user-tag\n                                            \"\n                                        ></i>\n\n                                    </div>\n\n\n                                    <div>\n\n                                        <div\n                                            class=\"fw-semibold\"\n                                        >\n                                            ".concat(escapeHtml(data), "\n                                        </div>\n\n                                        <small\n                                            class=\"text-muted\"\n                                        >\n                                            Role ID #").concat(row.id, "\n                                        </small>\n\n                                    </div>\n\n                                </div>\n\n                            ");
      }
    },
    /*
    |--------------------------------------------------------------------------
    | GROUP
    |--------------------------------------------------------------------------
    */

    {
      data: 'group',
      render: function render(data) {
        if (!data) {
          return "\n                                    <span\n                                        class=\"\n                                            badge\n                                            bg-secondary\n                                        \"\n                                    >\n                                        -\n                                    </span>\n                                ";
        }
        return "\n\n                                <span\n                                    class=\"\n                                        badge\n                                        bg-primary\n                                        bg-opacity-10\n                                        text-primary\n                                        px-3\n                                        py-2\n                                    \"\n                                >\n\n                                    <i\n                                        class=\"\n                                            fas\n                                            fa-users\n                                            me-1\n                                        \"\n                                    ></i>\n\n                                    ".concat(escapeHtml(data.group_name), "\n\n                                </span>\n\n                            ");
      }
    },
    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    {
      data: 'users_count',
      className: 'text-center',
      render: function render(data) {
        return "\n\n                                <span\n                                    class=\"\n                                        badge\n                                        bg-info\n                                        bg-opacity-10\n                                        text-info\n                                        px-3\n                                        py-2\n                                    \"\n                                >\n\n                                    <i\n                                        class=\"\n                                            fas\n                                            fa-user\n                                            me-1\n                                        \"\n                                    ></i>\n\n                                    ".concat(data !== null && data !== void 0 ? data : 0, "\n\n                                </span>\n\n                            ");
      }
    },
    /*
    |--------------------------------------------------------------------------
    | ACTION
    |--------------------------------------------------------------------------
    */

    {
      data: 'id',
      className: 'text-center',
      orderable: false,
      searchable: false,
      render: function render(id) {
        return "\n\n                                <div\n                                    class=\"\n                                        d-flex\n                                        justify-content-center\n                                        gap-1\n                                    \"\n                                >\n\n                                    <button\n                                        type=\"button\"\n                                        class=\"\n                                            btn\n                                            btn-sm\n                                            btn-outline-primary\n                                            btn-edit-role\n                                        \"\n                                        data-id=\"".concat(id, "\"\n                                        title=\"Edit\"\n                                    >\n\n                                        <i\n                                            class=\"\n                                                fas\n                                                fa-edit\n                                            \"\n                                        ></i>\n\n                                    </button>\n\n\n                                    <button\n                                        type=\"button\"\n                                        class=\"\n                                            btn\n                                            btn-sm\n                                            btn-outline-danger\n                                            btn-delete-role\n                                        \"\n                                        data-id=\"").concat(id, "\"\n                                        title=\"Hapus\"\n                                    >\n\n                                        <i\n                                            class=\"\n                                                fas\n                                                fa-trash\n                                            \"\n                                        ></i>\n\n                                    </button>\n\n                                </div>\n\n                            ");
      }
    }],
    language: {
      emptyTable: 'Belum ada user role.',
      zeroRecords: 'Role tidak ditemukan.',
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

function openRoleModal() {
  $('#roleForm')[0].reset();
  $('#role_id').val('');
  $('#role_name').removeClass('is-invalid');
  $('#groupId').removeClass('is-invalid');
  $('#roleNameError').text('');
  $('#groupIdError').text('');
  $('#roleModalTitle').text('Tambah User Role');
  loadGroups();
  if (!roleModal) {
    roleModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('roleModal'));
  }
  roleModal.show();
}

/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

function editRole(id) {
  $.ajax({
    url: "".concat(window.UserRoleConfig.baseUrl, "/").concat(id),
    type: 'GET',
    success: function success(response) {
      var data = response.data;
      $('#role_id').val(data.id);
      $('#role_name').val(data.role_name);
      $('#roleModalTitle').text('Edit User Role');
      loadGroups(data.groupId);
      $('#role_name').removeClass('is-invalid');
      $('#groupId').removeClass('is-invalid');
      if (!roleModal) {
        roleModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('roleModal'));
      }
      roleModal.show();
    },
    error: function error(xhr) {
      console.error(xhr.responseText);
      showError('Gagal mengambil data role.');
    }
  });
}

/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/

function saveRole() {
  var id = $('#role_id').val();
  var roleName = $('#role_name').val().trim();
  var groupId = $('#groupId').val();

  /*
  |--------------------------------------------------------------------------
  | RESET ERROR
  |--------------------------------------------------------------------------
  */

  $('#role_name').removeClass('is-invalid');
  $('#groupId').removeClass('is-invalid');
  $('#roleNameError').text('');
  $('#groupIdError').text('');

  /*
  |--------------------------------------------------------------------------
  | VALIDATION
  |--------------------------------------------------------------------------
  */

  var valid = true;
  if (!groupId) {
    $('#groupId').addClass('is-invalid');
    $('#groupIdError').text('User Group wajib dipilih.');
    valid = false;
  }
  if (!roleName) {
    $('#role_name').addClass('is-invalid');
    $('#roleNameError').text('Nama role wajib diisi.');
    valid = false;
  }
  if (!valid) {
    return;
  }

  /*
  |--------------------------------------------------------------------------
  | URL
  |--------------------------------------------------------------------------
  */

  var url = id ? "".concat(window.UserRoleConfig.baseUrl, "/").concat(id) : window.UserRoleConfig.baseUrl;
  var method = id ? 'PUT' : 'POST';

  /*
  |--------------------------------------------------------------------------
  | BUTTON
  |--------------------------------------------------------------------------
  */

  var button = $('#btnSaveRole');
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
      role_name: roleName,
      groupId: groupId,
      _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function success(response) {
      var _response$message;
      roleModal.hide();
      roleTable.ajax.reload(null, false);
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: (_response$message = response.message) !== null && _response$message !== void 0 ? _response$message : 'Role berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false
      });
    },
    error: function error(xhr) {
      var _xhr$responseJSON, _xhr$responseJSON$mes, _xhr$responseJSON2;
      console.error(xhr.responseText);
      if (xhr.status === 422 && (_xhr$responseJSON = xhr.responseJSON) !== null && _xhr$responseJSON !== void 0 && _xhr$responseJSON.errors) {
        var errors = xhr.responseJSON.errors;
        if (errors.role_name) {
          $('#role_name').addClass('is-invalid');
          $('#roleNameError').text(errors.role_name[0]);
        }
        if (errors.groupId) {
          $('#groupId').addClass('is-invalid');
          $('#groupIdError').text(errors.groupId[0]);
        }
        return;
      }
      showError((_xhr$responseJSON$mes = (_xhr$responseJSON2 = xhr.responseJSON) === null || _xhr$responseJSON2 === void 0 ? void 0 : _xhr$responseJSON2.message) !== null && _xhr$responseJSON$mes !== void 0 ? _xhr$responseJSON$mes : 'Terjadi kesalahan.');
    },
    complete: function complete() {
      button.prop('disabled', false).html("\n                        <i\n                            class=\"\n                                fas\n                                fa-save\n                                me-2\n                            \"\n                        ></i>\n\n                        Simpan\n                    ");
    }
  });
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

function deleteRole(id) {
  Swal.fire({
    title: 'Hapus User Role?',
    text: 'Role yang masih digunakan oleh user tidak dapat dihapus.',
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
      url: "".concat(window.UserRoleConfig.baseUrl, "/").concat(id),
      type: 'DELETE',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(response) {
        var _response$message2;
        roleTable.ajax.reload(null, false);
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: (_response$message2 = response.message) !== null && _response$message2 !== void 0 ? _response$message2 : 'Role berhasil dihapus.',
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
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {
  return $('<div>').text(value !== null && value !== void 0 ? value : '').html();
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
/******/ })()
;