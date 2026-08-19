/******/ (() => { // webpackBootstrap
/*!****************************************************!*\
  !*** ./resources/js/adminpanel/userpanel/users.js ***!
  \****************************************************/
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var userTable = null;
var userModal = null;

/*
|--------------------------------------------------------------------------
| CSRF CONFIGURATION
|--------------------------------------------------------------------------
*/

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {
  userModal = new bootstrap.Modal(document.getElementById('userModal'));
  initSelect2();
  loadGroups();
  loadProvinces();
  loadUserTable();
  bindEvents();
});

/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/
function bindEvents() {
  /*
  |--------------------------------------------------------------------------
  | ADD USER
  |--------------------------------------------------------------------------
  */

  $('#btnAddUser').on('click', function () {
    openUserModal();
  });

  /*
  |--------------------------------------------------------------------------
  | SEARCH
  |--------------------------------------------------------------------------
  */

  $('#searchUser').on('keyup', debounce(function () {
    if (userTable) {
      userTable.ajax.reload();
    }
  }, 400));

  /*
  |--------------------------------------------------------------------------
  | FILTER GROUP
  |--------------------------------------------------------------------------
  */

  $('#filterGroup').on('change', function () {
    var groupId = $(this).val();
    loadFilterRoles(groupId);
    if (userTable) {
      userTable.ajax.reload();
    }
  });

  /*
  |--------------------------------------------------------------------------
  | FILTER LAIN
  |--------------------------------------------------------------------------
  */

  $('#filterRole, #filterProvinsi, #filterKota').on('change', function () {
    if (userTable) {
      userTable.ajax.reload();
    }
  });

  /*
  |--------------------------------------------------------------------------
  | GROUP → ROLE
  |--------------------------------------------------------------------------
  */

  $('#groupid').on('change', function () {
    var groupId = $(this).val();
    loadRoles(groupId);
  });

  /*
  |--------------------------------------------------------------------------
  | PROVINSI → KOTA
  |--------------------------------------------------------------------------
  */

  $('#kodePropinsi').on('change', function () {
    var province = $(this).val();
    loadCities(province);
  });

  /*
  |--------------------------------------------------------------------------
  | KOTA → KECAMATAN
  |--------------------------------------------------------------------------
  */

  $('#kodeKota').on('change', function () {
    var city = $(this).val();
    loadDistricts(city);
  });

  /*
  |--------------------------------------------------------------------------
  | KECAMATAN → FASKES
  |--------------------------------------------------------------------------
  */

  $('#kodeKecamatan').on('change', function () {
    var district = $(this).val();
    loadFaskes(district);
  });

  /*
  |--------------------------------------------------------------------------
  | FORM SUBMIT
  |--------------------------------------------------------------------------
  */

  $('#userForm').on('submit', function (e) {
    e.preventDefault();
    saveUser();
  });

  /*
  |--------------------------------------------------------------------------
  | RESET FILTER
  |--------------------------------------------------------------------------
  */

  $('#btnResetFilter').on('click', function () {
    $('#searchUser').val('');
    $('#filterGroup').val('').trigger('change');
    $('#filterRole').html('<option value="">Semua Role</option>').val('').trigger('change');
    $('#filterProvinsi').val('').trigger('change');
    $('#filterKota').html('<option value="">Semua Kota</option>').val('').trigger('change');
    if (userTable) {
      userTable.ajax.reload();
    }
  });
}

/*
|--------------------------------------------------------------------------
| DATATABLE
|--------------------------------------------------------------------------
*/

function loadUserTable() {
  userTable = $('#userTable').DataTable({
    processing: true,
    serverSide: false,
    searching: false,
    lengthChange: true,
    pageLength: 25,
    responsive: true,
    ajax: {
      url: window.UserPanelConfig.datatableUrl,
      data: function data(d) {
        d.search = $('#searchUser').val();
        d.groupid = $('#filterGroup').val();
        d.role_id = $('#filterRole').val();
        d.kodePropinsi = $('#filterProvinsi').val();
        d.kodeKota = $('#filterKota').val();
      },
      dataSrc: function dataSrc(json) {
        var _json$data;
        var data = (_json$data = json.data) !== null && _json$data !== void 0 ? _json$data : [];
        $('#totalUser').text(data.length);
        $('#totalAdmin').text(data.filter(function (x) {
          return x.role === 'administrator';
        }).length);
        $('#totalFaskes').text(data.filter(function (x) {
          return x.role === 'faskes';
        }).length);
        return data;
      }
    },
    columns: [{
      data: null,
      className: 'text-center',
      render: function render(data, type, row, meta) {
        return meta.row + 1;
      }
    }, {
      data: null,
      render: function render(data) {
        return "\n\n                        <div class=\"d-flex align-items-center\">\n\n                            <div\n                                class=\"rounded-circle\n                                       bg-primary\n                                       bg-opacity-10\n                                       text-primary\n                                       d-flex\n                                       align-items-center\n                                       justify-content-center\n                                       me-3\"\n                                style=\"\n                                    width:42px;\n                                    height:42px;\n                                \"\n                            >\n\n                                <i class=\"fas fa-user\"></i>\n\n                            </div>\n\n                            <div>\n\n                                <div class=\"fw-semibold\">\n                                    ".concat(escapeHtml(data.namalengkap), "\n                                </div>\n\n                                <small class=\"text-muted\">\n                                    @").concat(escapeHtml(data.username), "\n                                </small>\n\n                                <br>\n\n                                <small class=\"text-muted\">\n                                    ").concat(escapeHtml(data.email), "\n                                </small>\n\n                            </div>\n\n                        </div>\n\n                    ");
      }
    }, {
      data: 'group_name',
      defaultContent: '-',
      render: function render(data) {
        return data ? "\n                            <span class=\"badge bg-primary bg-opacity-10 text-primary px-3 py-2\">\n                                <i class=\"fas fa-users me-1\"></i>\n                                ".concat(escapeHtml(data), "\n                            </span>\n                        ") : '-';
      }
    }, {
      data: 'role_name',
      defaultContent: '-',
      render: function render(data) {
        return data ? "\n                            <span class=\"badge bg-success bg-opacity-10 text-success px-3 py-2\">\n                                <i class=\"fas fa-user-tag me-1\"></i>\n                                ".concat(escapeHtml(data), "\n                            </span>\n                        ") : "\n                            <span class=\"text-muted\">\n                                -\n                            </span>\n                        ";
      }
    }, {
      data: null,
      render: function render(data) {
        var _data$kodeFaskes;
        if (!data.namaFaskes) {
          return "\n                            <span class=\"text-muted\">\n                                Tidak ada faskes\n                            </span>\n                        ";
        }
        return "\n\n                        <div>\n\n                            <div class=\"fw-semibold\">\n\n                                ".concat(escapeHtml(data.namaFaskes), "\n\n                            </div>\n\n                            <small class=\"text-muted\">\n\n                                ").concat(escapeHtml((_data$kodeFaskes = data.kodeFaskes) !== null && _data$kodeFaskes !== void 0 ? _data$kodeFaskes : ''), "\n\n                            </small>\n\n                        </div>\n\n                    ");
      }
    }, {
      data: null,
      render: function render(data) {
        var _ref, _data$provinsi_name, _ref2, _data$kota_name, _ref3, _data$kecamatan_name;
        var province = (_ref = (_data$provinsi_name = data.provinsi_name) !== null && _data$provinsi_name !== void 0 ? _data$provinsi_name : data.kodePropinsi) !== null && _ref !== void 0 ? _ref : '-';
        var city = (_ref2 = (_data$kota_name = data.kota_name) !== null && _data$kota_name !== void 0 ? _data$kota_name : data.kodeKota) !== null && _ref2 !== void 0 ? _ref2 : '-';
        var district = (_ref3 = (_data$kecamatan_name = data.kecamatan_name) !== null && _data$kecamatan_name !== void 0 ? _data$kecamatan_name : data.kodeKecamatan) !== null && _ref3 !== void 0 ? _ref3 : '-';
        return "\n\n            <div>\n\n                <div class=\"fw-semibold\">\n                    ".concat(escapeHtml(city), "\n                </div>\n\n                <small class=\"text-muted\">\n\n                    ").concat(escapeHtml(province), "\n\n                    ").concat(district !== '-' ? ' • ' + escapeHtml(district) : '', "\n\n                </small>\n\n            </div>\n\n        ");
      }
    }, {
      data: 'userid',
      className: 'text-center',
      orderable: false,
      render: function render(id) {
        return "\n\n                        <div class=\"d-flex justify-content-center gap-1\">\n\n                            <button\n                                type=\"button\"\n                                class=\"btn btn-sm btn-outline-primary\"\n                                onclick=\"editUser('".concat(id, "')\"\n                                title=\"Edit\"\n                            >\n                                <i class=\"fas fa-edit\"></i>\n                            </button>\n\n                            <button\n                                type=\"button\"\n                                class=\"btn btn-sm btn-outline-danger\"\n                                onclick=\"deleteUser('").concat(id, "')\"\n                                title=\"Hapus\"\n                            >\n                                <i class=\"fas fa-trash\"></i>\n                            </button>\n\n                        </div>\n\n                    ");
      }
    }],
    order: [[1, 'asc']],
    language: {
      emptyTable: 'Belum ada user.',
      zeroRecords: 'Data tidak ditemukan.',
      processing: 'Memuat data...',
      lengthMenu: '_MENU_ data',
      info: 'Menampilkan _START_ - _END_ dari _TOTAL_ user',
      paginate: {
        previous: '<i class="fas fa-chevron-left"></i>',
        next: '<i class="fas fa-chevron-right"></i>'
      }
    }
  });
}

/*
|--------------------------------------------------------------------------
| OPEN
|--------------------------------------------------------------------------
*/

function openUserModal() {
  $('#userForm')[0].reset();
  $('#userid').val('');
  clearValidation();
  $('#userModalTitle').text('Tambah User');
  $('#password').val('').prop('required', true);
  $('#passwordHelp').text('Password wajib diisi. Minimal 6 karakter.');

  /*
  |--------------------------------------------------------------------------
  | GROUP
  |--------------------------------------------------------------------------
  */

  $('#groupid').val('').trigger('change.select2');

  /*
  |--------------------------------------------------------------------------
  | ROLE
  |--------------------------------------------------------------------------
  */

  $('#role_id').html('<option value="">Pilih Role</option>').val('').trigger('change.select2');

  /*
  |--------------------------------------------------------------------------
  | LOCATION
  |--------------------------------------------------------------------------
  */

  $('#kodePropinsi').val('').trigger('change.select2');
  $('#kodeKota').html('<option value="">Pilih Kota / Kabupaten</option>').val('').trigger('change.select2');
  $('#kodeKecamatan').html('<option value="">Pilih Kecamatan</option>').val('').trigger('change.select2');
  $('#kodeFaskes').html('<option value="">Pilih Faskes</option>').val('').trigger('change.select2');
  userModal.show();
}

/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

function editUser(id) {
  clearValidation();
  $.ajax({
    url: "".concat(window.UserPanelConfig.baseUrl, "/").concat(id),
    type: 'GET',
    beforeSend: function beforeSend() {
      Swal.fire({
        title: 'Memuat data user...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: function didOpen() {
          Swal.showLoading();
        }
      });
    },
    success: function () {
      var _success = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(response) {
        var data;
        return _regenerator().w(function (_context) {
          while (1) switch (_context.n) {
            case 0:
              data = response.data;
              /*
              |--------------------------------------------------------------------------
              | BASIC DATA
              |--------------------------------------------------------------------------
              */
              $('#userid').val(data.userid);
              $('#username').val(data.username);
              $('#namalengkap').val(data.namalengkap);
              $('#email').val(data.email);
              $('#role').val(data.role);

              /*
              |--------------------------------------------------------------------------
              | PASSWORD
              |--------------------------------------------------------------------------
              */

              $('#password').val('').prop('required', false);
              $('#passwordHelp').text('Kosongkan jika password tidak ingin diubah.');

              /*
              |--------------------------------------------------------------------------
              | TITLE
              |--------------------------------------------------------------------------
              */

              $('#userModalTitle').text('Edit User');

              /*
              |--------------------------------------------------------------------------
              | GROUP → ROLE
              |--------------------------------------------------------------------------
              */
              _context.n = 1;
              return loadEditGroupAndRole(data);
            case 1:
              _context.n = 2;
              return loadEditLocation(data);
            case 2:
              /*
              |--------------------------------------------------------------------------
              | TUTUP LOADING
              |--------------------------------------------------------------------------
              */

              Swal.close();

              /*
              |--------------------------------------------------------------------------
              | SHOW MODAL
              |--------------------------------------------------------------------------
              */

              userModal.show();
            case 3:
              return _context.a(2);
          }
        }, _callee);
      }));
      function success(_x) {
        return _success.apply(this, arguments);
      }
      return success;
    }(),
    error: function error(xhr) {
      var _xhr$responseJSON$mes, _xhr$responseJSON;
      Swal.close();
      showError((_xhr$responseJSON$mes = (_xhr$responseJSON = xhr.responseJSON) === null || _xhr$responseJSON === void 0 ? void 0 : _xhr$responseJSON.message) !== null && _xhr$responseJSON$mes !== void 0 ? _xhr$responseJSON$mes : 'Gagal mengambil data user.');
    }
  });
}

