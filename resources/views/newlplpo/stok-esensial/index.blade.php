@extends('newlplpo.layouts.master')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
         HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="bi bi-capsule-pill me-2"></i>

                Stok & Esensial

            </h4>

            <div class="text-muted">

                Pengaturan stok minimum, stok optimum dan status obat esensial.

            </div>

        </div>


       <div class="d-flex gap-2">

    <button
        type="button"
        class="btn btn-outline-primary"
        id="btnDuplikasi">

        <i class="bi bi-copy me-1"></i>
        Duplikasi Tahun

    </button>

    <button
        type="button"
        class="btn btn-primary"
        id="btnTambah">

        <i class="bi bi-plus-lg me-1"></i>
        Tambah Data

    </button>

</div>

    </div>


    {{-- ==========================================================
         FILTER
    =========================================================== --}}

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-body">

            <div class="row g-3 align-items-end">

                @if(auth()->user()->groupid == 1 ||
                    auth()->user()->groupid == 2)

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            Faskes

                        </label>

                        <select
                            id="filterFaskes"
                            class="form-select">

                            <option value="">
                                Semua Faskes
                            </option>

                            @foreach($faskes as $item)

                                <option
                                    value="{{ $item->kodeFaskes }}">

                                    {{ $item->kodeFaskes }}
                                    —
                                    {{ $item->namaFaskes }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                @endif


                <div class="col-md-3">

                    <label class="form-label fw-semibold">

                        Tahun

                    </label>

                    <select
                        id="filterTahun"
                        class="form-select">

                        <option value="">
                            Semua Tahun
                        </option>

                        @foreach($tahunList as $tahun)

                            <option
                                value="{{ $tahun }}"
                                {{ $tahun == now()->year ? 'selected' : '' }}>

                                {{ $tahun }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-auto">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="btnResetFilter">

                        <i class="bi bi-arrow-clockwise me-1"></i>

                        Reset

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         TABLE
    =========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="datatable"
                    class="table table-hover align-middle w-100">

                    <thead>

                    <tr>

                        <th width="50">
                            No
                        </th>

                        <th>
                            Obat
                        </th>

                        <th>
                            Faskes
                        </th>

                        <th class="text-center">
                            Min
                        </th>

                        <th class="text-center">
                            Optimum
                        </th>

                        <th class="text-center">
                            Esensial
                        </th>
                          <th>Formularium Puskesmas</th>

                        <th class="text-center">
                            Tahun
                        </th>

                        <th width="100"
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


{{-- ==========================================================
     MODAL
=========================================================== --}}

<div
    class="modal fade"
    id="modalData"
    tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="modalTitle">

                    <i class="bi bi-plus-circle me-2"></i>

                    Tambah Stok & Esensial

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form id="formData">

                @csrf

                <input
                    type="hidden"
                    id="data_id">


                <div class="modal-body">

                    <div class="row g-3">

                        {{-- OBAT --}}

                        <div class="col-md-8">

                            <label class="form-label fw-semibold">

                                Obat

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                id="kode_obat"
                                name="kode_obat"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Obat --
                                </option>

                            </select>

                            <div
                                class="invalid-feedback"
                                id="kode_obat_error">
                            </div>

                        </div>


                        {{-- FASKES --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Faskes

                                <span class="text-danger">*</span>

                            </label>

                            @if(
                                auth()->user()->groupid == 1 ||
                                auth()->user()->groupid == 2
                            )

                                <select
                                    id="kodeFaskes"
                                    name="kodeFaskes"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        -- Pilih Faskes --
                                    </option>

                                    @foreach($faskes as $item)

                                        <option
                                            value="{{ $item->kodeFaskes }}">

                                            {{ $item->kodeFaskes }}

                                        </option>

                                    @endforeach

                                </select>

                            @else

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    value="{{ auth()->user()->kodeFaskes }}"
                                    readonly>

                                <input
                                    type="hidden"
                                    id="kodeFaskes"
                                    name="kodeFaskes"
                                    value="{{ auth()->user()->kodeFaskes }}">

                            @endif

                            <div
                                class="invalid-feedback"
                                id="kodeFaskes_error">
                            </div>

                        </div>


                        {{-- MINIMAL --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Stok Minimal

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="number"
                                min="0"
                                value="0"
                                id="stok_minimal"
                                name="stok_minimal"
                                class="form-control"
                                required>

                        </div>


                        {{-- OPTIMUM --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Stok Optimum

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="number"
                                min="0"
                                value="0"
                                id="stok_optimum"
                                name="stok_optimum"
                                class="form-control"
                                required>

                        </div>


                        {{-- ESENSIAL --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Status Obat

                            </label>

                            <select
                                id="obat_esensial"
                                name="obat_esensial"
                                class="form-select">

                                <option value="noe">
                                    Non Esensial
                                </option>

                                <option value="oe">
                                    Obat Esensial
                                </option>

                            </select>

                        </div>


                        {{-- TAHUN --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Tahun

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                id="tahun"
                                name="tahun"
                                class="form-select"
                                required>

                                @foreach($tahunList as $tahun)

                                    <option
                                        value="{{ $tahun }}"
                                        {{ $tahun == now()->year ? 'selected' : '' }}>

                                        {{ $tahun }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Obat Formularium

                            </label>

                           <select name="obat_formularium_puskesmas" id="obat_formularium_puskesmas" class="form-select" required>
                            <option value="false">Tidak</option>
                            <option value="true">Ya</option>
                        </select>

                        </div>


                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnSimpan">

                        <span
                            id="spinner"
                            class="spinner-border spinner-border-sm d-none">
                        </span>

                        <i
                            id="saveIcon"
                            class="bi bi-check-lg me-1">
                        </i>

                        <span id="saveText">
                            Simpan
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div
    class="modal fade"
    id="modalDuplikasi"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-copy me-2"></i>

                    Duplikasi Stok & Esensial

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="alert alert-info">

                    <i class="bi bi-info-circle me-1"></i>

                    Seluruh konfigurasi obat pada tahun sumber
                    akan disalin ke tahun tujuan.

                </div>

                <div class="row g-3">

                    {{-- TAHUN SUMBER --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Dari Tahun

                        </label>

                        <select
                            id="duplikat_dari_tahun"
                            class="form-select">

                            @foreach($tahunList as $tahun)

                                <option
                                    value="{{ $tahun }}"
                                    {{ $tahun == now()->year ? 'selected' : '' }}>

                                    {{ $tahun }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TAHUN TUJUAN --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Ke Tahun

                        </label>

                        <select
                            id="duplikat_ke_tahun"
                            class="form-select">

                            @foreach($tahunList as $tahun)

                                <option
                                    value="{{ $tahun }}"
                                    {{ $tahun == now()->year + 1 ? 'selected' : '' }}>

                                    {{ $tahun }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div
                    id="duplikasiWarning"
                    class="alert alert-warning mt-3 d-none">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Tahun sumber dan tahun tujuan tidak boleh sama.

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Batal

                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnConfirmDuplikasi">

                    <span
                        id="duplicateSpinner"
                        class="spinner-border spinner-border-sm d-none">
                    </span>

                    <i
                        id="duplicateIcon"
                        class="bi bi-copy me-1">
                    </i>

                    <span id="duplicateText">

                        Duplikasi

                    </span>

                </button>

            </div>

        </div>

    </div>

</div>


@endsection


@push('script')

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    */

    const dataUrl =
        "{{ route('newlplpo.stok-esensial.datatable') }}";

    const storeUrl =
        "{{ route('newlplpo.stok-esensial.store') }}";


    let editMode = false;


    /*
    |--------------------------------------------------------------------------
    | SELECT2 OBAT
    |--------------------------------------------------------------------------
    */

    $('#kode_obat').select2({

    dropdownParent: $('#modalData'),

    width: '100%',

    placeholder: '-- Pilih Obat --',

    allowClear: true,

    ajax: {

        url: "{{ url('/newlplpo/masterdataobat/datatable') }}",

        dataType: 'json',

        delay: 300,

        data: function (params) {

            return {

                search: params.term || '',

                start: 0,

                length: 50,

                kodeFaskes: $('#kodeFaskes').val(),

                tahun: $('#tahun').val(),

                exclude_stok_setting: 1,

                edit_id: $('#data_id').val() || ''

            };

        },

        processResults: function (response) {

            let data = response.data || [];

            return {

                results: data.map(function (item) {

                    return {

                        id: item.kode_obat,

                        text:
                            item.kode_obat +
                            ' — ' +
                            item.nama_obat

                    };

                })

            };

        }

    },

    minimumInputLength: 0

});

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    const table =
        $('#datatable').DataTable({

            processing:
                true,

            serverSide:
                true,

            responsive:
                true,

            pageLength:
                10,

            ajax: {

                url:
                    dataUrl,

                type:
                    'GET',

                data: function (d) {

                    @if(
                        auth()->user()->groupid == 1 ||
                        auth()->user()->groupid == 2
                    )

                    d.kodeFaskes =
                        $('#filterFaskes').val();

                    @endif

                    d.tahun =
                        $('#filterTahun').val();

                }

            },

            columns: [

                {
                    data:
                        'DT_RowIndex',

                    orderable:
                        false,

                    searchable:
                        false,

                    className:
                        'text-center'

                },

                {
                    data:
                        'obat',

                    name:
                        'o.nama_obat'

                },

                {
                    data:
                        'faskes',

                    name:
                        'f.namaFaskes'

                },

                {
                    data:
                        'stok_minimal',

                    className:
                        'text-center'

                },

                {
                    data:
                        'stok_optimum',

                    className:
                        'text-center'

                },

                {
                    data:
                        'obat_esensial',

                    className:
                        'text-center'

                },
                {
                    data: 'obat_formularium_puskesmas',
                    name: 's.obat_formularium_puskesmas',
                    className: 'text-center'
                },

                {
                    data:
                        'tahun',

                    className:
                        'text-center'

                },

                {
                    data:
                        'aksi',

                    orderable:
                        false,

                    searchable:
                        false,

                    className:
                        'text-center'

                }

            ],

            order: [

                [
                    1,
                    'asc'
                ]

            ],

            language: {

                search:
                    'Cari:',

                searchPlaceholder:
                    'Cari obat...',

                processing:
                    'Memuat data...',

                emptyTable:
                    'Belum ada data.',

                zeroRecords:
                    'Data tidak ditemukan.'

            }

        });


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    $('#filterFaskes, #filterTahun')
        .on(
            'change',
            function () {

                table.ajax.reload();

            }
        );


    $('#btnResetFilter')
        .on(
            'click',
            function () {

                @if(
                    auth()->user()->groupid == 1 ||
                    auth()->user()->groupid == 2
                )

                $('#filterFaskes')
                    .val('');

                @endif

                $('#filterTahun')
                    .val('{{ now()->year }}');

                table.ajax.reload();

            }
        );


    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    $('#btnTambah')
        .on(
            'click',
            function () {

                editMode =
                    false;

                clearForm();

                $('#modalTitle')
                    .html(`

                        <i class="bi bi-plus-circle me-2"></i>

                        Tambah Stok & Esensial

                    `);

                $('#saveText')
                    .text('Simpan');


                const modal =
                    bootstrap.Modal
                        .getOrCreateInstance(
                            document.getElementById(
                                'modalData'
                            )
                        );

                modal.show();

            }
        );


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $('#datatable')
        .on(
            'click',
            '.btn-edit',
            function () {

                const id =
                    $(this).data('id');

                editMode =
                    true;

                clearForm();

                $('#modalTitle')
                    .html(`

                        <i class="bi bi-pencil-square me-2"></i>

                        Edit Stok & Esensial

                    `);

                $('#saveText')
                    .text('Update');


                $.ajax({

                    url:
                        "{{ url('/newlplpo/stok-esensial') }}/" +
                        id,

                    type:
                        'GET',

                    success:
                        function (response) {

                            const data =
                                response.data;


                            /*
                            | SET OBAT SELECT2
                            */

                            const option =
                                new Option(

                                    data.obat?.nama_obat
                                        ? data.kode_obat +
                                          ' — ' +
                                          data.obat.nama_obat
                                        : data.kode_obat,

                                    data.kode_obat,

                                    true,
                                    true

                                );

                            $('#kode_obat')
                                .append(option)
                                .trigger('change');


                            $('#kodeFaskes')
                                .val(
                                    data.kodeFaskes
                                )
                                .trigger('change');


                            $('#stok_minimal')
                                .val(
                                    data.stok_minimal
                                );

                            $('#stok_optimum')
                                .val(
                                    data.stok_optimum
                                );

                            $('#obat_esensial')
                                .val(
                                    data.obat_esensial
                                );
                                 $('#obat_formularium_puskesmas')
                                .val(
                                    data.obat_formularium_puskesmas
                                );

                            $('#tahun')
                                .val(
                                    data.tahun
                                );


                            $('#data_id')
                                .val(
                                    data.id
                                );


                            const modal =
                                bootstrap.Modal
                                    .getOrCreateInstance(
                                        document.getElementById(
                                            'modalData'
                                        )
                                    );

                            modal.show();

                        },

                    error:
                        function (xhr) {

                            Swal.fire(
                                'Error',
                                getErrorMessage(xhr),
                                'error'
                            );

                        }

                });

            }
        );


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $('#datatable')
        .on(
            'click',
            '.btn-delete',
            function () {

                const id =
                    $(this).data('id');

                const nama =
                    $(this).data('nama');


                Swal.fire({

                    title:
                        'Hapus Data?',

                    html:
                        `Setting
                        <strong>${escapeHtml(nama)}</strong>
                        akan dihapus.`,

                    icon:
                        'warning',

                    showCancelButton:
                        true,

                    confirmButtonText:
                        'Ya, Hapus',

                    cancelButtonText:
                        'Batal',

                    confirmButtonColor:
                        '#dc3545'

                }).then(function (result) {

                    if (
                        !result.isConfirmed
                    ) {

                        return;

                    }


                    $.ajax({

                        url:
                            "{{ url('/newlplpo/stok-esensial') }}/" +
                            id,

                        type:
                            'DELETE',

                        data: {

                            _token:
                                "{{ csrf_token() }}"

                        },

                        success:
                            function (response) {

                                Swal.fire({

                                    icon:
                                        'success',

                                    title:
                                        'Berhasil',

                                    text:
                                        response.message,

                                    timer:
                                        1500,

                                    showConfirmButton:
                                        false

                                });


                                table.ajax.reload(
                                    null,
                                    false
                                );

                            },

                        error:
                            function (xhr) {

                                Swal.fire(
                                    'Tidak dapat dihapus',
                                    getErrorMessage(xhr),
                                    'error'
                                );

                            }

                    });

                });

            }
        );


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#formData')
        .on(
            'submit',
            function (e) {

                e.preventDefault();


                clearValidation();


                const id =
                    $('#data_id').val();


                let url =
                    storeUrl;

                let method =
                    'POST';


                if (editMode) {

                    url =
                        "{{ url('/newlplpo/stok-esensial') }}/" +
                        id;

                    method =
                        'PUT';

                }


                const payload = {

                    kode_obat:
                        $('#kode_obat').val(),

                    kodeFaskes:
                        $('#kodeFaskes').val(),

                    stok_minimal:
                        $('#stok_minimal').val(),

                    stok_optimum:
                        $('#stok_optimum').val(),

                    obat_esensial:
                        $('#obat_esensial').val(),
                        obat_formularium_puskesmas:
                        $('#obat_formularium_puskesmas').val(),

                    tahun:
                        $('#tahun').val(),

                    _token:
                        "{{ csrf_token() }}"

                };


                setLoading(true);


                $.ajax({

                    url:
                        url,

                    type:
                        method,

                    data:
                        payload,

                    success:
                        function (response) {

                            const modal =
                                bootstrap.Modal
                                    .getInstance(
                                        document.getElementById(
                                            'modalData'
                                        )
                                    );

                            if (modal) {

                                modal.hide();

                            }


                            Swal.fire({

                                icon:
                                    'success',

                                title:
                                    'Berhasil',

                                text:
                                    response.message,

                                timer:
                                    1500,

                                showConfirmButton:
                                    false

                            });


                            table.ajax.reload(
                                null,
                                false
                            );

                        },

                    error:
                        function (xhr) {

                            handleError(xhr);

                        },

                    complete:
                        function () {

                            setLoading(false);

                        }

                });

            }
        );


    /*
    |--------------------------------------------------------------------------
    | CLEAR
    |--------------------------------------------------------------------------
    */

    function clearForm()
    {

        $('#formData')[0].reset();

        $('#data_id')
            .val('');

        $('#kode_obat')
            .val(null)
            .trigger('change');

        $('#stok_minimal')
            .val(0);

        $('#stok_optimum')
            .val(0);

        $('#obat_esensial')
            .val('noe');

        $('#tahun')
            .val('{{ now()->year }}');

        clearValidation();

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    function clearValidation()
    {

        $('#formData')
            .find('.is-invalid')
            .removeClass('is-invalid');

        $('.invalid-feedback')
            .text('');

    }


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    function handleError(xhr)
    {

        console.error(
            xhr.responseText
        );


        if (
            xhr.status === 422 &&
            xhr.responseJSON?.errors
        ) {

            const errors =
                xhr.responseJSON.errors;


            Object.keys(errors)
                .forEach(function (field) {

                    const input =
                        $('#' + field);

                    input.addClass(
                        'is-invalid'
                    );

                    $('#' + field + '_error')
                        .text(
                            errors[field][0]
                        );

                });


            Swal.fire(
                'Validasi',
                'Periksa kembali data yang dimasukkan.',
                'warning'
            );

            return;

        }


        Swal.fire(
            'Error',
            getErrorMessage(xhr),
            'error'
        );

    }


    function getErrorMessage(xhr)
    {

        return (
            xhr.responseJSON?.message ??
            'Terjadi kesalahan pada server.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOADING
    |--------------------------------------------------------------------------
    */

    function setLoading(status)
    {

        $('#btnSimpan')
            .prop(
                'disabled',
                status
            );


        if (status) {

            $('#spinner')
                .removeClass('d-none');

            $('#saveIcon')
                .addClass('d-none');

            $('#saveText')
                .text(
                    'Menyimpan...'
                );

        } else {

            $('#spinner')
                .addClass('d-none');

            $('#saveIcon')
                .removeClass('d-none');

            $('#saveText')
                .text(
                    editMode
                        ? 'Update'
                        : 'Simpan'
                );

        }

    }


    function escapeHtml(value)
    {

        return $('<div>')
            .text(
                value ?? ''
            )
            .html();

    }

});



/* duplikasi tahun */
/*
|--------------------------------------------------------------------------
| DUPLIKASI TAHUN
|--------------------------------------------------------------------------
*/

$('#btnDuplikasi').on('click', function () {

    const tahunFilter =
        $('#filterTahun').val();

    if (tahunFilter) {

        $('#duplikat_dari_tahun')
            .val(tahunFilter);

        $('#duplikat_ke_tahun')
            .val(
                parseInt(tahunFilter) + 1
            );

    }


    const modal =
        bootstrap.Modal.getOrCreateInstance(
            document.getElementById(
                'modalDuplikasi'
            )
        );

    modal.show();

});
/* end duplikasi tahun */


/*
|--------------------------------------------------------------------------
| VALIDASI TAHUN DUPLIKASI
|--------------------------------------------------------------------------
*/

$('#duplikat_dari_tahun, #duplikat_ke_tahun')
    .on('change', function () {

        const dari =
            $('#duplikat_dari_tahun').val();

        const ke =
            $('#duplikat_ke_tahun').val();


        if (dari === ke) {

            $('#duplikasiWarning')
                .removeClass('d-none');

            $('#btnConfirmDuplikasi')
                .prop('disabled', true);

        } else {

            $('#duplikasiWarning')
                .addClass('d-none');

            $('#btnConfirmDuplikasi')
                .prop('disabled', false);

        }

    });


    /*
|--------------------------------------------------------------------------
| CONFIRM DUPLIKASI
|--------------------------------------------------------------------------
*/

$('#btnConfirmDuplikasi').on(
    'click',
    function () {

        const dari =
            $('#duplikat_dari_tahun').val();

        const ke =
            $('#duplikat_ke_tahun').val();


        if (dari === ke) {

            Swal.fire(
                'Tidak valid',
                'Tahun sumber dan tujuan tidak boleh sama.',
                'warning'
            );

            return;

        }


        Swal.fire({

            title:
                'Duplikasi Data?',

            html:
                `Seluruh data konfigurasi tahun
                <strong>${dari}</strong>
                akan diduplikasi ke tahun
                <strong>${ke}</strong>.`,

            icon:
                'question',

            showCancelButton:
                true,

            confirmButtonText:
                '<i class="bi bi-copy me-1"></i> Ya, Duplikasi',

            cancelButtonText:
                'Batal',

            reverseButtons:
                true

        }).then(function (result) {

            if (!result.isConfirmed) {

                return;

            }


            duplicateData(
                dari,
                ke
            );

        });

    }
);


/*
|--------------------------------------------------------------------------
| EXECUTE DUPLIKASI
|--------------------------------------------------------------------------
*/

function duplicateData(
    dariTahun,
    keTahun
) {

    const button =
        $('#btnConfirmDuplikasi');


    button.prop(
        'disabled',
        true
    );


    $('#duplicateSpinner')
        .removeClass('d-none');

    $('#duplicateIcon')
        .addClass('d-none');

    $('#duplicateText')
        .text('Memproses...');


    $.ajax({

        url:
            "{{ route('newlplpo.stok-esensial.duplicate') }}",

        type:
            'POST',

        data: {

            dari_tahun:
                dariTahun,

            ke_tahun:
                keTahun,

            @if(
                auth()->user()->groupid == 1 ||
                auth()->user()->groupid == 2
            )

            kodeFaskes:
                $('#filterFaskes').val(),

            @endif

            _token:
                "{{ csrf_token() }}"

        },

        success:
            function (response) {


                const modal =
                    bootstrap.Modal.getInstance(
                        document.getElementById(
                            'modalDuplikasi'
                        )
                    );


                if (modal) {

                    modal.hide();

                }


                /*
                |--------------------------------------------------------------------------
                | SET FILTER KE TAHUN HASIL DUPLIKASI
                |--------------------------------------------------------------------------
                */

                $('#filterTahun')
                    .val(keTahun);


                /*
                |--------------------------------------------------------------------------
                | RELOAD TABLE
                |--------------------------------------------------------------------------
                */

                table.ajax.reload(
                    null,
                    false
                );


                Swal.fire({

                    icon:
                        'success',

                    title:
                        'Berhasil',

                    html:
                        response.message,

                    timer:
                        2500,

                    showConfirmButton:
                        false

                });

            },

        error:
            function (xhr) {

                handleError(xhr);

            },

        complete:
            function () {

                button.prop(
                    'disabled',
                    false
                );


                $('#duplicateSpinner')
                    .addClass('d-none');

                $('#duplicateIcon')
                    .removeClass('d-none');

                $('#duplicateText')
                    .text('Duplikasi');

            }

    });

}

</script>

@endpush
