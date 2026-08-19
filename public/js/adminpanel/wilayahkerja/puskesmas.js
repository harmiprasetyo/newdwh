/******/ (() => { // webpackBootstrap
/*!***********************************************************!*\
  !*** ./resources/js/adminpanel/wilayahkerja/puskesmas.js ***!
  \***********************************************************/
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var wilayahTable = null;
var wilayahModal = null;

/*
|--------------------------------------------------------------------------
| CACHE MASTER FASKES
|--------------------------------------------------------------------------
|
| Data Master Faskes disimpan di memory.
| Jadi ketika user mengganti Puskesmas, kita tidak perlu request
| ulang ke server.
|
*/

var masterFaskes = [];

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
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {
  console.log('==========================================');
  console.log('INIT WILAYAH KERJA PUSKESMAS');
  console.log('CONFIG:', window.WilayahKerjaPuskesmasConfig);
  console.log('==========================================');

  /*
  |--------------------------------------------------------------------------
  | MODAL
  |--------------------------------------------------------------------------
  */

  var modalElement = document.getElementById('wilayahPuskesmasModal');
  if (modalElement) {
    wilayahModal = new bootstrap.Modal(modalElement);
  }

  /*
  |--------------------------------------------------------------------------
  | SELECT2
  |--------------------------------------------------------------------------
  */

  initSelect2();

  /*
  |--------------------------------------------------------------------------
  | EVENTS
  |--------------------------------------------------------------------------
  */

  bindEvents();

  /*
  |--------------------------------------------------------------------------
  | DATATABLE
  |--------------------------------------------------------------------------
  */

  loadTable();

  /*
  |--------------------------------------------------------------------------
  | MASTER FASKES
  |--------------------------------------------------------------------------
  */

  loadFaskes();

  /*
  |--------------------------------------------------------------------------
  | FILTER DESA
  |--------------------------------------------------------------------------
  */

  loadFilterDesa();
  console.log('WILAYAH KERJA PUSKESMAS READY');
});

/*
|--------------------------------------------------------------------------
| USER GROUP
|--------------------------------------------------------------------------
*/

function getGroupId() {
  var _window$WilayahKerjaP;
  return String(((_window$WilayahKerjaP = window.WilayahKerjaPuskesmasConfig) === null || _window$WilayahKerjaP === void 0 ? void 0 : _window$WilayahKerjaP.groupId) || '');
}
function getUserKodeFaskes() {
  var _window$WilayahKerjaP2;
  return String(((_window$WilayahKerjaP2 = window.WilayahKerjaPuskesmasConfig) === null || _window$WilayahKerjaP2 === void 0 ? void 0 : _window$WilayahKerjaP2.userKodeFaskes) || '');
}
function isGroup3() {
  return getGroupId() === '3';
}
function isGroup12() {
  return getGroupId() === '1' || getGroupId() === '2';
}

/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/

function bindEvents() {
  /*
  |--------------------------------------------------------------------------
  | ADD
  |--------------------------------------------------------------------------
  */

  $('#btnAddWilayah').on('click', function () {
    openModal();
  });

  /*
  |--------------------------------------------------------------------------
  | RESET FILTER
  |--------------------------------------------------------------------------
  */

  $('#btnResetFilter').on('click', function () {
    $('#filterFaskes').val('').trigger('change');
    $('#filterDesa').val('').trigger('change');
    reloadTable();
  });

  /*
  |--------------------------------------------------------------------------
  | FASKES CHANGE
  |--------------------------------------------------------------------------
  |
  | INI BAGIAN PALING PENTING
  |
  */

  $('#kodeFaskes').on('change', /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
    var kodeFaskes, faskes;
    return _regenerator().w(function (_context) {
      while (1) switch (_context.n) {
        case 0:
          kodeFaskes = String($(this).val() || '');
          console.log('------------------------------------------');
          console.log('FASKES CHANGE');
          console.log('kodeFaskes:', kodeFaskes);

          /*
          |--------------------------------------------------------------------------
          | Tidak ada faskes
          |--------------------------------------------------------------------------
          */
          if (kodeFaskes) {
            _context.n = 1;
            break;
          }
          console.log('Faskes kosong -> reset lokasi');
          resetLocation();
          return _context.a(2);
        case 1:
          /*
          |--------------------------------------------------------------------------
          | Ambil dari cache Master Faskes
          |--------------------------------------------------------------------------
          */
          faskes = findFaskesFromCache(kodeFaskes);
          console.log('FASKES DARI CACHE:', faskes);
          if (faskes) {
            _context.n = 2;
            break;
          }
          console.error('Faskes tidak ditemukan di cache:', kodeFaskes);
          resetLocation();
          showError('Data Puskesmas tidak ditemukan.');
          return _context.a(2);
        case 2:
          _context.n = 3;
          return applyFaskesLocation(kodeFaskes, faskes);
        case 3:
          return _context.a(2);
      }
    }, _callee, this);
  })));

  /*
  |--------------------------------------------------------------------------
  | FILTER
  |--------------------------------------------------------------------------
  */

  $('#filterFaskes').on('change', function () {
    reloadTable();
  });
  $('#filterDesa').on('change', function () {
    reloadTable();
  });

  /*
  |--------------------------------------------------------------------------
  | FORM SUBMIT
  |--------------------------------------------------------------------------
  */

  $('#wilayahPuskesmasForm').on('submit', function (e) {
    e.preventDefault();
    saveWilayah();
  });

  /*
  |--------------------------------------------------------------------------
  | EDIT
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btn-edit', function () {
    var id = $(this).data('id');
    editWilayah(id);
  });

  /*
  |--------------------------------------------------------------------------
  | DELETE
  |--------------------------------------------------------------------------
  */

  $(document).on('click', '.btn-delete', function () {
    var id = $(this).data('id');
    deleteWilayah(id);
  });
}