/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/

function saveUser() {
  var id = $('#userid').val();
  var data = {
    username: $('#username').val(),
    namalengkap: $('#namalengkap').val(),
    email: $('#email').val(),
    password: $('#password').val(),
    groupid: $('#groupid').val(),
    role_id: $('#role_id').val(),
    role: $('#role').val(),
    kodePropinsi: $('#kodePropinsi').val(),
    kodeKota: $('#kodeKota').val(),
    kodeKecamatan: $('#kodeKecamatan').val(),
    kodeFaskes: $('#kodeFaskes').val(),
    namaFaskes: $('#kodeFaskes option:selected').text()
  };
  clearValidation();
  var url = id ? "".concat(window.UserPanelConfig.baseUrl, "/").concat(id) : window.UserPanelConfig.baseUrl;
  var method = id ? 'PUT' : 'POST';
  var button = $('#btnSaveUser');
  button.prop('disabled', true).html("\n            <span class=\"spinner-border spinner-border-sm me-2\"></span>\n            Menyimpan...\n        ");
  $.ajax({
    url: url,
    type: method,
    data: data,
    success: function success(response) {
      var _response$message;
      userModal.hide();
      userTable.ajax.reload(null, false);
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: (_response$message = response.message) !== null && _response$message !== void 0 ? _response$message : 'User berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false
      });
    },
    error: function error(xhr) {
      var _xhr$responseJSON2, _xhr$responseJSON$mes2, _xhr$responseJSON3;
      if (xhr.status === 422 && (_xhr$responseJSON2 = xhr.responseJSON) !== null && _xhr$responseJSON2 !== void 0 && _xhr$responseJSON2.errors) {
        showValidationErrors(xhr.responseJSON.errors);
        return;
      }
      showError((_xhr$responseJSON$mes2 = (_xhr$responseJSON3 = xhr.responseJSON) === null || _xhr$responseJSON3 === void 0 ? void 0 : _xhr$responseJSON3.message) !== null && _xhr$responseJSON$mes2 !== void 0 ? _xhr$responseJSON$mes2 : 'Terjadi kesalahan.');
    },
    complete: function complete() {
      button.prop('disabled', false).html("\n                        <i class=\"fas fa-save me-2\"></i>\n                        Simpan User\n                    ");
    }
  });
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

