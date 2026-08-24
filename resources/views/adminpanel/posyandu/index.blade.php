@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-md-6">
                    <h4>Master Posyandu</h4>
                </div>

                <div class="col-md-6 text-end">

                    <a href="/adminpanel/posyandu/create"
                       class="btn btn-primary">

                        Tambah Posyandu

                    </a>

                </div>

            </div>

        </div>

        <div class="card-body">

            <table id="tablePosyandu"
                   class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>Kode</th>
                        <th>Nama Posyandu</th>
                        <th>Provinsi</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                        <th>Fasyankes</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>

                    </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>
window.PosyanduConfig = {

    dataUrl:
        @json(route('adminpanel.posyandu.data')),

    createUrl:
        @json(route('adminpanel.posyandu.create')),

    deleteUrl:
        @json(url('/adminpanel/posyandu/delete')),

    isGroup3:
        @json($isGroup3)

};
</script>

<script src="{{ mix('js/adminpanel/posyandu/index.js') }}"></script>


@endpush