/*
|--------------------------------------------------------------------------
| GROUP 3
|--------------------------------------------------------------------------
*/

function setupGroup3() {
  console.log('SETUP GROUP 3');

  /*
  |--------------------------------------------------------------------------
  | Sembunyikan faskes
  |--------------------------------------------------------------------------
  */

  $('#faskesContainer').hide();

  /*
  |--------------------------------------------------------------------------
  | Ambil kode faskes user
  |--------------------------------------------------------------------------
  */

  var kodeFaskes = getUserKodeFaskes();
  console.log('GROUP 3 KODE FASKES:', kodeFaskes);
  if (!kodeFaskes) {
    console.error('USER GROUP 3 TIDAK MEMILIKI kodeFaskes');
    showError('User belum memiliki Puskesmas.');
    return;
  }

  /*
  |--------------------------------------------------------------------------
  | Set faskes
  |--------------------------------------------------------------------------
  */

  $('#kodeFaskes').val(kodeFaskes).prop('disabled', true).trigger('change.select2');

  /*
  |--------------------------------------------------------------------------
  | Ambil dari cache
  |--------------------------------------------------------------------------
  */

  var faskes = findFaskesFromCache(kodeFaskes);
  if (faskes) {
    applyFaskesLocation(kodeFaskes, faskes);
  }
}

/*
|--------------------------------------------------------------------------
| DATATABLE
|--------------------------------------------------------------------------
*/

function loadTable() {
  console.log('LOAD DATATABLE');
  wilayahTable = $('#wilayahPuskesmasTable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    responsive: true,
    pageLength: 25,
    ajax: {
      url: window.WilayahKerjaPuskesmasConfig.datatableUrl,
      type: 'GET',
      data: function data(d) {
        d.kodeFaskes = $('#filterFaskes').val() || '';
        d.kodeDesa = $('#filterDesa').val() || '';
      },
      error: function error(xhr) {
        console.error('DATATABLE ERROR:', xhr.status, xhr.responseJSON || xhr.responseText);
      }
    },
    columns: [{
      data: 'DT_RowIndex',
      name: 'DT_RowIndex',
      className: 'text-center',
      orderable: false,
      searchable: false
    }, {
      data: 'namaFaskes',
      name: 'namaFaskes',
      defaultContent: '-'
    }, {
      data: 'namaDesa',
      name: 'namaDesa',
      defaultContent: '-'
    }, {
      data: 'kecamatan',
      name: 'kecamatan',
      defaultContent: '-'
    }, {
      data: 'kota',
      name: 'kota',
      defaultContent: '-'
    }, {
      data: 'provinsi',
      name: 'provinsi',
      defaultContent: '-'
    }, {
      data: 'action',
      name: 'action',
      className: 'text-center',
      orderable: false,
      searchable: false
    }],
    order: [[1, 'asc']],
    language: {
      emptyTable: 'Belum ada wilayah kerja.',
      zeroRecords: 'Data tidak ditemukan.',
      processing: 'Memuat data...',
      lengthMenu: '_MENU_ data',
      info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
      paginate: {
        previous: '<i class="fas fa-chevron-left"></i>',
        next: '<i class="fas fa-chevron-right"></i>'
      }
    }
  });
}

