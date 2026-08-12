@extends('newlplpo.layouts.master')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
         HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="bi bi-clipboard-data me-2"></i>

                Rekap Laporan LPLPO

            </h4>

            <div class="text-muted">

                Rekapitulasi LPLPO FINAL berdasarkan periode

            </div>

        </div>

    </div>


    {{-- ==========================================================
         FILTER PERIODE
    =========================================================== --}}

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-header bg-success text-white">

            <strong>
                <i class="bi bi-calendar-range me-1"></i>
                Filter Periode Rekap
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-3 align-items-end">

                {{-- PERIODE MULAI --}}

                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold">
                        Periode Mulai
                    </label>

                    <div class="input-group">

                        <select
                            id="bulan_mulai"
                            class="form-select">

                            @foreach(range(1,12) as $i)

                                <option
                                    value="{{ $i }}"
                                    {{ (int)$bulanMulai === $i ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()
                                        ->month($i)
                                        ->translatedFormat('F') }}

                                </option>

                            @endforeach

                        </select>

                        <select
                            id="tahun_mulai"
                            class="form-select">

                            @for(
                                $y = now()->year - 5;
                                $y <= now()->year + 1;
                                $y++
                            )

                                <option
                                    value="{{ $y }}"
                                    {{ (int)$tahunMulai === $y ? 'selected' : '' }}>

                                    {{ $y }}

                                </option>

                            @endfor

                        </select>

                    </div>

                </div>


                {{-- PERIODE SAMPAI --}}

                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold">
                        Periode Sampai
                    </label>

                    <div class="input-group">

                        <select
                            id="bulan_sampai"
                            class="form-select">

                            @foreach(range(1,12) as $i)

                                <option
                                    value="{{ $i }}"
                                    {{ (int)$bulanSampai === $i ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()
                                        ->month($i)
                                        ->translatedFormat('F') }}

                                </option>

                            @endforeach

                        </select>

                        <select
                            id="tahun_sampai"
                            class="form-select">

                            @for(
                                $y = now()->year - 5;
                                $y <= now()->year + 1;
                                $y++
                            )

                                <option
                                    value="{{ $y }}"
                                    {{ (int)$tahunSampai === $y ? 'selected' : '' }}>

                                    {{ $y }}

                                </option>

                            @endfor

                        </select>

                    </div>

                </div>


                {{-- FASKES --}}

                @if($groupId == 2)

                    <div class="col-lg-4 col-md-6">

                        <label class="form-label fw-semibold">
                            Faskes
                        </label>

                        <select
                            id="kode_faskes"
                            class="form-select">

                            <option value="">
                                Semua Faskes
                            </option>

                            @foreach($faskes as $f)

                                <option
                                    value="{{ $f->kodeFaskes }}">

                                    {{ $f->kodeFaskes }}
                                    -
                                    {{ $f->namaFaskes }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                @endif


                {{-- BUTTON --}}

                <div class="col-lg-2 col-md-6">

                    <button
                        type="button"
                        class="btn btn-success w-100"
                        id="btnFilter">

                        <i class="bi bi-search me-1"></i>

                        Tampilkan

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         INFO PERIODE
    =========================================================== --}}

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-header bg-success text-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    <i class="bi bi-file-earmark-bar-graph me-1"></i>
                    Rekap Laporan LPLPO
                </strong>

                <span class="badge bg-light text-success">
                    FINAL
                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="text-muted small">
                        PERIODE
                    </div>

                    <div
                        class="fw-bold fs-5"
                        id="infoPeriode">

                        -

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        JUMLAH LAPORAN
                    </div>

                    <div
                        class="fw-bold fs-5"
                        id="infoJumlahLaporan">

                        -

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        JUMLAH ITEM
                    </div>

                    <div
                        class="fw-bold fs-5"
                        id="infoJumlahItem">

                        -

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         DETAIL ITEM
    =========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-success text-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>

                    <i class="bi bi-capsule me-1"></i>

                    Detail Rekap Item Obat

                </strong>

                <span
                    class="badge bg-light text-dark"
                    id="jumlahItem">

                    0 Item

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover table-sm align-middle mb-0"
                    id="tableRekap">

                    <thead class="table-success text-center align-middle">

                        <tr>

                            <th rowspan="2">
                                No
                            </th>

                            <th rowspan="2">
                                Program
                            </th>

                            <th rowspan="2">
                                Kode
                            </th>

                            <th rowspan="2">
                                Nama Obat
                            </th>

                            <th rowspan="2">
                                Sat
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
                                Pemakaian
                            </th>

                            <th colspan="2">
                                Expired
                            </th>

                            <th colspan="2">
                                Stok Akhir
                            </th>

                            <th rowspan="2">
                                Permintaan
                            </th>

                            <th colspan="2">
                                Pemberian
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

                            <th>PKD</th>
                            <th>JKN</th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td
                                colspan="21"
                                class="text-center text-muted py-5">

                                <i class="bi bi-hourglass-split fs-3 d-block mb-2"></i>

                                Memuat data...

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         FOOTER
    =========================================================== --}}

    <div class="mt-3 text-end">

        <button
            type="button"
            class="btn btn-secondary"
            onclick="window.history.back()">

            <i class="bi bi-arrow-left me-1"></i>

            Kembali

        </button>

        <button
            type="button"
            class="btn btn-success"
            onclick="window.print()">

            <i class="bi bi-printer me-1"></i>

            Cetak

        </button>

    </div>

