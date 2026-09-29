@extends('newlplpo.layouts.master')

@section('title', isset($report) ? 'Edit LPLPO' : 'Membuat LPLPO')

@section('content')

{{-- =========================================================
| FORM HEADER LPLPO
========================================================= --}}

<form
    method="POST"
    action="{{ isset($report)
        ? route('newlplpo.update', $report->id)
        : route('newlplpo.store') }}"
    id="frmLplpo"
>

    @csrf

    @isset($report)
        @method('PUT')
    @endisset

    <input
        type="hidden"
        name="kode_faskes"
        value="{{ $faskes->kodeFaskes }}"
    >

    <input
        type="hidden"
        name="nama_faskes"
        value="{{ $faskes->namaFaskes }}"
    >

    <div class="card shadow-sm border-0 mb-4">

        {{-- =====================================================
        | HEADER
        ====================================================== --}}

        <div class="card-header bg-success text-white">

            <h4 class="text-center mb-0">
                LAPORAN PEMAKAIAN DAN LEMBAR PERMINTAAN OBAT (LPLPO)
            </h4>

        </div>


        {{-- =====================================================
        | DATA HEADER
        ====================================================== --}}

        <div class="card-body">

            <div class="row">

                <div class="col-lg-6">

                    @include('newlplpo.partials.header_faskes')

                </div>

                <div class="col-lg-6">

                    @include('newlplpo.partials.header_laporan')

                </div>

            </div>

        </div>


        {{-- =====================================================
        | FOOTER / ACTION
        ====================================================== --}}

        <div class="card-footer text-end">

            @if(isset($report))

                {{-- =============================================
                | DRAFT
                ============================================== --}}

                @if($report->report_status === 'DRAFT')

                    <button
                        type="button"
                        class="btn btn-success"
                        id="btnKirimApproval"
                    >

                        <i class="bi bi-send me-1"></i>

                        Kirim Approval

                    </button>

                @endif


                {{-- =============================================
                | WAITING
                ============================================== --}}

                @if($report->lplpo_status === 'waiting')

                    <span class="badge bg-warning text-dark p-2">

                        <i class="bi bi-hourglass-split me-1"></i>

                        Menunggu Approval Kepala Puskesmas

                    </span>

                @endif


                {{-- =============================================
                | APPROVED
                ============================================== --}}

                @if(
                    $report->report_status === 'SUBMITED' &&
                    $report->lplpo_status === 'approved'
                )

                    <span class="badge bg-success p-2">

                        <i class="bi bi-check-circle me-1"></i>

                        Telah Disetujui

                    </span>

                @endif


            @else

                {{-- =============================================
                | CREATE
                ============================================== --}}

              <button
    type="submit"
    class="btn btn-success"
    id="btnHeader"
>
    <i class="bi bi-plus-circle me-1"></i>
    Buat Laporan
</button>

            @endif

        </div>

    </div>

</form>


{{-- =========================================================
| AREA EDIT REPORT
========================================================= --}}