/*
|--------------------------------------------------------------------------
| LOAD MASTER FASKES
|--------------------------------------------------------------------------
*/

function loadFaskes() {
  console.log('LOAD MASTER FASKES');
  $.ajax({
    url: window.WilayahKerjaPuskesmasConfig.faskesUrl,
    type: 'GET',
    success: function success(response) {
      console.log('RESPONSE MASTER FASKES:', response);

      /*
      |--------------------------------------------------------------------------
      | Simpan ke cache
      |--------------------------------------------------------------------------
      */

      masterFaskes = Array.isArray(response.data) ? response.data : [];
      console.log('MASTER FASKES CACHE:', masterFaskes);

      /*
      |--------------------------------------------------------------------------
      | Validasi
      |--------------------------------------------------------------------------
      */

      if (!Array.isArray(masterFaskes) || masterFaskes.length === 0) {
        console.warn('MASTER FASKES KOSONG');
        return;
      }

      /*
      |--------------------------------------------------------------------------
      | GROUP 1 / 2
      |--------------------------------------------------------------------------
      */

      if (isGroup12()) {
        var options = '<option value="">Pilih Puskesmas</option>';
        masterFaskes.forEach(function (item) {
          var _item$kodeFaskes, _item$namaFaskes;
          var kode = (_item$kodeFaskes = item.kodeFaskes) !== null && _item$kodeFaskes !== void 0 ? _item$kodeFaskes : '';
          var nama = (_item$namaFaskes = item.namaFaskes) !== null && _item$namaFaskes !== void 0 ? _item$namaFaskes : '';
          options += "\n                            <option\n                                value=\"".concat(escapeHtml(kode), "\"\n                            >\n                                ").concat(escapeHtml(nama), "\n                            </option>\n                        ");
        });
        $('#kodeFaskes').html(options).prop('disabled', false).trigger('change.select2');
      }

      /*
      |--------------------------------------------------------------------------
      | FILTER FASKES
      |--------------------------------------------------------------------------
      */

      var filterOptions = '<option value="">Semua Puskesmas</option>';
      masterFaskes.forEach(function (item) {
        var _item$kodeFaskes2, _item$namaFaskes2;
        var kode = (_item$kodeFaskes2 = item.kodeFaskes) !== null && _item$kodeFaskes2 !== void 0 ? _item$kodeFaskes2 : '';
        var nama = (_item$namaFaskes2 = item.namaFaskes) !== null && _item$namaFaskes2 !== void 0 ? _item$namaFaskes2 : '';
        filterOptions += "\n                        <option\n                            value=\"".concat(escapeHtml(kode), "\"\n                        >\n                            ").concat(escapeHtml(nama), "\n                        </option>\n                    ");
      });
      $('#filterFaskes').html(filterOptions).trigger('change.select2');

      /*
      |--------------------------------------------------------------------------
      | GROUP 3
      |--------------------------------------------------------------------------
      */

      if (isGroup3()) {
        setupGroup3();
      }
    },
    error: function error(xhr) {
      console.error('LOAD MASTER FASKES ERROR:', xhr.status, xhr.responseJSON || xhr.responseText);
      showError('Gagal mengambil data Master Faskes.');
    }
  });
}

/*
|--------------------------------------------------------------------------
| FIND FASKES FROM CACHE
|--------------------------------------------------------------------------
*/

function findFaskesFromCache(kodeFaskes) {
  var target = String(kodeFaskes || '');
  return masterFaskes.find(function (item) {
    var _item$kodeFaskes3;
    return String((_item$kodeFaskes3 = item.kodeFaskes) !== null && _item$kodeFaskes3 !== void 0 ? _item$kodeFaskes3 : '') === target;
  }) || null;
}

