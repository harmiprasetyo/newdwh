@extends('layouts.mainrme')
@section('container')

<div class="container overflow-hidden mt-4">
  <div class="row gx-5 mt-4 mb-2">
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

                <tr>
                <th>No. Telp</th>
                <th>:</th>
                <th>{{ $dt['PID']['phone'] }}</th>
            <tr>


                <tr>
                  <tr>
                <th>Alamat</th>
                <th>:</th>
                <th>{{ @implode(", ",$dt['PID']['address']) }}</th>
            <tr>

        </thead>
        </table>

        </div>
        </div>


     </div>
    </div>


<!-- Start Tab Menu -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active"  id="tab1" aria-current="page" >Riwayat Kunjungan</a>
  </li>
  @if($dt['PID']['gender']=="female")
  <li class="nav-item">
    <a class="nav-link" id="tab2" >Resume Layanan ANC</a>
  </li>
  @endif
</ul>
<!-- end Tab Menu -->


<!--- Tab History kunjungan -->
    <div id="history">


        <table class="table table-dark" id="riwayatKunjungan">
         <!--   <thead>
                   <tr><td colspan="8"><pre>
                    <?php print_r($dt['ENCOUNTER']); ?>

                    </pre></td></tr>
            </thead> -->

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
    <TD>{{ $encount['tipe_kunjungan'] }}</TD>
    <TD>{{ $encount['serviceProvider_name'] }}</TD>
    <TD>{{ $encount['unit_poli'] }}</TD>
    <TD>{{ $encount['jeniskunjungan_name'] }} ( {{ $encount['kunjunganANC'] }})</TD>
    <TD>{{ $encount['practitioner_name'] }} </TD>
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

    <!-- END of History Kunjungan -->


<!-- Start Resume Kunjungan ANC -->

<div id="kunjunganANC" style="display: none">

      <table class="table table-light" id="riwayatKunjungan">
        <thead>
            <tr>
                <th>Jumlah Kunjungan Trimester Pertama </th><th>  kali</th>
            </tr>
            <tr>
                <th>Jumlah Kunjungan Trimester Kedua </th><th>  kali</th>
            </tr>
            <tr>
                <th>Jumlah Kunjungan Trimester Ketiga </th><th>  kali</th>
            </tr>
            <tr>
                <th>Pemeriksaan USG oleh dr/dr. SPOG pada Trimester pertama </th><th>  kali</th>
            </tr>
             <tr>
                <th>Pemeriksaan USG oleh dr/dr. SPOG pada Trimester kedua </th><th>  kali</th>
            </tr>
        </thead>
      </table>

</div>
<!-- End Kunjungan ANC -->


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



 $('#tab2').on('click',function(){
            $('#tab2').addClass('active');
            $('#tab1').removeClass('active');

               $('#kunjunganANC').show();
                $('#history').hide();

        })

         $('#tab1').on('click',function(){



               $('#history').show();


            $('#tab1').addClass('active');
            $('#tab2').removeClass('active');
            $('#kunjunganANC').hide();



        })



//$('#tbid').DataTable();


})
    </script>
@endsection()
