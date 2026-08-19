@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')

<div class="container-fluid">


    {{-- ==========================================================
         HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-history me-2"></i>
                Activity Log
            </h4>

            <div class="text-muted small">
                Riwayat aktivitas pengguna pada aplikasi
            </div>

        </div>

    </div>



    {{-- ==========================================================
         FILTER
    =========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex align-items-center">

                <i class="fas fa-filter me-2 text-primary"></i>

                <strong>
                    Filter Activity
                </strong>

            </div>

        </div>


        <div class="card-body">

            <form id="formFilter">

                <div class="row g-3">


                    {{-- TANGGAL MULAI --}}

                    <div class="col-md-3">

                        <label
                            for="start_date"
                            class="form-label"
                        >
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="form-control"
                        >

                    </div>


                    {{-- TANGGAL AKHIR --}}

                    <div class="col-md-3">

                        <label
                            for="end_date"
                            class="form-label"
                        >
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            class="form-control"
                        >

                    </div>


                    {{-- ACTION --}}

                    <div class="col-md-2">

                        <label
                            for="action"
                            class="form-label"
                        >
                            Action
                        </label>

                        <select
                            id="action"
                            name="action"
                            class="form-select"
                        >

                            <option value="">
                                Semua Action
                            </option>

                            <option value="create">
                                Create
                            </option>

                            <option value="update">
                                Update
                            </option>

                            <option value="delete">
                                Delete
                            </option>

                            <option value="login">
                                Login
                            </option>

                            <option value="logout">
                                Logout
                            </option>

                        </select>

                    </div>


                    {{-- MODULE --}}

                    <div class="col-md-2">

                        <label
                            for="module"
                            class="form-label"
                        >
                            Module
                        </label>

                        <input
                            type="text"
                            id="module"
                            name="module"
                            class="form-control"
                            placeholder="Nama module"
                        >

                    </div>


                    {{-- BUTTON --}}

                    <div class="col-md-2 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button
                                type="submit"
                                class="btn btn-primary flex-fill"
                                id="btnFilter"
                            >

                                <i class="fas fa-search me-1"></i>

                                Filter

                            </button>


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="btnReset"
                                title="Reset Filter"
                            >

                                <i class="fas fa-sync-alt"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- ==========================================================
         TABLE
    =========================================================== --}}

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="activityLogTable"
                    class="table table-bordered table-hover align-middle w-100"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="40">
                                #
                            </th>

                            <th>
                                Waktu
                            </th>

                            <th>
                                User
                            </th>

                            <th>
                                Action
                            </th>

                            <th>
                                Module
                            </th>

                            <th>
                                Deskripsi
                            </th>

                            <th>
                                Method
                            </th>

                            <th>
                                IP Address
                            </th>

                            <th width="70">
                                Detail
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
     DETAIL MODAL
=========================================================== --}}

<div
    class="modal fade"
    id="modalActivityDetail"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fas fa-info-circle me-2"></i>

                    Detail Activity Log

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">


                {{-- SUMMARY --}}

                <div class="row g-3 mb-4">


                    <div class="col-md-3">

                        <div class="small text-muted">
                            Action
                        </div>

                        <div id="detailAction">
                            -
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="small text-muted">
                            Module
                        </div>

                        <div id="detailModule">
                            -
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="small text-muted">
                            Subject
                        </div>

                        <div id="detailSubject">
                            -
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="small text-muted">
                            IP Address
                        </div>

                        <div id="detailIp">
                            -
                        </div>

                    </div>

                </div>


                {{-- DESCRIPTION --}}

                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Description
                    </label>

                    <div
                        id="detailDescription"
                        class="bg-light rounded p-3"
                    >
                        -
                    </div>

                </div>


                {{-- OLD / NEW --}}

                <div class="row g-3">


                    <div class="col-md-6">

                        <div class="card h-100">

                            <div class="card-header bg-danger-subtle">

                                <strong>
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Data Sebelum
                                </strong>

                            </div>

                            <div class="card-body p-0">

                                <pre
                                    id="detailOld"
                                    class="mb-0 p-3"
                                    style="
                                        max-height:400px;
                                        overflow:auto;
                                        font-size:12px;
                                    "
                                ></pre>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="card h-100">

                            <div class="card-header bg-success-subtle">

                                <strong>
                                    <i class="fas fa-arrow-right me-1"></i>
                                    Data Sesudah
                                </strong>

                            </div>

                            <div class="card-body p-0">

                                <pre
                                    id="detailNew"
                                    class="mb-0 p-3"
                                    style="
                                        max-height:400px;
                                        overflow:auto;
                                        font-size:12px;
                                    "
                                ></pre>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- REQUEST INFORMATION --}}

                <hr class="my-4">


                <h6 class="fw-bold mb-3">

                    <i class="fas fa-globe me-1"></i>

                    Request Information

                </h6>


                <div class="row g-3">


                    <div class="col-md-12">

                        <label class="small text-muted">
                            URL
                        </label>

                        <div
                            id="detailUrl"
                            class="text-break"
                        >
                            -
                        </div>

                    </div>


                    <div class="col-md-12">

                        <label class="small text-muted">
                            User Agent
                        </label>

                        <div
                            id="detailUserAgent"
                            class="text-break"
                        >
                            -
                        </div>

                    </div>

                </div>


            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >

                    Tutup

                </button>

            </div>


        </div>

    </div>

</div>


@endsection



@push('scripts')

<script>

    window.ActivityLogConfig = {

        datatableUrl:
            @json(route('activity-log.datatable'))

    };

</script>


<script src="{{ mix('js/adminpanel/activitylog.js') }}"></script>

@endpush