/*
|--------------------------------------------------------------------------
| APPLY FASKES LOCATION
|--------------------------------------------------------------------------
*/
function applyFaskesLocation(_x) {
  return _applyFaskesLocation.apply(this, arguments);
}
/*
|--------------------------------------------------------------------------
| GET FIRST VALUE
|--------------------------------------------------------------------------
*/
function _applyFaskesLocation() {
  _applyFaskesLocation = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3(kodeFaskes) {
    var faskesData,
      selectedDesa,
      faskes,
      provinceCode,
      cityCode,
      districtCode,
      provinceName,
      cityName,
      districtName,
      _args3 = arguments,
      _t2;
    return _regenerator().w(function (_context3) {
      while (1) switch (_context3.p = _context3.n) {
        case 0:
          faskesData = _args3.length > 1 && _args3[1] !== undefined ? _args3[1] : null;
          selectedDesa = _args3.length > 2 && _args3[2] !== undefined ? _args3[2] : null;
          _context3.p = 1;
          console.log('==========================================');
          console.log('APPLY FASKES LOCATION');
          console.log('kodeFaskes:', kodeFaskes);

          /*
          |--------------------------------------------------------------------------
          | Gunakan data yang sudah ada
          |--------------------------------------------------------------------------
          */
          faskes = faskesData;
          if (!faskes) {
            faskes = findFaskesFromCache(kodeFaskes);
          }
          if (faskes) {
            _context3.n = 2;
            break;
          }
          console.error('FASKES TIDAK DITEMUKAN:', kodeFaskes);
          resetLocation();
          return _context3.a(2);
        case 2:
          console.log('DATA MASTER FASKES:', faskes);

          /*
          |--------------------------------------------------------------------------
          | Ambil kode wilayah
          |--------------------------------------------------------------------------
          */
          provinceCode = getFirstValue(faskes, ['kodePropinsi', 'kodeProvinsi', 'kode_provinsi', 'province_code']);
          cityCode = getFirstValue(faskes, ['kodeKabupaten', 'kodeKota', 'kodeKabupatenKota', 'kode_kabupaten', 'city_code']);
          districtCode = getFirstValue(faskes, ['kodeKecamatan', 'kode_kecamatan', 'district_code']);
          console.log('KODE WILAYAH:', {
            provinceCode: provinceCode,
            cityCode: cityCode,
            districtCode: districtCode
          });

          /*
          |--------------------------------------------------------------------------
          | Validasi kecamatan
          |--------------------------------------------------------------------------
          */
          if (districtCode) {
            _context3.n = 3;
            break;
          }
          console.error('kodeKecamatan tidak ditemukan dari Master Faskes:', faskes);
          showError('Kode Kecamatan Puskesmas tidak ditemukan pada Master Faskes.');
          resetLocation();
          return _context3.a(2);
        case 3:
          /*
          |--------------------------------------------------------------------------
          | Nama wilayah
          |--------------------------------------------------------------------------
          */
          provinceName = getFirstValue(faskes, ['namaPropinsi', 'namaProvinsi', 'province_name']) || getRelationName(faskes.provinsi) || getRelationName(faskes.province);
          cityName = getFirstValue(faskes, ['namaKabupaten', 'namaKota', 'namaKabupatenKota', 'city_name']) || getRelationName(faskes.kota) || getRelationName(faskes.city);
          districtName = getFirstValue(faskes, ['namaKecamatan', 'district_name']) || getRelationName(faskes.kecamatan) || getRelationName(faskes.district);
          console.log('NAMA WILAYAH:', {
            provinceName: provinceName,
            cityName: cityName,
            districtName: districtName
          });

          /*
          |--------------------------------------------------------------------------
          | Provinsi
          |--------------------------------------------------------------------------
          */

          setLocationSelect('#kodePropinsi', provinceCode, provinceName || provinceCode);

          /*
          |--------------------------------------------------------------------------
          | Kota
          |--------------------------------------------------------------------------
          */

          setLocationSelect('#kodeKota', cityCode, cityName || cityCode);

          /*
          |--------------------------------------------------------------------------
          | Kecamatan
          |--------------------------------------------------------------------------
          */

          setLocationSelect('#kodeKecamatan', districtCode, districtName || districtCode);

          /*
          |--------------------------------------------------------------------------
          | Desa
          |--------------------------------------------------------------------------
          */
          _context3.n = 4;
          return loadVillages(districtCode, selectedDesa);
        case 4:
          console.log('LOKASI FASKES SELESAI');
          console.log('==========================================');
          _context3.n = 6;
          break;
        case 5:
          _context3.p = 5;
          _t2 = _context3.v;
          console.error('APPLY FASKES LOCATION ERROR:', _t2);
          resetLocation();
          showError('Gagal memuat wilayah Puskesmas.');
        case 6:
          return _context3.a(2);
      }
    }, _callee3, null, [[1, 5]]);
  }));
  return _applyFaskesLocation.apply(this, arguments);
}
function getFirstValue(object, keys) {
  if (!object) {
    return '';
  }
  for (var i = 0; i < keys.length; i++) {
    var key = keys[i];
    if (object[key] !== undefined && object[key] !== null && String(object[key]).trim() !== '') {
      return String(object[key]);
    }
  }
  return '';
}

