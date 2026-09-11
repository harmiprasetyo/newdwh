@extends('newlplpo.layouts.master')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
         HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="bi bi-capsule-pill me-2"></i>

                Stok & Esensial

            </h4>

            <div class="text-muted">

                Pengaturan stok minimum, stok optimum dan status obat esensial.

            </div>

        </div>


       <div class="d-flex gap-2">

    <button
        type="button"
        class="btn btn-outline-primary"
        id="btnDuplikasi">

        <i class="bi bi-copy me-1"></i>
        Duplikasi Tahun

    </button>

    <button
        type="button"
        class="btn btn-primary"
        id="btnTambah">

        <i class="bi bi-plus-lg me-1"></i>
        Tambah Data

    </button>

</div>

    </div>


    {{-- ==========================================================
         FILTER
    =========================================================== --}}

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-body">

            <div class="row g-3 align-items-end">

                @if(auth()->user()->groupid == 1 ||
                    auth()->user()->groupid == 2)

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            Faskes

                        </label>

                        <select
                            id="filterFaskes"
                            class="form-select">

                            <option value="">
                                Semua Faskes
                            </option>

                            @foreach($faskes as $item)

                                <option
                                    value="{{ $item->kodeFaskes }}">

                                    {{ $item->kodeFaskes }}
                                    —
                                    {{ $item->namaFaskes }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                @endif


                <div class="col-md-3">

                    <label class="form-label fw-semibold">

                        Tahun

                    </label>

                    <select
                        id="filterTahun"
                        class="form-select">

                        <option value="">
                            Semua Tahun
                        </option>

                        @foreach($tahunList as $tahun)

                            <option
                                value="{{ $tahun }}"
                                {{ $tahun == now()->year ? 'selected' : '' }}>

                                {{ $tahun }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-auto">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="btnResetFilter">

                        <i class="bi bi-arrow-clockwise me-1"></i>

                        Reset

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         TABLE
    =========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="datatable"
                    class="table table-hover align-middle w-100">

                   
<thead>
    <tr>

        <th width="50">
            No
        </th>

        <th>
            Obat
        </th>

        <th>
            Faskes
        </th>

        <th class="text-center">
            Min
        </th>

        <th class="text-center">
            Optimum
        </th>

        <th class="text-center">
            Esensial
        </th>

        {{-- KATEGORI --}}
        <th>
            Kategori
        </th>

        <th>
            Formularium Puskesmas
        </th>

        <th class="text-center">
            Tahun
        </th>

        <th width="100" class="text-center">
            Aksi
        </th>

    </tr>
</thead>



                </table>

            </div>

        </div>

    </div>

</div>


{{-- ==========================================================
     MODAL
=========================================================== --}}

<div
    class="modal fade"
    id="modalData"
    tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="modalTitle">

                    <i class="bi bi-plus-circle me-2"></i>

                    Tambah Stok & Esensial

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form id="formData">

                @csrf

                <input
                    type="hidden"
                    id="data_id">


                <div class="modal-body">

                    <div class="row g-3">

                        {{-- OBAT --}}

                        <div class="col-md-8">

                            <label class="form-label fw-semibold">

                                Obat

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                id="kode_obat"
                                name="kode_obat"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Obat --
                                </option>

                            </select>

                            <div
                                class="invalid-feedback"
                                id="kode_obat_error">
                            </div>

                        </div>


                        {{-- FASKES --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Faskes

                                <span class="text-danger">*</span>

                            </label>

                            @if(
                                auth()->user()->groupid == 1 ||
                                auth()->user()->groupid == 2
                            )

                                <select
                                    id="kodeFaskes"
                                    name="kodeFaskes"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        -- Pilih Faskes --
                                    </option>

                                    @foreach($faskes as $item)

                                        <option
                                            value="{{ $item->kodeFaskes }}">

                                            {{ $item->kodeFaskes }}

                                        </option>

                                    @endforeach

                                </select>

                            @else

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    value="{{ auth()->user()->kodeFaskes }}"
                                    readonly>

                                <input
                                    type="hidden"
                                    id="kodeFaskes"
                                    name="kodeFaskes"
                                    value="{{ auth()->user()->kodeFaskes }}">

                            @endif

                            <div
                                class="invalid-feedback"
                                id="kodeFaskes_error">
                            </div>

                        </div>


                        {{-- MINIMAL --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Stok Minimal

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="number"
                                min="0"
                                value="0"
                                id="stok_minimal"
                                name="stok_minimal"
                                class="form-control"
                                required>

                        </div>


                        {{-- OPTIMUM --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Stok Optimum

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="number"
                                min="0"
                                value="0"
                                id="stok_optimum"
                                name="stok_optimum"
                                class="form-control"
                                required>

                        </div>


                        {{-- ESENSIAL --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Status Obat

                            </label>

                            <select
                                id="obat_esensial"
                                name="obat_esensial"
                                class="form-select">

                                <option value="noe">
                                    Non Esensial
                                </option>

                                <option value="oe">
                                    Obat Esensial
                                </option>

                            </select>

                        </div>

                  
<div class="col-md-6" id="kategoriWrapper" style="display:none;">

    <label for="kategori" class="form-label">
        Kategori Obat
        <span class="text-danger">*</span>
    </label>

    <select
        id="kategori"
        name="kategori"
        class="form-select"
        style="width:100%;"
    >
        <option value="">
            Pilih Kategori
        </option>
    </select>

    <div class="invalid-feedback">
        Kategori obat wajib dipilih.
    </div>

</div>




                        {{-- TAHUN --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Tahun

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                id="tahun"
                                name="tahun"
                                class="form-select"
                                required>

                                @foreach($tahunList as $tahun)

                                    <option
                                        value="{{ $tahun }}"
                                        {{ $tahun == now()->year ? 'selected' : '' }}>

                                        {{ $tahun }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Obat Formularium

                            </label>

                           <select name="obat_formularium_puskesmas" id="obat_formularium_puskesmas" class="form-select" required>
                            <option value="false">Tidak</option>
                            <option value="true">Ya</option>
                        </select>

                        </div>


                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnSimpan">

                        <span
                            id="spinner"
                            class="spinner-border spinner-border-sm d-none">
                        </span>

                        <i
                            id="saveIcon"
                            class="bi bi-check-lg me-1">
                        </i>

                        <span id="saveText">
                            Simpan
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div
    class="modal fade"
    id="modalDuplikasi"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-copy me-2"></i>

                    Duplikasi Stok & Esensial

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="alert alert-info">

                    <i class="bi bi-info-circle me-1"></i>

                    Seluruh konfigurasi obat pada tahun sumber
                    akan disalin ke tahun tujuan.

                </div>

                <div class="row g-3">

                    {{-- TAHUN SUMBER --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Dari Tahun

                        </label>

                        <select
                            id="duplikat_dari_tahun"
                            class="form-select">

                            @foreach($tahunList as $tahun)

                                <option
                                    value="{{ $tahun }}"
                                    {{ $tahun == now()->year ? 'selected' : '' }}>

                                    {{ $tahun }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TAHUN TUJUAN --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Ke Tahun

                        </label>

                        <select
                            id="duplikat_ke_tahun"
                            class="form-select">

                            @foreach($tahunList as $tahun)

                                <option
                                    value="{{ $tahun }}"
                                    {{ $tahun == now()->year + 1 ? 'selected' : '' }}>

                                    {{ $tahun }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div
                    id="duplikasiWarning"
                    class="alert alert-warning mt-3 d-none">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Tahun sumber dan tahun tujuan tidak boleh sama.

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Batal

                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnConfirmDuplikasi">

                    <span
                        id="duplicateSpinner"
                        class="spinner-border spinner-border-sm d-none">
                    </span>

                    <i
                        id="duplicateIcon"
                        class="bi bi-copy me-1">
                    </i>

                    <span id="duplicateText">

                        Duplikasi

                    </span>

                </button>

            </div>

        </div>

    </div>

</div>


@endsection


@push('script')
<script>
    window.StokEsensialConfig = {
        dataUrl: @json(route('newlplpo.stok-esensial.datatable')),
        storeUrl: @json(route('newlplpo.stok-esensial.store')),
        kategoriUrl: @json(route('newlplpo.stok-esensial.kategori')),
        obatUrl: @json(route('newlplpo.masterdataobat.datatable')),

        currentUser: {
            groupid: @json(auth()->user()->groupid ?? 0),
            kodeFaskes: @json(auth()->user()->kodeFaskes ?? null),
            kodePropinsi: @json(auth()->user()->kodePropinsi ?? null),
            kodeKota: @json(auth()->user()->kodeKota ?? null),
            kodeKecamatan: @json(auth()->user()->kodeKecamatan ?? null)
        }
    };
</script>

<script src="{{ asset('js/newlplpo/stokesensial.js') }}"></script>


@endpush
