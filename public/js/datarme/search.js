/******/ (() => { // webpackBootstrap
/*!****************************************!*\
  !*** ./resources/js/datarme/search.js ***!
  \****************************************/
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
$(document).ready(function () {
  // =========================================================
  // INITIALIZATION
  // =========================================================

  $('#loaderbtn').hide();
  $('#searchModal').modal({
    backdrop: 'static',
    keyboard: false
  }).modal('show');

  // =========================================================
  // CHANGE PLACEHOLDER BASED ON SEARCH TYPE
  // =========================================================

  $('#search_type').on('change', function () {
    var type = $(this).val();
    if (type === 'nik_ibu') {
      $('#nik').attr('placeholder', 'Masukkan NIK Ibu');
    } else {
      $('#nik').attr('placeholder', 'Masukkan NIK');
    }
    $('#nik').val('');
  });

  // =========================================================
  // VALIDATION
  // =========================================================

  $('#searchForm').validate({
    errorPlacement: function errorPlacement(error, element) {
      error.addClass('text-danger mt-2');
      if (element.attr('id') === 'nik') {
        error.insertAfter(element.closest('.input-group'));
      } else {
        error.insertAfter(element);
      }
    },
    rules: {
      nik: {
        required: true
      },
      search_type: {
        required: true
      }
    },
    messages: {
      nik: {
        required: 'NIK wajib diisi'
      },
      search_type: {
        required: 'Jenis identitas wajib dipilih'
      }
    },
    submitHandler: function submitHandler() {
      searchPatient();
    }
  });

  // =========================================================
  // SEARCH BUTTON
  // =========================================================

  $('#btnSearch').on('click', function () {
    $('#searchForm').submit();
  });

  // =========================================================
  // SEARCH PATIENT
  // =========================================================

  function searchPatient() {
    var searchType = $('#search_type').val();
    var nik = $('#nik').val().trim();
    $('#btnSearch').hide();
    $('#loaderbtn').show();
    $.ajax({
      url: '/datarme/search',
      type: 'POST',
      data: {
        nik: nik,
        search_type: searchType,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      // =================================================
      // SUCCESS
      // =================================================

      success: function () {
        var _success = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(response) {
          var patients, selectedPatient;
          return _regenerator().w(function (_context) {
            while (1) switch (_context.n) {
              case 0:
                $('#loaderbtn').hide();
                $('#btnSearch').show();

                // =============================================
                // NOT FOUND
                // =============================================
                if (!(response.status === 'not_found')) {
                  _context.n = 2;
                  break;
                }
                _context.n = 1;
                return Swal.fire({
                  title: 'Data Tidak Ditemukan',
                  text: 'Data pasien tidak ditemukan.',
                  icon: 'info',
                  confirmButtonText: 'OK'
                });
              case 1:
                return _context.a(2);
              case 2:
                if (!(response.status === 'success')) {
                  _context.n = 8;
                  break;
                }
                patients = Array.isArray(response.data) ? response.data : [];
                selectedPatient = null; // =========================================
                // ONE PATIENT
                // =========================================
                if (!(patients.length === 1)) {
                  _context.n = 3;
                  break;
                }
                selectedPatient = patients[0];
                _context.n = 5;
                break;
              case 3:
                if (!(patients.length > 1)) {
                  _context.n = 5;
                  break;
                }
                _context.n = 4;
                return selectPatient(patients);
              case 4:
                selectedPatient = _context.v;
                if (selectedPatient) {
                  _context.n = 5;
                  break;
                }
                return _context.a(2);
              case 5:
                if (selectedPatient) {
                  _context.n = 7;
                  break;
                }
                _context.n = 6;
                return Swal.fire({
                  title: 'Data Tidak Ditemukan',
                  text: 'Tidak ada pasien yang dapat dipilih.',
                  icon: 'warning'
                });
              case 6:
                return _context.a(2);
              case 7:
                // =========================================
                // SIMPAN SEARCH TYPE
                // =========================================

                /*
                 * Pastikan pasien yang dipilih mengetahui
                 * jenis pencarian yang digunakan.
                 */

                selectedPatient.search_type = response.search_type || searchType;

                // =========================================
                // CONTINUE ACCESS FLOW
                // =========================================
                _context.n = 8;
                return accessPatient(selectedPatient);
              case 8:
                return _context.a(2);
            }
          }, _callee);
        }));
        function success(_x) {
          return _success.apply(this, arguments);
        }
        return success;
      }(),
      // =================================================
      // AJAX ERROR
      // =================================================

      error: function error(xhr, status, _error) {
        console.error('AJAX Error:', _error);
        $('#loaderbtn').hide();
        $('#btnSearch').show();
        var message = 'Terjadi kesalahan saat mencari data pasien.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        }
        Swal.fire({
          title: 'Error',
          text: message,
          icon: 'error'
        });
      }
    });
  }

  // =========================================================
  // PATIENT ACCESS FLOW
  // =========================================================
  function accessPatient(_x2) {
    return _accessPatient.apply(this, arguments);
  } // =========================================================
  // OTP PROCESS
  // =========================================================
  function _accessPatient() {
    _accessPatient = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2(data) {
      var result;
      return _regenerator().w(function (_context2) {
        while (1) switch (_context2.n) {
          case 0:
            _context2.n = 1;
            return Swal.fire({
              title: 'Data Tersedia',
              html: "\n                    <div class=\"text-center\">\n\n                        <strong>\n                            ".concat(escapeHtml(data.name || '-'), "\n                        </strong>\n\n                        <br>\n\n                        <small>\n                            Patient ID:\n                            ").concat(escapeHtml(data.patient_id || '-'), "\n                        </small>\n\n                        <br>\n\n                        <small>\n                            NIK:\n                            ").concat(escapeHtml(data.nik || '-'), "\n                        </small>\n\n                        <br>\n\n                        <small>\n                            NIK Ibu:\n                            ").concat(escapeHtml(data.nik_ibu || '-'), "\n                        </small>\n\n                    </div>\n\n                    <hr>\n\n                    <div>\n                        Pilih metode akses data pasien\n                    </div>\n                "),
              icon: 'info',
              showDenyButton: true,
              confirmButtonText: 'ENTER OTP',
              denyButtonText: 'INFORM CONSENT',
              confirmButtonColor: '#198754',
              denyButtonColor: '#0d6efd'
            });
          case 1:
            result = _context2.v;
            if (!result.isConfirmed) {
              _context2.n = 3;
              break;
            }
            _context2.n = 2;
            return processOtp(data);
          case 2:
            return _context2.a(2);
          case 3:
            // =====================================================
            // INFORM CONSENT
            // =====================================================

            if (result.isDenied) {
              console.log('PATIENT YANG DIPILIH:', data);
              $('#patient_id').val(data.patient_id || '');
              $('#updnik').val(data.nik || '');
              $('#nikibu').val(data.nik_ibu || '');
              $('#search_type').val(data.search_type || '');
              console.log('patient_id:', $('#patient_id').val());
              console.log('nik:', $('#updnik').val());
              console.log('nik_ibu:', $('#nikibu').val());
              console.log('search_type:', $('#search_type').val());
              $('#uploadModal').modal('show');
            }
          case 4:
            return _context2.a(2);
        }
      }, _callee2);
    }));
    return _accessPatient.apply(this, arguments);
  }
  function processOtp(_x3) {
    return _processOtp.apply(this, arguments);
  } // =========================================================
  // VERIFY OTP
  // =========================================================
  function _processOtp() {
    _processOtp = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3(data) {
      var result, _t;
      return _regenerator().w(function (_context3) {
        while (1) switch (_context3.p = _context3.n) {
          case 0:
            if (data.phone) {
              _context3.n = 2;
              break;
            }
            _context3.n = 1;
            return Swal.fire({
              title: 'Nomor HP Tidak Tersedia',
              text: 'Nomor HP pasien tidak tersedia.',
              icon: 'warning'
            });
          case 1:
            return _context3.a(2);
          case 2:
            _context3.p = 2;
            _context3.n = 3;
            return $.post('/send-otp', {
              identifier: data.phone,
              nama: data.name,
              _token: $('meta[name="csrf-token"]').attr('content')
            });
          case 3:
            _context3.n = 6;
            break;
          case 4:
            _context3.p = 4;
            _t = _context3.v;
            console.error('Send OTP Error:', _t);
            _context3.n = 5;
            return Swal.fire({
              title: 'Gagal',
              text: 'OTP gagal dikirim.',
              icon: 'error'
            });
          case 5:
            return _context3.a(2);
          case 6:
            _context3.n = 7;
            return Swal.fire({
              title: 'Input OTP',
              input: 'text',
              inputLabel: 'Kode OTP',
              inputPlaceholder: 'Masukkan OTP',
              showCancelButton: true,
              confirmButtonText: 'Verifikasi',
              cancelButtonText: 'Batal',
              inputAttributes: {
                maxlength: 6,
                autocomplete: 'one-time-code',
                inputmode: 'numeric'
              },
              inputValidator: function inputValidator(value) {
                if (!value) {
                  return 'OTP wajib diisi';
                }
                if (!/^\d{6}$/.test(value)) {
                  return;
                  // removed by dead control flow

                }
              }
            });
          case 7:
            result = _context3.v;
            if (!(!result.isConfirmed || !result.value)) {
              _context3.n = 8;
              break;
            }
            return _context3.a(2);
          case 8:
            _context3.n = 9;
            return verifyOtp(data, result.value);
          case 9:
            return _context3.a(2);
        }
      }, _callee3, null, [[2, 4]]);
    }));
    return _processOtp.apply(this, arguments);
  }
  function verifyOtp(data, otp) {
    $.ajax({
      url: '/verify-otp',
      type: 'POST',
      dataType: 'json',
      data: {
        identifier: data.phone,
        otp: otp,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(response) {
        if (response.success === true) {
          Swal.fire({
            title: 'Berhasil',
            text: response.message,
            icon: 'success',
            confirmButtonText: 'OK'
          }).then(function (result) {
            if (result.isConfirmed) {
              redirectToPatient(data);
            }
          });
        } else {
          Swal.fire({
            title: 'Gagal',
            text: response.message,
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      },
      error: function error(xhr) {
        console.error('Verify OTP Error:', xhr);
        var message = 'Verifikasi OTP gagal.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        }
        Swal.fire({
          title: 'Error',
          text: message,
          icon: 'error'
        });
      }
    });
  }

  // =========================================================
  // OLD OTP MODAL BUTTON
  // =========================================================

  $('#btnOTP').on('click', function () {
    var identifier = $('#identifier').val();
    var otp = $('#otp').val();
    var patientId = $('#otppatientid').val();
    var nik = $('#otpnik').val();
    var nikIbu = $('#otpnikiibu').val();
    if (!otp) {
      Swal.fire({
        title: 'OTP',
        text: 'OTP wajib diisi.',
        icon: 'warning'
      });
      return;
    }
    $.ajax({
      url: '/verify-otp',
      type: 'POST',
      dataType: 'json',
      data: {
        identifier: identifier,
        otp: otp,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(response) {
        if (response.success === true) {
          Swal.fire({
            title: response.message,
            icon: 'success',
            confirmButtonText: 'OK'
          }).then(function (result) {
            if (result.isConfirmed) {
              var params = new URLSearchParams();
              if (patientId) {
                params.set('patient_id', patientId);
              }
              if (nik) {
                params.set('nik', nik);
              }
              if (nikIbu) {
                params.set('nik_ibu', nikIbu);
              }
              window.location.href = '/datarme/search?' + params.toString();
            }
          });
        } else {
          Swal.fire({
            title: response.message,
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      },
      error: function error(xhr) {
        console.error('OTP Error:', xhr);
        Swal.fire({
          title: 'Error',
          text: 'Terjadi kesalahan saat verifikasi OTP.',
          icon: 'error'
        });
      }
    });
  });

  // =========================================================
  // UPLOAD INFORM CONSENT
  // =========================================================

  $('#btnUpload').on('click', function () {
    var patientId = $('#patient_id').val();
    var nik = $('#updnik').val();
    var nikIbu = $('#nikibu').val();
    var searchType = $('#search_type').val();
    console.log('=== UPLOAD ===');
    console.log('patient_id:', patientId);
    console.log('nik:', nik);
    console.log('nik_ibu:', nikIbu);
    console.log('search_type:', searchType);
    if (!patientId) {
      Swal.fire({
        title: 'Error',
        text: 'Pasien belum dipilih.',
        icon: 'error'
      });
      return;
    }
    if (!nik && !nikIbu) {
      Swal.fire({
        title: 'Error',
        text: 'NIK pasien atau NIK-IBU tidak tersedia.',
        icon: 'error'
      });
      return;
    }
    var params = new URLSearchParams();
    params.set('patient_id', patientId);
    if (nik) {
      params.set('nik', nik);
    }
    if (nikIbu) {
      params.set('nik_ibu', nikIbu);
    }
    if (searchType) {
      params.set('search_type', searchType);
    }
    window.location.href = '/datarme/search?' + params.toString();
  });

  // =========================================================
  // SELECT PATIENT WHEN MULTIPLE RESULTS
  // =========================================================
  function selectPatient(_x4) {
    return _selectPatient.apply(this, arguments);
  } // =========================================================
  // REDIRECT TO PATIENT
  // =========================================================
  function _selectPatient() {
    _selectPatient = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee4(patients) {
      var html, result;
      return _regenerator().w(function (_context4) {
        while (1) switch (_context4.n) {
          case 0:
            html = "\n            <div class=\"text-start\">\n\n                <div class=\"mb-3\">\n\n                    <strong>\n                        Ditemukan\n                        ".concat(patients.length, "\n                        pasien.\n                    </strong>\n\n                </div>\n        ");
            patients.forEach(function (patient, index) {
              var birthDate = patient.birth_date ? formatDate(patient.birth_date) : '-';
              var nik = patient.nik || '-';
              var nikIbu = patient.nik_ibu || '-';
              html += "\n                    <div\n                        class=\"form-check border rounded p-3 mb-2\"\n                    >\n\n                        <input\n                            class=\"\n                                form-check-input\n                                patient-option\n                            \"\n                            type=\"radio\"\n                            name=\"selected_patient\"\n                            id=\"patient_".concat(index, "\"\n                            value=\"").concat(index, "\"\n                        >\n\n                        <label\n                            class=\"\n                                form-check-label\n                                w-100\n                            \"\n                            for=\"patient_").concat(index, "\"\n                        >\n\n                            <strong>\n                                ").concat(escapeHtml(patient.name || '-'), "\n                            </strong>\n\n                            <br>\n\n                            <small>\n                                Patient ID:\n                                ").concat(escapeHtml(patient.patient_id || '-'), "\n                            </small>\n\n                            <br>\n\n                            <small>\n                                NIK:\n                                ").concat(escapeHtml(nik), "\n                            </small>\n\n                            <br>\n\n                            <small>\n                                NIK Ibu:\n                                ").concat(escapeHtml(nikIbu), "\n                            </small>\n\n                            <br>\n\n                            <small>\n                                Tanggal Lahir:\n                                ").concat(escapeHtml(birthDate), "\n                            </small>\n\n                        </label>\n\n                    </div>\n                ");
            });
            html += "\n            </div>\n        ";
            _context4.n = 1;
            return Swal.fire({
              title: 'Pilih Pasien',
              html: html,
              width: 600,
              showCancelButton: true,
              confirmButtonText: 'Pilih Pasien',
              cancelButtonText: 'Batal',
              confirmButtonColor: '#198754',
              preConfirm: function preConfirm() {
                var selected = $('input[name="selected_patient"]:checked').val();
                if (selected === undefined) {
                  Swal.showValidationMessage('Silakan pilih pasien terlebih dahulu.');
                  return false;
                }
                return patients[selected];
              }
            });
          case 1:
            result = _context4.v;
            if (result.isConfirmed) {
              _context4.n = 2;
              break;
            }
            return _context4.a(2, null);
          case 2:
            return _context4.a(2, result.value);
        }
      }, _callee4);
    }));
    return _selectPatient.apply(this, arguments);
  }
  function redirectToPatient(data) {
    var params = new URLSearchParams();

    /*
     * patient_id adalah identifier utama.
     */

    if (data.patient_id) {
      params.set('patient_id', data.patient_id);
    }
    if (data.nik) {
      params.set('nik', data.nik);
    }
    if (data.nik_ibu) {
      params.set('nik_ibu', data.nik_ibu);
    }
    if (data.search_type) {
      params.set('search_type', data.search_type);
    }
    window.location.href = '/datarme/search?' + params.toString();
  }

  // =========================================================
  // RESET SEARCH MODAL
  // =========================================================

  $('#searchModal').on('shown.bs.modal', function () {
    $('#nik').trigger('focus');
  });

  // =========================================================
  // FORMAT DATE
  // =========================================================

  function formatDate(date) {
    if (!date) {
      return '-';
    }
    var parts = date.split('-');
    if (parts.length !== 3) {
      return date;
    }
    return "\n            ".concat(parts[2], "-\n            ").concat(parts[1], "-\n            ").concat(parts[0], "\n        ").replace(/\s/g, '');
  }

  // =========================================================
  // ESCAPE HTML
  // =========================================================

  function escapeHtml(value) {
    return $('<div>').text(value !== null && value !== void 0 ? value : '').html();
  }
});
/******/ })()
;