/*
|--------------------------------------------------------------------------
| GET RELATION NAME
|--------------------------------------------------------------------------
*/

function getRelationName(relation) {
  if (!relation) {
    return '';
  }
  return relation.name || relation.nama || relation.namaPropinsi || relation.namaProvinsi || relation.namaKabupaten || relation.namaKota || relation.namaKecamatan || '';
}

/*
|--------------------------------------------------------------------------
| SET LOCATION SELECT
|--------------------------------------------------------------------------
*/

function setLocationSelect(selector, code, name) {
  var select = $(selector);
  if (!select.length) {
    console.warn('SELECT TIDAK DITEMUKAN:', selector);
    return;
  }
  if (!code) {
    select.html('<option value="">Tidak tersedia</option>').val('').prop('disabled', true).trigger('change.select2');
    return;
  }
  select.html("\n            <option\n                value=\"".concat(escapeHtml(code), "\"\n                selected\n            >\n                ").concat(escapeHtml(name || code), "\n            </option>\n        ")).val(String(code)).prop('disabled', true).trigger('change.select2');
}

/*
|--------------------------------------------------------------------------
| LOAD VILLAGES
|--------------------------------------------------------------------------
*/

function loadVillages(districtCode) {
  var selected = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  return new Promise(function (resolve, reject) {
    var select = $('#kodeDesa');
    console.log('LOAD DESA');
    console.log('districtCode:', districtCode);

    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */

    select.html('<option value="">Memuat desa...</option>').val('').prop('disabled', true).trigger('change.select2');
    if (!districtCode) {
      select.html('<option value="">Pilih Desa / Kelurahan</option>').prop('disabled', true).trigger('change.select2');
      resolve();
      return;
    }

    /*
    |--------------------------------------------------------------------------
    | Request Desa
    |--------------------------------------------------------------------------
    */

    $.ajax({
      url: window.WilayahKerjaPuskesmasConfig.villageUrl,
      type: 'GET',
      data: {
        district_code: districtCode
      },
      success: function success(response) {
        console.log('RESPONSE DESA:', response);
        var data = Array.isArray(response.data) ? response.data : [];
        var options = '<option value="">Pilih Desa / Kelurahan</option>';
        data.forEach(function (item) {
          /*
          |--------------------------------------------------------------------------
          | Support beberapa kemungkinan field
          |--------------------------------------------------------------------------
          */

          var code = getFirstValue(item, ['code', 'kodeDesa', 'kode_desa', 'id']);
          var name = getFirstValue(item, ['name', 'namaDesa', 'nama', 'nama_desa']);
          if (!code) {
            return;
          }
          options += "\n                                <option\n                                    value=\"".concat(escapeHtml(code), "\"\n                                >\n                                    ").concat(escapeHtml(name || code), "\n                                </option>\n                            ");
        });
        select.html(options).prop('disabled', false);

        /*
        |--------------------------------------------------------------------------
        | Selected Desa
        |--------------------------------------------------------------------------
        */

        if (selected !== null && selected !== undefined && selected !== '') {
          select.val(String(selected));
        }
        select.trigger('change.select2');
        console.log('DESA BERHASIL DIMUAT:', data.length);
        resolve();
      },
      error: function error(xhr) {
        console.error('LOAD DESA ERROR:', xhr.status, xhr.responseJSON || xhr.responseText);
        select.html('<option value="">Gagal memuat desa</option>').prop('disabled', true).trigger('change.select2');
        reject(xhr);
      }
    });
  });
}

/*
|--------------------------------------------------------------------------
| LOAD FILTER DESA
|--------------------------------------------------------------------------
|
| Filter Desa tidak boleh memanggil listdesa tanpa kecamatan jika
| endpoint controller memang membutuhkan district_code.
|
| Untuk filter global, kita coba panggil tanpa parameter.
| Jika backend memang mengharuskan district_code, endpoint perlu
| menyediakan endpoint khusus untuk semua desa.
|
*/

