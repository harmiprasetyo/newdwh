{{-- ========================================================= --}}
{{-- OFFCANVAS MASTER OBAT --}}
{{-- resources/views/newlplpo/partials/offcanvas_masterobat.blade.php --}}
{{-- PART 1 --}}
{{-- ========================================================= --}}

<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="offcanvasObat"
     style="width:95vw;max-width:1600px;">

    <div class="offcanvas-header bg-success text-white">

        <h4 class="mb-0">
            <i class="bi bi-capsule"></i>
            Tambah Item Obat
        </h4>

        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="offcanvas">
        </button>

    </div>

   <div class="offcanvas-body p-0 overflow-hidden">

        <div class="container-fluid h-100">

            <div class="row h-100">

                {{-- ========================================= --}}
                {{-- PANEL KIRI --}}
                {{-- ========================================= --}}

                <div class="col-lg-5 border-end bg-light">

                    <div class="p-3">

                        <h5 class="mb-3">

                            Master Obat

                        </h5>

                        <div class="input-group mb-3">

                            <span class="input-group-text">

                                <i class="bi bi-search"></i>

                            </span>

                            <input
                                type="text"
                                id="keywordObat"
                                class="form-control"
                                placeholder="Cari kode, nama atau barcode...">

                        </div>

                        <div class="table-responsive">

                            <table
                                id="tblMasterObat"
                                class="table table-bordered table-hover table-striped table-sm w-100">

                                <thead class="table-success">

                                <tr>

                                    <th width="120">
                                        Kode
                                    </th>

                                    <th>
                                        Nama Obat
                                    </th>

                                    <th width="80">
                                        Sat
                                    </th>

                                    <th width="80"
                                        class="text-center">
                                        Pilih
                                    </th>

                                </tr>

                                </thead>

                                <tbody>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                {{-- ========================================= --}}
                {{-- PANEL KANAN --}}
                {{-- ========================================= --}}

                <div class="col-lg-7">

                    <form id="frmItem">

                        @csrf

                        <input
                            type="hidden",
                            id="report_id"
                            name="report_id"
                            value="{{ $report->id ?? '' }}">

                        <div class="p-3">

                            <h5 class="mb-3">

                                Data Pemakaian Obat

                            </h5>

                            <div class="card shadow-sm">

                                <div class="card-header bg-primary text-white">

                                    Informasi Obat

                                </div>

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-3">

                                            <label class="form-label">

                                                Kode Obat

                                            </label>

                                            <input
                                                type="text"
                                                id="kode_obat"
                                                name="kode_obat"
                                                class="form-control"
                                                readonly>

                                        </div>

                                        <div class="col-md-6">

                                            <label class="form-label">

                                                Nama Obat

                                            </label>

                                            <input
                                                type="text"
                                                id="nama_obat"
                                                name="nama_obat"
                                                class="form-control"
                                                readonly>

                                        </div>

                                        <div class="col-md-3">

                                            <label class="form-label">

                                                Satuan

                                            </label>

                                            <input
                                                type="text"
                                                id="satuan"
                                                name="satuan"
                                                class="form-control"
                                                readonly>

                                        </div>

                                    </div>

                                    <div class="row mt-3">

                                        <div class="col-md-6">

                                            <label class="form-label">

                                                Program

                                            </label>

                                            <select
                                                name="program_id"
                                                class="form-select" required>
                                                <option value="">-- Pilih Program --</option>

                                                @foreach($programs as $program)

                                                    <option
                                                        value="{{ $program->id }}">

                                                        {{ $program->program_name }}

                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="col-md-6">

                                            <label class="form-label">

                                                Obat Expired

                                            </label>

                                            <input
                                                type="number"
                                                name="item_expired"
                                                class="form-control"
                                                value="0"
                                                >

                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- ========================================= --}}
                            {{-- PART 2 DIMULAI DARI SINI --}}
                            {{-- Card Stok Awal, Penerimaan, Persediaan,
                                 Pemakaian, Stok Akhir --}}
                            {{-- Jangan tutup form/div dulu --}}
                                                        {{-- ========================================= --}}
                            {{-- STOK & PEMAKAIAN --}}
                            {{-- ========================================= --}}

                            <div class="card shadow-sm mt-3">

                                <div class="card-header bg-success text-white">

                                    Data Stok dan Pemakaian

                                </div>

                                <div class="card-body p-0">

                                    <div class="table-responsive">

                                        <table class="table table-bordered table-sm mb-0">

                                            <thead class="table-light">

                                            <tr class="text-center">

                                                <th width="260">
                                                    Keterangan
                                                </th>

                                                <th width="170">
                                                    Program PKD
                                                </th>

                                                <th width="170">
                                                    JKN
                                                </th>

                                            </tr>

                                            </thead>

                                            <tbody>

                                            {{-- ========================= --}}
                                            {{-- STOK AWAL --}}
                                            {{-- ========================= --}}

                                            <tr>

                                                <th class="align-middle">

                                                    Stok Awal

                                                </th>

                                                <td>

                                                    <input
                                                        type="number"
                                                        min="0"
                                                          value="0"
                                                        name="stok_awal_progam_pkd"
                                                        class="form-control hitung">

                                                </td>

                                                <td>

                                                    <input
                                                        type="number"
                                                        min="0"
                                                          value="0"
                                                        name="stok_awal_jkn"
                                                        class="form-control hitung">

                                                </td>

                                            </tr>

                                            {{-- ========================= --}}
                                            {{-- PENERIMAAN --}}
                                            {{-- ========================= --}}

                                            <tr>

                                                <th class="align-middle">

                                                    Penerimaan

                                                </th>

                                                <td>

                                                    <input
                                                        type="number"
                                                        min="0"
                                                          value="0"
                                                        name="penerimaan_program_pkd"
                                                        class="form-control hitung">

                                                </td>

                                                <td>

                                                    <input
                                                        type="number"
                                                        min="0"
                                                          value="0"
                                                        name="penerimaan_jkn"
                                                        class="form-control hitung">

                                                </td>

                                            </tr>

                                            {{-- ========================= --}}
                                            {{-- PERSEDIAAN --}}
                                            {{-- ========================= --}}

                                            <tr class="table-warning">

                                                <th class="align-middle">

                                                    Persediaan

                                                </th>

                                                <td>

                                                    <input
                                                        type="number"
                                                         value="0"

                                                        name="persediaan_program_pkd"
                                                        class="form-control bg-light">

                                                </td>

                                                <td>

                                                    <input
                                                        type="number"
                                                         value="0"

                                                        name="persediaan_jkn"
                                                        class="form-control bg-light">

                                                </td>

                                            </tr>

                                            {{-- ========================= --}}
                                            {{-- PEMAKAIAN --}}
                                            {{-- ========================= --}}

                                            <tr>

                                                <th class="align-middle">

                                                    Pemakaian

                                                </th>

                                                <td>

                                                    <input
                                                        type="number"
                                                        min="0"
                                                          value="0"
                                                        name="pemakaian_program_pkd"
                                                        class="form-control hitung">

                                                </td>

                                                <td>

                                                    <input
                                                        type="number"
                                                        min="0"
                                                          value="0"
                                                        name="pemakaian_jkn"
                                                        class="form-control hitung">

                                                </td>

                                            </tr>

                                            {{-- ========================= --}}
                                            {{-- STOK AKHIR --}}
                                            {{-- ========================= --}}

                                            <tr class="table-info">

                                                <th class="align-middle">

                                                    Stok Akhir

                                                </th>

                                                <td>

                                                    <input
                                                        type="number"
                                                         value="0"
                                                        name="stok_akhir_program_pkd"
                                                        class="form-control bg-light">

                                                </td>

                                                <td>

                                                    <input
                                                        type="number"
                                                         value="0"
                                                        name="stok_akhir_jkn"
                                                        class="form-control bg-light">

                                                </td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>

                            {{-- ========================================= --}}
                            {{-- PART 3 DIMULAI DARI SINI --}}
                            {{-- Parameter, Permintaan, Pemberian --}}
                            {{-- Footer Simpan --}}
                            {{-- Jangan tutup form/div dulu --}}
                                                        {{-- ========================================= --}}
                            {{-- PARAMETER STOK --}}
                            {{-- ========================================= --}}

                            <div class="card shadow-sm mt-3">

                                <div class="card-header bg-warning">

                                    Parameter Persediaan

                                </div>

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-3">

                                            <label class="form-label">

                                                Stok Minimum

                                            </label>

                                            <input
                                                type="number"
                                                id="stok_minimum"
                                                name="stok_minimum"
                                                readonly
                                                class="form-control bg-light">

                                        </div>

                                        <div class="col-md-3">

                                            <label class="form-label">

                                                Stok Optimum

                                            </label>

                                            <input
                                                type="number"
                                                id="stok_optimum"
                                                name="stok_optimum"
                                                  value="0"
                                                class="form-control bg-light">

                                        </div>

                                        <div class="col-md-3">

                                            <label class="form-label">

                                                Permintaan

                                            </label>

                                            <input
                                                type="number"
                                                name="permintaan"
                                                 value="0"
                                                class="form-control bg-warning fw-bold">

                                        </div>

                                       <!-- <div class="col-md-3">

                                            <label class="form-label">

                                                Pemberian

                                            </label>

                                            <input
                                                type="number"
                                                name="pemberian"
                                                readonly
                                                min="0"
                                                value="0"
                                                class="form-control">

                                        </div>-->
                                        <input type="hidden"
       name="pemberian_program_pkd"
       value="0">

