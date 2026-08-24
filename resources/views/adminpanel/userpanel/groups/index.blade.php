@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

    {{-- ==========================================================
         HEADER
    =========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <div
                    class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center"
                    style="width:46px;height:46px;"
                >
                    <i class="fas fa-users-cog"></i>
                </div>

                <div>

                    <h4 class="mb-0 fw-bold">
                        User Group
                    </h4>

                    <small class="text-muted">
                        Kelola kelompok pengguna aplikasi
                    </small>

                </div>

            </div>

        </div>


      <button
    type="button"
    class="btn btn-primary px-4"
    id="btnAddGroup"
>
    <i class="fas fa-plus me-2"></i>
    Tambah Group
</button>

    </div>


    {{-- ==========================================================
         STATISTIC
    =========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Group
                            </small>

                            <h3
                                id="totalGroup"
                                class="fw-bold mb-0"
                            >
                                0
                            </h3>

                        </div>

                        <div
                            class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="fas fa-users"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Role
                            </small>

                            <h3
                                id="totalRole"
                                class="fw-bold mb-0"
                            >
                                0
                            </h3>

                        </div>

                        <div
                            class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="fas fa-user-tag"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

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

                        <div
                            class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="fas fa-user"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         TABLE CARD
    =========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h5 class="fw-bold mb-1">
                        Daftar User Group
                    </h5>

                    <small class="text-muted">
                        Group menentukan kumpulan role yang tersedia
                    </small>

                </div>


                <div class="col-md-6">

                    <div class="d-flex justify-content-md-end mt-3 mt-md-0">

                        <div
                            class="input-group"
                            style="max-width:320px;"
                        >

                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>

                            <input
                                type="text"
                                id="searchGroup"
                                class="form-control"
                                placeholder="Cari group..."
                            >

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="card-body px-4">

            <div class="table-responsive">

                <table
                    id="groupTable"
                    class="table table-hover align-middle"
                    width="100%"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Nama Group
                            </th>

                            <th class="text-center">
                                Role
                            </th>

                            <th class="text-center">
                                User
                            </th>

                            <th width="150" class="text-center">
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
     MODAL
=========================================================== --}}

<div
    class="modal fade"
    id="groupModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <form id="groupForm">

                <div class="modal-header border-0">

                    <div>

                        <h5
                            class="modal-title fw-bold"
                            id="groupModalTitle"
                        >
                            Tambah User Group
                        </h5>

                        <small class="text-muted">
                            Masukkan nama group pengguna
                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body px-4">

                    <input
                        type="hidden"
                        id="group_id"
                    >


                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold"
                        >
                            Nama Group
                        </label>

                        <input
                            type="text"
                            id="group_name"
                            class="form-control form-control-lg"
                            placeholder="Contoh: Administrator"
                            autocomplete="off"
                        >

                        <div
                            class="invalid-feedback"
                            id="groupNameError"
                        ></div>

                    </div>

                </div>


                <div class="modal-footer border-0 px-4 pb-4">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                        id="btnSaveGroup"
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
        @json(route('adminpanel.userpanel.groups.index')),

    datatableUrl:
        @json(route('adminpanel.userpanel.groups.datatable'))

};
</script>

<script src="{{ mix('js/adminpanel/userpanel/groups.js') }}"></script>
@endsection