function loadFilterDesa() {
  console.log('LOAD FILTER DESA');
  $.ajax({
    url: window.WilayahKerjaPuskesmasConfig.villageUrl,
    type: 'GET',
    data: {},
    success: function success(response) {
      console.log('FILTER DESA RESPONSE:', response);
      var data = Array.isArray(response.data) ? response.data : [];
      var options = '<option value="">Semua Desa</option>';
      data.forEach(function (item) {
        var code = getFirstValue(item, ['code', 'kodeDesa', 'kode_desa', 'id']);
        var name = getFirstValue(item, ['name', 'namaDesa', 'nama', 'nama_desa']);
        if (!code) {
          return;
        }
        options += "\n                        <option\n                            value=\"".concat(escapeHtml(code), "\"\n                        >\n                            ").concat(escapeHtml(name || code), "\n                        </option>\n                    ");
      });
      $('#filterDesa').html(options).trigger('change.select2');
    },
    error: function error(xhr) {
      console.error('LOAD FILTER DESA ERROR:', xhr.status, xhr.responseJSON || xhr.responseText);
    }
  });
}

/*
|--------------------------------------------------------------------------
| OPEN MODAL
|--------------------------------------------------------------------------
*/

function openModal() {
  console.log('OPEN ADD MODAL');

  /*
  |--------------------------------------------------------------------------
  | Reset form
  |--------------------------------------------------------------------------
  */

  var form = document.getElementById('wilayahPuskesmasForm');
  if (form) {
    form.reset();
  }
  $('#wilayahId').val('');
  clearValidation();
  $('#wilayahPuskesmasModalTitle').text('Tambah Wilayah Kerja Puskesmas');
  resetLocation();

  /*
  |--------------------------------------------------------------------------
  | GROUP 3
  |--------------------------------------------------------------------------
  */

  if (isGroup3()) {
    $('#faskesContainer').hide();
    var kodeFaskes = getUserKodeFaskes();
    $('#kodeFaskes').val(kodeFaskes).prop('disabled', true).trigger('change.select2');
    var faskes = findFaskesFromCache(kodeFaskes);
    if (faskes) {
      applyFaskesLocation(kodeFaskes, faskes);
    }
  } else {
    /*
    |--------------------------------------------------------------------------
    | GROUP 1 / 2
    |--------------------------------------------------------------------------
    */

    $('#faskesContainer').show();
    $('#kodeFaskes').prop('disabled', false).val('').trigger('change.select2');
  }
  wilayahModal.show();
}

/*
|--------------------------------------------------------------------------
| RESET LOCATION
|--------------------------------------------------------------------------
*/

function resetLocation() {
  /*
  |--------------------------------------------------------------------------
  | Province
  |--------------------------------------------------------------------------
  */

  $('#kodePropinsi').html('<option value="">Pilih Provinsi</option>').val('').prop('disabled', true).trigger('change.select2');

  /*
  |--------------------------------------------------------------------------
  | City
  |--------------------------------------------------------------------------
  */

  $('#kodeKota').html('<option value="">Pilih Kota / Kabupaten</option>').val('').prop('disabled', true).trigger('change.select2');

  /*
  |--------------------------------------------------------------------------
  | District
  |--------------------------------------------------------------------------
  */

  $('#kodeKecamatan').html('<option value="">Pilih Kecamatan</option>').val('').prop('disabled', true).trigger('change.select2');

  /*
  |--------------------------------------------------------------------------
  | Village
  |--------------------------------------------------------------------------
  */

  $('#kodeDesa').html('<option value="">Pilih Desa / Kelurahan</option>').val('').prop('disabled', true).trigger('change.select2');
}

/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