@if(isset($report))


    {{-- =====================================================
    | INFORMASI PENOLAKAN
    ====================================================== --}}

    @if(
        $report->report_status === 'REJECTED' &&
        $report->linkApproval &&
        $report->linkApproval->rejectedReason
    )

        <div class="alert alert-danger shadow-sm mb-4">

            <div class="d-flex align-items-start">

                <div class="me-3">

                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>

                </div>

                <div class="flex-grow-1">

                    <h5 class="alert-heading mb-2">

                        Laporan Ditolak

                    </h5>


                    {{-- ALASAN --}}

                    <div class="mb-3">

                        <strong>
                            Alasan Penolakan:
                        </strong>

                        <div class="mt-2 p-3 bg-white border rounded">

                            {!! nl2br(e($report->linkApproval->rejectedReason)) !!}

                        </div>

                    </div>


                    {{-- KAPUS --}}

                    @if($report->linkApproval->kapus)

                        <div class="small text-muted">

                            <strong>
                                Ditolak oleh:
                            </strong>

                            {{ $report->linkApproval->kapus->namaKapus }}

                            <br>

                            <strong>
                                Tanggal:
                            </strong>

                            {{ $report->linkApproval->updated_at
                                ? $report->linkApproval->updated_at->format('d-m-Y H:i')
                                : '-' }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    @endif


    {{-- =====================================================
    | KIRIM ULANG APPROVAL
    ====================================================== --}}

    @if($report->report_status === 'REJECTED')

        <div class="card border-danger shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="mb-1 text-danger">

                            <i class="bi bi-arrow-repeat me-1"></i>

                            Perbaiki Laporan

                        </h6>

                        <small class="text-muted">

                            Perbaiki data sesuai alasan penolakan,
                            kemudian kirim kembali laporan ini
                            untuk approval Kepala Puskesmas.

                        </small>

                    </div>


                    <button
                        type="button"
                        class="btn btn-danger"
                        id="btnResubmitApproval"
                    >

                        <i class="bi bi-send me-1"></i>

                        Kirim Ulang Approval

                    </button>

                </div>

            </div>

        </div>

    @endif


    {{-- =====================================================
    | DAFTAR ITEM OBAT
    ====================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0">

                        Daftar Item Obat

                    </h5>

                </div>

                <div>

                    <span class="badge bg-primary">

                        {{ $items->count() }} Item

                    </span>

                </div>

            </div>

        </div>


        <div class="card-body">


            {{-- =================================================
            | TOOLBAR
            ================================================== --}}

            <div class="row mb-3">

                <div class="col-md-8">

                    {{-- =========================================
                    | EDITABLE
                    ========================================== --}}

                    @if(
                        in_array(
                            $report->report_status,
                            ['DRAFT', 'REJECTED'],
                            true
                        )
                    )

                        {{-- TAMBAH OBAT --}}

                        <button
                            type="button"
                            class="btn btn-success"
                            id="btnTambah"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasObat"
                        >

                            <i class="bi bi-capsule me-1"></i>

                            Tambah Obat

                        </button>


                        {{-- =====================================
                        | KUNJUNGAN
                        ====================================== --}}

                        @if($report->kunjungan)

                            <a
                                href="{{ route(
                                    'newlplpo.kunjungan.edit',
                                    $report->id
                                ) }}"
                                class="btn btn-warning"
                            >

                                <i class="bi bi-pencil-square me-1"></i>

                                Edit Kunjungan

                            </a>

                        @else

                            <a
                                href="{{ route(
                                    'newlplpo.kunjungan.create',
                                    $report->id
                                ) }}"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-people-fill me-1"></i>

                                Input Kunjungan

                            </a>

                        @endif

                    @endif

                </div>


                {{-- SEARCH --}}

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        id="searchItem"
                        placeholder="Cari Item..."
                    >

                </div>

            </div>


            {{-- =================================================
            | TABLE ITEM
            ================================================== --}}

            <div class="table-responsive table-lplpo">

                <table
                    class="table table-bordered table-hover align-middle"
                    id="tblItem"
                >

                    <thead class="table-success text-center align-middle">

                        <tr>

                            <th rowspan="2">
                                No
                            </th>

                            <th rowspan="2">
                                Kode Obat
                            </th>

                            <th rowspan="2">
                                Nama Barang
                            </th>

                            <th rowspan="2">
                                Satuan
                            </th>

                            <th rowspan="2">
                                Obat Esensial
                            </th>

                            <th colspan="2">
                                Stok Awal
                            </th>

                            <th colspan="2">
                                Penerimaan
                            </th>

                            <th colspan="2">
                                Persediaan
                            </th>

                            <th colspan="2">
                                Pengeluaran
                            </th>

                            <th colspan="2">
                                Expired/Retur
                            </th>

                            <th colspan="2">
                                Stok Akhir
                            </th>

                            <th rowspan="2">
                                Stok Minimum
                            </th>

                            <th rowspan="2">
                                Stok Optimum
                            </th>

                            <th rowspan="2">
                                Permintaan
                            </th>

                            <th rowspan="2">
                                Pemberian
                            </th>

                            <th
                                width="100"
                                rowspan="2"
                                class="text-center"
                            >
                                Aksi
                            </th>

                        </tr>


                        <tr>

                            <th>PKD</th>
                            <th>JKN</th>

                            <th>PKD</th>
                            <th>JKN</th>

                            <th>PKD</th>
                            <th>JKN</th>

                            <th>PKD</th>
                            <th>JKN</th>

                            <th>PKD</th>
                            <th>JKN</th>

                            <th>PKD</th>
                            <th>JKN</th>



                        </tr>

                    </thead>


                    <tbody>

                        @foreach($items as $no => $item)

                            @include(
                                'newlplpo.partials.row_item'
                            )

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endif


