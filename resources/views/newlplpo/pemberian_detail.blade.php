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

    <div class="card-body p-0">

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

                    <th rowspan="2">Expired</th>

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

                </tr>

                </thead>

                <tbody>

@php
    $program = '';
    $no = 1;
@endphp

@foreach($items as $item)

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

                        </div>

                        <div class="col-md-6">

                            <label>Pemberian JKN</label>

                            <input
                                type="number"
                                class="form-control"
                                id="pemberian_jkn"
                                name="pemberian_jkn">

                        </div>

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

});

</script>

@endpush