function editWilayah(id) {
  console.log('EDIT WILAYAH:', id);
  Swal.fire({
    title: 'Memuat data...',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: function didOpen() {
      Swal.showLoading();
    }
  });
  $.ajax({
    url: window.WilayahKerjaPuskesmasConfig.baseUrl + '/' + id,
    type: 'GET',
    success: function () {
      var _success = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2(response) {
        var data, kodeFaskes, faskes, _t;
        return _regenerator().w(function (_context2) {
          while (1) switch (_context2.p = _context2.n) {
            case 0:
              _context2.p = 0;
              console.log('EDIT RESPONSE:', response);
              data = response.data;
              if (data) {
                _context2.n = 1;
                break;
              }
              throw new Error('Data wilayah tidak ditemukan.');
            case 1:
              /*
              |--------------------------------------------------------------------------
              | ID
              |--------------------------------------------------------------------------
              */

              $('#wilayahId').val(data.id);

              /*
              |--------------------------------------------------------------------------
              | TITLE
              |--------------------------------------------------------------------------
              */

              $('#wilayahPuskesmasModalTitle').text('Edit Wilayah Kerja Puskesmas');

              /*
              |--------------------------------------------------------------------------
              | FASKES
              |--------------------------------------------------------------------------
              */
              kodeFaskes = String(data.kodeFaskes || '');
              /*
              |--------------------------------------------------------------------------
              | GROUP 3
              |--------------------------------------------------------------------------
              */
              if (isGroup3()) {
                $('#faskesContainer').hide();
                $('#kodeFaskes').val(getUserKodeFaskes()).prop('disabled', true).trigger('change.select2');
              } else {
                $('#faskesContainer').show();
                $('#kodeFaskes').val(kodeFaskes).prop('disabled', false).trigger('change.select2');
              }

              /*
              |--------------------------------------------------------------------------
              | FASKES CACHE
              |--------------------------------------------------------------------------
              */
              faskes = findFaskesFromCache(kodeFaskes);
              /*
              |--------------------------------------------------------------------------
              | Kalau tidak ada di cache
              |--------------------------------------------------------------------------
              */
              if (faskes) {
                _context2.n = 3;
                break;
              }
              console.warn('Faskes tidak ada di cache.');
              _context2.n = 2;
              return findFaskes(kodeFaskes);
            case 2:
              faskes = _context2.v;
            case 3:
              _context2.n = 4;
              return applyFaskesLocation(kodeFaskes, faskes, data.kodeDesa || null);
            case 4:
              Swal.close();
              wilayahModal.show();
              _context2.n = 6;
              break;
            case 5:
              _context2.p = 5;
              _t = _context2.v;
              console.error('EDIT WILAYAH ERROR:', _t);
              Swal.close();
              showError(_t.message || 'Gagal memproses data wilayah.');
            case 6:
              return _context2.a(2);
          }
        }, _callee2, null, [[0, 5]]);
      }));
      function success(_x2) {
        return _success.apply(this, arguments);
      }
      return success;
    }(),
    error: function error(xhr) {
      var _xhr$responseJSON;
      Swal.close();
      console.error('GET WILAYAH ERROR:', xhr.status, xhr.responseJSON || xhr.responseText);
      showError(((_xhr$responseJSON = xhr.responseJSON) === null || _xhr$responseJSON === void 0 ? void 0 : _xhr$responseJSON.message) || 'Gagal mengambil data wilayah.');
    }
  });
}

/*
|--------------------------------------------------------------------------
| FIND FASKES VIA API
|--------------------------------------------------------------------------
|
| Fallback apabila data tidak ada di cache.
|
*/

function findFaskes(kodeFaskes) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: window.WilayahKerjaPuskesmasConfig.faskesUrl,
      type: 'GET',
      success: function success(response) {
        var data = Array.isArray(response.data) ? response.data : [];
        var faskes = data.find(function (item) {
          var _item$kodeFaskes4;
          return String((_item$kodeFaskes4 = item.kodeFaskes) !== null && _item$kodeFaskes4 !== void 0 ? _item$kodeFaskes4 : '') === String(kodeFaskes !== null && kodeFaskes !== void 0 ? kodeFaskes : '');
        });
        resolve(faskes || null);
      },
      error: function error(xhr) {
        reject(xhr);
      }
    });
  });
}

/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/