function deleteUser(id) {
  Swal.fire({
    title: 'Hapus User?',
    text: 'Data user akan dihapus secara permanen.',
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
      success: function success(response) {
        var _response$message2;
        userTable.ajax.reload(null, false);
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: (_response$message2 = response.message) !== null && _response$message2 !== void 0 ? _response$message2 : 'User berhasil dihapus.',
          timer: 1500,
          showConfirmButton: false
        });
      },
      error: function error(xhr) {
        var _xhr$responseJSON$mes3, _xhr$responseJSON4;
        showError((_xhr$responseJSON$mes3 = (_xhr$responseJSON4 = xhr.responseJSON) === null || _xhr$responseJSON4 === void 0 ? void 0 : _xhr$responseJSON4.message) !== null && _xhr$responseJSON$mes3 !== void 0 ? _xhr$responseJSON$mes3 : 'User tidak dapat dihapus.');
      }
    });
  });
}

/*
|--------------------------------------------------------------------------
| GROUP
|--------------------------------------------------------------------------
*/

function loadGroups() {
  $.get('/adminpanel/userpanel/groups/datatable', function (response) {
    var _response$data;
    var filter = '<option value="">Semua Group</option>';
    var modal = '<option value="">Pilih Group</option>';
    ((_response$data = response.data) !== null && _response$data !== void 0 ? _response$data : []).forEach(function (item) {
      filter += "\n                            <option value=\"".concat(item.group_id, "\">\n                                ").concat(escapeHtml(item.group_name), "\n                            </option>\n                        ");
      modal += "\n                            <option value=\"".concat(item.group_id, "\">\n                                ").concat(escapeHtml(item.group_name), "\n                            </option>\n                        ");
    });
    $('#filterGroup').html(filter).trigger('change.select2');
    $('#groupid').html(modal).trigger('change.select2');
  });
}

