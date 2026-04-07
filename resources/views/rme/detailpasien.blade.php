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
                    <th> G : {{ $dt['ANC']['gravida'] }} &nbsp; P : {{ $dt['ANC']['parity'] }} A: {{ $dt['ANC']['abortions'] }}</th>
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

                                @if (isset($dt['lab']))



                                  @foreach ($dt['lab'] as $key=>$val )
                                  {{  $val['label'] }}  : {{  $val['val'] }} </span><br>
                                @endforeach

                                     @endif
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
                    <th>
                    {{ $dt['anc_hpl'] }}</th>
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
                    <th>
                        @if(isset($dt['lab']))
                        {{ $dt['lab']['lab_hb']['val'] }}
                    @endif
                    </th>
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
                    <th>
                          @if(isset($dt['lab']))
                        {{ $dt['lab']['lab_urin_protein']['val'] }}
                    @endif
                    </th>
                    <th></th>
                </tr>


                <tr>
                    <th>Glukosa</th>
                    <th>:</th>


                    <th>
                        @if(isset($dt['lab']))

                        {{ $dt['lab']['lab_gula_darah']['val'] }}
                        @endif


                    </th>

                        <th></th>
                </tr>


                  <tr>
                    <th>HIV</th>
                    <th>:</th>
                    <th>
                        @if(isset($dt['lab']))

                        {{ $dt['lab']['lab_hiv']['val'] }}
                    @endif
                    </th>
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
                    <th>
                          @if(isset($dt['lab']))
                        {{ $dt['lab']['lab_hepatitis_b']['val'] }}

                    @endif
                    </th>
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
            <tbody>
                <tr>
                <td colspan="4">

 <table class="table table-light">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                       @foreach($dt['label']['bln'] as $key=>$label)
                       <td>{{ $label }}</td>
                       @endforeach
                    </tr>
                   <tr>
                    <td>Tanggal Kunjungan</td>
                    @foreach($dt['label']['bln'] as $key=>$label)
                       <td>
 @if(isset($dt['KOHORT']))
                        @foreach($dt['KOHORT'] as $k=>$v)
                         @if($v['anc_bulan']==$key)

                        {{ \Carbon\Carbon::parse($v['anc_kunjungan'])->format('d M Y') }}<br>

                        @endif
                        @endforeach
                        @endif

                       </td>
                       @endforeach

                   </tr>
                   <tr>
                    <td>Jenis Kunjungan</td>
                    @foreach($dt['label']['bln'] as $key=>$label)
                       <td>

 @if(isset($dt['KOHORT']))
                     @foreach($dt['KOHORT'] as $k=>$v)
                         @if($v['anc_bulan']==$key)


                        @if(isset($v['anc_jenis_kunjungan']))
                       {{ $v['anc_jenis_kunjungan'] }}
                        @else
                        -
                        @endif

                        <br>

                        @endif
                        @endforeach
                        @endif

                       </td>
                       @endforeach


                   </tr>
                   <tr>
                    <td>Berat Badan</td>
                    @foreach($dt['label']['bln'] as $key=>$label)
                       <td>

 @if(isset($dt['KOHORT']))
                     @foreach($dt['KOHORT'] as $k=>$v)
                         @if($v['anc_bulan']==$key)


                        @if(isset($v['anc_body_weight']))
                       {{ $v['anc_body_weight'] }}
                        @else
                        -
                        @endif

                        <br>

                        @endif
                        @endforeach
                        @endif

                       </td>
                       @endforeach
                   </tr>
                   <tr>
                    <td>Tinggi Fundus</td>
                     @foreach($dt['label']['bln'] as $key=>$label)
                       <td>

 @if(isset($dt['KOHORT']))
                     @foreach($dt['KOHORT'] as $k=>$v)
                         @if($v['anc_bulan']==$key)


                        @if(isset($v['anc_tinggi_fundus']))
                       {{ $v['anc_tinggi_fundus'] }}
                        @else
                        -
                        @endif

                        <br>

                        @endif
                        @endforeach
                        @endif

                       </td>
                       @endforeach
                   </tr>
                   <tr>
                    <td>Detak Jantung Janin</td>
                     @foreach($dt['label']['bln'] as $key=>$label)
                       <td>

 @if(isset($dt['KOHORT']))
                     @foreach($dt['KOHORT'] as $k=>$v)
                         @if($v['anc_bulan']==$key)


                        @if(isset($v['anc_djj']))
                       {{ $v['anc_djj'] }}
                        @else
                        -
                        @endif

                        <br>

                        @endif
                        @endforeach

                        @endif

                       </td>
                       @endforeach
                   </tr>
                   <tr>
                    <td>Taksiran Berat Janin</td>
                    @foreach($dt['label']['bln'] as $key=>$label)
                       <td>


                        @if(isset($dt['KOHORT']))


                     @foreach($dt['KOHORT'] as $k=>$v)
                         @if($v['anc_bulan']==$key)


                        @if(isset($v['anc_tbj']))
                       {{ $v['anc_tbj'] }}
                        @else
                        -
                        @endif

                        <br>

                        @endif
                        @endforeach

                        @endif

                       </td>
                       @endforeach
                   </tr>
                   <tr>
                    <td>Presentasi</td>
                    @foreach($dt['label']['bln'] as $key=>$label)
                       <td>

 @if(isset($dt['KOHORT']))
                     @foreach($dt['KOHORT'] as $k=>$v)
                         @if($v['anc_bulan']==$key)


                        @if(isset($v['anc_presentasi']))
                       {{ $v['anc_presentasi'] }}
                        @else
                        -
                        @endif

                        <br>

                        @endif
                        @endforeach
                        @endif

                       </td>
                       @endforeach
                   </tr>
                   <tr>
                    <td>Posisi Kepala</td>
                    @foreach($dt['label']['bln'] as $key=>$label)
                       <td>
 @if(isset($dt['KOHORT']))

                     @foreach($dt['KOHORT'] as $k=>$v)
                         @if($v['anc_bulan']==$key)


                        @if(isset($v['anc_posisi_kepala']))
                       {{ $v['anc_posisi_kepala'] }}
                        @else
                        -
                        @endif

                        <br>

                        @endif
                        @endforeach
                        @endif

                       </td>
                       @endforeach
                   </tr>
                </thead>
            </table>



                </td>
            </tr>
        </tbody>
            </table>


        </div>