function saveWilayah() {
  clearValidation();

  /*
  |--------------------------------------------------------------------------
  | Faskes
  |--------------------------------------------------------------------------
  */

  var kodeFaskes = isGroup3() ? getUserKodeFaskes() : String($('#kodeFaskes').val() || '');

  /*
  |--------------------------------------------------------------------------
  | Desa
  |--------------------------------------------------------------------------
  */

  var kodeDesa = String($('#kodeDesa').val() || '');
  console.log('SAVE DATA:', {
    kodeFaskes: kodeFaskes,
    kodeDesa: kodeDesa
  });

  /*
  |--------------------------------------------------------------------------
  | Validasi
  |--------------------------------------------------------------------------
  */

  if (!kodeFaskes) {
    showError('Puskesmas wajib dipilih.');
    return;
  }
  if (!kodeDesa) {
    showError('Desa / Kelurahan wajib dipilih.');
    return;
  }

  /*
  |--------------------------------------------------------------------------
  | ID
  |--------------------------------------------------------------------------
  */

  var id = $('#wilayahId').val();

  /*
  |--------------------------------------------------------------------------
  | URL
  |--------------------------------------------------------------------------
  */

  var baseUrl = window.WilayahKerjaPuskesmasConfig.baseUrl;
  var url = id ? "".concat(baseUrl, "/").concat(id) : baseUrl;
  var method = id ? 'PUT' : 'POST';

  /*
  |--------------------------------------------------------------------------
  | BUTTON
  |--------------------------------------------------------------------------
  */

  var button = $('#btnSaveWilayah');
  button.prop('disabled', true).html("\n            <span\n                class=\"spinner-border spinner-border-sm me-2\"\n            ></span>\n            Menyimpan...\n        ");

  /*
  |--------------------------------------------------------------------------
  | AJAX
  |--------------------------------------------------------------------------
  */

  $.ajax({
    url: url,
    type: method,
    data: {
      kodeFaskes: kodeFaskes,
      kodeDesa: kodeDesa
    },
    success: function success(response) {
      console.log('SAVE RESPONSE:', response);
      wilayahModal.hide();
      reloadTable();
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: response.message || 'Data berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false
      });
    },
    error: function error(xhr) {
      var _xhr$responseJSON2, _xhr$responseJSON3;
      console.error('SAVE ERROR:', xhr.status, xhr.responseJSON || xhr.responseText);

      /*
      |--------------------------------------------------------------------------
      | Validation 422
      |--------------------------------------------------------------------------
      */

      if (xhr.status === 422 && (_xhr$responseJSON2 = xhr.responseJSON) !== null && _xhr$responseJSON2 !== void 0 && _xhr$responseJSON2.errors) {
        showValidationErrors(xhr.responseJSON.errors);
        return;
      }
      showError(((_xhr$responseJSON3 = xhr.responseJSON) === null || _xhr$responseJSON3 === void 0 ? void 0 : _xhr$responseJSON3.message) || 'Terjadi kesalahan saat menyimpan data.');
    },
    complete: function complete() {
      button.prop('disabled', false).html("\n                        <i class=\"fas fa-save me-2\"></i>\n                        Simpan\n                    ");
    }
  });
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

function deleteWilayah(id) {
  Swal.fire({
    title: 'Hapus Wilayah?',
    text: 'Mapping wilayah kerja ini akan dihapus.',
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
      url: "".concat(window.WilayahKerjaPuskesmasConfig.baseUrl, "/").concat(id),
      type: 'DELETE',
      success: function success(response) {
        reloadTable();
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
        console.error('DELETE ERROR:', xhr.status, xhr.responseJSON || xhr.responseText);
        showError(((_xhr$responseJSON4 = xhr.responseJSON) === null || _xhr$responseJSON4 === void 0 ? void 0 : _xhr$responseJSON4.message) || 'Data tidak dapat dihapus.');
      }
    });
  });
}

/*
|--------------------------------------------------------------------------
| RELOAD DATATABLE
|--------------------------------------------------------------------------
*/

function reloadTable() {
  if (wilayahTable) {
    wilayahTable.ajax.reload(null, false);
  }
}

/*
|--------------------------------------------------------------------------
| SELECT2
|--------------------------------------------------------------------------
*/

function initSelect2() {
  /*
  |--------------------------------------------------------------------------
  | Modal Select2
  |--------------------------------------------------------------------------
  */

  $('#kodeFaskes,' + '#kodePropinsi,' + '#kodeKota,' + '#kodeKecamatan,' + '#kodeDesa').select2({
    dropdownParent: $('#wilayahPuskesmasModal'),
    width: '100%'
  });

  /*
  |--------------------------------------------------------------------------
  | Filter Select2
  |--------------------------------------------------------------------------
  */

  $('#filterFaskes, #filterDesa').select2({
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
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {
  return $('<div>').text(value !== null && value !== void 0 ? value : '').html();
}
/******/ })()
;