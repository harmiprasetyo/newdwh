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
            </tr>
                  <tr>
                <th>NIK</th>
                <th>:</th>
                <th>{{ $dt['PID']['nik'] }}</th>
                  </tr>

                  <tr>
                <th>Tgl Lahir</th>
                <th>:</th>
                <th>{{ $dt['PID']['birthdate'] }}</th>
                  </tr>
                  <tr>
                <th>Jenis Kelamin</th>
                <th>:</th>
                <th>{{ $dt['PID']['gender'] }}</th>
                  </tr>

                  <tr>
                <th>Tanggal Kunjungan</th>
                <th>:</th>
                <th>{{ $dt['ENC']['tglKunjungan'] }}</th>
                  </tr>

                   <tr>
                <th>Fasilitas Kesehatan</th>
                <th>:</th>
                <th>{{ $dt['ENC']['serviceProvider_name'] }}</th>
                  </tr>
                  <tr>
                    <th>Status G/P/A</th>
                    <th>: </th>
                    <th> G : {{ $dt['gravida'] }} &nbsp; P : {{ $dt['parity'] }} A: {{ $dt['abortions'] }}</th>
                  </tr>


        </thead>
        </table>

        </div>
        </div>


     </div>
    </div>




    <div class="row gx-5 mt-4">

          <div class="container">
    <div class="col">
       <div class="card">
         @include('partials.tabpasien')
        <div class="card-body" id="maincard">
            @if ($dt['INFO']['total']==0)
                Data Pemeriksaan Vital Sign Tidak ditemukan
                @else
                <table class="table">
                    <thead>
                        <tr>

                            @foreach ($dt['OBS'] as $n)
                            <th>{{ $n['param_name'] }}</th>
                            @endforeach

                        </tr>
                        <tr>
                             @foreach ($dt['OBS'] as $ndata)
                            <td>{{ $ndata['valueQty'] }} {{ $ndata['valueUnit'] }}</td>
                            @endforeach
                        </tr>
                    </thead>
                </table>
            @endif



        </div>


         <div class="card-body" id="layananUmum">
            @if ($dt['INFO']['total']==0)
                Data  Tidak ditemukan
                @else
                <table class="table" style="width: 1200px">
                    <thead>
                       <tr>
                        <th>Tekanan Darah</th>
                        <th>Suhu</th>
                        <th>Nadi</th>
                        <th>Pernapasan</th>
                        <th>Diagnosis</th>
                        <th>Tindakan</th>
                        <th>Laboratorium</th>
                        <th>Obat</th>
                        <th>Rencana Tindak Lanjut</th>
                        <th>Kondisi Saat Pulang</th>
                       </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $dt['sistole'] }} / {{ $dt['diastole'] }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><span>

                                  @foreach ($dt['lab'] as $key=>$val )
                                  {{  $val['label'] }}  : {{  $val['val'] }} </span><br>
                                @endforeach
                            </td>
                            <td>



                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @endif



        </div>

        <div class="card-body" id="layananAnc">
            <table class="table">
            <thead>
                <tr>
                    <th>Jarak Kehamilan</th>
                    <th>:</th>
                    <th>{{ $dt['anc_jarak_hamil'] }}</th>
                    <th></th>
                </tr>
                <tr>
                    <th>HPL</th>
                    <th>:</th>
                    <th>{{ $dt['anc_hpl'] }}</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Tinggi Badan</th>
                    <th>:</th>
                    <th>{{ $dt['anc_body_heigh'] }}</th>
                    <th></th>
                </tr>

                <tr>
                    <th>LILA</th>
                    <th>:</th>
                    <th>{{ $dt['anc_lila'] }}</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Status Imunisasi</th>
                    <th>:</th>
                    <th>-</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Skrining TBC</th>
                    <th>:</th>
                    <th>-</th>
                    <th></th>
                </tr>

                 <tr>
                    <th colspan="4" class="text-center"> <h2>Pemeriksaan Laboratorium</h2></th>
                </tr>

                <tr>
                    <th>HB</th>
                    <th>:</th>
                    <th>{{ $dt['lab']['lab_hb']['val'] }}</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Gol Darah</th>
                    <th>:</th>
                    <th>-</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Urin Protein</th>
                    <th>:</th>
                    <th>{{ $dt['lab']['lab_urin_protein']['val'] }}</th>
                    <th></th>
                </tr>


                <tr>
                    <th>Glukosa</th>
                    <th>:</th>
                    <th>{{ $dt['lab']['lab_gula_darah']['val'] }}</th>
                    <th></th>
                </tr>


                  <tr>
                    <th>HIV</th>
                    <th>:</th>
                    <th>{{ $dt['lab']['lab_hiv']['val'] }}</th>
                    <th></th>
                </tr>

                  <tr>
                    <th>Sifilis</th>
                    <th>:</th>
                    <th>-</th>
                    <th></th>
                </tr>

                  <tr>
                    <th>Hepatitis B</th>
                    <th>:</th>
                    <th>{{ $dt['lab']['lab_hepatitis_b']['val'] }}</th>
                    <th></th>
                </tr>


                  <tr>
                    <th>TBC</th>
                    <th>:</th>
                    <th>-</th>
                    <th></th>
                </tr>


                  <tr>
                    <th>Malaria</th>
                    <th>:</th>
                    <th>-</th>
                    <th></th>
                </tr>


            </thead>
            </table>


        </div>

       </div>


     </div>
          </div>
    </div>


</div>


<script>
    $(document).ready(function(){

        $('#home').removeClass('active');
$('#rme').addClass('active');

        $('#layananUmum').hide();
         $('#layananAnc').hide();
        $('#tab2').on('click',function(){
            $('#tab1').removeClass('active');
             $('#tab3').removeClass('active');
              $('#tab4').removeClass('active');
               $('#tab2').addClass('active');

               $('#layananUmum').show();
               $('#maincard').hide();
                $('#layananAnc').hide();



        })

         $('#tab1').on('click',function(){
            $('#tab1').addClass('active');
             $('#tab3').removeClass('active');
              $('#tab4').removeClass('active');
               $('#tab2').removeClass('active');

               $('#maincard').show();
                $('#layananUmum').hide();
                 $('#layananAnc').hide();


        })

         $('#tab3').on('click',function(){
            $('#tab3').addClass('active');
             $('#tab1').removeClass('active');
              $('#tab4').removeClass('active');
               $('#tab2').removeClass('active');

                 $('#layananAnc').show();
               $('#maincard').hide();
                $('#layananUmum').hide();



        })
    })
    </script>

@endsection