@endsection


{{-- =========================================================
| STYLES
========================================================= --}}

@if(isset($report))

    @push('styles')

        <style>

            .row-napza {
                background-color: #ffe4ef !important;
            }

            .row-napza td {
                background-color: #ffe4ef !important;
            }

            #tblMasterObat tbody tr {
    cursor: pointer;
}

#tblMasterObat tbody tr:hover {
    background-color: #e8f5e9 !important;
}

#tblMasterObat tbody tr.table-primary {
    background-color: #cfe2ff !important;
}

        </style>

    @endpush


    {{-- =====================================================
    | OFFCANVAS
    ====================================================== --}}

    @push('offcanvas')

        @include(
            'newlplpo.partials.offcanvas_masterobat'
        )

    @endpush


    {{-- =====================================================
    | MASTER OBAT SCRIPT
    ====================================================== --}}

    @push('script')

        @include(
            'newlplpo.partials.scripts_masterobat'
        )

    @endpush


    {{-- =====================================================
    | PAGE SCRIPT
    ====================================================== --}}

    @push('script')

        <script>

        $(function () {


            /*
            |--------------------------------------------------------------------------
            | KIRIM APPROVAL - DRAFT
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '#btnKirimApproval',
                function () {

                    const btn = $(this);

                    Swal.fire({

                        icon: 'question',

                        title: 'Kirim Approval?',

                        html: `
                            <p class="mb-2">
                                Laporan akan dikirim kepada
                                Kepala Puskesmas untuk proses approval.
                            </p>

                            <strong>
                                Setelah dikirim, laporan tidak dapat
                                diubah sampai proses approval selesai.
                            </strong>
                        `,

                        showCancelButton: true,

                        confirmButtonText:
                            '<i class="bi bi-send me-1"></i> Ya, Kirim Approval',

                        cancelButtonText:
                            'Periksa Lagi',

                        reverseButtons: true

                    }).then(function (result) {

                        if (!result.isConfirmed) {
                            return;
                        }


                        const originalHtml = btn.html();


                        btn
                            .prop('disabled', true)
                            .html(`
                                <span
                                    class="spinner-border spinner-border-sm me-1">
                                </span>
                                Mengirim...
                            `);


                        $.ajax({

                            url:
                                "{{ route(
                                    'newlplpo.resubmit-approval',
                                    $report->id
                                ) }}",

                            type: 'POST',

                            data: {

                                _token:
                                    $('meta[name="csrf-token"]').attr(
                                        'content'
                                    )

                            },


                            success: function (response) {

                                Swal.fire({

                                    icon: 'success',

                                    title: 'Berhasil',

                                    text:
                                        response.message ??
                                        'Laporan berhasil dikirim untuk approval.',

                                    confirmButtonText: 'OK'

                                }).then(function () {

                                    location.reload();

                                });

                            },


                            error: function (xhr) {

                                let message =
                                    'Laporan gagal dikirim untuk approval.';


                                if (
                                    xhr.responseJSON &&
                                    xhr.responseJSON.message
                                ) {

                                    message =
                                        xhr.responseJSON.message;

                                }


                                if (
                                    xhr.status === 422 &&
                                    xhr.responseJSON &&
                                    xhr.responseJSON.errors
                                ) {

                                    const errors =
                                        xhr.responseJSON.errors;

                                    message =
                                        Object.values(errors)
                                            .flat()
                                            .join('<br>');

                                }


                                Swal.fire({

                                    icon: 'error',

                                    title: 'Gagal',

                                    html: message

                                });

                            },


                            complete: function () {

                                btn
                                    .prop('disabled', false)
                                    .html(originalHtml);

                            }

                        });

                    });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | KIRIM ULANG APPROVAL - REJECTED
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '#btnResubmitApproval',
                function () {

                    const btn = $(this);


                    Swal.fire({

                        icon: 'question',

                        title: 'Kirim Ulang Approval?',

                        html: `
                            <p class="mb-2">
                                Pastikan seluruh data LPLPO
                                sudah diperbaiki sesuai alasan penolakan.
                            </p>

                            <strong>
                                Setelah dikirim, laporan akan kembali
                                menunggu approval Kepala Puskesmas.
                            </strong>
                        `,

                        showCancelButton: true,

                        confirmButtonText:
                            '<i class="bi bi-send me-1"></i> Ya, Kirim',

                        cancelButtonText:
                            'Periksa Lagi',

                        reverseButtons: true

                    }).then(function (result) {

                        if (!result.isConfirmed) {
                            return;
                        }


                        const originalHtml =
                            btn.html();


                        btn
                            .prop('disabled', true)
                            .html(`
                                <span
                                    class="spinner-border spinner-border-sm me-1">
                                </span>
                                Mengirim...
                            `);


                        $.ajax({

                            url:
                                "{{ route(
                                    'newlplpo.resubmit-approval',
                                    $report->id
                                ) }}",

                            type: 'POST',

                            data: {

                                _token:
                                    $('meta[name="csrf-token"]').attr(
                                        'content'
                                    )

                            },


                            success: function (response) {

                                Swal.fire({

                                    icon: 'success',

                                    title: 'Berhasil',

                                    text:
                                        response.message ??
                                        'Laporan berhasil dikirim kembali.',

                                    confirmButtonText: 'OK'

                                }).then(function () {

                                    location.reload();

                                });

                            },


                            error: function (xhr) {

                                let message =
                                    'Laporan gagal dikirim kembali.';


                                if (
                                    xhr.responseJSON &&
                                    xhr.responseJSON.message
                                ) {

                                    message =
                                        xhr.responseJSON.message;

                                }


                                if (
                                    xhr.status === 422 &&
                                    xhr.responseJSON &&
                                    xhr.responseJSON.errors
                                ) {

                                    const errors =
                                        xhr.responseJSON.errors;

                                    message =
                                        Object.values(errors)
                                            .flat()
                                            .join('<br>');

                                }


                                Swal.fire({

                                    icon: 'error',

                                    title: 'Gagal',

                                    html: message

                                });

                            },


                            complete: function () {

                                btn
                                    .prop('disabled', false)
                                    .html(originalHtml);

                            }

                        });

                    });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | EDIT HEADER
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '#btnEditHeader',
                function () {

                    $('select[name="bulan"]')
                        .prop('disabled', false);

                    $('input[name="tahun"]')
                        .prop('readonly', false);


                    $(this)
                        .removeClass('btn-warning')
                        .addClass('btn-success')
                        .html(`
                            <i class="bi bi-check-circle"></i>
                            Simpan Header
                        `)
                        .attr('id', 'btnSaveHeader');

                }
            );


            /*
            |--------------------------------------------------------------------------
            | SAVE HEADER
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '#btnSaveHeader',
                function () {

                    const btn = $(this);

                    const bulan =
                        $('select[name="bulan"]').val();

                    const tahun =
                        $('input[name="tahun"]').val();


                    $.ajax({

                        url:
                            "{{ route(
                                'newlplpo.update',
                                $report->id
                            ) }}",

                        type: 'PUT',

                        data: {

                            _token:
                                "{{ csrf_token() }}",

                            bulan: bulan,

                            tahun: tahun

                        },


                        beforeSend: function () {

                            btn
                                .prop('disabled', true)
                                .html(`
                                    <span
                                        class="spinner-border spinner-border-sm me-1">
                                    </span>
                                    Menyimpan...
                                `);

                        },


                        success: function (response) {

                            if (!response.success) {
                                return;
                            }


                            $('select[name="bulan"]')
                                .prop('disabled', true);

                            $('input[name="tahun"]')
                                .prop('readonly', true);


                            btn
                                .prop('disabled', false)
                                .removeClass('btn-success')
                                .addClass('btn-warning')
                                .html(`
                                    <i class="bi bi-pencil-square"></i>
                                    Edit Header
                                `)
                                .attr('id', 'btnEditHeader');


                            Swal.fire({

                                icon: 'success',

                                title: 'Berhasil',

                                text:
                                    response.message ??
                                    'Header berhasil diperbarui.',

                                timer: 1500,

                                showConfirmButton: false

                            });

                        },


                        error: function (xhr) {

                            let message =
                                'Gagal menyimpan perubahan.';


                            if (
                                xhr.responseJSON &&
                                xhr.responseJSON.message
                            ) {

                                message =
                                    xhr.responseJSON.message;

                            }


                            if (
                                xhr.status === 422 &&
                                xhr.responseJSON &&
                                xhr.responseJSON.errors
                            ) {

                                message =
                                    Object.values(
                                        xhr.responseJSON.errors
                                    )
                                    .flat()
                                    .join('<br>');

                            }


                            Swal.fire({

                                icon: 'error',

                                title: 'Gagal',

                                html: message

                            });


                            btn
                                .prop('disabled', false)
                                .html(`
                                    <i class="bi bi-check-circle"></i>
                                    Simpan Header
                                `);

                        }

                    });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | SEARCH ITEM
            |--------------------------------------------------------------------------
            */

            $('#searchItem').on(
                'keyup',
                function () {

                    const keyword =
                        $(this).val().toLowerCase();


                    $('#tblItem tbody tr').each(
                        function () {

                            const text =
                                $(this).text().toLowerCase();


                            $(this).toggle(
                                text.indexOf(keyword) !== -1
                            );

                        }
                    );

                }
            );


        });

        </script>

    @endpush


    {{-- =====================================================
    | EDIT ITEM
    ====================================================== --}}

    @push('script')

        <script>

        document.addEventListener(
            'click',
            function (e) {

                const btn =
                    e.target.closest('.btnEditItem');


                if (!btn) {
                    return;
                }


                e.preventDefault();


                const id =
                    btn.dataset.id;


                console.log(
                    'EDIT ITEM ID:',
                    id
                );


                const url =
                    "{{ url('newlplpo/item') }}/"
                    + id
                    + "/edit";


                fetch(
                    url,
                    {

                        method: 'GET',

                        headers: {

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'

                        }

                    }
                )


                .then(
                    async response => {

                        const text =
                            await response.text();


                        console.log(
                            'STATUS:',
                            response.status
                        );


                        console.log(
                            'RESPONSE:',
                            text
                        );


                        if (!response.ok) {

                            throw new Error(
                                'HTTP '
                                + response.status
                                + ': '
                                + text
                            );

                        }


                        return JSON.parse(text);

                    }
                )


                .then(
                    res => {

                        if (!res.success) {

                            Swal.fire(
                                'Error',
                                'Data item tidak ditemukan.',
                                'error'
                            );

                            return;

                        }


                        const item =
                            res.data;


                        console.table({

                            id:
                                item.id,

                            program_id:
                                item.program_id,

                            program_name:
                                item.program_name,

                            kode_obat:
                                item.kode_obat,

                            nama_obat:
                                item.nama_obat,

                            satuan:
                                item.satuan,

                            stok_awal_progam_pkd:
                                item.stok_awal_progam_pkd,

                            stok_awal_jkn:
                                item.stok_awal_jkn,

                            penerimaan_program_pkd:
                                item.penerimaan_program_pkd,

                            penerimaan_jkn:
                                item.penerimaan_jkn,

                            persediaan_program_pkd:
                                item.persediaan_program_pkd,

                            persediaan_jkn:
                                item.persediaan_jkn,

                            pemakaian_program_pkd:
                                item.pemakaian_program_pkd,

                            pemakaian_jkn:
                                item.pemakaian_jkn

                        });


                        const form =
                            document.getElementById(
                                'frmItem'
                            );


                        if (!form) {

                            console.error(
                                'Form #frmItem tidak ditemukan.'
                            );

                            return;

                        }


                        form.dataset.editId =
                            item.id;


                        /*
                        |--------------------------------------------------------------------------
                        | FORM VALUE
                        |--------------------------------------------------------------------------
                        */

                        setValue(
                            'program_id',
                            item.program_id
                        );

                        setValue(
                            'kode_obat',
                            item.kode_obat
                        );

                        setValue(
                            'nama_obat',
                            item.nama_obat
                        );

                        setValue(
                            'satuan',
                            item.satuan
                        );

                        setValue(
                            'stok_awal_progam_pkd',
                            item.stok_awal_progam_pkd
                        );

                        setValue(
                            'stok_awal_jkn',
                            item.stok_awal_jkn
                        );

                        setValue(
                            'penerimaan_program_pkd',
                            item.penerimaan_program_pkd
                        );

                        setValue(
                            'penerimaan_jkn',
                            item.penerimaan_jkn
                        );

                        setValue(
                            'persediaan_program_pkd',
                            item.persediaan_program_pkd
                        );

                        setValue(
                            'persediaan_jkn',
                            item.persediaan_jkn
                        );

                        setValue(
                            'pemakaian_program_pkd',
                            item.pemakaian_program_pkd
                        );

                        setValue(
                            'pemakaian_jkn',
                            item.pemakaian_jkn
                        );

                        setValue(
                            'item_expired_pkd',
                            item.item_expired_pkd
                        );

                        setValue(
                            'item_expired_jkn',
                            item.item_expired_jkn
                        );

                        setValue(
                            'stok_akhir_program_pkd',
                            item.stok_akhir_program_pkd
                        );

                        setValue(
                            'stok_akhir_jkn',
                            item.stok_akhir_jkn
                        );

                        setValue(
                            'stok_minimum',
                            item.stok_minimum
                        );

                        setValue(
                            'stok_optimum',
                            item.stok_optimum
                        );

                        setValue(
                            'permintaan',
                            item.permintaan
                        );

                        setValue(
                            'pemberian_program_pkd',
                            item.pemberian_program_pkd
                        );

                        setValue(
                            'pemberian_jkn',
                            item.pemberian_jkn
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | PROGRAM
                        |--------------------------------------------------------------------------
                        */

                        const program =
                            document.getElementById(
                                'program_id'
                            );


                        if (program) {

                            program.value =
                                item.program_id ?? '';


                            program.dispatchEvent(
                                new Event(
                                    'change',
                                    {
                                        bubbles: true
                                    }
                                )
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | BUTTON
                        |--------------------------------------------------------------------------
                        */

                        const saveButton =
                            document.getElementById(
                                'btnSaveItem'
                            );


                        if (saveButton) {

                            saveButton.innerHTML =
                                '<i class="bi bi-save"></i> Update Item';

                            saveButton.dataset.mode =
                                'edit';

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | OFFCANVAS
                        |--------------------------------------------------------------------------
                        */

                        const offcanvasElement =
                            document.getElementById(
                                'offcanvasObat'
                            );


                        if (!offcanvasElement) {

                            console.error(
                                'Element #offcanvasObat tidak ditemukan.'
                            );

                            return;

                        }


                        const offcanvas =
                            bootstrap.Offcanvas
                                .getOrCreateInstance(
                                    offcanvasElement
                                );


                        offcanvas.show();

                    }
                )


                .catch(
                    error => {

                        console.error(
                            'EDIT ITEM ERROR:',
                            error
                        );


                        Swal.fire({

                            icon: 'error',

                            title: 'Gagal',

                            text:
                                'Gagal mengambil data item.'

                        });

                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | HELPER
        |--------------------------------------------------------------------------
        */

        function setValue(id, value)
        {

            const element =
                document.getElementById(id);


            if (!element) {

                console.warn(
                    'Element #' + id + ' tidak ditemukan.'
                );

                return;

            }


            element.value =
                value ?? '';

        }

        </script>

    @endpush

@endif