<!-- INC -->
        <div class="card-body" id="inc">

            <table class="table table-light">
                <thead>



                                    <tr><td style="width: 30%">Tanggal Persalinan</td><td>:</td><td></td></tr><tr>
                                    <td>Penolong Persalinan</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td>Lokasi Kelahiran</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td>Cara Persalinan</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td>Tekanan Darah</td><td>:</td>
                                    <td>{{ $dt['sistole'] }} / {{  $dt['diastole'] }}</td></tr><tr>
                                    <td>Suhu</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td>Nadi</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td>Pernafasan</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td>Keadaan Ibu</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td>Tindakan</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td style="vertical-align: top">Laboratorium</td><td style="vertical-align: top">:</td>
                                    <td>   @if(isset($dt['lab'])) @foreach ($dt['lab'] as $key=>$val )
                                  {{  $val['label'] }}  : {{  $val['val'] }} </span><br>
                                @endforeach

                            @endif
                            </td></tr><tr>
                                    <td>Obat</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td>Rencana Tindak Lanjut</td><td>:</td>
                                    <td></td></tr><tr>
                                    <td>Kondisi Keluar</td><td>:</td>
                                    <td></td>
                                </tr>
                            </thead>




            </table>

        </div>

        <!-- End INC -->


        <!-- PNC -->
        <div class="card-body" id="pnc">

            <table class="table table-light">
                <thead>
                   <tr>
                    <td style="width:30%">Tanggal Persalinan</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Jenis Kunjungan</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>G.P.A</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Tekanan Darah</td>
                    <td>:</td>
                    <td>{{ $dt['sistole'] }} / {{ $dt['diastole'] }}</td>
                   </tr>
                   <tr>
                    <td>Suhu</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Nadi</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Pernafasan</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Diagnosis Utama</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Diagnosis sekunder</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Kondisi Payudara</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Produksi ASI</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Pendarahan Pervaginum</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Infeksi Perineum</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Konseling Perawat Bayi</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Skrining Kesehatan Jiwa</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>TTD/MMS</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Vitamin A</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Tindakan</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Laboratorium</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Obat</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Rencana Tindak Lanjut</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Kondisi Pulang</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                </thead>
            </table>

        </div>

        <!-- End PNC -->


        <!-- NEONATUS -->
        <div class="card-body" id="neonatus">

            <table class="table table-light">
                <thead>
                    <tr>
                        <td style="width:30%">Tanggal Lahir</td>
                        <td style="width: 5%">:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Jenis Kunjungan</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>BB Saat Lahir</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Panjang Badan</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Lingkar Kepala</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Suhu</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Nadi</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Pernapasan</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Skor APGAR (menit 1)</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Skor APGAR (menit 5)</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Skor APGAR (menit 10)</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Triple Eliminasi</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Vitamin K1 Injeksi</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Vitamin A</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Imunisasi HB0</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Tindakan</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Laboratorium</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Obat</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Rencana tindak Lanjut</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Kondisi Pulang</td>
                        <td>:</td>
                        <td></td>
                    </tr>

                    <tr><td colspan='3' class="text-center">
                    <h2>Pemeriksaan Head To Toe</h2>
                    </td></tr>
                    <tr>
                        <td colspan='3'>


                            <table class="table table-light">
                                <tr>
                                    <td>Kulit</td>
                                    <td>kepala</td>
                                    <td>Mata</td>
                                    <td>Mulut</td>
                                    <td>Perut</td>
                                    <td>Punggung</td>
                                    <td>Alat Kelamin</td>
                                    <td>Lubang Anus</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>


                            </table>

                             <tr><td colspan='3' class="text-center">
                    <h2>Skrining</h2>
                    </td></tr>
                     <tr><td colspan='3' class="text-center">
                        <table class="table table-light">
                            <thead>
                                <tr>
                                    <td>Hipotiroid</td>
                                    <td>PJB</td>
                                    <td>G6PD</td>
                                    <td>HAK</td>
                                    <td>Atesa Biller</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                        </table>

                    </td></tr>



                        </td>
                    </tr>
                </thead>
            </table>

        </div>

        <!-- End NEONATUS -->


         <!-- Imunisasi -->
        <div class="card-body" id="imunisasi">

            <table class="table table-light">
                <thead>
                <tr>
                    <td>Imunisasi</td>
                    <td>Tanggal Imunisasi</td>
                    <td>Tanggal Input</td>
                    <td>POS Imunisasi</td>
                    <td>PKM Pemberi Imunisasi</td>
                    <td>Status</td>
                    <td>Sumber Pencatatan</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Imunisasi HBO</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi BCG 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi POLIO 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                 <tr>
                    <td>Imunisasi POLIO 2</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Imunisasi POLIO 3</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Imunisasi POLIO 4</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Imunisasi DPT-HB-HIB 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi DPT-HB-HIB 2</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi DPT-HB-HIB 3</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                 <tr>
                    <td>Imunisasi IPV 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi IPV 2</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                 <tr>
                    <td>Imunisasi ROTA 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Imunisasi ROTA 2</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Imunisasi ROTA 3</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Imunisasi PCV 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi PCV 2</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi JE 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi MR 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>








                </tbody>
            </table>

        </div>

        <!-- End Imunisasi -->

       </div>


     </div>
          </div>
    </div>


