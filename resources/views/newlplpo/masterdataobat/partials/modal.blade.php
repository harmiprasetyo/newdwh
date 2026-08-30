{{-- ==========================================================
    MODAL MASTER DATA OBAT
=========================================================== --}}

<div
    class="modal fade"
    id="modalObat"
    tabindex="-1"
    aria-labelledby="modalObatTitle"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            {{-- ==================================================
                HEADER
            =================================================== --}}

            <div class="modal-header bg-primary text-white">

                <h5
                    class="modal-title"
                    id="modalObatTitle">

                    <i class="bi bi-capsule me-2"></i>

                    Tambah Obat

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>


            {{-- ==================================================
                FORM
            =================================================== --}}

            <form id="formObat">

                @csrf

                {{-- ID untuk edit --}}
                <input
                    type="hidden"
                    id="obat_id"
                    name="obat_id">


                {{-- ==================================================
                    BODY
                =================================================== --}}

                <div class="modal-body">

                    <div class="row g-3">

                        {{-- ==========================================
    KODE OBAT
=========================================== --}}

<div class="col-md-5">

    <label
        for="kode_obat"
        class="form-label fw-semibold">

        Kode Obat

        <span class="text-danger">*</span>

    </label>

    <input
        type="text"
        id="kode_obat"
        name="kode_obat"
        class="form-control"
        maxlength="50"
        autocomplete="off"
        placeholder="Masukkan kode obat"
        required>

    <div
        class="invalid-feedback"
        id="kode_obat_error">
    </div>

</div>


                        {{-- ==========================================
                            SATUAN
                        =========================================== --}}

                        <div class="col-md-3">

                            <label
                                for="satuan"
                                class="form-label fw-semibold">

                                Satuan

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                id="satuan"
                                name="satuan"
                                class="form-control"
                                maxlength="255"
                                autocomplete="off"
                                placeholder="Contoh: Tablet"
                                required>

                            <div
                                class="invalid-feedback"
                                id="satuan_error">
                            </div>

                        </div>


                        {{-- ==========================================
                            NAPZA
                        =========================================== --}}

                        <div class="col-md-4">

                            <label
                                for="obat_napza"
                                class="form-label fw-semibold">

                                Obat-obatan NAPZA

                            </label>

                            <select
                                id="obat_napza"
                                name="obat_napza"
                                class="form-select">

                                <option value="tidak">
                                    Tidak
                                </option>

                                <option value="ya">
                                    Ya
                                </option>

                            </select>

                            <div
                                class="invalid-feedback"
                                id="obat_napza_error">
                            </div>

                        </div>


                        {{-- ==========================================
                            NAMA OBAT
                        =========================================== --}}

                        <div class="col-md-12">

                            <label
                                for="nama_obat"
                                class="form-label fw-semibold">

                                Nama Obat

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                id="nama_obat"
                                name="nama_obat"
                                class="form-control"
                                maxlength="255"
                                autocomplete="off"
                                placeholder="Masukkan nama obat"
                                required>

                            <div
                                class="invalid-feedback"
                                id="nama_obat_error">
                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                    FOOTER
                =================================================== --}}

                <div class="modal-footer bg-light">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        <i class="bi bi-x-lg me-1"></i>

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnSimpanObat">

                        <span
                            id="spinnerObat"
                            class="spinner-border spinner-border-sm d-none me-1">
                        </span>

                        <i
                            id="saveIconObat"
                            class="bi bi-check-lg me-1">
                        </i>

                        <span id="saveTextObat">
                            Simpan
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
