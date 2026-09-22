@extends('layouts.mainrme')

@section('container')

<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="searchModalLabel">
                    Pencarian Pasien
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <form id="searchForm">

                    <div class="mb-3">
                        <label for="search_type" class="form-label">
                            Jenis Identitas
                        </label>

                        <div class="input-group">

                            <select id="search_type"
                                    name="search_type"
                                    class="form-select"
                                    style="max-width: 125px;">
                                <option value="nik">NIK</option>
                                <option value="nik_ibu">NIK-IBU</option>
                            </select>

                            <input type="text"
                                   id="nik"
                                   name="nik"
                                   class="form-control"
                                   placeholder="Masukkan NIK"
                                   inputmode="numeric"
                                   autocomplete="off"
                                   required>

                        </div>

                        <div id="search_type_error"></div>
                    </div>

                </form>

            </div>

            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-success w-100"
                        id="btnSearch">
                    <i class="bi bi-search"></i>
                    Cari
                </button>

                <button type="button"
                        class="btn btn-success w-100"
                        id="loaderbtn"
                        disabled>
                    <span class="spinner-border spinner-border-sm me-2"
                          aria-hidden="true">
                    </span>
                    <span>Loading...</span>
                </button>

            </div>

        </div>
    </div>
</div>


{{-- =========================================================
     OTP MODAL
     ========================================================= --}}
<div class="modal fade"
     id="nextModal"
     tabindex="-1"
     aria-labelledby="nextModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="nextModalLabel">
                    Verifikasi OTP
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <form id="otpForm">

                    <div class="mb-3">
                        <label for="otp" class="form-label">
                            Kode OTP
                        </label>

                        <input type="text"
                               id="otp"
                               name="otp"
                               class="form-control"
                               placeholder="Masukkan OTP"
                               maxlength="6"
                               autocomplete="one-time-code">
                    </div>

                    <input type="hidden"
                           id="otpnik"
                           name="otpnik">

                    <input type="hidden"
                           id="identifier"
                           name="identifier">

                </form>

            </div>

            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-success w-100"
                        id="btnOTP">
                    Verifikasi
                </button>

            </div>

        </div>
    </div>
</div>


{{-- =========================================================
     INFORM CONSENT MODAL
     ========================================================= --}}
<div class="modal fade"
     id="uploadModal"
     tabindex="-1"
     aria-labelledby="uploadModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="uploadModalLabel">
                    Upload Inform Consent
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <form id="uploadForm"
                      enctype="multipart/form-data">
<input
    type="hidden"
    id="patient_id"
    name="patient_id"
>

<input
    type="hidden"
    id="updnik"
    name="nik"
>

<input
    type="hidden"
    id="nikibu"
    name="nik_ibu"
>

<input
    type="hidden"
    id="upload_search_type"
    name="search_type"
>


                    <div class="mb-3">

                        <label for="file" class="form-label">
                            Upload File
                        </label>

                        <input type="file"
                               id="file"
                               name="file"
                               class="form-control"
                               accept=".pdf,image/*">

                        <small class="text-muted">
                            Format yang diperbolehkan: PDF atau gambar.
                        </small>

                    </div>

                </form>

            </div>

            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-success w-100"
                        id="btnUpload">
                    Upload
                </button>

            </div>

        </div>
    </div>
</div>


{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Laravel Mix --}}
<script src="{{ mix('js/datarme/search.js') }}"></script>

@endsection