</div>


<script>
    $(document).ready(function(){

        $('#home').removeClass('active');
$('#rme').addClass('active');

        $('#layananUmum,#layananAnc, #inc, #imunisasi, #neonatus, #pnc ').hide();

        $('#tab2').on('click',function(){
            $('#tab2').addClass('active');
            $('#tab1,#tab3,#tab4,#tab5,#tab6,#tab7').removeClass('active');

               $('#layananUmum').show();
                $('#maincard,#layananAnc, #inc, #imunisasi, #neonatus, #pnc').hide();

        })

         $('#tab1').on('click',function(){



               $('#maincard').show();


            $('#tab1').addClass('active');
            $('#tab2,#tab3,#tab4,#tab5,#tab6,#tab7').removeClass('active');
            $('#layananUmum,#layananAnc, #inc, #imunisasi, #neonatus, #pnc').hide();



        })

         $('#tab3').on('click',function(){
            $('#tab3').addClass('active');
            $('#tab2,#tab1,#tab4,#tab5,#tab6,#tab7').removeClass('active');
              $('#layananAnc').show();
            $('#layananUmum,#maincard, #inc, #imunisasi, #neonatus, #pnc').hide();

        })


         $('#tab4').on('click',function(){
            $('#tab4').addClass('active');
            $('#tab2,#tab1,#tab3,#tab5,#tab6,#tab7').removeClass('active');
              $('#inc').show();
            $('#layananUmum,#maincard, #layananANC, #imunisasi, #neonatus, #pnc').hide();

        })

         $('#tab5').on('click',function(){
            $('#tab5').addClass('active');
            $('#tab2,#tab1,#tab3,#tab4,#tab6,#tab7').removeClass('active');
              $('#pnc').show();
            $('#layananUmum,#maincard, #layananANC, #imunisasi, #neonatus, #inc').hide();

        })

          $('#tab6').on('click',function(){
            $('#tab6').addClass('active');
            $('#tab2,#tab1,#tab3,#tab4,#tab5,#tab7').removeClass('active');
              $('#neonatus').show();
            $('#layananUmum,#maincard, #layananANC, #imunisasi, #pnc, #inc').hide();

        })

          $('#tab7').on('click',function(){
            $('#tab7').addClass('active');
            $('#tab2,#tab1,#tab3,#tab4,#tab5,#tab6').removeClass('active');
              $('#imunisasi').show();
            $('#layananUmum,#maincard, #layananANC, #neonatus, #pnc, #inc').hide();

        })
    })
    </script>

@endsection
