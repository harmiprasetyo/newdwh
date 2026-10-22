/******/ (() => { // webpackBootstrap
/*!****************************************************!*\
  !*** ./resources/js/adminpanel/userpanel/users.js ***!
  \****************************************************/
$(function () {
  var table = null;
  var config = window.UserPanelConfig || {};
  var currentUser = config.currentUser || {};
  var isGroup3 = parseInt(currentUser.groupid) === 3;
  console.log('USER PANEL DEBUG', {
    groupid: currentUser.groupid,
    isGroup3: isGroup3,
    kodePropinsi: currentUser.kodePropinsi,
    kodeKota: currentUser.kodeKota,
    kodeKecamatan: currentUser.kodeKecamatan,
    kodeFaskes: currentUser.kodeFaskes
  });
  console.log('USER.JS BERHASIL DIJALANKAN');

  /*
  |--------------------------------------------------------------------------
  | CSRF
  |--------------------------------------------------------------------------
  */

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

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
  function validatePassword(password) {
    return {
      length: password.length >= 8,
      uppercase: /[A-Z]/.test(password),
      lowercase: /[a-z]/.test(password),
      number: /[0-9]/.test(password),
      special: /[^A-Za-z0-9]/.test(password)
    };
  }
  function updatePasswordRequirements(password) {
    var result = validatePassword(password);
    updatePasswordRequirement('#reqLength', result.length);
    updatePasswordRequirement('#reqUppercase', result.uppercase);
    updatePasswordRequirement('#reqLowercase', result.lowercase);
    updatePasswordRequirement('#reqNumber', result.number);
    updatePasswordRequirement('#reqSpecial', result.special);
  }

  /*
  |--------------------------------------------------------------------------
  | UPDATE PASSWORD REQUIREMENT
  |--------------------------------------------------------------------------
  */
  function updatePasswordRequirement(selector, valid) {
    var element = $(selector);
    if (!element.length) {
      return;
    }
    element.toggleClass('text-success', valid).toggleClass('text-danger', !valid);
    var icon = element.find('i');
    if (icon.length) {
      icon.toggleClass('fa-check', valid).toggleClass('fa-times', !valid);
    }
  }
  $(document).on('input', '#password', function () {
    updatePasswordRequirements($(this).val());
  });
  function resetPasswordRequirements() {
    updatePasswordRequirements('');
  }
  $('#btnAddUser').on('click', function () {
    $('#userForm')[0].reset();
    $('#userid').val('');
    $('#password').val('').removeClass('is-invalid');
    $('#passwordError').text('');
    resetPasswordRequirements();
    $('#userModalTitle').text('Tambah User');

    // kode Anda untuk membuka modal
  });

  /*
  |--------------------------------------------------------------------------
  | SELECT2
  |--------------------------------------------------------------------------
  */

  function initSelect2() {
    $('#groupid, #role_id, #kodePropinsi, #kodeKota, #kodeKecamatan, #kodeFaskes').select2({
      dropdownParent: $('#userModal'),
      width: '100%',
      placeholder: 'Pilih',
      allowClear: true
    });
    $('#filterGroup, #filterRole, #filterProvinsi, #filterKota').select2({
      width: '100%',
      placeholder: 'Filter',
      allowClear: true
    });
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD GROUP
  |--------------------------------------------------------------------------
  */

  function loadGroups() {
    var selected = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    $.ajax({
      url: config.groupsUrl,
      type: 'GET',
      success: function success(res) {
        var html = '<option value="">Pilih Group</option>';
        if (!res || !Array.isArray(res.data)) {
          console.error('Format response group tidak valid.', res);
          return;
        }
        res.data.forEach(function (group) {
          var groupId = parseInt(group.group_id);

          /*
          |--------------------------------------------------------------------------
          | GROUP 3
          |--------------------------------------------------------------------------
          | Group 3 hanya boleh membuat user Group 4, 5, 6.
          */

          if (isGroup3 && groupId !== 4 && groupId !== 5 && groupId !== 6) {
            return;
          }
          html += "\n                            <option value=\"".concat(escapeHtml(group.group_id), "\">\n                                ").concat(escapeHtml(group.group_name), "\n                            </option>\n                        ");
        });
        $('#groupid').html(html).val(selected !== null ? String(selected) : '').trigger('change.select2');

        /*
        |--------------------------------------------------------------------------
        | FILTER GROUP
        |--------------------------------------------------------------------------
        */

        var filterHtml = '<option value="">Semua Group</option>';
        res.data.forEach(function (group) {
          filterHtml += "\n                            <option value=\"".concat(escapeHtml(group.group_id), "\">\n                                ").concat(escapeHtml(group.group_name), "\n                            </option>\n                        ");
        });
        $('#filterGroup').html(filterHtml).trigger('change.select2');
      },
      error: function error(xhr) {
        console.error('Gagal load group:', xhr.responseText);
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD ROLES
  |--------------------------------------------------------------------------
  */

  function loadRoles(groupId) {
    var selectedRole = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    console.log('LOAD ROLE', {
      groupId: groupId,
      selectedRole: selectedRole,
      url: config.rolesByGroupUrl
    });
    var $role = $('#role_id');
    $role.empty().append($('<option>', {
      value: '',
      text: 'Pilih Role'
    })).val('').trigger('change.select2');
    if (!groupId) {
      console.warn('Group belum dipilih.');
      return;
    }
    $.ajax({
      url: config.rolesByGroupUrl,
      type: 'GET',
      data: {
        groupid: groupId
      },
      dataType: 'json',
      success: function success(res) {
        console.log('ROLE RESPONSE:', res);
        if (!res || !Array.isArray(res.data)) {
          console.error('Format response role tidak valid:', res);
          return;
        }
        var html = '<option value="">Pilih Role</option>';
        res.data.forEach(function (role) {
          html += "\n                            <option value=\"".concat(escapeHtml(role.id), "\">\n                                ").concat(escapeHtml(role.role_name), "\n                            </option>\n                        ");
        });
        $role.html(html).val(selectedRole !== null ? String(selectedRole) : '').trigger('change.select2');
        console.log('ROLE LOADED:', res.data);
      },
      error: function error(xhr) {
        console.error('Gagal load role:', xhr.status, xhr.responseText);
        $role.empty().append($('<option>', {
          value: '',
          text: 'Gagal memuat Role'
        })).trigger('change.select2');
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD PROVINSI
  |--------------------------------------------------------------------------
  */

  function loadProvinsi() {
    var selected = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    $.get('/adminpanel/wilayah/listpropinsi', function (res) {
      var html = '<option value="">Pilih Provinsi</option>';
      if (!res || !Array.isArray(res.data)) {
        console.error('Format response provinsi tidak valid.', res);
        return;
      }

      /*
      |--------------------------------------------------------------------------
      | GROUP 3
      |--------------------------------------------------------------------------
      */

      if (isGroup3) {
        var kodePropinsi = currentUser.kodePropinsi;
        var item = res.data.find(function (item) {
          return String(item.code) === String(kodePropinsi);
        });
        if (item) {
          html += "\n                            <option value=\"".concat(escapeHtml(item.code), "\">\n                                ").concat(escapeHtml(item.name), "\n                            </option>\n                        ");
        }
      } else {
        res.data.forEach(function (item) {
          html += "\n                            <option value=\"".concat(escapeHtml(item.code), "\">\n                                ").concat(escapeHtml(item.name), "\n                            </option>\n                        ");
        });
      }
      var value = selected !== null ? selected : isGroup3 ? currentUser.kodePropinsi : '';
      $('#kodePropinsi').html(html).val(value ? String(value) : '').trigger('change.select2');

      /*
      |--------------------------------------------------------------------------
      | FILTER PROVINSI
      |--------------------------------------------------------------------------
      */

      var filterHtml = '<option value="">Semua Provinsi</option>';
      res.data.forEach(function (item) {
        filterHtml += "\n                        <option value=\"".concat(escapeHtml(item.code), "\">\n                            ").concat(escapeHtml(item.name), "\n                        </option>\n                    ");
      });
      $('#filterProvinsi').html(filterHtml).val('').trigger('change.select2');
      if (typeof callback === 'function') {
        callback();
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD KOTA
  |--------------------------------------------------------------------------
  */

  function loadKota(provinceCode) {
    var selected = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    var callback = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
    $('#kodeKota').html('<option value="">Pilih Kota / Kabupaten</option>').val('').trigger('change.select2');
    if (!provinceCode) {
      if (typeof callback === 'function') {
        callback();
      }
      return;
    }
    $.get("/adminpanel/wilayah/listkota?province_code=".concat(encodeURIComponent(provinceCode)), function (res) {
      var html = '<option value="">Pilih Kota / Kabupaten</option>';
      if (!res || !Array.isArray(res.data)) {
        console.error('Format response kota tidak valid.', res);
        return;
      }

      /*
      |--------------------------------------------------------------------------
      | GROUP 3
      |--------------------------------------------------------------------------
      */

      if (isGroup3) {
        var kodeKota = currentUser.kodeKota;
        var item = res.data.find(function (item) {
          return String(item.code) === String(kodeKota);
        });
        if (item) {
          html += "\n                            <option value=\"".concat(escapeHtml(item.code), "\">\n                                ").concat(escapeHtml(item.name), "\n                            </option>\n                        ");
        }
      } else {
        res.data.forEach(function (item) {
          html += "\n                            <option value=\"".concat(escapeHtml(item.code), "\">\n                                ").concat(escapeHtml(item.name), "\n                            </option>\n                        ");
        });
      }
      var value = selected !== null ? selected : isGroup3 ? currentUser.kodeKota : '';
      $('#kodeKota').html(html).val(value ? String(value) : '').trigger('change.select2');
      if (typeof callback === 'function') {
        callback();
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD KECAMATAN
  |--------------------------------------------------------------------------
  */

  function loadKecamatan(cityCode) {
    var selected = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    var callback = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
    $('#kodeKecamatan').html('<option value="">Pilih Kecamatan</option>').val('').trigger('change.select2');
    $('#kodeFaskes').html('<option value="">Pilih Faskes</option>').val('').trigger('change.select2');
    if (!cityCode) {
      if (typeof callback === 'function') {
        callback();
      }
      return;
    }
    $.get("/adminpanel/wilayah/listkecamatan?city_code=".concat(encodeURIComponent(cityCode)), function (res) {
      var html = '<option value="">Pilih Kecamatan</option>';
      if (!res || !Array.isArray(res.data)) {
        console.error('Format response kecamatan tidak valid.', res);
        return;
      }

      /*
      |--------------------------------------------------------------------------
      | GROUP 3
      |--------------------------------------------------------------------------
      */

      if (isGroup3) {
        var kodeKecamatan = currentUser.kodeKecamatan;
        var item = res.data.find(function (item) {
          return String(item.code) === String(kodeKecamatan);
        });
        if (item) {
          html += "\n                            <option value=\"".concat(escapeHtml(item.code), "\">\n                                ").concat(escapeHtml(item.name), "\n                            </option>\n                        ");
        }
      } else {
        res.data.forEach(function (item) {
          html += "\n                            <option value=\"".concat(escapeHtml(item.code), "\">\n                                ").concat(escapeHtml(item.name), "\n                            </option>\n                        ");
        });
      }
      var value = selected !== null ? selected : isGroup3 ? currentUser.kodeKecamatan : '';
      $('#kodeKecamatan').html(html).val(value ? String(value) : '').trigger('change.select2');
      if (typeof callback === 'function') {
        callback();
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | LOAD FASKES
  |--------------------------------------------------------------------------
  */

  function loadFaskes(kecamatan) {
    var selected = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    var callback = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
    $('#kodeFaskes').html('<option value="">Pilih Faskes</option>').val('').trigger('change.select2');
    if (!kecamatan) {
      if (typeof callback === 'function') {
        callback();
      }
      return;
    }
    $.get("".concat(config.faskesUrl, "?kecamatan=").concat(encodeURIComponent(kecamatan)), function (res) {
      var html = '<option value="">Pilih Faskes</option>';
      if (!res || !Array.isArray(res.data)) {
        console.error('Format response faskes tidak valid.', res);
        return;
      }

      /*
      |--------------------------------------------------------------------------
      | GROUP 3
      |--------------------------------------------------------------------------
      */

      if (isGroup3) {
        var kodeFaskes = currentUser.kodeFaskes;
        var item = res.data.find(function (item) {
          return String(item.kodeFaskes) === String(kodeFaskes);
        });
        if (item) {
          html += "\n                            <option value=\"".concat(escapeHtml(item.kodeFaskes), "\">\n                                ").concat(escapeHtml(item.namaFaskes), "\n                            </option>\n                        ");
        }
      } else {
        res.data.forEach(function (item) {
          html += "\n                            <option value=\"".concat(escapeHtml(item.kodeFaskes), "\">\n                                ").concat(escapeHtml(item.namaFaskes), "\n                            </option>\n                        ");
        });
      }
      var value = selected !== null ? selected : isGroup3 ? currentUser.kodeFaskes : '';
      $('#kodeFaskes').html(html).val(value ? String(value) : '').trigger('change.select2');
      if (typeof callback === 'function') {
        callback();
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | FORM PROVINSI -> KOTA
  |--------------------------------------------------------------------------
  */

  $(document).on('change', '#kodePropinsi', function () {
    var provinceCode = $(this).val();
    console.log('PROVINSI CHANGE:', provinceCode);

    /*
    |--------------------------------------------------------------------------
    | Reset Kota
    |--------------------------------------------------------------------------
    */

    $('#kodeKota').html('<option value="">Pilih Kota / Kabupaten</option>').val('').trigger('change.select2');

    /*
    |--------------------------------------------------------------------------
    | Reset Kecamatan
    |--------------------------------------------------------------------------
    */

    $('#kodeKecamatan').html('<option value="">Pilih Kecamatan</option>').val('').trigger('change.select2');

    /*
    |--------------------------------------------------------------------------
    | Reset Faskes
    |--------------------------------------------------------------------------
    */

    $('#kodeFaskes').html('<option value="">Pilih Faskes</option>').val('').trigger('change.select2');
    if (!provinceCode) {
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if (isGroup3) {
      console.log('Group 3 - lokasi dikunci.');
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD KOTA
    |--------------------------------------------------------------------------
    */

    loadKota(provinceCode);
  });

  /*
  |--------------------------------------------------------------------------
  | FORM KOTA -> KECAMATAN
  |--------------------------------------------------------------------------
  */

  $(document).on('change', '#kodeKota', function () {
    var cityCode = $(this).val();
    console.log('KOTA CHANGE:', cityCode);

    /*
    |--------------------------------------------------------------------------
    | Reset Kecamatan
    |--------------------------------------------------------------------------
    */

    $('#kodeKecamatan').html('<option value="">Pilih Kecamatan</option>').val('').trigger('change.select2');

    /*
    |--------------------------------------------------------------------------
    | Reset Faskes
    |--------------------------------------------------------------------------
    */

    $('#kodeFaskes').html('<option value="">Pilih Faskes</option>').val('').trigger('change.select2');
    if (!cityCode) {
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if (isGroup3) {
      console.log('Group 3 - lokasi dikunci.');
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD KECAMATAN
    |--------------------------------------------------------------------------
    */

    loadKecamatan(cityCode);
  });

  /*
  |--------------------------------------------------------------------------
  | FORM KECAMATAN -> FASKES
  |--------------------------------------------------------------------------
  */

  $(document).on('change', '#kodeKecamatan', function () {
    var kecamatan = $(this).val();
    console.log('KECAMATAN CHANGE:', kecamatan);

    /*
    |--------------------------------------------------------------------------
    | Reset Faskes
    |--------------------------------------------------------------------------
    */

    $('#kodeFaskes').html('<option value="">Pilih Faskes</option>').val('').trigger('change.select2');
    if (!kecamatan) {
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if (isGroup3) {
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD FASKES
    |--------------------------------------------------------------------------
    */

    loadFaskes(kecamatan);
  });

  /*
  |--------------------------------------------------------------------------
  | FILTER PROVINSI -> KOTA
  |--------------------------------------------------------------------------
  */

  $(document).on('change', '#filterProvinsi', function () {
    var province = $(this).val();
    console.log('FILTER PROVINSI CHANGE:', province);

    /*
    |--------------------------------------------------------------------------
    | Reset Kota
    |--------------------------------------------------------------------------
    */

    $('#filterKota').html('<option value="">Semua Kota</option>').val('').trigger('change.select2');
    if (!province) {
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD KOTA
    |--------------------------------------------------------------------------
    */

    $.get("/adminpanel/wilayah/listkota?province_code=".concat(encodeURIComponent(province)), function (res) {
      var html = '<option value="">Semua Kota</option>';
      if (!res || !Array.isArray(res.data)) {
        console.error('Format response filter kota tidak valid.', res);
        return;
      }
      res.data.forEach(function (item) {
        html += "\n                            <option value=\"".concat(escapeHtml(item.code), "\">\n                                ").concat(escapeHtml(item.name), "\n                            </option>\n                        ");
      });
      $('#filterKota').html(html).val('').trigger('change.select2');
    });
  });

  /*
  |--------------------------------------------------------------------------
  | FILTER GROUP / ROLE / PROVINSI / KOTA
  |--------------------------------------------------------------------------
  */

  $('#filterGroup, #filterRole, #filterProvinsi, #filterKota').on('change', function () {
    if (table) {
      table.ajax.reload(null, false);
    }
  });

  /*
  |--------------------------------------------------------------------------
  | FILTER SEARCH
  |--------------------------------------------------------------------------
  */

  $('#searchUser').on('keyup', function () {
    if (table) {
      table.ajax.reload(null, false);
    }
  });

  /*
  |--------------------------------------------------------------------------
  | GROUP CHANGE
  |--------------------------------------------------------------------------
  */

  $('#groupid').on('change', function () {
    var groupId = $(this).val();
    console.log('GROUP CHANGED:', groupId);
    loadRoles(groupId);
  });

  /*
  |--------------------------------------------------------------------------
  | DEFAULT LOCATION GROUP 3
  |--------------------------------------------------------------------------
  */

  function loadDefaultLocation() {
    if (!isGroup3) {
      return;
    }
    var province = currentUser.kodePropinsi;
    var city = currentUser.kodeKota;
    var district = currentUser.kodeKecamatan;
    var faskes = currentUser.kodeFaskes;
    if (!province || !city || !district || !faskes) {
      console.warn('Data lokasi user Group 3 tidak lengkap.', currentUser);
      return;
    }
    loadProvinsi(province, function () {
      loadKota(province, city, function () {
        loadKecamatan(city, district, function () {
          loadFaskes(district, faskes, function () {
            lockLocationForGroup3();
          });
        });
      });
    });
  }

  /*
  |--------------------------------------------------------------------------
  | LOCK LOCATION GROUP 3
  |--------------------------------------------------------------------------
  */

  function lockLocationForGroup3() {
    if (!isGroup3) {
      return;
    }
    $('#kodePropinsi').prop('disabled', true);
    $('#kodeKota').prop('disabled', true);
    $('#kodeKecamatan').prop('disabled', true);
    $('#kodeFaskes').prop('disabled', true);
    $('#group3LocationInfo').remove();
    $('#kodeFaskes').closest('.mb-3').append("\n                <small\n                    id=\"group3LocationInfo\"\n                    class=\"text-muted\"\n                >\n                    <i class=\"fas fa-lock me-1\"></i>\n                    Penempatan mengikuti faskes user login.\n                </small>\n            ");
  }

  /*
  |--------------------------------------------------------------------------
  | UNLOCK LOCATION
  |--------------------------------------------------------------------------
  */

  function unlockLocation() {
    $('#kodePropinsi').prop('disabled', false);
    $('#kodeKota').prop('disabled', false);
    $('#kodeKecamatan').prop('disabled', false);
    $('#kodeFaskes').prop('disabled', false);
    $('#group3LocationInfo').remove();
  }

  /*
  |--------------------------------------------------------------------------
  | RESET FORM
  |--------------------------------------------------------------------------
  */

  function resetForm() {
    $('#userForm')[0].reset();
    $('#userid').val('');

    /*
    |--------------------------------------------------------------------------
    | Reset validation
    |--------------------------------------------------------------------------
    */

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    /*
    |--------------------------------------------------------------------------
    | Reset Group
    |--------------------------------------------------------------------------
    */

    $('#groupid').html('<option value="">Pilih Group</option>').val('').trigger('change.select2');

    /*
    |--------------------------------------------------------------------------
    | Reset Role
    |--------------------------------------------------------------------------
    */

    $('#role_id').html('<option value="">Pilih Role</option>').val('').trigger('change.select2');

    /*
    |--------------------------------------------------------------------------
    | Reset Provinsi
    |--------------------------------------------------------------------------
    */

    $('#kodePropinsi').html('<option value="">Pilih Provinsi</option>').val('').trigger('change.select2');

    /*
    |--------------------------------------------------------------------------
    | Reset Kota
    |--------------------------------------------------------------------------
    */

    $('#kodeKota').html('<option value="">Pilih Kota / Kabupaten</option>').val('').trigger('change.select2');

    /*
    |--------------------------------------------------------------------------
    | Reset Kecamatan
    |--------------------------------------------------------------------------
    */

    $('#kodeKecamatan').html('<option value="">Pilih Kecamatan</option>').val('').trigger('change.select2');

    /*
    |--------------------------------------------------------------------------
    | Reset Faskes
    |--------------------------------------------------------------------------
    */

    $('#kodeFaskes').html('<option value="">Pilih Faskes</option>').val('').trigger('change.select2');
    unlockLocation();
  }

  /*
  |--------------------------------------------------------------------------
  | DATATABLE
  |--------------------------------------------------------------------------
  */

  function initTable() {
    table = $('#userTable').DataTable({
      processing: true,
      ajax: {
        url: config.datatableUrl,
        data: function data(d) {
          d.search = $('#searchUser').val();
          d.groupid = $('#filterGroup').val();
          d.role_id = $('#filterRole').val();
          d.kodePropinsi = $('#filterProvinsi').val();
          d.kodeKota = $('#filterKota').val();
        },
        dataSrc: 'data'
      },
      columns: [
      /*
      |--------------------------------------------------------------------------
      | #
      |--------------------------------------------------------------------------
      */

      {
        data: null,
        orderable: false,
        render: function render(data, type, row, meta) {
          return meta.row + 1;
        }
      },
      /*
      |--------------------------------------------------------------------------
      | USER
      |--------------------------------------------------------------------------
      */

      {
        data: null,
        render: function render(data) {
          return "\n                                    <div>\n\n                                        <strong>\n                                            ".concat(escapeHtml(data.namalengkap || '-'), "\n                                        </strong>\n\n                                        <br>\n\n                                        <small class=\"text-muted\">\n                                            ").concat(escapeHtml(data.username || '-'), "\n                                        </small>\n\n                                        <br>\n\n                                        <small>\n                                            ").concat(escapeHtml(data.email || '-'), "\n                                        </small>\n\n                                    </div>\n                                ");
        }
      },
      /*
      |--------------------------------------------------------------------------
      | GROUP
      |--------------------------------------------------------------------------
      */

      {
        data: null,
        render: function render(data) {
          return "\n                                    <span class=\"badge bg-primary\">\n                                        ".concat(escapeHtml(data.group_name || '-'), "\n                                    </span>\n                                ");
        }
      },
      /*
      |--------------------------------------------------------------------------
      | ROLE
      |--------------------------------------------------------------------------
      */

      {
        data: null,
        render: function render(data) {
          return data.role_name ? "\n                                        <span class=\"badge bg-success\">\n                                            ".concat(escapeHtml(data.role_name), "\n                                        </span>\n                                    ") : '-';
        }
      },
      /*
      |--------------------------------------------------------------------------
      | FASKES
      |--------------------------------------------------------------------------
      */

      {
        data: null,
        render: function render(data) {
          return "\n                                    <strong>\n                                        ".concat(escapeHtml(data.namaFaskes || '-'), "\n                                    </strong>\n\n                                    <br>\n\n                                    <small class=\"text-muted\">\n                                        ").concat(escapeHtml(data.kodeFaskes || ''), "\n                                    </small>\n                                ");
        }
      },
      /*
      |--------------------------------------------------------------------------
      | WILAYAH
      |--------------------------------------------------------------------------
      */

      {
        data: null,
        render: function render(data) {
          return "\n                                    <small>\n                                        ".concat(escapeHtml(data.provinsi_name || '-'), "\n\n                                        <br>\n\n                                        ").concat(escapeHtml(data.kota_name || '-'), "\n\n                                        <br>\n\n                                        ").concat(escapeHtml(data.kecamatan_name || '-'), "\n\n                                    </small>\n                                ");
        }
      },
      /*
      |--------------------------------------------------------------------------
      | ACTION
      |--------------------------------------------------------------------------
      */

      {
        data: null,
        orderable: false,
        className: 'text-center',
        render: function render(data) {
          /*
          |--------------------------------------------------------------------------
          | GROUP 3
          |--------------------------------------------------------------------------
          */

          if (isGroup3 && parseInt(data.group_id) === 3) {
            return "\n                                        <span\n                                            class=\"text-muted\"\n                                            title=\"User Group 3 tidak dapat diubah\"\n                                        >\n                                            <i class=\"fas fa-lock\"></i>\n                                        </span>\n                                    ";
          }
          return "\n                                    <div class=\"btn-group\">\n\n                                        <button\n                                            type=\"button\"\n                                            class=\"btn btn-sm btn-info btn-edit\"\n                                            data-id=\"".concat(escapeHtml(data.userid), "\"\n                                            title=\"Edit\"\n                                        >\n                                            <i class=\"fas fa-edit\"></i>\n                                        </button>\n\n                                        <button\n                                            type=\"button\"\n                                            class=\"btn btn-sm btn-danger btn-delete\"\n                                            data-id=\"").concat(escapeHtml(data.userid), "\"\n                                            title=\"Hapus\"\n                                        >\n                                            <i class=\"fas fa-trash\"></i>\n                                        </button>\n\n                                    </div>\n                                ");
        }
      }]
    });
  }

  /*
  |--------------------------------------------------------------------------
  | ADD USER
  |--------------------------------------------------------------------------
  */

  $('#btnAddUser').on('click', function () {
    resetForm();
    $('#userModalTitle').text('Tambah User');
    $('#passwordHelp').text('Minimal 6 karakter');

    /*
    |--------------------------------------------------------------------------
    | GROUP
    |--------------------------------------------------------------------------
    */

    loadGroups();

    /*
    |--------------------------------------------------------------------------
    | LOCATION
    |--------------------------------------------------------------------------
    */

    if (isGroup3) {
      loadDefaultLocation();
    } else {
      loadProvinsi();
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW MODAL
    |--------------------------------------------------------------------------
    */

    $('#userModal').modal('show');
  });

  /*
  |--------------------------------------------------------------------------
  | EDIT USER
  |--------------------------------------------------------------------------
  */

  $('#userTable').on('click', '.btn-edit', function () {
    var id = $(this).data('id');
    $.ajax({
      url: "".concat(config.baseUrl, "/").concat(id),
      type: 'GET',
      success: function success(res) {
        var data = res.data;
        resetForm();

        /*
        |--------------------------------------------------------------------------
        | BASIC DATA
        |--------------------------------------------------------------------------
        */

        $('#userid').val(data.userid);
        $('#username').val(data.username);
        $('#namalengkap').val(data.namalengkap);
        $('#email').val(data.email);
        $('#password').val('');

        /*
        |--------------------------------------------------------------------------
        | GROUP
        |--------------------------------------------------------------------------
        */

        loadGroups(data.groupid);

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        loadRoles(data.groupid, data.role_id);

        /*
        |--------------------------------------------------------------------------
        | LOCATION
        |--------------------------------------------------------------------------
        */

        var province = isGroup3 ? currentUser.kodePropinsi : data.kodePropinsi;
        var city = isGroup3 ? currentUser.kodeKota : data.kodeKota;
        var district = isGroup3 ? currentUser.kodeKecamatan : data.kodeKecamatan;
        var faskes = isGroup3 ? currentUser.kodeFaskes : data.kodeFaskes;
        loadProvinsi(province, function () {
          loadKota(province, city, function () {
            loadKecamatan(city, district, function () {
              loadFaskes(district, faskes, function () {
                if (isGroup3) {
                  lockLocationForGroup3();
                }
              });
            });
          });
        });

        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        $('#userModalTitle').text('Edit User');

        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        $('#passwordHelp').text('Kosongkan jika password tidak ingin diubah');

        /*
        |--------------------------------------------------------------------------
        | SHOW MODAL
        |--------------------------------------------------------------------------
        */

        $('#userModal').modal('show');
      },
      error: function error(xhr) {
        var _xhr$responseJSON;
        console.error('Gagal mengambil data user:', xhr.responseText);
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: ((_xhr$responseJSON = xhr.responseJSON) === null || _xhr$responseJSON === void 0 ? void 0 : _xhr$responseJSON.message) || 'Data user tidak dapat diambil.'
        });
      }
    });
  });

  /*
  |--------------------------------------------------------------------------
  | DELETE
  |--------------------------------------------------------------------------
  */

  $('#userTable').on('click', '.btn-delete', function () {
    var id = $(this).data('id');
    Swal.fire({
      title: 'Hapus user?',
      text: 'Data user akan dihapus.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then(function (result) {
      if (!result.isConfirmed) {
        return;
      }
      $.ajax({
        url: "".concat(config.baseUrl, "/").concat(id),
        type: 'DELETE',
        success: function success(res) {
          table.ajax.reload(null, false);
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: res.message || 'User berhasil dihapus.',
            timer: 1500,
            showConfirmButton: false
          });
        },
        error: function error(xhr) {
          var _xhr$responseJSON2;
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: ((_xhr$responseJSON2 = xhr.responseJSON) === null || _xhr$responseJSON2 === void 0 ? void 0 : _xhr$responseJSON2.message) || 'User tidak dapat dihapus.'
          });
        }
      });
    });
  });

  /*
  |--------------------------------------------------------------------------
  | SAVE USER
  |--------------------------------------------------------------------------
  */

  $('#userForm').on('submit', function (e) {
    e.preventDefault();

    /*
    |--------------------------------------------------------------------------
    | CLEAR VALIDATION
    |--------------------------------------------------------------------------
    */

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    var id = $('#userid').val();
    var data = {
      username: $('#username').val(),
      namalengkap: $('#namalengkap').val(),
      email: $('#email').val(),
      password: $('#password').val(),
      groupid: $('#groupid').val(),
      role_id: $('#role_id').val(),
      kodePropinsi: $('#kodePropinsi').val(),
      kodeKota: $('#kodeKota').val(),
      kodeKecamatan: $('#kodeKecamatan').val(),
      kodeFaskes: $('#kodeFaskes').val()
    };

    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if (isGroup3) {
      data.kodePropinsi = currentUser.kodePropinsi;
      data.kodeKota = currentUser.kodeKota;
      data.kodeKecamatan = currentUser.kodeKecamatan;
      data.kodeFaskes = currentUser.kodeFaskes;
      if (![4, 5, 6].includes(parseInt(data.groupid))) {
        Swal.fire({
          icon: 'warning',
          title: 'Group tidak valid',
          text: 'User Group 3 hanya dapat membuat user Group 4, Group 5, atau Group 6.'
        });
        return;
      }
    }

    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    */

    var url = id ? "".concat(config.baseUrl, "/").concat(id) : config.baseUrl;
    var method = id ? 'PUT' : 'POST';

    /*
    |--------------------------------------------------------------------------
    | BUTTON
    |--------------------------------------------------------------------------
    */

    $('#btnSaveUser').prop('disabled', true);

    /*
    |--------------------------------------------------------------------------
    | AJAX
    |--------------------------------------------------------------------------
    */

    $.ajax({
      url: url,
      type: method,
      data: data,
      success: function success(res) {
        $('#userModal').modal('hide');
        table.ajax.reload(null, false);
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: res.message || 'User berhasil disimpan.',
          timer: 1500,
          showConfirmButton: false
        });
      },
      error: function error(xhr) {
        var _xhr$responseJSON3, _xhr$responseJSON4;
        console.error(xhr.responseText);

        /*
        |--------------------------------------------------------------------------
        | VALIDATION 422
        |--------------------------------------------------------------------------
        */

        if (xhr.status === 422 && (_xhr$responseJSON3 = xhr.responseJSON) !== null && _xhr$responseJSON3 !== void 0 && _xhr$responseJSON3.errors) {
          showValidationErrors(xhr.responseJSON.errors);
          return;
        }
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: ((_xhr$responseJSON4 = xhr.responseJSON) === null || _xhr$responseJSON4 === void 0 ? void 0 : _xhr$responseJSON4.message) || 'Terjadi kesalahan.'
        });
      },
      complete: function complete() {
        $('#btnSaveUser').prop('disabled', false);
      }
    });
  });

  /*
  |--------------------------------------------------------------------------
  | VALIDATION ERROR
  |--------------------------------------------------------------------------
  */

  function showValidationErrors(errors) {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    Object.keys(errors).forEach(function (field) {
      var input = $('#' + field);
      input.addClass('is-invalid');
      var errorElement = $('#' + field + 'Error');
      if (errorElement.length) {
        errorElement.text(errors[field][0]);
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | INITIALIZE
  |--------------------------------------------------------------------------
  */

  initSelect2();
  loadGroups();
  loadProvinsi();
  initTable();
});
/******/ })()
;