</div>

@endsection


{{-- ==============================================================
     STYLE
=============================================================== --}}

@push('styles')

<style>

#tableRekap {
    font-size: 13px;
}

#tableRekap th {
    white-space: nowrap;
    vertical-align: middle;
}

#tableRekap td {
    white-space: nowrap;
    vertical-align: middle;
}

#tableRekap thead th {
    position: sticky;
    top: 0;
    z-index: 2;
}

.table-primary td {
    background-color: #cfe2ff !important;
}

.rekap-loading {
    opacity: .6;
    pointer-events: none;
}

@media print {

    .sidebar,
    .navbar,
    #btnFilter,
    .btn,
    .card-header .badge {

        display: none !important;

    }

    .card {

        box-shadow: none !important;
        border: 1px solid #ddd !important;

    }

    #tableRekap {

        font-size: 10px;

    }

}

</style>

@endpush


{{-- ==============================================================
     SCRIPT
=============================================================== --}}

@push('script')

<script>

$(function () {

    const dataUrl =
        "{{ route('newlplpo.rekap.data') }}";


    /*
    |--------------------------------------------------------------------------
    | STATUS REQUEST
    |--------------------------------------------------------------------------
    */

    let currentRequest = null;


    /*
    |--------------------------------------------------------------------------
    | AMBIL FILTER
    |--------------------------------------------------------------------------
    */

    function getFilter()
    {

        return {

            bulan_mulai:
                parseInt($('#bulan_mulai').val(), 10),

            tahun_mulai:
                parseInt($('#tahun_mulai').val(), 10),

            bulan_sampai:
                parseInt($('#bulan_sampai').val(), 10),

            tahun_sampai:
                parseInt($('#tahun_sampai').val(), 10),

            kode_faskes:
                $('#kode_faskes').length
                    ? $('#kode_faskes').val()
                    : ''

        };

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDASI PERIODE
    |--------------------------------------------------------------------------
    */

    function validatePeriod(filter)
    {

        const periodeMulai =
            (filter.tahun_mulai * 100) +
            filter.bulan_mulai;

        const periodeSampai =
            (filter.tahun_sampai * 100) +
            filter.bulan_sampai;


        if (periodeMulai > periodeSampai) {

            Swal.fire({

                icon: 'warning',

                title: 'Periode Tidak Valid',

                text:
                    'Periode mulai tidak boleh lebih besar dari periode sampai.'

            });

            return false;

        }

        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | LOADING BUTTON
    |--------------------------------------------------------------------------
    */

    function setLoading(status)
    {

        const button =
            $('#btnFilter');


        if (status) {

            button
                .prop('disabled', true)
                .html(`
                    <span
                        class="spinner-border spinner-border-sm me-1">
                    </span>
                    Memuat...
                `);

            $('#tableRekap')
                .addClass('rekap-loading');

        }
        else {

            button
                .prop('disabled', false)
                .html(`
                    <i class="bi bi-search me-1"></i>
                    Tampilkan
                `);

            $('#tableRekap')
                .removeClass('rekap-loading');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD DATA
    |--------------------------------------------------------------------------
    */

    function loadData()
    {

        const filter =
            getFilter();


        console.log(
            'FILTER REKAP LPLPO:',
            filter
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI FRONTEND
        |--------------------------------------------------------------------------
        */

        if (!validatePeriod(filter)) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ABORT REQUEST SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        if (currentRequest) {

            currentRequest.abort();

        }


        setLoading(true);


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN LOADING TABLE
        |--------------------------------------------------------------------------
        */

        $('#tableRekap tbody').html(`

            <tr>

                <td
                    colspan="20"
                    class="text-center py-5 text-muted">

                    <div
                        class="spinner-border text-success mb-2">
                    </div>

                    <div>
                        Mengambil data rekap...
                    </div>

                </td>

            </tr>

        `);


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        currentRequest = $.ajax({

            url: dataUrl,

            method: 'GET',

            data: {

                bulan_mulai:
                    filter.bulan_mulai,

                tahun_mulai:
                    filter.tahun_mulai,

                bulan_sampai:
                    filter.bulan_sampai,

                tahun_sampai:
                    filter.tahun_sampai,

                kode_faskes:
                    filter.kode_faskes,

                /*
                |--------------------------------------------------------------------------
                | PENTING
                |--------------------------------------------------------------------------
                | Mencegah browser menggunakan response GET lama.
                |--------------------------------------------------------------------------
                */

                _:
                    new Date().getTime()

            },

            cache: false,

            dataType: 'json',

            success: function (response) {

                console.log(
                    'RESPONSE REKAP LPLPO:',
                    response
                );


                if (!response.success) {

                    showError(
                        response.message ??
                        'Data tidak dapat diproses.'
                    );

                    return;

                }


                renderHeader(
                    response
                );


                renderTable(
                    response.items ?? []
                );

            },

            error: function (
                xhr,
                status
            ) {

                /*
                |--------------------------------------------------------------------------
                | Request dibatalkan karena filter baru
                |--------------------------------------------------------------------------
                */

                if (status === 'abort') {

                    return;

                }


                console.error(
                    'ERROR REKAP:',
                    xhr.responseText
                );


                let message =
                    'Gagal mengambil data rekap LPLPO.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                showError(message);

            },

            complete: function () {

                setLoading(false);

                currentRequest = null;

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    function renderHeader(response)
    {

        const bulanMulaiText =
            $('#bulan_mulai option:selected')
                .text()
                .trim();

        const tahunMulai =
            $('#tahun_mulai')
                .val();


        const bulanSampaiText =
            $('#bulan_sampai option:selected')
                .text()
                .trim();

        const tahunSampai =
            $('#tahun_sampai')
                .val();


        $('#infoPeriode').html(`

            ${escapeHtml(bulanMulaiText)}
            ${escapeHtml(tahunMulai)}

            <span class="mx-2 text-muted">
                s/d
            </span>

            ${escapeHtml(bulanSampaiText)}
            ${escapeHtml(tahunSampai)}

        `);


        $('#infoJumlahLaporan')
            .text(
                number(
                    response.jumlah_laporan
                )
            );


        $('#infoJumlahItem')
            .text(
                number(
                    response.jumlah_item
                )
            );


        $('#jumlahItem')
            .text(
                number(
                    response.jumlah_item
                ) +
                ' Item'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER TABLE
    |--------------------------------------------------------------------------
    */

    function renderTable(items)
    {

        const tbody =
            $('#tableRekap tbody');


        tbody.empty();


        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA DATA
        |--------------------------------------------------------------------------
        */

        if (!items || !items.length) {

            tbody.html(`

                <tr>

                    <td
                        colspan="20"
                        class="text-center text-muted py-5">

                        <i
                            class="bi bi-inbox fs-1 d-block mb-2">
                        </i>

                        <strong>
                            Tidak ada data
                        </strong>

                        <div class="small mt-1">

                            Tidak ditemukan LPLPO FINAL
                            pada periode yang dipilih.

                        </div>

                    </td>

                </tr>

            `);

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | NOMOR
        |--------------------------------------------------------------------------
        */

        let no = 1;

        let lastProgram = null;


        /*
        |--------------------------------------------------------------------------
        | LOOP ITEM
        |--------------------------------------------------------------------------
        */

        items.forEach(function (item) {

            const program =
                item.program_name ??
                'Non Program';


            /*
            |--------------------------------------------------------------------------
            | PROGRAM HEADER
            |--------------------------------------------------------------------------
            */

            if (
                lastProgram !== program
            ) {

                tbody.append(`

                    <tr class="table-primary">

                        <td
                            colspan="20"
                            class="fw-bold">

                            <i
                                class="bi bi-folder2-open me-1">
                            </i>

                            ${escapeHtml(program)}

                        </td>

                    </tr>

                `);


                lastProgram =
                    program;

            }


            /*
            |--------------------------------------------------------------------------
            | DATA ROW
            |--------------------------------------------------------------------------
            */

            tbody.append(`

                <tr>

                    <td class="text-center fw-semibold">

                        ${no++}

                    </td>


                    <td>

                        ${escapeHtml(program)}

                    </td>


                    <td>

                        ${escapeHtml(
                            item.kode_obat ?? '-'
                        )}

                    </td>


                    <td>

                        ${escapeHtml(
                            item.nama_obat ?? '-'
                        )}

                    </td>


                    <td class="text-center">

                        ${escapeHtml(
                            item.satuan ?? '-'
                        )}

                    </td>


                    <!-- STOK AWAL -->

                    <td class="text-end">

                        ${number(
                            item.stok_awal_program_pkd
                        )}

                    </td>

                    <td class="text-end">

                        ${number(
                            item.stok_awal_jkn
                        )}

                    </td>


                    <!-- PENERIMAAN -->

                    <td class="text-end">

                        ${number(
                            item.penerimaan_program_pkd
                        )}

                    </td>

                    <td class="text-end">

                        ${number(
                            item.penerimaan_jkn
                        )}

                    </td>


                    <!-- PERSEDIAAN -->

                    <td class="text-end">

                        ${number(
                            item.persediaan_program_pkd
                        )}

                    </td>

                    <td class="text-end">

                        ${number(
                            item.persediaan_jkn
                        )}

                    </td>


                    <!-- PEMAKAIAN -->

                    <td class="text-end">

                        ${number(
                            item.pemakaian_program_pkd
                        )}

                    </td>

                    <td class="text-end">

                        ${number(
                            item.pemakaian_jkn
                        )}

                    </td>


                    <!-- EXPIRED -->

                    <td class="text-end">

                        ${number(
                            item.item_expired_pkd
                        )}

                    </td>

                    <td class="text-end">

                        ${number(
                            item.item_expired_jkn
                        )}

                    </td>


                    <!-- STOK AKHIR -->

                    <td class="text-end">

                        ${number(
                            item.stok_akhir_program_pkd
                        )}

                    </td>

                    <td class="text-end">

                        ${number(
                            item.stok_akhir_jkn
                        )}

                    </td>


                    <!-- PERMINTAAN -->

                    <td class="text-end fw-semibold">

                        ${number(
                            item.permintaan
                        )}

                    </td>


                    <!-- PEMBERIAN -->

                    <td class="text-end fw-semibold text-success">

                        ${number(
                            item.pemberian_program_pkd
                        )}

                    </td>

                    <td class="text-end fw-semibold text-success">

                        ${number(
                            item.pemberian_jkn
                        )}

                    </td>

                </tr>

            `);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER BUTTON
    |--------------------------------------------------------------------------
    */

    $('#btnFilter').on(
        'click',
        function () {

            loadData();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ENTER PADA FILTER
    |--------------------------------------------------------------------------
    */

    $('#bulan_mulai, #tahun_mulai, #bulan_sampai, #tahun_sampai, #kode_faskes')
        .on(
            'change',
            function () {

                // Tidak otomatis request.
                // User harus klik Tampilkan.

            }
        );


    /*
    |--------------------------------------------------------------------------
    | NUMBER FORMAT
    |--------------------------------------------------------------------------
    */

    function number(value)
    {

        const numeric =
            Number(value ?? 0);


        if (isNaN(numeric)) {

            return '0';

        }


        return new Intl.NumberFormat(
            'id-ID'
        ).format(numeric);

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value)
    {

        return $('<div>')
            .text(
                value ?? ''
            )
            .html();

    }


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    function showError(message)
    {

        $('#tableRekap tbody').html(`

            <tr>

                <td
                    colspan="20"
                    class="text-center text-danger py-5">

                    <i
                        class="bi bi-exclamation-triangle fs-2 d-block mb-2">
                    </i>

                    ${escapeHtml(message)}

                </td>

            </tr>

        `);


        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text: message

        });

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    loadData();

});

</script>

@endpush
