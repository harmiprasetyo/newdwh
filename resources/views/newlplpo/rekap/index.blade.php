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

                Rekapitulasi LPLPO yang telah selesai

            </div>

        </div>

    </div>


    {{-- ==========================================================
         FILTER
    =========================================================== --}}

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-body">

            <div class="row g-3 align-items-end">

                {{-- BULAN --}}

                <div class="col-md-3">

                    <label class="form-label fw-semibold">

                        Bulan

                    </label>

                    <select
                        id="bulan"
                        class="form-select">

                        @foreach(range(1,12) as $i)

                            <option
                                value="{{ $i }}"
                                {{ $bulan == $i ? 'selected' : '' }}>

                                {{ \Carbon\Carbon::create()
                                    ->month($i)
                                    ->translatedFormat('F') }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- TAHUN --}}

                <div class="col-md-3">

                    <label class="form-label fw-semibold">

                        Tahun

                    </label>

                    <select
                        id="tahun"
                        class="form-select">

                        @for(
                            $y = now()->year - 5;
                            $y <= now()->year + 1;
                            $y++
                        )

                            <option
                                value="{{ $y }}"
                                {{ $tahun == $y ? 'selected' : '' }}>

                                {{ $y }}

                            </option>

                        @endfor

                    </select>

                </div>


                {{-- FASKES KHUSUS DINKES --}}

                @if($groupId == 2)

                    <div class="col-md-4">

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


                <div class="col-md-2">

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
         INFO
    =========================================================== --}}

    <div
        class="card border-0 shadow-sm mb-3"
        id="reportHeader">

        <div class="card-header bg-success text-white">

            <div class="d-flex justify-content-between">

                <strong>

                    Rekap Laporan LPLPO

                </strong>

                <strong>

                    FINAL

                </strong>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <strong>Bulan</strong>

                    <div id="infoBulan">
                        -
                    </div>

                </div>

                <div class="col-md-4">

                    <strong>Tahun</strong>

                    <div id="infoTahun">
                        -
                    </div>

                </div>

                <div class="col-md-4">

                    <strong>Jumlah Laporan</strong>

                    <div id="infoJumlahLaporan">
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

            <div class="d-flex justify-content-between">

                <strong>

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
                    class="table table-bordered table-sm align-middle mb-0"
                    id="tableRekap">

                    <thead class="table-success">

                        <tr>

                            <th rowspan="2"
                                class="text-center">

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

                            <th colspan="2"
                                class="text-center">

                                Stok Awal

                            </th>

                            <th colspan="2"
                                class="text-center">

                                Penerimaan

                            </th>

                            <th colspan="2"
                                class="text-center">

                                Persediaan

                            </th>

                            <th colspan="2"
                                class="text-center">

                                Pemakaian

                            </th>

                            <th rowspan="2">

                                Expired

                            </th>

                            <th colspan="2"
                                class="text-center">

                                Stok Akhir

                            </th>

                            <th rowspan="2">

                                Permintaan

                            </th>

                            <th rowspan="2">

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

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td
                                colspan="18"
                                class="text-center text-muted py-4">

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

}

.table-success th {

    background-color: #d1e7dd !important;

}

@media print {

    .sidebar,
    .navbar,
    .btn,
    #filter {

        display: none !important;

    }

    .card {

        box-shadow: none !important;

        border: 1px solid #ddd !important;

    }

}

</style>

@endpush


@push('script')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    */

    const dataUrl =
        "{{ route('newlplpo.rekap.data') }}";


    /*
    |--------------------------------------------------------------------------
    | LOAD DATA
    |--------------------------------------------------------------------------
    */

    function loadData()
    {

        const bulan =
            $('#bulan').val();

        const tahun =
            $('#tahun').val();

        const kodeFaskes =
            $('#kode_faskes').length
                ? $('#kode_faskes').val()
                : '';


        $('#btnFilter')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Memuat...'
            );


        $.ajax({

            url: dataUrl,

            type: 'GET',

            data: {

                bulan: bulan,

                tahun: tahun,

                kode_faskes:
                    kodeFaskes

            },

            dataType: 'json',

            success: function (response) {

                renderHeader(response);

                renderTable(response.items || []);

            },

            error: function (xhr) {

                console.error(
                    'Rekap LPLPO:',
                    xhr.responseText
                );


                Swal.fire({

                    icon: 'error',

                    title: 'Gagal',

                    text:
                        xhr.responseJSON?.message ??
                        'Gagal mengambil data rekap LPLPO.'

                });

            },

            complete: function () {

                $('#btnFilter')
                    .prop('disabled', false)
                    .html(
                        '<i class="bi bi-search me-1"></i> Tampilkan'
                    );

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

        const bulan =
            $('#bulan option:selected').text();

        $('#infoBulan')
            .text(bulan);

        $('#infoTahun')
            .text(response.tahun);

        $('#infoJumlahLaporan')
            .text(response.jumlah_laporan);

        $('#jumlahItem')
            .text(
                (response.items?.length ?? 0) +
                ' Item'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    function renderTable(items)
    {

        const tbody =
            $('#tableRekap tbody');

        tbody.empty();


        if (!items.length) {

            tbody.html(`

                <tr>

                    <td
                        colspan="18"
                        class="text-center text-muted py-5">

                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                        Tidak ada LPLPO FINAL
                        pada periode yang dipilih.

                    </td>

                </tr>

            `);

            return;

        }


        let no = 1;

        let lastProgram = null;


        items.forEach(function (item) {

            /*
            |--------------------------------------------------------------------------
            | PROGRAM HEADER
            |--------------------------------------------------------------------------
            */

            if (
                lastProgram !== item.program_name
            ) {

                tbody.append(`

                    <tr class="table-primary">

                        <td
                            colspan="18"
                            class="fw-bold">

                            ${escapeHtml(
                                item.program_name ??
                                'Non Program'
                            )}

                        </td>

                    </tr>

                `);

                lastProgram =
                    item.program_name;

            }


            tbody.append(`

                <tr>

                    <td class="text-center">
                        ${no++}
                    </td>

                    <td>
                        ${escapeHtml(
                            item.program_name ??
                            'Non Program'
                        )}
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

                    <td>
                        ${escapeHtml(
                            item.satuan ?? '-'
                        )}
                    </td>

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

                    <td class="text-end fw-semibold">
                        ${number(
                            item.pemakaian_program_pkd
                        )}
                    </td>

                    <td class="text-end fw-semibold">
                        ${number(
                            item.pemakaian_jkn
                        )}
                    </td>

                    <td class="text-end">
                        ${number(
                            item.item_expired
                        )}
                    </td>

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

                    <td class="text-end fw-semibold">
                        ${number(
                            item.permintaan
                        )}
                    </td>

                    <td class="text-end text-success fw-semibold">
                        ${number(
                            item.pemberian_program_pkd
                        )}
                    </td>

                </tr>

            `);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    $('#btnFilter').on(
        'click',
        function () {

            loadData();

        }
    );


    $('#bulan, #tahun').on(
        'change',
        function () {

            loadData();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | NUMBER
    |--------------------------------------------------------------------------
    */

    function number(value)
    {

        return new Intl.NumberFormat(
            'id-ID'
        ).format(
            Number(value || 0)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value)
    {

        return $('<div>')
            .text(value ?? '')
            .html();

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
