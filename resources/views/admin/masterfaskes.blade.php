@extends('layouts.admin')
@section('content')
<!-- CSS WAJIB -->



<div class="container-fluid mt-3">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">🏥 Master Faskes</h4>

        <button class="btn btn-primary" onclick="openModal()">
            + Tambah Faskes
        </button>
    </div>

    <!-- CARD TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table id="faskesTable" class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Type</th>
                        <th>
                            Provinsi
                        </th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Kepemilikan</th>
                        <th width="150">Aksi</th>
                    </tr>

                    <tr>
    <th></th>
    <th></th>
    <th><select id="fType" class="form-select form-select-sm"></select></th>

    <!-- FILTER -->
    <th><select id="fProvinsi" class="form-select form-select-sm"></select></th>
    <th><select id="fKota" class="form-select form-select-sm"></select></th>

    <th><select id="fKecamatan" class="form-select form-select-sm"></select></th>

    <!-- KEPEMILIKAN -->
    <th>
        <select id="fKepemilikan" class="form-select form-select-sm">
            <option value="">Semua</option>
            <option value="Pemerintah">Pemerintah</option>
            <option value="Swasta">Swasta</option>
        </select>
    </th>
     <th></th>
</tr>
                </thead>
            </table>

        </div>
    </div>

</div>


<!-- ===================== -->
<!-- MODAL FORM -->
<!-- ===================== -->
<div class="modal fade" id="masterModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="masterForm">

                <div class="modal-header">
                    <h5 class="modal-title">Form Master Faskes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="id">

                    <div class="row">

                        <!-- LEFT -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label>Kode Faskes</label>
                                <input type="text" id="kodeFaskes" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Nama Faskes</label>
                                <input type="text" id="namaFaskes" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Type Faskes</label>
                                <select id="typeFaskes" class="form-select" required></select>
                            </div>

                            <div class="mb-3">
                                <label>Kepemilikan</label>
                                <select id="kepemilikan" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Pemerintah">Pemerintah</option>
                                    <option value="Swasta">Swasta</option>
                                </select>
                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label>Provinsi</label>
                                <select id="kodePropinsi" class="form-select"></select>
                            </div>

                            <div class="mb-3">
                                <label>Kabupaten</label>
                                <select id="kodeKabupaten" class="form-select"></select>
                            </div>

                            <div class="mb-3">
                                <label>Kecamatan</label>
                                <select id="kodeKecamatan" class="form-select"></select>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">
                        💾 Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


<!-- ===================== -->
<!-- JS WAJIB -->
<!-- ===================== -->
<!-- jQuery -->


<!-- Bootstrap -->


<!-- DataTables (VERSI MATCH) -->

<script>
let table;
window.API_KEY = '{{ config("app.api_key") }}';