<input type="hidden"
       name="pemberian_jkn"
       value="0">

                                    </div>

                                </div>

                            </div>

                            {{-- ========================================= --}}
                            {{-- RINGKASAN --}}
                            {{-- ========================================= --}}

                            <div class="card shadow-sm mt-3">

                                <div class="card-header bg-secondary text-white">

                                    Ringkasan

                                </div>

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-6">

                                            <table class="table table-borderless table-sm">

                                                <tr>

                                                    <th width="180">

                                                        Kode Obat

                                                    </th>

                                                    <td id="previewKode">

                                                        -

                                                    </td>

                                                </tr>

                                                <tr>

                                                    <th>

                                                        Nama Obat

                                                    </th>

                                                    <td id="previewNama">

                                                        -

                                                    </td>

                                                </tr>

                                                <tr>

                                                    <th>

                                                        Program

                                                    </th>

                                                    <td id="previewProgram">

                                                        -

                                                    </td>

                                                </tr>

                                            </table>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="alert alert-info mb-0">

                                                <strong>Catatan :</strong>

                                                <ul class="mb-0 mt-2">

                                                    <li>
                                                        Persediaan dihitung otomatis.
                                                    </li>

                                                    <li>
                                                        Stok Akhir dihitung otomatis.
                                                    </li>

                                                    <li>
                                                        Permintaan dihitung otomatis berdasarkan Stok Optimum.
                                                    </li>

                                                </ul>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- ========================================= --}}
                            {{-- FOOTER --}}
                            {{-- ========================================= --}}

                            <div class="text-end mt-4">

                                <button
                                    type="reset"
                                    class="btn btn-secondary">

                                    <i class="bi bi-arrow-clockwise"></i>

                                    Reset

                                </button>

                                <button
                                    type="button"
                                    id="btnSaveItem"
                                    class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Simpan Item

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

