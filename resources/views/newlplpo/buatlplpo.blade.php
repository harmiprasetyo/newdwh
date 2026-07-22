@extends('newlplpo.layouts.master')

@section('title','Membuat LPLPO')

@section('content')

<form method="POST"
      action="{{ isset($report)
            ? route('newlplpo.update',$report->id)
            : route('newlplpo.store') }}">

    @csrf

    @isset($report)
        @method('PUT')
    @endisset

    <input type="hidden"
           name="kode_faskes"
           value="{{ $faskes->kodeFaskes }}">

    <input type="hidden"
           name="nama_faskes"
           value="{{ $faskes->namaFaskes }}">

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-success text-white">

            <h4 class="text-center mb-0">
                LAPORAN PEMAKAIAN DAN LEMBAR PERMINTAAN OBAT (LPLPO)
            </h4>

        </div>

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

        <div class="card-footer text-end">

@if(isset($report))



 @if($report->report_status=='DRAFT')

            <button type="button" class="btn btn-success" id="editBTN">

                Edit Header

            </button>

       <form
    method="POST"
    action="{{ route('newlplpo.update',$report->id) }}"
    id="frmSubmit">

    @csrf
    @method('PUT')

    <input type="hidden"
           name="report_status"
           value="SUBMITED">

</form>
<button
        type="button"
        class="btn btn-primary" id="btnSubmit">

        <i class="bi bi-send"></i>
        Kirim Laporan

    </button>
        @endif

        @else
        <button class="btn btn-success" id="btnHeader">

                Buat Laporan

            </button>

        @endif


        </div>

    </div>

</form>


@if(isset($report))

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

        <div class="row mb-3">

            <div class="col-md-8">

                @if($report->report_status=='DRAFT')



               <button
    type="button"
    class="btn btn-success"
    id="btnTambah"
    data-bs-toggle="offcanvas"
    data-bs-target="#offcanvasObat">

    Tambah Obat

</button>


@endif

                <button
                    class="btn btn-warning">

                    Import Excel

                </button>

                <button
                    class="btn btn-info">

                    Export Excel

                </button>

            </div>

            <div class="col-md-4">

                <input
                    class="form-control"
                    placeholder="Cari Item...">

            </div>

        </div>


        <div class="table-responsive table-lplpo">

            <table
                class="table table-bordered table-hover align-middle"
                id="tblItem">

               <thead class="table-success text-center align-middle">

<tr>
    <th rowspan="2">No</th>
    <th rowspan="2">Kode Obat</th>
    <th rowspan="2">Nama Barang</th>
    <th rowspan="2">Satuan</th>

    <th colspan="2">Stok Awal</th>
    <th colspan="2">Penerimaan</th>
    <th colspan="2">Persediaan</th>
    <th colspan="3">Pengeluaran</th>
    <th colspan="2">Stok Akhir</th>

    <th rowspan="2">Stok Minimum</th>
    <th rowspan="2">Stok Optimum</th>
    <th rowspan="2">Permintaan</th>
    <th colspan="2">Pemberian</th>
    <th width="100" rowspan="2" class="text-center">
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
    <th>ED/Retur</th>

    <th>PKD</th>
    <th>JKN</th>
    <th>PKD</th>
    <th>JKN</th>
</tr>

</thead>

 <tbody>

@foreach($items as $no => $item)

    @include('newlplpo.partials.row_item')

@endforeach

</tbody>
            </table>

        </div>

    </div>

</div>

@endif

@endsection


@if(isset($report))





@push('offcanvas')
@include('newlplpo.partials.offcanvas_masterobat')
@endpush

@push('script')
@include('newlplpo.partials.scripts_masterobat')
@endpush

@push('script')
<script>


   $('#btnSubmit').click(function () {

    Swal.fire({
        title: 'Submit Laporan?',
        text: 'Setelah disubmit laporan tidak dapat diubah lagi.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Submit',
        cancelButtonText: 'Batal'
    }).then(function(result){

        if(!result.isConfirmed){
            return;
        }

        $.ajax({

            url: "{{ route('newlplpo.update',$report->id) }}",

            type: "POST",

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "PUT",
                report_status: "SUBMITED"
            },

            success:function(){

                Swal.fire({
                    icon:'success',
                    title:'Berhasil',
                    text:'Laporan berhasil disubmit'
                }).then(function(){

                    location.reload();

                });

            },

            error:function(xhr){

                Swal.fire({
                    icon:'error',
                    title:'Gagal',
                    text:xhr.responseJSON?.message ??
                         'Terjadi kesalahan'
                });

            }

        });

    });

});
    </script>

@endpush

@endif