/*
|--------------------------------------------------------------------------
| ROLE
|--------------------------------------------------------------------------
*/
function loadRoles(groupId) {
  var $role = $('#role_id');
  $role.html('<option value="">Memuat role...</option>').trigger('change.select2');
  if (!groupId) {
    $role.html('<option value="">Pilih Role</option>').trigger('change.select2');
    return;
  }
  $.ajax({
    url: '/adminpanel/userpanel/roles/bygroup',
    type: 'GET',
    data: {
      groupId: groupId
    },
    success: function success(response) {
      var _response$data2;
      var option = '<option value="">Pilih Role</option>';
      ((_response$data2 = response.data) !== null && _response$data2 !== void 0 ? _response$data2 : []).forEach(function (item) {
        option += "\n                    <option value=\"".concat(item.id, "\">\n                        ").concat(escapeHtml(item.role_name), "\n                    </option>\n                ");
      });
      $role.html(option).trigger('change.select2');
    },
    error: function error(xhr) {
      var _xhr$responseJSON5, _xhr$responseJSON$mes4, _xhr$responseJSON6;
      console.error('Gagal mengambil role:', (_xhr$responseJSON5 = xhr.responseJSON) !== null && _xhr$responseJSON5 !== void 0 ? _xhr$responseJSON5 : xhr.responseText);
      $role.html('<option value="">Role tidak tersedia</option>').trigger('change.select2');
      showError((_xhr$responseJSON$mes4 = (_xhr$responseJSON6 = xhr.responseJSON) === null || _xhr$responseJSON6 === void 0 ? void 0 : _xhr$responseJSON6.message) !== null && _xhr$responseJSON$mes4 !== void 0 ? _xhr$responseJSON$mes4 : 'Gagal mengambil data role.');
    }
  });
}

/*
|--------------------------------------------------------------------------
| FILTER ROLE
|--------------------------------------------------------------------------
*/

function loadFilterRoles(groupId) {
  var option = '<option value="">Semua Role</option>';
  if (!groupId) {
    $('#filterRole').html(option).trigger('change.select2');
    return;
  }
  $.get('/adminpanel/userpanel/roles/datatable', {
    groupId: groupId
  }, function (response) {
    var _response$data3;
    ((_response$data3 = response.data) !== null && _response$data3 !== void 0 ? _response$data3 : []).forEach(function (item) {
      option += "\n                            <option value=\"".concat(item.id, "\">\n                                ").concat(escapeHtml(item.role_name), "\n                            </option>\n                        ");
    });
    $('#filterRole').html(option).trigger('change.select2');
  });
}

