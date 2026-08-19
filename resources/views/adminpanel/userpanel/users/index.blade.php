@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-3">

                <div
                    class="bg-primary text-white rounded-3
                           d-flex align-items-center justify-content-center"
                    style="width:48px;height:48px;"
                >
                    <i class="fas fa-users"></i>
                </div>

                <div>

                    <h4 class="fw-bold mb-0">
                        User Management
                    </h4>

                    <small class="text-muted">
                        Kelola pengguna aplikasi
                    </small>

                </div>

            </div>

        </div>


       <button
    type="button"
    id="btnAddUser"
    class="btn btn-primary"
>
    <i class="fas fa-plus me-2"></i>
    Tambah User
</button>

    </div>


    {{-- STATISTICS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Total User
                    </small>

                    <h3
                        id="totalUser"
                        class="fw-bold mb-0"
                    >
                        0
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Administrator
                    </small>

                    <h3
                        id="totalAdmin"
                        class="fw-bold mb-0"
                    >
                        0
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Faskes
                    </small>

                    <h3
                        id="totalFaskes"
                        class="fw-bold mb-0"
                    >
                        0
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- FILTER --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <label class="form-label">
                        Cari User
                    </label>

                    <input
                        type="text"
                        id="searchUser"
                        class="form-control"
                        placeholder="Username / nama / email"
                    >

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Group
                    </label>

                    <select
                        id="filterGroup"
                        class="form-select"
                    >
                        <option value="">
                            Semua Group
                        </option>
                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Role
                    </label>

                    <select
                        id="filterRole"
                        class="form-select"
                    >
                        <option value="">
                            Semua Role
                        </option>
                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Provinsi
                    </label>

                    <select
                        id="filterProvinsi"
                        class="form-select"
                    >
                        <option value="">
                            Semua Provinsi
                        </option>
                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Kota / Kabupaten
                    </label>

                    <select
                        id="filterKota"
                        class="form-select"
                    >
                        <option value="">
                            Semua Kota
                        </option>
                    </select>

                </div>

            </div>

        </div>

    </div>


    {{-- TABLE --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="userTable"
                    class="table table-hover align-middle"
                    width="100%"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="50">
                                #
                            </th>

                            <th>
                                User
                            </th>

                            <th>
                                Group
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Faskes
                            </th>

                            <th>
                                Wilayah
                            </th>

                            <th
                                width="120"
                                class="text-center"
                            >
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ==========================================================
     MODAL USER
========================================================== --}}

<div
    class="modal fade"
    id="userModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <form id="userForm">

                <div class="modal-header">

                    <div>

                        <h5
                            class="modal-title fw-bold"
                            id="userModalTitle"
                        >
                            Tambah User
                        </h5>

                        <small class="text-muted">
                            Informasi akun dan penempatan user
                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <input
                        type="hidden"
                        id="userid"
                    >


                    <div class="row g-4">

                        {{-- ACCOUNT --}}

                        <div class="col-md-6">

                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-user me-2"></i>
                                Informasi User
                            </h6>


                            <div class="mb-3">

                                <label class="form-label">
                                    Username
                                </label>

                                <input
                                    type="text"
                                    id="username"
                                    class="form-control"
                                >

                                <div
                                    id="usernameError"
                                    class="invalid-feedback"
                                ></div>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Lengkap
                                </label>

                                <input
                                    type="text"
                                    id="namalengkap"
                                    class="form-control"
                                >

                                <div
                                    id="namalengkapError"
                                    class="invalid-feedback"
                                ></div>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    class="form-control"
                                >

                                <div
                                    id="emailError"
                                    class="invalid-feedback"
                                ></div>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    id="password"
                                    class="form-control"
                                >

                                <small
                                    id="passwordHelp"
                                    class="text-muted"
                                >
                                    Minimal 6 karakter
                                </small>

                                <div
                                    id="passwordError"
                                    class="invalid-feedback"
                                ></div>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Group
                                </label>

                                <select
                                    id="groupid"
                                    class="form-select"
                                ></select>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Role
                                </label>

                                <select
                                    id="role_id"
                                    class="form-select"
                                >
                                    <option value="">
                                        Pilih Role
                                    </option>
                                </select>

                            </div>

                        </div>


                        {{-- LOCATION --}}

                        <div class="col-md-6">

                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Penempatan
                            </h6>


                            <div class="mb-3">

                                <label class="form-label">
                                    Provinsi
                                </label>

                                <select
                                    id="kodePropinsi"
                                    class="form-select"
                                ></select>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Kota / Kabupaten
                                </label>

                                <select
                                    id="kodeKota"
                                    class="form-select"
                                ></select>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Kecamatan
                                </label>

                                <select
                                    id="kodeKecamatan"
                                    class="form-select"
                                ></select>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Faskes
                                </label>

                                <select
                                    id="kodeFaskes"
                                    class="form-select"
                                ></select>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        id="btnSaveUser"
                        class="btn btn-primary px-4"
                    >
                        <i class="fas fa-save me-2"></i>
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
window.UserPanelConfig = {

    baseUrl:
        @json(route('adminpanel.userpanel.users.index')),

    datatableUrl:
        @json(route('adminpanel.userpanel.users.datatable')),

    faskesUrl:
        @json(route('adminpanel.userpanel.users.faskes')),

    rolesByGroupUrl:
        @json(route('adminpanel.userpanel.roles.bygroup'))

};

</script>

<script src="{{ mix('js/adminpanel/userpanel/users.js') }}"></script>

@endsection
