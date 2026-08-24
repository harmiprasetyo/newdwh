@extends('layouts.admin')

@section('title', 'Wilayah Kerja Puskesmas')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>
                <h5 class="mb-0">
                    <i class="fas fa-hospital me-2"></i>
                    Wilayah Kerja Puskesmas
                </h5>

                <small class="text-muted">
                    Mapping Puskesmas dengan Desa wilayah kerja
                </small>
            </div>

            <button
                type="button"
                class="btn btn-primary"
                id="btnAddWilayah"
            >
                <i class="fas fa-plus me-2"></i>
                Tambah Wilayah
            </button>

        </div>

        <div class="card-body">

            {{-- FILTER --}}

            <div class="row g-3 mb-4">

                <div class="col-md-5">

                    <label class="form-label">
                        Puskesmas
                    </label>

                    <select
                        id="filterFaskes"
                        class="form-select"
                    >
                        <option value="">
                            Semua Puskesmas
                        </option>
                    </select>

                </div>

                <div class="col-md-5">

                    <label class="form-label">
                        Desa
                    </label>

                    <select
                        id="filterDesa"
                        class="form-select"
                    >
                        <option value="">
                            Semua Desa
                        </option>
                    </select>

                </div>

                <div class="col-md-2 d-flex align-items-end">

                    <button
                        type="button"
                        class="btn btn-outline-secondary w-100"
                        id="btnResetFilter"
                    >
                        <i class="fas fa-sync-alt me-2"></i>
                        Reset
                    </button>

                </div>

            </div>

            {{-- TABLE --}}

            <div class="table-responsive">

                <table
                    id="wilayahPuskesmasTable"
                    class="table table-bordered table-hover align-middle w-100"
                >

                    <thead>

                        <tr>

                            <th width="50">
                                No
                            </th>

                            <th>
                                Puskesmas
                            </th>

                            <th>
                                Desa
                            </th>

                            <th>
                                Kecamatan
                            </th>

                            <th>
                                Kota/Kabupaten
                            </th>

                            <th>
                                Provinsi
                            </th>

                            <th width="100">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL WILAYAH KERJA PUSKESMAS
========================================================= --}}

<div
    class="modal fade"
    id="wilayahPuskesmasModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="wilayahPuskesmasModalTitle"
                >
                    Tambah Wilayah Kerja Puskesmas
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <form id="wilayahPuskesmasForm">

                <div class="modal-body">

                    <input
                        type="hidden"
                        id="wilayahId"
                        name="id"
                    >

                    {{-- =====================================================
                         FASKES
                    ====================================================== --}}

                 <div
    class="mb-3"
    id="faskesContainer"
>

    <label
        for="kodeFaskes"
        class="form-label"
    >
        Puskesmas
        <span class="text-danger">*</span>
    </label>

    <select
        id="kodeFaskes"
        name="kodeFaskes"
        class="form-select"
    >
        <option value="">
            Pilih Puskesmas
        </option>
    </select>

</div>

              <div
    id="wilayahFaskesContainer"
>

    {{-- PROVINSI --}}

    <div class="mb-3">

        <label
            for="kodePropinsi"
            class="form-label"
        >
            Provinsi
        </label>

        <select
            id="kodePropinsi"
            class="form-select"
            disabled
        >
            <option value="">
                Pilih Provinsi
            </option>
        </select>

    </div>


    {{-- KABUPATEN --}}

    <div class="mb-3">

        <label
            for="kodeKota"
            class="form-label"
        >
            Kota / Kabupaten
        </label>

        <select
            id="kodeKota"
            class="form-select"
            disabled
        >
            <option value="">
                Pilih Kota / Kabupaten
            </option>
        </select>

    </div>


    {{-- KECAMATAN --}}

    <div class="mb-3">

        <label
            for="kodeKecamatan"
            class="form-label"
        >
            Kecamatan
        </label>

        <select
            id="kodeKecamatan"
            class="form-select"
            disabled
        >
            <option value="">
                Pilih Kecamatan
            </option>
        </select>

    </div>

</div>

                    {{-- =====================================================
                         DESA
                    ====================================================== --}}

                   <div class="mb-3">

    <label
        for="kodeDesa"
        class="form-label"
    >
        Desa / Kelurahan
        <span class="text-danger">*</span>
    </label>

    <select
        id="kodeDesa"
        name="kodeDesa"
        class="form-select"
    >
        <option value="">
            Pilih Desa / Kelurahan
        </option>
    </select>

    <div
        id="kodeDesaError"
        class="invalid-feedback"
    ></div>

</div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnSaveWilayah"
                    >
                        <i class="fas fa-save me-2"></i>
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

window.WilayahKerjaPuskesmasConfig = {

    datatableUrl:
        @json(route(
            'adminpanel.wilayahkerja.puskesmas.datatable'
        )),

    storeUrl:
        @json(route(
            'adminpanel.wilayahkerja.puskesmas.store'
        )),

    showUrl:
        @json(route(
            'adminpanel.wilayahkerja.puskesmas.show',
            ['id' => '__ID__']
        )),

    updateUrl:
        @json(route(
            'adminpanel.wilayahkerja.puskesmas.update',
            ['id' => '__ID__']
        )),

    deleteUrl:
        @json(route(
            'adminpanel.wilayahkerja.puskesmas.destroy',
            ['id' => '__ID__']
        )),

    faskesUrl:
        @json(route(
            'adminpanel.wilayahkerja.puskesmas.faskes'
        )),

    desaByFaskesUrl:
        @json(route(
            'adminpanel.wilayahkerja.puskesmas.desaByFaskes'
        )),

    groupId:
        @json(auth()->user()->groupid ?? null),

    userKodeFaskes:
        @json(auth()->user()->kodeFaskes ?? null),

};

</script>

<script src="{{ mix('js/adminpanel/wilayahkerja/puskesmas.js') }}"></script>
@endpush