/*
|--------------------------------------------------------------------------
| PROVINCE
|--------------------------------------------------------------------------
*/

function loadProvinces() {
  $.ajax({
    url: '/adminpanel/wilayah/listpropinsi',
    type: 'GET',
    success: function success(response) {
      var _response$data4;
      var provinces = (_response$data4 = response.data) !== null && _response$data4 !== void 0 ? _response$data4 : [];
      var filter = '<option value="">Semua Provinsi</option>';
      var modal = '<option value="">Pilih Provinsi</option>';
      provinces.forEach(function (item) {
        filter += "\n                    <option value=\"".concat(item.code, "\">\n                        ").concat(escapeHtml(item.name), "\n                    </option>\n                ");
        modal += "\n                    <option value=\"".concat(item.code, "\">\n                        ").concat(escapeHtml(item.name), "\n                    </option>\n                ");
      });
      $('#filterProvinsi').html(filter).trigger('change');
      $('#kodePropinsi').html(modal).trigger('change');
    },
    error: function error(xhr) {
      var _xhr$responseJSON7;
      console.error('LOAD PROVINCE ERROR:', (_xhr$responseJSON7 = xhr.responseJSON) !== null && _xhr$responseJSON7 !== void 0 ? _xhr$responseJSON7 : xhr.responseText);
      showError('Gagal mengambil data provinsi.');
    }
  });
}

/*
|--------------------------------------------------------------------------
| CITY
|--------------------------------------------------------------------------
*/

function loadCities(province) {
  var selectedCity = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var callback = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
  var select = $('#kodeKota');
  select.html('<option value="">Pilih Kota / Kabupaten</option>');
  select.val('').trigger('change');
  if (!province) {
    if (typeof callback === 'function') {
      callback();
    }
    return;
  }
  select.prop('disabled', true);
  $.ajax({
    url: '/adminpanel/wilayah/listkota',
    type: 'GET',
    data: {
      province_code: province
    },
    success: function success(response) {
      var _response$data5;
      var cities = (_response$data5 = response.data) !== null && _response$data5 !== void 0 ? _response$data5 : [];
      cities.forEach(function (item) {
        select.append("\n                    <option value=\"".concat(item.code, "\">\n                        ").concat(escapeHtml(item.name), "\n                    </option>\n                "));
      });
      if (selectedCity) {
        select.val(selectedCity).trigger('change');
      }
      if (typeof callback === 'function') {
        callback();
      }
    },
    error: function error(xhr) {
      var _xhr$responseJSON8;
      console.error('LOAD CITY ERROR:', (_xhr$responseJSON8 = xhr.responseJSON) !== null && _xhr$responseJSON8 !== void 0 ? _xhr$responseJSON8 : xhr.responseText);
      showError('Gagal mengambil data kota/kabupaten.');
    },
    complete: function complete() {
      select.prop('disabled', false);
    }
  });
}

/*
|--------------------------------------------------------------------------
| DISTRICT
|--------------------------------------------------------------------------
*/
function loadDistricts(city) {
  var selectedDistrict = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var callback = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
  var select = $('#kodeKecamatan');
  select.html('<option value="">Pilih Kecamatan</option>');
  select.val('').trigger('change');
  if (!city) {
    if (typeof callback === 'function') {
      callback();
    }
    return;
  }
  select.prop('disabled', true);
  $.ajax({
    url: '/adminpanel/wilayah/listkecamatan',
    type: 'GET',
    data: {
      city_code: city
    },
    success: function success(response) {
      var _response$data6;
      var districts = (_response$data6 = response.data) !== null && _response$data6 !== void 0 ? _response$data6 : [];
      districts.forEach(function (item) {
        select.append("\n                    <option value=\"".concat(item.code, "\">\n                        ").concat(escapeHtml(item.name), "\n                    </option>\n                "));
      });
      if (selectedDistrict) {
        select.val(selectedDistrict).trigger('change');
      }
      if (typeof callback === 'function') {
        callback();
      }
    },
    error: function error(xhr) {
      var _xhr$responseJSON9;
      console.error('LOAD DISTRICT ERROR:', (_xhr$responseJSON9 = xhr.responseJSON) !== null && _xhr$responseJSON9 !== void 0 ? _xhr$responseJSON9 : xhr.responseText);
      showError('Gagal mengambil data kecamatan.');
    },
    complete: function complete() {
      select.prop('disabled', false);
    }
  });
}

