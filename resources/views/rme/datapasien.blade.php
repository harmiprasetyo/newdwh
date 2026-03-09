@extends('layouts.mainrme')
@section('container')

<div class="container overflow-hidden mt-4">
  <div class="row gx-5 mt-4">
    <div class="col">
        <div class="card mt-4">
        <div class="card-body">
             <table class="table">
        <thead>
            <tr>

                <th>ID</th>
                <th>:</th>
                <th>{{ $dt['PID']['id'] }}</th>
            </tr>
            <tr>
                <th>Nama Pasien</th>
                <th>:</th>
                <th>{{ $dt['PID']['nama'] }}</th>
            <tr>
                  <tr>
                <th>NIK</th>
                <th>:</th>
                <th>{{ $dt['PID']['nik'] }}</th>
            <tr>

                  <tr>
                <th>Tgl Lahir</th>
                <th>:</th>
                <th>{{ $dt['PID']['birthdate'] }}</th>
            <tr>
                  <tr>
                <th>Jenis Kelamin</th>
                <th>:</th>
                <th>{{ $dt['PID']['gender'] }}</th>
            <tr>
        </thead>
        </table>

        </div>
        </div>


     </div>
    </div>






    <div>


        <table class="table table-dark" id="riwayatKunjungan">

            <thead class="text-center align-top">
                <tr><th>ID</th>
                    <th>Tanggal Kunjungan</th>
                    <th>Jenis Kunjungan</th>

                    <th>Fasilitas Kesehatan</th>
                     <th>Unit/Poli</th>
                     <th>Jenis Layanan</th>
                     <th>Dokter</th>
                     <th>&nbsp;</th>
                </tr>
            </thead>

                <tbody class="text-center align-top">

@foreach ($dt['ENC'] as $encount )
<TR><TD>{{ $loop->index+1 }}</TD>
    <TD>{{ $encount['tglKunjungan'] }}</TD>
    <TD>{{ $encount['tipe_kunjungan'] }} - {{ $encount['tipe_kunjungan_display'] }}</TD>
    <TD>{{ $encount['serviceProvider_name'] }}</TD>
    <TD>{{ $encount['unit_poli'] }}</TD>
    <TD>{{ $encount['jeniskunjungan_name'] }}</TD>
    <TD>{{ $encount['practitioner_name'] }}</TD>
    <td><button class="btn btn-secondary btn-outline-light btn-sm" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="getDetail('{{ $dt['PID']['nik'] }}','{{ $encount['id'] }}')">Detail</button></td>
</TR>

@endforeach

                   <tfoot>
                    <tr>
                        <th colspan="8">&nbsp;</th>

                    </tr>
                   </tfoot>





                </tbody>

        </table>


    </div>


        <div class="row gx-5 mt-4">
    <div class="col">
        <div class="table-responsive">

             <table class="table caption-top table-light" id="tbid">
            <caption>Jenis Layanan ANC
<pre><?php //print_r($dt); ?></pre>

            </caption>
            <thead class="text-center align-top" style="min-width: 900px;width:1020px">
                <tr>
                    <th rowspan="2" style="width: 200">Tanggal Kunjungan</th>
                     <th rowspan="2" style="width: 100">Jarak Kehamilan</th>
                      <th rowspan="2">Taksiran Persalinan</th>
                      <th rowspan="2">TB (cm)</th>
                       <th rowspan="2">LILA</th>
                       <th colspan="2">Skrining Imunisasi td</th>
                       <th rowspan="2">Skrining BC</th>
                       <th rowspan="2">skrining Wa</th>
                       <th colspan="10">Laboratorium</th>
                       <th rowspan="2">Konseling</th>
                       <th rowspan="2">Komplikasi</th>
                       <th rowspan="2">Tata Laksana Kasus ibu hamil</th>
                </tr>
                <tr>
                    <th>Status TD</th>
                    <th>Tanggal TD</th>

                     <th>Hasil Hb</th>
                    <th>Gol darah</th>
                     <th>Protein Urin</th>
                    <th>Glukosa urin</th>
                     <th>HIV</th>
                    <th>Sifilis</th>
                     <th>Hepatitis B</th>
                    <th>TBC</th>
                     <th>Pemeriksaan Malaria</th>
                    <th>Lainnya</th>
                </tr>
            </thead>
            <tbody>
                <td></td>
                <td></td>
                <td></td>

                <td></td>
                 <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tbody>

             </table>


        </div>
    </div>
        </div>

  </div>
</div>


<script>

    function getDetail(nik,id){

        window.location.href= '/datarme/detail?nik='+nik+'&idencounter='+id

    }
    $('document').ready(function(){
      /*  Swal.fire({
  title: 'Error!',
  text: 'Do you want to continue',
  icon: 'error',
  confirmButtonText: 'Cool'
})*/
$('#home').removeClass('active');
$('#rme').addClass('active');




//$('#tbid').DataTable();


})
    </script>
@endsection()
