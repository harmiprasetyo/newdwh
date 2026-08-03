@extends('newlplpo.layouts.master')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="bi bi-capsule me-2"></i>

                Master Data Obat

            </h4>

            <div class="text-muted">

                Kelola data obat LPLPO

            </div>

        </div>

        <button
            type="button"
            class="btn btn-primary"
            id="btnTambahObat">

            <i class="bi bi-plus-lg me-1"></i>

            Tambah Obat

        </button>

    </div>


    {{-- TABLE --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="datatableObat"
                    class="table table-hover align-middle w-100">

                    <thead>

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Kode Obat
                            </th>

                            <th>
                                Nama Obat
                            </th>

                            <th>
                                Satuan
                            </th>

                            <th>
                                Stok Minimum
                            </th>

                            <th>
                                Stok Optimum
                            </th>

                            <th width="120"
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

@endsection


@push('script')

<script>

$(document).ready(function () {

    const table = $('#datatableObat').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        pageLength: 10,

        ajax: {

            url:
                "{{ route('newlplpo.masterdataobat.datatable') }}",

            type: 'GET'

        },

        columns: [

            {
                data: 'DT_RowIndex',

                name: 'DT_RowIndex',

                orderable: false,

                searchable: false,

                className: 'text-center'
            },

            {
                data: 'kode_obat',

                name: 'kode_obat'
            },

            {
                data: 'nama_obat',

                name: 'nama_obat',

                render: function (data) {

                    return `

                        <div class="fw-semibold">

                            <i class="bi bi-capsule me-2 text-primary"></i>

                            ${escapeHtml(data)}

                        </div>

                    `;

                }
            },

            {
                data: 'satuan',

                name: 'satuan'
            },

            {
                data: 'stok_minimum',

                name: 'stok_minimum',

                className: 'text-center'
            },

            {
                data: 'stok_optimum',

                name: 'stok_optimum',

                className: 'text-center'
            },

            {
                data: 'aksi',

                name: 'aksi',

                orderable: false,

                searchable: false,

                className: 'text-center'
            }

        ],

        order: [

            [2, 'asc']

        ],

        language: {

            processing:
                'Memuat data...',

            search:
                'Cari:',

            searchPlaceholder:
                'Cari obat...',

            lengthMenu:
                '_MENU_ data',

            info:
                'Menampilkan _START_ - _END_ dari _TOTAL_ obat',

            infoEmpty:
                'Tidak ada obat',

            zeroRecords:
                'Obat tidak ditemukan',

            emptyTable:
                'Belum ada data obat',

            paginate: {

                previous:
                    '<i class="bi bi-chevron-left"></i>',

                next:
                    '<i class="bi bi-chevron-right"></i>'

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    $('#btnTambahObat').on(
        'click',
        function () {

            console.log(
                'Tambah obat'
            );

            // Modal CRUD akan kita masukkan berikutnya

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $('#datatableObat').on(
        'click',
        '.btn-edit-obat',
        function () {

            const id =
                $(this).data('id');

            console.log(
                'Edit obat:',
                id
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $('#datatableObat').on(
        'click',
        '.btn-delete-obat',
        function () {

            const id =
                $(this).data('id');

            const nama =
                $(this).data('nama');

            console.log(
                'Delete obat:',
                id,
                nama
            );

        }
    );


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

});

</script>

@endpush