/*
|--------------------------------------------------------------------------
| FASKES
|--------------------------------------------------------------------------
*/
function loadFaskes(district) {
  var select = $('#kodeFaskes');
  select.html('<option value="">Memuat Faskes...</option>').prop('disabled', true).trigger('change.select2');
  if (!district) {
    select.html('<option value="">Pilih Faskes</option>').prop('disabled', true).trigger('change.select2');
    return;
  }
  $.ajax({
    url: window.UserPanelConfig.faskesUrl,
    type: 'GET',
    data: {
      kecamatan: district
    },
    success: function success(response) {
      var _response$data7;
      var option = '<option value="">Pilih Faskes</option>';
      ((_response$data7 = response.data) !== null && _response$data7 !== void 0 ? _response$data7 : []).forEach(function (item) {
        option += "\n                        <option value=\"".concat(escapeHtml(item.kodeFaskes), "\">\n                            ").concat(escapeHtml(item.namaFaskes), "\n                        </option>\n                    ");
      });
      select.html(option).prop('disabled', false).trigger('change.select2');
    },
    error: function error(xhr) {
      var _xhr$responseJSON$mes5, _xhr$responseJSON0;
      console.error('Gagal mengambil faskes:', xhr.responseText);
      select.html('<option value="">Gagal memuat faskes</option>').prop('disabled', true).trigger('change.select2');
      showError((_xhr$responseJSON$mes5 = (_xhr$responseJSON0 = xhr.responseJSON) === null || _xhr$responseJSON0 === void 0 ? void 0 : _xhr$responseJSON0.message) !== null && _xhr$responseJSON$mes5 !== void 0 ? _xhr$responseJSON$mes5 : 'Gagal mengambil data faskes.');
    }
  });
}

/*
|--------------------------------------------------------------------------
| EDIT LOCATION
|--------------------------------------------------------------------------
*/
function loadEditLocation(_x2) {
  return _loadEditLocation.apply(this, arguments);
}
function _loadEditLocation() {
  _loadEditLocation = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2(data) {
    var _ref5, _data$kodePropinsi, _ref6, _data$kodeKota, _ref7, _data$kodeKecamatan, _ref8, _data$kodeFaskes2;
    var provinceCode, cityCode, districtCode, faskesCode;
    return _regenerator().w(function (_context2) {
      while (1) switch (_context2.n) {
        case 0:
          /*
          |--------------------------------------------------------------------------
          | GET LOCATION VALUES
          |--------------------------------------------------------------------------
          */
          provinceCode = (_ref5 = (_data$kodePropinsi = data.kodePropinsi) !== null && _data$kodePropinsi !== void 0 ? _data$kodePropinsi : data.kode_propinsi) !== null && _ref5 !== void 0 ? _ref5 : '';
          cityCode = (_ref6 = (_data$kodeKota = data.kodeKota) !== null && _data$kodeKota !== void 0 ? _data$kodeKota : data.kode_kota) !== null && _ref6 !== void 0 ? _ref6 : '';
          districtCode = (_ref7 = (_data$kodeKecamatan = data.kodeKecamatan) !== null && _data$kodeKecamatan !== void 0 ? _data$kodeKecamatan : data.kode_kecamatan) !== null && _ref7 !== void 0 ? _ref7 : '';
          faskesCode = (_ref8 = (_data$kodeFaskes2 = data.kodeFaskes) !== null && _data$kodeFaskes2 !== void 0 ? _data$kodeFaskes2 : data.kode_faskes) !== null && _ref8 !== void 0 ? _ref8 : '';
          console.log('EDIT LOCATION:', {
            provinceCode: provinceCode,
            cityCode: cityCode,
            districtCode: districtCode,
            faskesCode: faskesCode
          });

          /*
          |--------------------------------------------------------------------------
          | PROVINSI
          |--------------------------------------------------------------------------
          */

          $('#kodePropinsi').val(String(provinceCode)).trigger('change.select2');

          /*
          |--------------------------------------------------------------------------
          | KOTA
          |--------------------------------------------------------------------------
          */
          _context2.n = 1;
          return loadEditCities(provinceCode, cityCode);
        case 1:
          _context2.n = 2;
          return loadEditDistricts(cityCode, districtCode);
        case 2:
          _context2.n = 3;
          return loadEditFaskes(districtCode, faskesCode);
        case 3:
          return _context2.a(2);
      }
    }, _callee2);
  }));
  return _loadEditLocation.apply(this, arguments);
}
function loadEditDistricts(cityCode, selectedDistrict) {
  return new Promise(function (resolve, reject) {
    var select = $('#kodeKecamatan');
    select.html('<option value="">Memuat kecamatan...</option>').prop('disabled', true).trigger('change.select2');
    if (!cityCode) {
      select.html('<option value="">Pilih Kecamatan</option>').prop('disabled', false).val('').trigger('change.select2');
      resolve();
      return;
    }
    $.ajax({
      url: '/adminpanel/wilayah/listkecamatan',
      type: 'GET',
      data: {
        city_code: cityCode
      },
      success: function success(response) {
        var _response$data8;
        var options = '<option value="">Pilih Kecamatan</option>';
        ((_response$data8 = response.data) !== null && _response$data8 !== void 0 ? _response$data8 : []).forEach(function (item) {
          options += "\n                        <option value=\"".concat(item.code, "\">\n                            ").concat(escapeHtml(item.name), "\n                        </option>\n                    ");
        });
        select.html(options).prop('disabled', false);

        /*
        |--------------------------------------------------------------------------
        | SELECT KECAMATAN
        |--------------------------------------------------------------------------
        */

        if (selectedDistrict !== null && selectedDistrict !== undefined) {
          select.val(String(selectedDistrict));
        }
        select.trigger('change.select2');
        resolve();
      },
      error: function error(xhr) {
        select.html('<option value="">Gagal memuat kecamatan</option>').prop('disabled', false).trigger('change.select2');
        reject(xhr);
      }
    });
  });
}
function loadEditCities(provinceCode, selectedCity) {
  return new Promise(function (resolve, reject) {
    var select = $('#kodeKota');
    select.html('<option value="">Memuat kota/kabupaten...</option>').prop('disabled', true).trigger('change.select2');
    if (!provinceCode) {
      select.html('<option value="">Pilih Kota</option>').prop('disabled', false).val('').trigger('change.select2');
      resolve();
      return;
    }
    $.ajax({
      url: '/adminpanel/wilayah/listkota',
      type: 'GET',
      data: {
        province_code: provinceCode
      },
      success: function success(response) {
        var _response$data9;
        var options = '<option value="">Pilih Kota</option>';
        ((_response$data9 = response.data) !== null && _response$data9 !== void 0 ? _response$data9 : []).forEach(function (item) {
          options += "\n                        <option value=\"".concat(item.code, "\">\n                            ").concat(escapeHtml(item.name), "\n                        </option>\n                    ");
        });
        select.html(options).prop('disabled', false);

        /*
        |--------------------------------------------------------------------------
        | SELECT KOTA
        |--------------------------------------------------------------------------
        */

        if (selectedCity !== null && selectedCity !== undefined) {
          select.val(String(selectedCity));
        }
        select.trigger('change.select2');
        resolve();
      },
      error: function error(xhr) {
        select.html('<option value="">Gagal memuat kota</option>').prop('disabled', false).trigger('change.select2');
        reject(xhr);
      }
    });
  });
}
function loadFilterCities(province) {
  $('#filterKota').html('<option value="">Semua Kota</option>');
  if (!province) {
    $('#filterKota').trigger('change.select2');
    return;
  }
  $.get('/adminpanel/wilayah/listkota', {
    province_code: province
  }, function (response) {
    var _response$data0;
    ((_response$data0 = response.data) !== null && _response$data0 !== void 0 ? _response$data0 : []).forEach(function (item) {
      $('#filterKota').append("\n                                <option value=\"".concat(item.code, "\">\n                                    ").concat(escapeHtml(item.name), "\n                                </option>\n                            "));
    });
    $('#filterKota').trigger('change.select2');
  });
}

