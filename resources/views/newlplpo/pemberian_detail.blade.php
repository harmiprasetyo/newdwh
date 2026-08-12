@extends('newlplpo.layouts.master')

@section('title','Detail  LPLPO')

@section('content')

@include('newlplpo.partials.header_detail')

<div class="card shadow-sm mt-3">

    <div class="card-header bg-success text-white">

        <div class="d-flex justify-content-between">

            <h5 class="mb-0">
                Detail Item Obat
            </h5>

            <span class="badge bg-light text-dark">
                {{ $items->count() }} Item
            </span>

        </div>

    </div>

   <div class="card-body p-3">

        {{-- TAMBAH OBAT --}}

        @if($report->report_status != 'FINAL')

            <div class="mb-3 d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-1">
                        <i class="bi bi-capsule"></i>
                        Daftar Obat
                    </h6>

                    <small class="text-muted">
                        Tambahkan obat yang belum terdapat dalam laporan.
                    </small>

                </div>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnTambahObat">

                    <i class="bi bi-plus-circle me-1"></i>
                    Tambah Obat

                </button>

            </div>

        @endif


        <div class="table-responsive">

            <table class="table table-bordered table-hover table-sm mb-0">
                  <thead>

                <tr class="table-success text-center">

                    <th rowspan="2">No</th>
                    <th rowspan="2">Program</th>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Nama Obat</th>
                    <th rowspan="2">Sat</th>

                    <th colspan="2">Stok Awal</th>
                    <th colspan="2">Penerimaan</th>
                    <th colspan="2">Persediaan</th>
                    <th colspan="2">Pemakaian</th>

                    <th colspan="2">Expired</th>

                    <th colspan="2">Stok Akhir</th>

                    <th rowspan="2">Min</th>
                    <th rowspan="2">Opt</th>

                    <th rowspan="2">Permintaan</th>
                    <th colspan="2">Pemberian</th>
                     <th rowspan="2">Aksi</th>

                </tr>

                <tr class="table-success text-center">

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
@php
    $program = '';
@endphp

@foreach($items as $no => $item)

    @php
        $programName = optional($item->program)->program_name ?? 'Non Program';
    @endphp

    @if($program != $programName)

        @if(strtolower($programName) != 'non program')

            <tr class="table-primary">
                <td colspan="22">
                    <strong>{{ $programName }}</strong>
                </td>
            </tr>

        @endif

        @php
            $program = $programName;
        @endphp

    @endif

    @include('newlplpo.partials.row_item_pemberian')

@endforeach

</tbody>

            </table>

        </div>

    </div>

</div>


<!-- Button -->
<div class="card mt-3">

    <div class="card-body text-end">

        <button
            class="btn btn-success"
            id="btnFinal">

            <i class="bi bi-check-circle"></i>

            Selesaikan Pemberian

        </button>

    </div>

</div>



<!-- Modal -->
<div class="modal fade" id="modalPemberian" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="frmPemberian">

                @csrf

                <input type="hidden" id="item_id" name="item_id">

                <div class="modal-header bg-success text-white">

                    <h5 class="modal-title">

                        Input Pemberian Obat

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Nama Obat</label>

                        <input
                            type="text"
                            id="nama_obat"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <label>Pemberian PKD</label>

                            <input
                                type="number"
                                class="form-control"
                                id="pemberian_program_pkd"
                                name="pemberian_program_pkd">

                                <input
                                type="hidden"
                                class="form-control"
                                id="pemberian_jkn" value="0"
                                name="pemberian_jkn">

                        </div>

                      <!--  <div class="col-md-6">

                            <label>Pemberian JKN</label>

                            <input
                                type="number"
                                class="form-control"
                                id="pemberian_jkn"
                                name="pemberian_jkn">

                        </div> -->

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="button"
                        class="btn btn-success"
                        id="btnSavePemberian">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
<!-- End Modal -->


@if($report->report_status != 'FINAL')

<div class="modal fade"
     id="modalTambahObat"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form id="frmTambahObat">

                @csrf

                <input
                    type="hidden"
                    name="report_id"
                    value="{{ $report->id }}">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        <i class="bi bi-capsule me-2"></i>

                        Tambah Obat ke Laporan

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-info">

                        <i class="bi bi-info-circle me-1"></i>

                        Obat yang ditambahkan akan langsung masuk
                        ke laporan LPLPO ini.

                    </div>


                    {{-- OBAT --}}

                    <div class="mb-3">

                        <label class="form-label fw-bold">

                            Nama Obat

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="kode_obat"
                            id="tambah_kode_obat"
                            class="form-select"
                            required>

                            <option value="">
                                -- Pilih Obat --
                            </option>

                            @foreach($masterObats as $obat)

                                <option
                                    value="{{ $obat->kode_obat }}"
                                    data-nama="{{ $obat->nama_obat }}"
                                    data-satuan="{{ $obat->satuan }}">

                                    {{ $obat->nama_obat }}
                                    - {{ $obat->kode_obat }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- INFO OBAT --}}

                    <div
                        id="infoObatTambah"
                        class="alert alert-light border d-none">

                        <div class="row">

                            <div class="col-md-8">

                                <small class="text-muted">
                                    Nama Obat
                                </small>

                                <div
                                    id="infoNamaObat"
                                    class="fw-bold">
                                </div>

                            </div>

                            <div class="col-md-4">

                                <small class="text-muted">
                                    Satuan
                                </small>

                                <div
                                    id="infoSatuanObat"
                                    class="fw-bold">
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- PROGRAM --}}

                    <div class="mb-3">

                        <label class="form-label fw-bold">

                            Nama Program

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="program_id"
                            id="tambah_program_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Pilih Program --
                            </option>

                            @foreach($programs as $program)

                                <option value="{{ $program->id }}">

                                    {{ $program->program_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- JUMLAH PEMBERIAN --}}

                    <div class="mb-3">

                        <label class="form-label fw-bold">

                            Jumlah Pemberian

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="jumlah_pemberian"
                            id="jumlah_pemberian"
                            class="form-control form-control-lg"
                            min="1"
                            value="0"
                            required>

                        <small class="text-muted">

                            Jumlah akan dimasukkan sebagai
                            Pemberian PKD.

                        </small>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        <i class="bi bi-x-circle me-1"></i>

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnSimpanTambahObat">

                        <i class="bi bi-save me-1"></i>

                        Simpan Obat

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif

@endsection

@push('script')

<script>

$(function(){

    let updateUrl = "{{ route('newlplpo.pemberian.item.update', ':id') }}";

    let itemId = 0;

$(document).on('click','.btnPemberian',function(){

    itemId = $(this).data('id');

    $('#item_id').val(itemId);

    $('#nama_obat').val($(this).data('obat'));

    $('#pemberian_program_pkd').val($(this).data('pkd'));

    $('#pemberian_jkn').val($(this).data('jkn'));

    bootstrap.Modal
        .getOrCreateInstance(document.getElementById('modalPemberian'))
        .show();

});

$('#btnSavePemberian').click(function(){

    let url = updateUrl.replace(':id', itemId);

    $.ajax({

        url: url,

        type: 'POST',

        data: $('#frmPemberian').serialize(),

        success:function(){

            Swal.fire({

                icon:'success',

                title:'Berhasil'

            }).then(function(){

                location.reload();

            });

        },

        error:function(xhr){

            Swal.fire({

                icon:'error',

                title:'Gagal',

                text:xhr.responseJSON?.message ?? 'Terjadi kesalahan'

            });

        }

    });

});


$('#btnFinal').click(function(){

  $.ajax({

    url: "{{ route('newlplpo.pemberian.finish',$report->id) }}",

    type: "POST",

    data: {
        _token: $('meta[name=csrf-token]').attr('content')
    },

    success: function(res){

        console.log(res);

        Swal.fire({
            icon:'success',
            title:'Berhasil'
        }).then(function(){
            location.href="{{ route('newlplpo.pemberian.index') }}";
        });

    },

    error:function(xhr){

        console.log(xhr);

        Swal.fire({
            icon:'error',
            title:'Error',
            text:xhr.responseJSON?.message ?? xhr.statusText
        });

    }

});

});



  /*
    |--------------------------------------------------------------------------
    | Buka modal
    |--------------------------------------------------------------------------
    */

    $('#btnTambahObat').on('click', function () {

        $('#frmTambahObat')[0].reset();

        $('#infoObatTambah').addClass('d-none');

        bootstrap.Modal
            .getOrCreateInstance(
                document.getElementById('modalTambahObat')
            )
            .show();

    });


    /*
    |--------------------------------------------------------------------------
    | Pilih obat
    |--------------------------------------------------------------------------
    */

    $('#tambah_kode_obat').on('change', function () {

        let option = $(this).find(':selected');

        let nama = option.data('nama') || '';

        let satuan = option.data('satuan') || '';

        if (!nama) {

            $('#infoObatTambah')
                .addClass('d-none');

            return;

        }

        $('#infoNamaObat').text(nama);

        $('#infoSatuanObat').text(satuan);

        $('#infoObatTambah')
            .removeClass('d-none');

    });


    /*
    |--------------------------------------------------------------------------
    | Simpan
    |--------------------------------------------------------------------------
    */

    $('#frmTambahObat').on('submit', function (e) {

        e.preventDefault();

        let form = this;

        let btn = $('#btnSimpanTambahObat');

        let originalHtml = btn.html();

        btn.prop('disabled', true);

        btn.html(`
            <span class="spinner-border spinner-border-sm me-1"></span>
            Menyimpan...
        `);


        $.ajax({

            url: "{{ route('newlplpo.pemberian.tambah-obat', $report->id) }}",

            type: 'POST',

            data: $(form).serialize(),

            success: function (res) {

                bootstrap.Modal
                    .getOrCreateInstance(
                        document.getElementById('modalTambahObat')
                    )
                    .hide();

                Swal.fire({

                    icon: 'success',

                    title: 'Berhasil',

                    text: res.message,

                    timer: 1500,

                    showConfirmButton: false

                }).then(function () {

                    location.reload();

                });

            },

            error: function (xhr) {

                let message =
                    xhr.responseJSON?.message
                    ?? 'Data gagal disimpan.';

                /*
                |--------------------------------------------------------------------------
                | Validation Laravel
                |--------------------------------------------------------------------------
                */

                if (xhr.responseJSON?.errors) {

                    let errors = xhr.responseJSON.errors;

                    message = Object.values(errors)
                        .flat()
                        .join('\n');

                }

                Swal.fire({

                    icon: 'error',

                    title: 'Gagal',

                    text: message

                });

            },

            complete: function () {

                btn.prop('disabled', false);

                btn.html(originalHtml);

            }

        });

    });

});

</script>

@endpush
