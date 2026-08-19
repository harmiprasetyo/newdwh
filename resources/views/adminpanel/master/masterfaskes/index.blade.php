@extends('layouts.admin')

@section('title', 'Master Faskes')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="fas fa-hospital"></i>
                Master Faskes
            </h5>

            <button
                type="button"
                class="btn btn-primary"
                id="btnTambah">
                <i class="fas fa-plus"></i>
                Tambah Faskes
            </button>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="tableFaskes"
                    class="table table-bordered table-striped table-hover w-100">

                    <thead>

                        <tr>
                            <th width="40">No</th>
                            <th>Kode Faskes</th>
                            <th>Nama Faskes</th>
                            <th>Tipe</th>
                            <th>Provinsi</th>
                            <th>Kota/Kabupaten</th>
                            <th>Kecamatan</th>
                            <th>Kepemilikan</th>
                            <th width="100">Aksi</th>
                        </tr>

                    </thead>

                    <tbody>
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="modalFaskes"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="modalFaskesTitle">

                    Tambah Faskes

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form id="formFaskes">

                @csrf

                <input
                    type="hidden"
                    id="faskes_id"
                    name="id">

                <div class="modal-body">

                    {{-- Kode Faskes --}}
                    <div class="mb-3">

                        <label
                            for="kodeFaskes"
                            class="form-label">

                            Kode Faskes
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="kodeFaskes"
                            name="kodeFaskes"
                            maxlength="50"
                            autocomplete="off">

                        <div
                            class="invalid-feedback"
                            id="error-kodeFaskes">
                        </div>

                    </div>


                    {{-- Nama Faskes --}}
                    <div class="mb-3">

                        <label
                            for="namaFaskes"
                            class="form-label">

                            Nama Faskes
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="namaFaskes"
                            name="namaFaskes"
                            maxlength="255"
                            autocomplete="off">

                        <div
                            class="invalid-feedback"
                            id="error-namaFaskes">
                        </div>

                    </div>


                    {{-- Tipe Faskes --}}
                    <div class="mb-3">

                        <label
                            for="typeFaskes"
                            class="form-label">

                            Tipe Faskes
                            <span class="text-danger">*</span>

                        </label>

                       <select
    class="form-select"
    id="typeFaskes"
    name="typeFaskes">

    <option value="">
        -- Pilih Tipe Faskes --
    </option>

    @foreach($types as $type)
        <option value="{{ $type->id }}">
            {{ $type->typeFaskes }}
        </option>
    @endforeach

</select>

                        <div
                            class="invalid-feedback"
                            id="error-typeFaskes">
                        </div>

                    </div>


                    {{-- Provinsi --}}
                    <div class="mb-3">

                        <label
                            for="kodePropinsi"
                            class="form-label">

                            Provinsi
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            class="form-select"
                            id="kodePropinsi"
                            name="kodePropinsi">

                            <option value="">
                                -- Pilih Provinsi --
                            </option>

                        </select>

                        <div
                            class="invalid-feedback"
                            id="error-kodePropinsi">
                        </div>

                    </div>


                    {{-- Kota --}}
                    <div class="mb-3">

                        <label
                            for="kodeKabupaten"
                            class="form-label">

                            Kota/Kabupaten
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            class="form-select"
                            id="kodeKabupaten"
                            name="kodeKabupaten"
                            disabled>

                            <option value="">
                                -- Pilih Kota/Kabupaten --
                            </option>

                        </select>

                        <div
                            class="invalid-feedback"
                            id="error-kodeKabupaten">
                        </div>

                    </div>


                    {{-- Kecamatan --}}
                    <div class="mb-3">

                        <label
                            for="kodeKecamatan"
                            class="form-label">

                            Kecamatan
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            class="form-select"
                            id="kodeKecamatan"
                            name="kodeKecamatan"
                            disabled>

                            <option value="">
                                -- Pilih Kecamatan --
                            </option>

                        </select>

                        <div
                            class="invalid-feedback"
                            id="error-kodeKecamatan">
                        </div>

                    </div>


                    {{-- Kepemilikan --}}
                   <div class="mb-3">
    <label for="kepemilikan" class="form-label">
        Kepemilikan <span class="text-danger">*</span>
    </label>

    <select
        class="form-select"
        id="kepemilikan"
        name="kepemilikan"
        required
    >
        <option value="">-- Pilih Kepemilikan --</option>

        <option value="Pemerintah">
            Pemerintah
        </option>

        <option value="Swasta">
            Swasta
        </option>
    </select>

    <div
        class="invalid-feedback"
        id="error-kepemilikan">
    </div>
</div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnSimpan">

                        <i class="fas fa-save"></i>
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
    window.routeFaskesDatatable = @json(
        route('adminpanel.master.faskes.datatable')
    );

    window.routeFaskesShow = @json(
        route('adminpanel.master.faskes.show', ':id')
    );

    window.routeFaskesStore = @json(
        route('adminpanel.master.faskes.store')
    );

    window.routeFaskesUpdate = @json(
        route('adminpanel.master.faskes.update', ':id')
    );

    window.routeFaskesDelete = @json(
        route('adminpanel.master.faskes.destroy', ':id')
    );

    window.routeFaskesProvinces = @json(
        route('adminpanel.master.faskes.provinces')
    );

    window.routeFaskesCities = @json(
        route('adminpanel.master.faskes.cities')
    );

    window.routeFaskesDistricts = @json(
        route('adminpanel.master.faskes.districts')
    );

    window.routeFaskesTypes = @json(
        route('adminpanel.master.faskes.types')
    );
</script>

<script src="{{ mix('js/adminpanel/master/masterfaskes.js') }}"></script>


@endpush
