@extends('newlplpo.layouts.master')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
         HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="bi bi-capsule me-2"></i>

                Master Data Obat

            </h4>

            <div class="text-muted">

                Kelola data obat LPLPO

            </div>

        </div>


        <button
            type="button"
            class="btn btn-primary"
            id="btnTambahObat">

            <i class="bi bi-plus-lg me-1"></i>

            Tambah Obat

        </button>

    </div>


    {{-- ==========================================================
         TABLE
    =========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="datatableObat"
                    class="table table-hover align-middle w-100">

                    <thead>

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Kode Obat
                            </th>

                            <th>
                                Nama Obat
                            </th>

                            <th>
                                Satuan
                            </th>

                            <th class="text-center">
                                Obat-obatan Napza
                            </th>

                            <th width="120"
                                class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>

@include('newlplpo.masterdataobat.partials.modal')

@endsection


@push('script')
<script>

    window.masterDataObatConfig = {

        dataUrl: @json(
            route('newlplpo.masterdataobat.datatable')
        ),

        storeUrl: @json(
            route('newlplpo.masterdataobat.store')
        ),

        baseUrl: @json(
            url('/newlplpo/masterdataobat')
        ),

        csrfToken: @json(
            csrf_token()
        )

    };

</script>
<script src="{{ mix('js/newlplpo/masterdataobat.js') }}"></script>

@endpush