$(document).ready(function () {

$.ajaxSetup({
    headers: {
        'X-API-KEY': window.API_KEY,
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

  table = $('#faskesTable').DataTable({
    processing: true,
   ajax: {
    url: "/api/master/faskes",
    data: function (d) {
        d.provinsi = $('#fProvinsi').val();
        d.kota = $('#fKota').val();
        d.kecamatan = $('#fKecamatan').val();
        d.kepemilikan = $('#fKepemilikan').val();
        d.type = $('#fType').val();
    }
},
    columns: [
        { data: "kodeFaskes" },
        { data: "namaFaskes" },
        { data: "type.typeFaskes", defaultContent: "-" },
        { data: "provinsi.name", defaultContent: "-" },
        { data: "kota.name", defaultContent: "-" },
        { data: "kecamatan.name", defaultContent: "-" },
        { data: "kepemilikan" },
        {
            data: null,
            render: function (data) {
                return `
                    <button class="btn btn-warning btn-sm" onclick="editData(${data.id})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteData(${data.id})">Hapus</button>
                `;
            }
        }
    ]
});

 $('#typeFaskes, #kodePropinsi, #kodeKabupaten, #kodeKecamatan').select2({
        dropdownParent: $('#masterModal'),
        width: '100%'
    });

    $('#fType, #fProvinsi, #fKota, #fKecamatan, #fKepemilikan').select2({
    width: '100%',
    placeholder: 'Filter'
});





   loadFilterType();
    loadFilterProvinsi();
});

</script>

<script>

// Filter Provinsi

function loadFilterProvinsi() {

    $.get("/adminpanel/wilayah/listpropinsi", function (res) {

        let html = '<option value="">Semua</option>';

        res.data.forEach(item => {
            html += `<option value="${item.code}">${item.name}</option>`;
        });

        $('#fProvinsi').html(html);
    });
}

$('#fProvinsi').on('change', function () {

    let prov = $(this).val();

    $('#fKota').html('<option value="">Semua</option>');

    if (!prov) {
        table.ajax.reload();
        return;
    }

    $.get(`/adminpanel/wilayah/listkota?province_code=${prov}`, function (res) {

        let html = '<option value="">Semua</option>';

        res.data.forEach(item => {
            html += `<option value="${item.code}">${item.name}</option>`;
        });

        $('#fKota').html(html);
    });

    table.ajax.reload();
});

//filter kota



$('#fKota').on('change', function () {

    let city = $(this).val();

    $('#fKecamatan').html('<option value="">Semua</option>');

    if (!city) {
        table.ajax.reload();
        return;
    }

    $.get(`/adminpanel/wilayah/listkecamatan?city_code=${city}`, function (res) {

        let html = '<option value="">Semua</option>';

        res.data.forEach(item => {
            html += `<option value="${item.code}">${item.name}</option>`;
        });

        $('#fKecamatan').html(html);
    });

    table.ajax.reload();
});


//filter type faskes
function loadFilterType() {
    $.get("/api/master/typefaskes", function (res) {

        let html = '<option value="">Semua</option>';

        res.data.forEach(item => {
            html += `<option value="${item.id}">${item.typeFaskes}</option>`;
        });

        $('#fType').html(html);
    });
}


$('#fKecamatan, #fKepemilikan, #fType').on('change', function () {
    table.ajax.reload();
});


function editData(id) {

    mode = "update";

    showLoading();

    $.get(`/api/master/faskes/${id}`, function (res) {

        let data = res.data;

        $('#id').val(data.id);
        $('#kodeFaskes').val(data.kodeFaskes);
        $('#namaFaskes').val(data.namaFaskes);
        $('#kepemilikan').val(data.kepemilikan);

        loadTypeFaskes(data.typeFaskes);

        // simpan untuk cascading
        selectedKabupaten = data.kodeKabupaten;
        selectedKecamatan = data.kodeKecamatan;

        loadProvinsi(data.kodePropinsi);

        hideLoading();
        $('#masterModal').modal('show');
    });
}
function deleteData(id) {

    Swal.fire({
        title: 'Hapus data?',
        text: 'Data tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus'
    }).then((result) => {

        if (result.isConfirmed) {

            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

           $.ajax({
    url: `/api/master/faskes/${id}`,
    type: "DELETE",
    success: function () {
        table.ajax.reload(null, false);
    },
    error: function () {
        Swal.fire('Error', 'Tidak bisa menghapus data', 'error');
    }
});

        }

    });
}
let mode = "create";

// ==========================
// LOADING SPINNER
// ==========================
function showLoading() {
    Swal.fire({
        title: 'Loading...',
        text: 'Sedang memuat data',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
}

function hideLoading() {
    Swal.close();
}

// ==========================
// INIT SELECT2
// ==========================
function initSelect2() {
    $('#typeFaskes').select2({
        dropdownParent: $('#masterModal'),
        width: '100%',
        placeholder: "Pilih Type Faskes"
    });

    $('#kodePropinsi').select2({
        dropdownParent: $('#masterModal'),
        width: '100%',
        placeholder: "Pilih Provinsi"
    });

    $('#kodeKabupaten').select2({
        dropdownParent: $('#masterModal'),
        width: '100%',
        placeholder: "Pilih Kabupaten"
    });

    $('#kodeKecamatan').select2({
        dropdownParent: $('#masterModal'),
        width: '100%',
        placeholder: "Pilih Kecamatan"
    });
}

// ==========================
// LOAD TYPE FASKES
// ==========================
function loadTypeFaskes(selected = null) {

    $.get("/api/master/typefaskes", function (res) {

        let html = '<option value="">-- Pilih --</option>';

        res.data.forEach(item => {
            html += `<option value="${item.id}">${item.typeFaskes}</option>`;
        });

        $('#typeFaskes').html(html);

        if (selected) {
            $('#typeFaskes').val(selected).trigger('change');
        }
    });
}
// ==========================
// LOAD PROVINSI
// ==========================
function loadProvinsi(selected = null) {

    $.get("/adminpanel/wilayah/listpropinsi", function (res) {

        let html = '<option value="">-- Pilih --</option>';

        res.data.forEach(item => {
            html += `<option value="${item.code}">${item.name}</option>`;
        });

        $('#kodePropinsi').html(html);

        if (selected) {
            $('#kodePropinsi').val(selected).trigger('change');
        }
    });
}

// ==========================
// CASCADING PROV → KAB
// ==========================
let selectedKecamatan = null;
let selectedKabupaten = null;

$('#kodePropinsi').on('change', function () {

    let prov = $(this).val();
    if (!prov) return;

    $.get(`/adminpanel/wilayah/listkota?province_code=${prov}`, function (res) {

        let html = '<option value="">-- Pilih --</option>';

        res.data.forEach(item => {
            html += `<option value="${item.code}">${item.name}</option>`;
        });

        $('#kodeKabupaten').html(html);

        if (selectedKabupaten) {
            $('#kodeKabupaten').val(selectedKabupaten).trigger('change');
        }
    });
});

$('#kodeKabupaten').on('change', function () {

    let city = $(this).val();
    if (!city) return;

    $.get(`/adminpanel/wilayah/listkecamatan?city_code=${city}`, function (res) {

        let html = '<option value="">-- Pilih --</option>';

        res.data.forEach(item => {
            html += `<option value="${item.code}">${item.name}</option>`;
        });

        $('#kodeKecamatan').html(html);

        if (selectedKecamatan) {
            $('#kodeKecamatan').val(selectedKecamatan).trigger('change');
            selectedKecamatan = null;
        }
    });
});

// ==========================
// OPEN MODAL (CREATE)
// ==========================
function openModal() {

    mode = "create";

    $('#masterForm')[0].reset();
    $('#id').val('');

    initSelect2();
    loadTypeFaskes();
    loadProvinsi();

    $('#masterModal').modal('show');
}

// ==========================
// EDIT DATA
// ==========================


// ==========================
// SUBMIT FORM (CREATE/UPDATE)
// ==========================
$('#masterForm').submit(function (e) {
    e.preventDefault();

    let id = $('#id').val();
    let url = "/api/master/faskes";
    let method = "POST";

    if (mode === "update") {
        url = `/api/master/faskes/${id}`;
        method = "PUT";
    }

    showLoading();

    $.ajax({
        url: url,
        type: method,
        data: {
            kodeFaskes: $('#kodeFaskes').val(),
            namaFaskes: $('#namaFaskes').val(),
            typeFaskes: $('#typeFaskes').val(),
            kepemilikan: $('#kepemilikan').val(),
            kodePropinsi: $('#kodePropinsi').val(),
            kodeKabupaten: $('#kodeKabupaten').val(),
            kodeKecamatan: $('#kodeKecamatan').val()
        },
        success: function () {

            hideLoading();
            $('#masterModal').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data tersimpan',
                timer: 1500,
                showConfirmButton: false
            });

            // reload datatable kalau ada
            if (typeof table !== 'undefined') {
                table.ajax.reload();
            }
        },
        error: function () {

            hideLoading();

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan'
            });
        }
    });
});
</script>

@endsection
