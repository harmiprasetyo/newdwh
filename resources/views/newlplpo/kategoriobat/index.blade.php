
@extends('newlplpo.layouts.master')

@section('title', 'Master Kategori Obat')

@section('content')

<div class="container-fluid">

    {{-- ======================================================
         HEADER
    ======================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">
                Master Kategori Obat
            </h4>

            <small class="text-muted">
                Pengelolaan kategori obat LPLPO
            </small>
        </div>

        <button
            type="button"
            class="btn btn-primary"
            id="btnTambahKategori"
        >
            <i class="bi bi-plus-lg"></i>
            Tambah Kategori
        </button>

    </div>


    {{-- ======================================================
         CARD TABLE
    ======================================================= --}}

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="tableKategoriObat"
                    class="table table-bordered table-striped table-hover w-100"
                >

                    <thead>

                        <tr>
                            <th width="70" class="text-center">
                                No
                            </th>

                            <th>
                                Kategori Obat
                            </th>

                            <th width="160" class="text-center">
                                Aksi
                            </th>
                        </tr>

                    </thead>

                    <tbody>
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ==========================================================
     MODAL KATEGORI OBAT
========================================================== --}}

<div
    class="modal fade"
    id="modalKategoriObat"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            {{-- HEADER --}}

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="modalKategoriTitle"
                >
                    Tambah Kategori Obat
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- FORM --}}

            <form id="formKategoriObat">

                <div class="modal-body">

                    <input
                        type="hidden"
                        id="kategoriId"
                        name="id"
                    >

                    <div class="mb-3">

                        <label
                            for="kategori"
                            class="form-label"
                        >
                            Kategori Obat
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="kategori"
                            name="kategori"
                            maxlength="100"
                            autocomplete="off"
                            placeholder="Masukkan kategori obat"
                        >

                        <div
                            class="invalid-feedback"
                            id="kategoriError"
                        ></div>

                    </div>

                </div>


                {{-- FOOTER --}}

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
                        id="btnSimpanKategori"
                    >
                        <span
                            class="spinner-border spinner-border-sm d-none"
                            id="spinnerKategori"
                        ></span>

                        <span id="textSimpanKategori">
                            Simpan
                        </span>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('script')

<script>
    window.KategoriObatConfig = {
        datatableUrl: @json(route('newlplpo.kategoriobat.datatable')),
        storeUrl: @json(route('newlplpo.kategoriobat.store')),
        baseUrl: @json(url('newlplpo/kategori-obat')),
        csrfToken: @json(csrf_token())
    };
</script>

<script src="{{ mix('js/newlplpo/kategoriobat.js') }}"></script>

@endpush

