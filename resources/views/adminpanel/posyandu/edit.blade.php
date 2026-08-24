@extends('layouts.admin')

@section('content')

<div class="container">

    <h4>Edit Master Posyandu</h4>

    <form id="frmPosyanduEdit">

        @csrf

        @method('PUT')


        {{-- =====================================================
             GROUP 3
        ====================================================== --}}

        @if($isGroup3)

            <div class="alert alert-info">

                <i class="fas fa-info-circle me-2"></i>

                Wilayah dan Fasyankes mengikuti
                Fasyankes user login.

            </div>


            {{-- PROVINSI --}}

            <div class="mb-3">

                <label class="form-label">
                    Provinsi
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $location['province']->name ?? '' }}"
                    disabled
                >

                <input
                    type="hidden"
                    name="province_code"
                    value="{{ $faskes->kodePropinsi ?? '' }}"
                >

            </div>


            {{-- KABUPATEN --}}

            <div class="mb-3">

                <label class="form-label">
                    Kabupaten/Kota
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $location['city']->name ?? '' }}"
                    disabled
                >

                <input
                    type="hidden"
                    name="city_code"
                    value="{{ $faskes->kodeKabupaten ?? '' }}"
                >

            </div>


            {{-- KECAMATAN --}}

            <div class="mb-3">

                <label class="form-label">
                    Kecamatan
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $location['district']->name ?? '' }}"
                    disabled
                >

                <input
                    type="hidden"
                    name="district_code"
                    value="{{ $faskes->kodeKecamatan ?? '' }}"
                >

            </div>


            {{-- FASKES --}}

            <div class="mb-3">

                <label class="form-label">
                    Fasyankes
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $faskes->namaFaskes ?? '' }}"
                    disabled
                >

                <input
                    type="hidden"
                    name="kodeFaskes"
                    value="{{ $faskes->kodeFaskes ?? '' }}"
                >

            </div>


            {{-- DESA --}}

            <div class="mb-3">

                <label class="form-label">
                    Desa
                </label>

                <select
                    id="desa"
                    name="village_code"
                    class="form-control"
                    required
                >

                    <option value="">
                        Memuat Desa...
                    </option>

                </select>

                <small class="text-muted">
                    Desa hanya berasal dari wilayah
                    kecamatan Fasyankes.
                </small>

            </div>


        @else

            {{-- =================================================
                 NON GROUP 3
            ================================================== --}}

            <div class="mb-3">

                <label>Provinsi</label>

                <select
                    id="provinsi"
                    name="province_code"
                    class="form-control"
                >
                </select>

            </div>


            <div class="mb-3">

                <label>Kabupaten/Kota</label>

                <select
                    id="kota"
                    name="city_code"
                    class="form-control"
                    disabled
                >
                </select>

            </div>


            <div class="mb-3">

                <label>Kecamatan</label>

                <select
                    id="kecamatan"
                    name="district_code"
                    class="form-control"
                    disabled
                >
                </select>

            </div>


            <div class="mb-3">

                <label>Desa</label>

                <select
                    id="desa"
                    name="village_code"
                    class="form-control"
                    disabled
                >
                </select>

            </div>


            <div class="mb-3">

                <label>Fasyankes</label>

                <select
                    id="faskes"
                    name="kodeFaskes"
                    class="form-control"
                    disabled
                >
                </select>

            </div>

        @endif


        {{-- =====================================================
             KODE POSYANDU
        ====================================================== --}}

        <div class="mb-3">

            <label>
                Kode Posyandu
            </label>

            <input
                type="text"
                name="kodePosyandu"
                class="form-control"
                value="{{ $posyandu->kodePosyandu }}"
                required
            >

        </div>


        {{-- =====================================================
             NAMA POSYANDU
        ====================================================== --}}

        <div class="mb-3">

            <label>
                Nama Posyandu
            </label>

            <input
                type="text"
                name="namaPosyandu"
                class="form-control"
                value="{{ $posyandu->namaPosyandu }}"
                required
            >

        </div>


        <button
            type="submit"
            class="btn btn-primary"
        >

            <i class="fas fa-save me-2"></i>

            Simpan Perubahan

        </button>


        <a
            href="{{ route('adminpanel.posyandu.index') }}"
            class="btn btn-secondary"
        >

            Batal

        </a>

    </form>

</div>

<script>
window.PosyanduConfig = {

    indexUrl:
        @json(route('adminpanel.posyandu.index')),

    updateUrl:
        @json(route(
            'adminpanel.posyandu.update',
            $posyandu->id
        )),

    villagesUrl:
        @json(route('adminpanel.posyandu.villages')),

    isGroup3:
        @json($isGroup3),

    faskes:
        @json($faskes),

    posyandu:
        @json($posyandu)

};
</script>

@endsection

@push('scripts')

<script src="{{ mix('js/adminpanel/posyandu/edit.js') }}"></script>

@endpush
