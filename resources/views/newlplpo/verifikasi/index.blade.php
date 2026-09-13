@extends('newlplpo.layouts.master')

@section('title','Verifikasi LPLPO')

@section('content')

<div class="card shadow-sm">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="card-header bg-success text-white">

        <h4 class="mb-0">
            Verifikasi Laporan LPLPO
        </h4>

    </div>


    <div class="card-body">

        {{-- =====================================================
             FILTER
        ====================================================== --}}
        <div class="row mb-3">

            {{-- BULAN --}}
            <div class="col-md-2">

                <select
                    id="bulan"
                    class="form-select">

                    @for($i = 1; $i <= 12; $i++)

                        <option
                            value="{{ $i }}"
                            {{ $i == date('n') ? 'selected' : '' }}>

                            {{ bulan($i) }}

                        </option>

                    @endfor

                </select>

            </div>


            {{-- TAHUN --}}
            <div class="col-md-2">

                <select
                    id="tahun"
                    class="form-select">

                    @for($i = date('Y'); $i >= 2023; $i--)

                        <option
                            value="{{ $i }}"
                            {{ $i == date('Y') ? 'selected' : '' }}>

                            {{ $i }}

                        </option>

                    @endfor

                </select>

            </div>

        </div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}
        <div class="table-responsive">

            <table
                id="tblVerification"
                class="table table-bordered table-hover table-striped w-100">

                <thead class="table-success">

                    <tr>

                        <th width="50">
                            No
                        </th>

                        <th width="140">
                            Tanggal
                        </th>

                        <th width="300">
                            Nama Faskes
                        </th>

                        <th width="80">
                            Bulan
                        </th>

                        <th width="80">
                            Tahun
                        </th>

                        <th width="90">
                            Total Item
                        </th>

                        <th width="120">
                            Status
                        </th>

                        <th width="130">
                            QR Approval
                        </th>

                        <th width="80">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>
                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


@push('script')

{{-- =============================================================
     QR CODE LIBRARY
============================================================= --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>


<script>

$(function () {

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    let table = $('#tblVerification').DataTable({

        destroy: true,

        processing: true,

        serverSide: false,

        responsive: false,

        scrollX: true,

        scrollCollapse: true,

        autoWidth: false,

        pageLength: 10,


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        ajax: {

            url: "{{ route('newlplpo.verifikasi.datatable') }}",

            data: function (d) {

                d.bulan = $('#bulan').val();

                d.tahun = $('#tahun').val();

            }

        },


        /*
        |--------------------------------------------------------------------------
        | COLUMN DEFINITIONS
        |--------------------------------------------------------------------------
        */

        columnDefs: [

            {
                targets: 0,

                width: "50px",

                className: "text-center"

            },

            {
                targets: 1,

                width: "140px"

            },

            {
                targets: 2,

                width: "300px"

            },

            {
                targets: 3,

                width: "80px",

                className: "text-center"

            },

            {
                targets: 4,

                width: "80px",

                className: "text-center"

            },

            {
                targets: 5,

                width: "90px",

                className: "text-center"

            },

            {
                targets: 6,

                width: "120px",

                className: "text-center"

            },

            {
                targets: 7,

                width: "130px",

                className: "text-center",

                orderable: false,

                searchable: false

            },

            {
                targets: 8,

                width: "80px",

                className: "text-center",

                orderable: false,

                searchable: false

            }

        ],


        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        columns: [

            {
                data: 'DT_RowIndex',

                name: 'DT_RowIndex',

                orderable: false,

                searchable: false

            },

            {
                data: 'created_at',

                name: 'created_at'

            },

            {
                data: 'nama_faskes',

                name: 'namaFaskes',

                defaultContent: '-'

            },

            {
                data: 'bulan',

                name: 'bulan'

            },

            {
                data: 'tahun',

                name: 'tahun'

            },

            {
                data: 'items_count',

                name: 'items_count',

                orderable: false,

                searchable: false,

                className: 'text-center'

            },

            {
                data: 'status_badge',

                name: 'status_badge',

                orderable: false,

                searchable: false,

                className: 'text-center'

            },

            {
                data: 'qr_code',

                name: 'qr_code',

                orderable: false,

                searchable: false,

                className: 'text-center'

            },

            {
                data: 'action',

                name: 'action',

                orderable: false,

                searchable: false,

                className: 'text-center'

            }

        ]

    });


    /*
    |--------------------------------------------------------------------------
    | GENERATE QR CODE
    |--------------------------------------------------------------------------
    |
    | QR dibuat setelah DataTables selesai melakukan render.
    |
    */

    table.on('draw', function () {

        $('.qr-code').each(function () {

            const element = this;

            const url = $(element).attr('data-url');


            /*
            |--------------------------------------------------------------------------
            | Jika URL tidak tersedia
            |--------------------------------------------------------------------------
            */

            if (!url) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Bersihkan QR sebelumnya
            |--------------------------------------------------------------------------
            */

            $(element).empty();


            /*
            |--------------------------------------------------------------------------
            | Generate QR
            |--------------------------------------------------------------------------
            */

            new QRCode(element, {

                text: url,

                width: 90,

                height: 90,

                correctLevel: QRCode.CorrectLevel.M

            });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | FILTER BULAN / TAHUN
    |--------------------------------------------------------------------------
    */

    $('#bulan, #tahun').on('change', function () {

        table.ajax.reload();

    });

});

</script>

@endpush