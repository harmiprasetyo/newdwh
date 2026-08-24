@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">

            <h5>
                Wilayah Kerja Posyandu
            </h5>

            <button
                class="btn btn-primary"
                id="btnTambah">

                <i class="bi bi-plus-circle"></i>

                Tambah

            </button>

        </div>


        <div class="card-body">

            <table
                class="table table-bordered"
                id="datatable">

                <thead>

                <tr>

                    <th>No</th>

                    <th>Posyandu</th>

                    <th>Desa</th>

                    <th>Kecamatan</th>

                    <th>Kabupaten</th>

                    <th>Provinsi</th>

                    <th>RW</th>

                    <th width="120">Action</th>

                </tr>

                </thead>

            </table>

        </div>

    </div>

</div>


@include('adminpanel.wilayahkerja.form')

@endsection


@push('styles')

<style>

.btn-action {
    width: 36px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border-radius: 6px;
}

.btn-action i {
    font-size: 16px;
}

.action-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
}

</style>

@endpush

@push('scripts')

<script>
window.WilayahKerjaPosyanduConfig = {

    datatableUrl:
        @json(route('adminpanel.posyandu.wilayahkerja.datatable')),

    storeUrl:
        @json(route('adminpanel.posyandu.wilayahkerja.store')),

    editUrl:
        @json(route(
            'adminpanel.posyandu.wilayahkerja.edit',
            ['id' => '__ID__']
        )),

    updateUrl:
        @json(route(
            'adminpanel.posyandu.wilayahkerja.update',
            ['id' => '__ID__']
        )),

    deleteUrl:
        @json(route(
            'adminpanel.posyandu.wilayahkerja.destroy',
            ['id' => '__ID__']
        )),

    selectPosyanduUrl:
        @json(route(
            'adminpanel.posyandu.selectPosyandu'
        )),

    currentUser: {

        groupid:
            @json(auth()->user()->groupid ?? null),

        kodeFaskes:
            @json(auth()->user()->kodeFaskes ?? null)

    }

};
</script>

<script src="{{ mix('js/adminpanel/wilayahkerja/posyandu.js') }}"></script>

@endpush
