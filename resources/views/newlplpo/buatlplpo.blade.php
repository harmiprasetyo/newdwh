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



@if($report->report_status == 'DRAFT')

    <form
        method="POST"
        action="{{ route('newlplpo.update', $report->id) }}"
        id="frmSubmit">

        @csrf
        @method('PUT')

        <input
            type="hidden"
            name="report_status"
            value="SUBMITED">

        <button
            type="submit"
            class="btn btn-success"
            id="btnKirimLaporan"
            {{ !$report->kunjungan ? 'disabled' : '' }}>

            <i class="bi bi-send"></i>
            Kirim Laporan

        </button>

    </form>

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

                @if($report->report_status == 'DRAFT')

    {{-- TAMBAH OBAT --}}
    <button
        type="button"
        class="btn btn-success"
        id="btnTambah"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasObat">

        <i class="bi bi-capsule"></i>
        Tambah Obat

    </button>


    {{-- INPUT KUNJUNGAN --}}
    @if($report->kunjungan)

        <a
            href="{{ route('newlplpo.kunjungan.edit', $report->id) }}"
            class="btn btn-warning">

            <i class="bi bi-pencil-square"></i>
            Edit Kunjungan

        </a>

    @else

        <a
            href="{{ route('newlplpo.kunjungan.create', $report->id) }}"
            class="btn btn-primary">

            <i class="bi bi-people-fill"></i>
            Input Kunjungan

        </a>

    @endif


    {{-- IMPORT --}}
  <!--  <button
        type="button"
        class="btn btn-warning">

        <i class="bi bi-file-earmark-excel"></i>
        Import Excel

    </button>


    {{-- EXPORT --}}
    <button
        type="button"
        class="btn btn-info">

        <i class="bi bi-download"></i>
        Export Excel

    </button> -->

@endif

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
     <th rowspan="2">Obat Esensial</th>

    <th colspan="2">Stok Awal</th>
    <th colspan="2">Penerimaan</th>
    <th colspan="2">Persediaan</th>
    <th colspan="2">Pengeluaran</th>
    <th colspan="2">Expired/Retur</th>
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

    @include('newlplpo.partials.row_item')

@endforeach

</tbody>
            </table>

          <script>

document.addEventListener('click', function (e) {

    const btn = e.target.closest('.btnEditItem');

    if (!btn) {
        return;
    }

    e.preventDefault();

    const id = btn.dataset.id;

    console.log('EDIT ITEM ID:', id);


    /*
    |--------------------------------------------------------------------------
    | URL EDIT
    |--------------------------------------------------------------------------
    */

    const url =
        "{{ url('newlplpo/item') }}/" + id + "/edit";


    /*
    |--------------------------------------------------------------------------
    | REQUEST DATA
    |--------------------------------------------------------------------------
    */

    fetch(url, {

        method: 'GET',

        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }

    })

    .then(async response => {

        const text = await response.text();

        console.log('STATUS:', response.status);
        console.log('RESPONSE:', text);


        if (!response.ok) {

            throw new Error(
                'HTTP ' +
                response.status +
                ': ' +
                text
            );

        }


        return JSON.parse(text);

    })


    /*
    |--------------------------------------------------------------------------
    | DATA BERHASIL DIDAPAT
    |--------------------------------------------------------------------------
    */

   .then(res => {

    console.log('=================================');
    console.log('DATA EDIT FULL');
    console.log(res);
    console.log('=================================');

    if (!res.success) {
        Swal.fire(
            'Error',
            'Data item tidak ditemukan.',
            'error'
        );
        return;
    }

    const item = res.data;

    console.table({
        id: item.id,

        program_id: item.program_id,
        program_name: item.program_name,

        kode_obat: item.kode_obat,
        nama_obat: item.nama_obat,
        satuan: item.satuan,

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

    // lanjutkan kode yang sekarang...

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ID EDIT
        |--------------------------------------------------------------------------
        */

        const form =
            document.getElementById('frmItem');


        form.dataset.editId = item.id;


        /*
        |--------------------------------------------------------------------------
        | ISI FORM
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
        | PROGRAM SELECT
        |--------------------------------------------------------------------------
        */

        const program =
            document.getElementById('program_id');

        if (program) {

            program.value =
                item.program_id ?? '';

            program.dispatchEvent(
                new Event('change', {
                    bubbles: true
                })
            );

        }


        /*
        |--------------------------------------------------------------------------
        | UBAH BUTTON
        |--------------------------------------------------------------------------
        */

        const saveButton =
            document.getElementById('btnSaveItem');


        if (saveButton) {

            saveButton.innerHTML =
                '<i class="bi bi-save"></i> Update Item';

            saveButton.dataset.mode =
                'edit';

        }


        /*
        |--------------------------------------------------------------------------
        | BUKA OFFCANVAS
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
            bootstrap.Offcanvas.getOrCreateInstance(
                offcanvasElement
            );


        offcanvas.show();

    })


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    .catch(error => {

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

    });

});


/*
|--------------------------------------------------------------------------
| HELPER SET VALUE
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


        </div>

    </div>

</div>

@endif

@endsection


@if(isset($report))



@push('styles')
<style>
.row-napza {
    background-color: #ffe4ef !important;
}

.row-napza td {
    background-color: #ffe4ef !important;
}
</style>
@endpush

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

$(document).on('click', '#btnEditHeader', function () {

    $('select[name="bulan"]').prop('disabled', false);

    $('input[name="tahun"]').prop('readonly', false);

    $(this)
        .removeClass('btn-warning')
        .addClass('btn-success')
        .html(`
            <i class="bi bi-check-circle"></i>
            Simpan Header
        `)
        .attr('id', 'btnSaveHeader');

});


$(document).on('click', '#btnSaveHeader', function () {

    let btn = $(this);

    let bulan = $('select[name="bulan"]').val();
    let tahun = $('input[name="tahun"]').val();

    let reportId = $('#report_id').val();

    console.log({
        report_id: reportId,
        bulan: bulan,
        tahun: tahun
    });


    $.ajax({

        url: "{{ route('newlplpo.update', $report->id) }}",

        type: "PUT",

        data: {

            _token: "{{ csrf_token() }}",

            bulan: bulan,

            tahun: tahun

        },

        beforeSend: function () {

            btn
                .prop('disabled', true)
                .html(`
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Menyimpan...
                `);

        },

        success: function (response) {

            console.log(response);


            if (response.success) {

                // Kunci kembali field

                $('select[name="bulan"]')
                    .prop('disabled', true);

                $('input[name="tahun"]')
                    .prop('readonly', true);


                // Kembalikan tombol menjadi Edit Header

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

                    text: response.message ?? 'Header berhasil diperbarui.',

                    timer: 1500,

                    showConfirmButton: false

                });

            }

        },

        error: function (xhr) {

            console.error(xhr.responseText);


            btn
                .prop('disabled', false)
                .html(`
                    <i class="bi bi-check-circle"></i>
                    Simpan Header
                `);


            let message = 'Gagal menyimpan perubahan.';


            if (xhr.responseJSON?.message) {

                message = xhr.responseJSON.message;

            }


            if (xhr.status === 422 && xhr.responseJSON?.errors) {

                let errors = xhr.responseJSON.errors;

                message = Object.values(errors)
                    .flat()
                    .join('<br>');

            }


            Swal.fire({

                icon: 'error',

                title: 'Gagal',

                html: message

            });

        }

    });

});



    </script>

@endpush

@endif