/*
|--------------------------------------------------------------------------
| RESET LOCATION
|--------------------------------------------------------------------------
*/

function resetLocationFields() {
  $('#kodePropinsi').val('').trigger('change.select2');
  $('#kodeKota').html('<option value="">Pilih Kota</option>').trigger('change.select2');
  $('#kodeKecamatan').html('<option value="">Pilih Kecamatan</option>').trigger('change.select2');
  $('#kodeFaskes').html('<option value="">Pilih Faskes</option>').trigger('change.select2');
}

/*
|--------------------------------------------------------------------------
| SELECT2
|--------------------------------------------------------------------------
*/

function initSelect2() {
  $('#groupid, #role_id, #kodePropinsi, #kodeKota, #kodeKecamatan, #kodeFaskes').select2({
    dropdownParent: $('#userModal'),
    width: '100%'
  });
  $('#filterGroup, #filterRole, #filterProvinsi, #filterKota').select2({
    width: '100%'
  });
}

/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
*/

function clearValidation() {
  $('.is-invalid').removeClass('is-invalid');
  $('.invalid-feedback').text('');
}
function showValidationErrors(errors) {
  Object.keys(errors).forEach(function (field) {
    var input = $('#' + field);
    input.addClass('is-invalid');
    $('#' + field + 'Error').text(errors[field][0]);
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
| DEBOUNCE
|--------------------------------------------------------------------------
*/

function debounce(func, wait) {
  var timeout;
  return function () {
    var context = this;
    var args = arguments;
    clearTimeout(timeout);
    timeout = setTimeout(function () {
      func.apply(context, args);
    }, wait);
  };
}

/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {
  return $('<div>').text(value !== null && value !== void 0 ? value : '').html();
}
function loadEditGroupAndRole(_x3) {
  return _loadEditGroupAndRole.apply(this, arguments);
}
function _loadEditGroupAndRole() {
  _loadEditGroupAndRole = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3(data) {
    var _ref9, _data$groupid, _ref0, _ref1, _data$role_id;
    var groupId, roleId;
    return _regenerator().w(function (_context3) {
      while (1) switch (_context3.n) {
        case 0:
          /*
          |--------------------------------------------------------------------------
          | SET GROUP
          |--------------------------------------------------------------------------
          */
          groupId = (_ref9 = (_data$groupid = data.groupid) !== null && _data$groupid !== void 0 ? _data$groupid : data.group_id) !== null && _ref9 !== void 0 ? _ref9 : '';
          $('#groupid').val(String(groupId)).trigger('change.select2');

          /*
          |--------------------------------------------------------------------------
          | LOAD ROLE
          |--------------------------------------------------------------------------
          */
          roleId = (_ref0 = (_ref1 = (_data$role_id = data.role_id) !== null && _data$role_id !== void 0 ? _data$role_id : data.roleid) !== null && _ref1 !== void 0 ? _ref1 : data.role) !== null && _ref0 !== void 0 ? _ref0 : '';
          _context3.n = 1;
          return loadRolesForEdit(groupId, roleId);
        case 1:
          return _context3.a(2);
      }
    }, _callee3);
  }));
  return _loadEditGroupAndRole.apply(this, arguments);
}
function loadRolesForEdit(groupId, selectedRoleId) {
  return new Promise(function (resolve, reject) {
    var _window$UserPanelConf;
    var select = $('#role_id');

    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    select.prop('disabled', true).html('<option value="">Memuat role...</option>').trigger('change.select2');

    /*
    |--------------------------------------------------------------------------
    | GROUP KOSONG
    |--------------------------------------------------------------------------
    */

    if (!groupId) {
      select.html('<option value="">Pilih Role</option>').prop('disabled', false).val('').trigger('change.select2');
      resolve();
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD ROLE
    |--------------------------------------------------------------------------
    */

    $.ajax({
      url: (_window$UserPanelConf = window.UserPanelConfig.rolesByGroupUrl) !== null && _window$UserPanelConf !== void 0 ? _window$UserPanelConf : '/adminpanel/userpanel/roles/bygroup',
      type: 'GET',
      data: {
        /*
        | Gunakan nama parameter yang sama dengan
        | fungsi loadRoles()
        */

        groupId: groupId
      },
      success: function success(response) {
        var _response$data1;
        var roles = (_response$data1 = response.data) !== null && _response$data1 !== void 0 ? _response$data1 : [];
        var options = '<option value="">Pilih Role</option>';

        /*
        |--------------------------------------------------------------------------
        | BUILD OPTIONS
        |--------------------------------------------------------------------------
        */

        roles.forEach(function (item) {
          var _item$id, _ref4, _item$role_name;
          var roleId = (_item$id = item.id) !== null && _item$id !== void 0 ? _item$id : item.role_id;
          var roleName = (_ref4 = (_item$role_name = item.role_name) !== null && _item$role_name !== void 0 ? _item$role_name : item.name) !== null && _ref4 !== void 0 ? _ref4 : '-';
          if (roleId !== null && roleId !== undefined) {
            options += "\n                            <option value=\"".concat(escapeHtml(String(roleId)), "\">\n                                ").concat(escapeHtml(roleName), "\n                            </option>\n                        ");
          }
        });

        /*
        |--------------------------------------------------------------------------
        | INSERT OPTIONS
        |--------------------------------------------------------------------------
        */

        select.html(options).prop('disabled', false);

        /*
        |--------------------------------------------------------------------------
        | SET SELECTED ROLE
        |--------------------------------------------------------------------------
        */

        var selectedValue = selectedRoleId !== null && selectedRoleId !== undefined ? String(selectedRoleId) : '';

        /*
        |--------------------------------------------------------------------------
        | CEK OPTION
        |--------------------------------------------------------------------------
        */

        var optionExists = select.find('option').filter(function () {
          return String($(this).val()) === selectedValue;
        }).length > 0;
        console.log('EDIT ROLE', {
          groupId: groupId,
          selectedRoleId: selectedRoleId,
          selectedValue: selectedValue,
          optionExists: optionExists
        });
        if (optionExists) {
          select.val(selectedValue);
        } else {
          console.warn('Role tidak ditemukan:', selectedValue);
          select.val('');
        }

        /*
        |--------------------------------------------------------------------------
        | REFRESH SELECT2
        |--------------------------------------------------------------------------
        */

        select.trigger('change.select2');
        resolve();
      },
      error: function error(xhr) {
        var _xhr$responseJSON1;
        console.error('LOAD EDIT ROLE ERROR:', (_xhr$responseJSON1 = xhr.responseJSON) !== null && _xhr$responseJSON1 !== void 0 ? _xhr$responseJSON1 : xhr.responseText);
        select.html('<option value="">Role tidak tersedia</option>').prop('disabled', false).val('').trigger('change.select2');
        reject(xhr);
      }
    });
  });
}

/*
|--------------------------------------------------------------------------
| GLOBAL FUNCTIONS
|--------------------------------------------------------------------------
|
| Dibutuhkan karena tombol pada Blade menggunakan onclick=""
|
|--------------------------------------------------------------------------
*/

window.openUserModal = openUserModal;
window.editUser = editUser;
window.deleteUser = deleteUser;
/******/ })()
;