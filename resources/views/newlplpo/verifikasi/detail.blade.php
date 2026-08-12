@extends('newlplpo.layouts.master')

@section('title','Verifikasi LPLPO')

@section('content')

@include('newlplpo.partials.header_detail')

<!--- Munculkan Laporan Input Kunjungan disini -->
@include('newlplpo.partials.kunjungan_verifikasi')
<!-- End Munculkan Laporan Input Kunjungan disini -->

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
                    <th rowspan="2">Pemberian</th>

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

                                <td colspan="20">

                                    <strong>{{ $programName }}</strong>

                                </td>

                            </tr>

                        @endif

                        @php
                            $program = $programName;
                        @endphp

                    @endif

                    @include('newlplpo.partials.row_item_verifikasi')

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="card mt-3">

    <div class="card-body text-end">

        <button class="btn btn-danger btnReject">

            <i class="bi bi-x-circle"></i>

            Tolak

        </button>

        <button class="btn btn-success btnApprove">

            <i class="bi bi-check-circle"></i>

            Terima

        </button>

    </div>

</div>

@endsection

@push('script')

<script>

$('.btnApprove').click(function(){

    Swal.fire({
        title:'Terima laporan?',
        icon:'question',
        showCancelButton:true
    }).then((r)=>{

        if(!r.isConfirmed) return;

        $.post(
            "{{ route('newlplpo.verifikasi.approve',$report->id) }}",
            {
                _token:$('meta[name=csrf-token]').attr('content')
            },
            function(){

                Swal.fire({
                    icon:'success',
                    title:'Berhasil'
                }).then(function(){

                    location.href="{{ route('newlplpo.verifikasi.index') }}";

                });

            }
        );

    });

});

$('.btnReject').click(function(){

    Swal.fire({

        title:'Alasan Penolakan',

        input:'textarea',

        inputPlaceholder:'Tuliskan alasan penolakan',

        showCancelButton:true,

        confirmButtonText:'Tolak'

    }).then((r)=>{

        if(!r.isConfirmed) return;

        $.post(

            "{{ route('newlplpo.verifikasi.reject',$report->id) }}",

            {

                _token:$('meta[name=csrf-token]').attr('content'),

                note:r.value

            },

            function(){

                Swal.fire({

                    icon:'success',

                    title:'Laporan ditolak'

                }).then(function(){

                    location.href="{{ route('newlplpo.verifikasi.index') }}";

                });

            }

        );

    });

});

</script>

@endpush
