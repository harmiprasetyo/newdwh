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
                <th>{{ $dt['PATIENTID']['patient_id'] }}</th>
            </tr>
            <tr>
                <th>Nama Pasien</th>
                <th>:</th>
                <th>{{ $dt['PATIENTID']['name'] }}</th>
            </tr>
                  <tr>
                <th>NIK</th>
                <th>:</th>
                <th>{{ $dt['PATIENTID']['nik'] }}</th>
                  </tr>

                  <tr>
                <th>No. Telp</th>
                <th>:</th>
                <th>{{ $dt['PATIENTID']['phone'] }}</th>
            <tr>


                  <tr>
                <th>Tgl Lahir</th>
                <th>:</th>
                <th>{{ \Carbon\Carbon::parse($dt['PATIENTID']['birth_date'])->format('d M Y') }}</th>
                  </tr>
                  <tr>
                <th>Jenis Kelamin</th>
                <th>:</th>
                <th>{{ $dt['PATIENTID']['gender'] }}</th>
                  </tr>

                  <tr>
                <th>Tanggal Kunjungan</th>
                <th>:</th>
                <th>{{ $dt['ENCOUNTER'][0]['start'] }}</th>
                  </tr>

                   <tr>
                <th>Fasilitas Kesehatan</th>
                <th>:</th>
                <th>{{ $dt['ENCOUNTER'][0]['service_provider_name'] }}</th>
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
              @if(!isset($dt['OBS']))
                Data Pemeriksaan Vital Sign Tidak ditemukan
                @else
                <table class="table">
                    <thead>
                        <tr>
                            @if(isset($dt['OBS']))

                            @foreach ($dt['OBS'] as $n)
                            <th>{{ $n['param_name'] }}</th>
                            @endforeach
                            @endif

                        </tr>
                        <tr>  @if(isset($dt['OBS']))
                             @foreach ($dt['OBS'] as $ndata)
                            <td>{{ $ndata['valueQty'] }} {{ $ndata['valueUnit'] }}</td>
                            @endforeach
                             @endif
                        </tr>
                    </thead>
                </table>
            @endif



        </div>


         <div class="card-body" id="layananUmum">
            @if (!isset($dt['INFO']['total']))
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
                            <td>@if(isset($dt['sistole'])) {{ $dt['sistole'] }}  @endif  / @if(isset($dt['diastole'])) {{ $dt['diastole'] }}  @endif</td>
                            <td>@if(isset($dt['VS'])) {{ $dt['VS']['suhuBadan'] }} @endif</td>
                            <td>@if(isset($dt['VS'])) {{ $dt['VS']['nadi'] }} @endif</td>
                            <td>@if(isset($dt['VS'])) {{ $dt['VS']['pernafasan'] }} @endif</td>
                            <td>

                                @if(isset($dt['ANAMNESE']))
                        @foreach($dt['ANAMNESE'] as $diagnose)

                        <li>{{ $diagnose['diagnosa_kode'] }} - {{ $diagnose['diagnosa_display'] }}</li>

                        @endforeach



                        @endif</td>

                            </td>
                            <td>
                                @if(isset($dt['PNCPROC'][0]['procedure']))
                        @foreach($dt['PNCPROC'][0]['procedure']['coding'] as $proc)
                        <li>{{ $proc['code'] }} - {{ $proc['display'] }}</li>
                        @endforeach
                        @endif
                            </td>
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
                            <td>  @if(isset($dt['PLAN'][0]['RTL']))
                        {{ $dt['PLAN'][0]['RTL'] }}

                        @endif</td>
                            <td>

                                 @foreach($dt['ANAMNESE'] as $k=>$v)
                            @if(isset($v['code']) && $v['code']=='359746009')
                            {{  $v['display'] }}
                            @endif
                            @endforeach

                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif



        </div>

        <div class="card-body" id="layananAnc">
            <table class="table">
            <thead>
                <tr>
                    <th>Trimester Ke </th>
                    <th>:</th>
                    <th>@if(isset($dt['ANC']['anc_trimester'])){{ $dt['ANC']['anc_trimester'] }} @endif</th>
                    <th></th>
                </tr>
                <tr>
                    <th>Jarak Kehamilan</th>
                    <th>:</th>
                    <th>@if(isset($dt['ANC']['anc_jarak_hamil'])){{ $dt['ANC']['anc_jarak_hamil'] }} @endif</th>
                    <th></th>
                </tr>
                <tr>
                    <th>HPL</th>
                    <th>:</th>
                    <th>
                        @if(isset($dt['ANC']['anc_hpl'])){{ $dt['ANC']['anc_hpl'] }} @endif

                </th>
                    <th></th>
                </tr>

                 <tr>
                    <th>HPHT</th>
                    <th>:</th>
                    <th>

                        @if(isset($dt['ANC']['anc_hpht'])){{ $dt['ANC']['anc_hpht'] }} @endif

                </th>
                    <th></th>
                </tr>


                <tr>
                    <th>Usia Kehamilan</th>
                    <th>:</th>
                    <th>

                        @if(isset($dt['ANC']['anc_usia_kehamilan'])){{ $dt['ANC']['anc_usia_kehamilan'] }} @endif

                </th>
                    <th></th>
                </tr>



                <tr>
                    <th>Tinggi Badan</th>
                    <th>:</th>
                    <th>
                        @if(isset($dt['ANC']['anc_body_heigh'])){{ $dt['ANC']['anc_body_heigh'] }} @endif
                        </th>
                    <th></th>
                </tr>


                 <tr>
                    <th>BB Sebelum Hamil</th>
                    <th>:</th>
                    <th>
                        @if(isset($dt['ANC']['anc_bb_pre'])){{ $dt['ANC']['anc_bb_pre'] }} @endif
                        </th>
                    <th></th>
                </tr>



                <tr>
                    <th>LILA</th>
                    <th>:</th>
                    <th>
                        @if(isset($dt['ANC']['anc_lila'])){{ $dt['ANC']['anc_lila'] }} @endif


                    </th>
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
                    <th>Merokok</th>
                    <th>:</th>
                    <th>

                        @if(isset($dt['ANC']['anc_smooking'])){{ $dt['ANC']['anc_smooking'] }} @endif




                    </th>
                    <th></th>
                </tr>

                 <tr>
                    <th>Riwayat Alkohol</th>
                    <th>:</th>
                    <th>

                        @if(isset($dt['ANC']['anc_smooking'])){{ $dt['ANC']['anc_alch'] }} @endif




                    </th>
                    <th></th>
                </tr>


                 <tr>
                    <th colspan="4" class="text-center"> <h2>Pemeriksaan Fisik</h2></th>
                </tr>

                 <tr>
                    <th>Pemeriksaan Fisik Konjungtiva</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_conjungtiva'])){{ $dt['ANC']['anc_conjungtiva'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Pemeriksaan Fisik Skelra</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_sklera'])){{ $dt['ANC']['anc_sklera'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Pemeriksaan Fisik Leher</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_leher'])){{ $dt['ANC']['anc_leher'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Pemeriksaan Fisik Mulut</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_mulut'])){{ $dt['ANC']['anc_leher'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Pemeriksaan Fisik THT</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_tht'])){{ $dt['ANC']['anc_leher'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Pemeriksaan Fisik Jantung</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_jantung'])){{ $dt['ANC']['anc_leher'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Pemeriksaan Fisik Perut</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_perut'])){{ $dt['ANC']['anc_perut'] }} @endif</th>
                    <th></th>
                </tr>

                 <tr>
                    <th colspan="4" class="text-center"> <h2>Pemeriksaan Janin</h2></th>
                </tr>



                 <tr>
                    <th>Jumlah Janin</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_jumlah_janin'])){{ $dt['ANC']['anc_jumlah_janin'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>TBJ</th>
                    <th>:</th>
                    <th>@if(isset($dt['ANC']['anc_tbj'])){{ $dt['ANC']['anc_tbj'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>TFU</th>
                    <th>:</th>
                    <th>@if(isset($ndt['ANC']['anc_tfu'])){{ $ndt['ANC']['anc_tfu'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>DJJ</th>
                    <th>:</th>
                    <th>>@if(isset($dt['ANC']['anc_djj'])){{ $dt['ANC']['anc_djj'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Posisi Kepala</th>
                    <th>:</th>
                    <th>@if(isset($dt['ANC']['anc_head'])){{ $dt['ANC']['anc_head'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Presentasi</th>
                    <th>:</th>
                    <th>@if(isset($dt['ANC']['anc_presentasi'])){{ $dt['ANC']['anc_presentasi'] }} @endif</th>
                    <th></th>
                </tr>







                 <tr>
                    <th colspan="4" class="text-center"> &nbsp;</th>
                </tr>
                 <tr>
                    <th style="vertical-align: middle">Diagnosis</th>
                    <th style="vertical-align: middle">:</th>
                    <th>
                       @if(!empty($dt['ANC']['anc_diagnosa']))
    <ul>
        @foreach($dt['ANC']['anc_diagnosa'] as $diagnosa)
            <li>
                {{ $diagnosa['display'] }}
                @if(!empty($diagnosa['code']))
                    ({{ $diagnosa['code'] }})
                @endif
            </li>
        @endforeach
    </ul>
@else
    -
@endif


                    </th>
                    <th></th>
                </tr>

                 <tr>
                    <th style="vertical-align: middle">Pemeriksaan USG</th>
                    <th style="vertical-align: middle">:</th>
                    <th>

@if(isset($dt['ANC']['anc_usg']))
{{  $dt['ANC']['anc_usg'] }}
@endif


                    </th>
                    <th></th>
                </tr>


                            <tr>
                    <th style="vertical-align: middle">Edukasi</th>
                    <th style="vertical-align: middle">:</th>
                    <th>

@if(isset($dt['ANC']['anc_education']))
{{  $dt['ANC']['anc_education'] }}
@endif


                    </th>
                    <th></th>
                </tr>



                <tr>
                    <th colspan="4" class="text-center"> <h2>Pemeriksaan Laboratorium</h2></th>
                </tr>

                <tr>
                    <th>HB</th>
                    <th>:</th>
                    <th>
                        @if(isset($dt['lab_hb']))
                        {{ $dt['lab']['lab_hb']['val'] }}
                    @endif


                    </th>
                    <th></th>
                </tr>

                <tr>
                    <th>Gol Darah</th>
                    <th>:</th>
                    <th>@if(isset($ndt['ANC']['anc_gol_darah'])){{ $ndt['ANC']['anc_gol_darah'] }} @endif</th>
                    <th></th>
                </tr>
                  <tr>
                    <th>Rhesus</th>
                    <th>:</th>
                    <th>
  @if(isset($dt['lab_rh']))
                        {{ $dt['lab']['lab_rh']['val'] }}
                    @endif

                    </th>
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

                    <!--

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
            </table> -->



                </td>
            </tr>
        </tbody>
            </table>


        </div>


<!-- INC -->
        <div class="card-body" id="inc">

            <table class="table table-light">
                <thead>



                                    <tr><td style="width: 30%">Tanggal Persalinan</td><td>:</td><td>
                                        @if(isset($ndt['INC']['inc_dtd']))
                                        {{ $ndt['INC']['inc_dtd'] }}
                                        @endif

                                    </td></tr>

                                     <tr><td style="width: 30%">GPA</td><td>:</td><td>

                                        @if(isset($ndt['gravida']))
                                        {{ $ndt['gradiva'] }} / {{ $ndt['parity'] }} / {{ $ndt['abortions'] }}
                                        @elseif(isset($dt['ANC']['gravida']))
                                        {{ $dt['ANC']['gravida'] }} / {{ $dt['ANC']['parity'] }} / {{ $dt['ANC']['abortions'] }}
                                        @endif

                                    </td></tr>

                                        <tr>
                                    <td>Usia Kehamilan (minggu)</td><td>:</td>
                                    <td>
                                        @if(isset($ndt['ANC']['anc_usia_kehamilan']))
                                        {{ str_replace('wk','mg',$ndt['ANC']['anc_usia_kehamilan']) }}
                                        @endif
                                    </td></tr>

                                        <tr>
                                    <td>Penolong Persalinan</td><td>:</td>
                                    <td>
                                        @if(isset($ndt['INC']['inc_penolong']))

                                        {{ $ndt['INC']['inc_penolong'] }}

                                        @endif
                                    </td></tr><tr>
                                    <td>Lokasi Kelahiran</td><td>:</td>
                                    <td>{{ $dt['ENCOUNTER'][0]['service_provider_name'] }}</td></tr><tr>
                                    <td>Cara Persalinan</td><td>:</td>
                                    <td> @if(isset($ndt['INC']['inc_cara_persalinan']))

                                        {{ $ndt['INC']['inc_cara_persalinan'] }}

                                        @endif</td></tr><tr>
                                    <td>Kala #1</td><td>:</td>
                                    <td>@if(isset($ndt['INC']['inc_kala1']))

                                        {{ $ndt['INC']['inc_kala1'] }}

                                        @endif</td></tr><tr>
                                    <td>Kala #2</td><td>:</td>
                                    <td>@if(isset($ndt['INC']['inc_kala2']))

                                        {{ $ndt['INC']['inc_kala2'] }}

                                        @endif</td></tr><tr>
                                    <td>Kala #3</td><td>:</td>
                                    <td>@if(isset($ndt['INC']['inc_kala3']))

                                        {{ $ndt['INC']['inc_kala3'] }}

                                        @endif</td></tr><tr>
                                    <td>Kala #4</td><td>:</td>
                                    <td>@if(isset($ndt['INC']['inc_kala4']))

                                        {{ $ndt['INC']['inc_kala4'] }}

                                        @endif</td></tr><tr>
                                    <td>Keadaan Ibu</td><td>:</td>
                                    <td> @if(isset($ndt['INC']['inc_keadaan_ibu']))

                                        {{ $ndt['INC']['inc_keadaan_ibu'] }}

                                        @endif</td></tr>

                                        <tr>
                    <td style="vertical-align: middle">Diagnosis</td>
                    <td style="vertical-align: middle">:</td>
                    <td>
                       @if(!empty($dt['ANC']['anc_diagnosa']))
    <ul>
        @foreach($dt['ANC']['anc_diagnosa'] as $diagnosa)
            <li>
                {{ $diagnosa['display'] }}
                @if(!empty($diagnosa['code']))
                    ({{ $diagnosa['code'] }})
                @endif
            </li>
        @endforeach
    </ul>
@else
    -
@endif


                    </td>
                    <td></td>
                </tr>

                 <tr>
                                    <td>Tindakan</td><td>:</td>
                                    <td>
                                        @if(isset($ndt['INC']['inc_tindakan']))

                                        {{ $ndt['INC']['inc_tindakan'] }}

                                        @endif
                                    </td></tr>

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
                    <td> @if(isset($dt['INC'][0]['delivery_time'])) {{ \Carbon\Carbon::parse($dt['INC'][0]['delivery_time'])->translatedFormat('d F Y   H:i') }} @endif</td>
                   </tr>
                   <tr>
                    <td>Jenis Kunjungan</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>G.P.A</td>
                    <td>:</td>
                    <td>@if(isset($dt['PNC'][0]['gravida'])) {{ $dt['PNC'][0]['gravida'] }}  / {{ $dt['PNC'][0]['parity'] }}  / {{ $dt['PNC'][0]['abortus'] }} @endif</td>
                   </tr>
                   <tr>
                    <td>Tekanan Darah</td>
                    <td>:</td>
                    <td>@if(isset($dt['sistole'])) {{ $dt['sistole'] }}  @endif  / @if(isset($dt['diastole'])) {{ $dt['diastole'] }}  @endif</td>
                   </tr>
                   <tr>
                    <td>Suhu</td>
                    <td>:</td>
                    <td>@if(isset($dt['VS']['suhuBadan'])) {{  $dt['VS']['suhuBadan'] }} @endif</td>
                   </tr>
                   <tr>
                    <td>Nadi</td>
                    <td>:</td>
                    <td>@if(isset($dt['VS']['nadi'])) {{  $dt['VS']['nadi'] }} @endif</td>
                   </tr>
                   <tr>
                    <td>Pernafasan</td>
                    <td>:</td>
                    <td>@if(isset($dt['VS']['pernafasan'])) {{  $dt['VS']['pernafasan'] }} @endif</td>
                   </tr>
                   <tr>
                    <td style="vertical-align: top">Diagnosis</td>
                    <td style="vertical-align: top">:</td>
                    <td>@if(isset($dt['ANAMNESE']))
                        @foreach($dt['ANAMNESE'] as $diagnose)

                        <li>{{ $diagnose['diagnosa_kode'] }} - {{ $diagnose['diagnosa_display'] }}</li><br>

                        @endforeach



                        @endif</td>
                   </tr>

                   <tr>
                    <td>Kondisi Payudara</td>
                    <td>:</td>
                    <td>@if(isset($dt['PNC'][0]['pemeriksaan_payudara'])) {{ $dt['PNC'][0]['pemeriksaan_payudara'] == 'Normal breast'
        ? 'Payudara Normal'
        : $dt['PNC'][0]['pemeriksaan_payudara'] }} @endif</td>
                   </tr>
                   <tr>
                    <td>Produksi ASI</td>
                    <td>:</td>
                    <td>@if(isset($dt['PNC'][0]['produksi_asi'])) {{ $dt['PNC'][0]['produksi_asi'] }} @endif</td>
                   </tr>
                   <tr>
                    <td>Pendarahan Pervaginum</td>
                    <td>:</td>
                    <td>@if(isset($dt['PNC'][0]['pendarahan'])) {{ $dt['PNC'][0]['pendarahan'] }} mL @endif</td>
                   </tr>
                   <tr>
                    <td>Infeksi Perineum</td>
                    <td>:</td>
                    <td>@if(isset($dt['PNC'][0]['tanda_infeksi_perineum']))

                        {{ $dt['PNC'][0]['tanda_infeksi_perineum'] }}

                        @endif</td>
                   </tr>
                   <tr>
                    <td>Konseling Perawat Bayi</td>
                    <td>:</td>
                    <td>



                        @if(isset($dt['PNCPROC'][0]['code']) && $dt['PNCPROC'][0]['code']=='408988007') Ya @endif</td>
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
                    <td>

                        @if(isset($dt['PNCPROC'][0]['procedure']))
                        @foreach($dt['PNCPROC'][0]['procedure']['coding'] as $proc)
                        <li>{{ $proc['code'] }} - {{ $proc['display'] }}</li>
                        @endforeach
                        @endif


                    </td>
                   </tr>
                   <tr>
                    <td>Laboratorium</td>
                    <td>:</td>
                    <td><span>

                                @if (isset($dt['lab']))



                                  @foreach ($dt['lab'] as $key=>$val )
                                  {{  $val['label'] }}  : {{  $val['val'] }} </span><br>
                                @endforeach

                                     @endif</td>
                   </tr>
                   <tr>
                    <td>Obat</td>
                    <td>:</td>
                    <td></td>
                   </tr>
                   <tr>
                    <td>Rencana Tindak Lanjut</td>
                    <td>:</td>
                    <td>
                        @if(isset($dt['PLAN'][0]['RTL']))
                        {{ $dt['PLAN'][0]['RTL'] }}

                        @endif



                    </td>
                   </tr>
                   <tr>
                    <td>Kondisi Pulang</td>
                    <td>:</td>
                    <td> @foreach($dt['ANAMNESE'] as $k=>$v)
                            @if($v['diagnosa_kode']=='359746009')
                            {{  $v['diagnosa_display'] }}
                            @endif
                            @endforeach</td>
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
                        <td>BB Saat Lahir</td>
                        <td>:</td>
                        <td>@if(isset($dt['NEONATAL'][0]['berat_lahir'])){{ $dt['NEONATAL'][0]['berat_lahir'] }} gram @endif</td>
                    </tr>
                    <tr>
                        <td>Panjang Badan</td>
                        <td>:</td>
                        <td>@if(isset($dt['NEONATAL'][0]['panjang_badan'])){{ $dt['NEONATAL'][0]['panjang_badan'] }} cm @endif</td>
                    </tr>
                    <tr>
                        <td>Lingkar Kepala</td>
                        <td>:</td>
                        <td>@if(isset($dt['NEONATAL'][0]['lingkar_kepala'])){{ $dt['NEONATAL'][0]['lingkar_kepala'] }} cm @endif</td>
                    </tr>
                    <tr>
                        <td>Skor APGAR (menit 1)</td>
                        <td>:</td>
                        <td>
                            @if(isset($dt['APGAR1']))
                            {{ $dt['APGAR1'] }}
                            @endif


                        </td>
                    </tr>
                    <tr>
                        <td>Skor APGAR (menit 5)</td>
                        <td>:</td>
                        <td>  @if(isset($dt['APGAR5']))
                            {{ $dt['APGAR5'] }}
                            @endif</td>
                    </tr>
                    <tr>
                        <td>Skor APGAR (menit 10)</td>
                        <td>:</td>
                        <td>  @if(isset($dt['APGAR10']))
                            {{ $dt['APGAR10'] }}
                            @endif</td>
                    </tr>
                <!--    <tr>
                        <td>Triple Eliminasi</td>
                        <td>:</td>
                        <td></td>
                    </tr> -->
                    <tr>
                        <td>Vitamin K1 Injeksi</td>
                        <td>:</td>
                        <td>


                            @foreach($dt['PNCPROC'] as $k=>$val)
                            @if($val['code']=='448883004')
                            {{ \Carbon\Carbon::parse($val['tglvitamin'])->format("d M Y") }}
                            @endif
                            @endforeach

                        </td>
                    </tr>
                 <!--   <tr>
                        <td>Vitamin A</td>
                        <td>:</td>
                        <td></td>
                    </tr> -->
                    <tr>
                        <td>Imunisasi HB0</td>
                        <td>:</td>
                        <td>

                            @if(isset($dt['IMN_NN'][0]['tglImunisasi'])){{ $dt['IMN_NN'][0]['tglImunisasi'] }} @endif</td>
                    </tr>
                    <tr>
                        <td>Tindakan</td>
                        <td>:</td>
                        <td>

                             @foreach($dt['PNCPROC'] as $k=>$val)
                            @if($val['code']!='448883004')
                            <li>{{ $val['display'] }}
                            @endif
                            @endforeach


                        </td>
                    </tr>
                   <!-- <tr>
                        <td>Laboratorium</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Obat</td>
                        <td>:</td>
                        <td></td>
                    </tr>  -->
                    <tr>
                        <td>Rencana tindak Lanjut</td>
                        <td>:</td>
                        <td>  @if(isset($dt['PLAN'][0]['RTL']))
                        {{ $dt['PLAN'][0]['RTL'] }}

                        @endif</td>
                    </tr>
                    <tr>
                        <td>Kondisi Pulang</td>
                        <td>:</td>
                        <td>
                            @foreach($dt['ANAMNESE'] as $k=>$v)
                            @if($v['diagnosa_kode']=='359746009')
                            {{  $v['diagnosa_display'] }}
                            @endif
                            @endforeach

                        </td>
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
                                    <td>@if(isset($dt['NN']['kulit'])) {{ $dt['NN']['kulit'] }} @endif</td>
                                    <td>@if(isset($dt['NN']['kepala'])) {{ $dt['NN']['kepala'] }} @endif</td>
                                    <td>@if(isset($dt['NN']['mata'])) {{ $dt['NN']['mata'] }} @endif</td>
                                    <td>@if(isset($dt['NN']['mulut'])) {{ $dt['NN']['mulut'] }} @endif</td>
                                    <td>@if(isset($dt['NN']['abdomen'])) {{ $dt['NN']['abdomen'] }} @endif</td>
                                    <td>@if(isset($dt['NN']['punggung'])) {{ $dt['NN']['punggung'] }} @endif</td>
                                    <td>@if(isset($dt['NN']['genitalia'])) {{ $dt['NN']['genitalia'] }} @endif</td>
                                    <td>@if(isset($dt['NN']['bokong'])) {{ $dt['NN']['bokong'] }} @endif</td>
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
<pre>

            </pre>
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
                    <td>@foreach($dt['IMUNISASI'] as $n=>$imn)

                        @if($imn['code']=='VG19')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG19')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

              <!--   <tr>
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
                </tr> -->

                <tr>
                    <td>Imunisasi DPT-HB-HIB 1</td>
                    <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG107' || $imn['code']=='93001282')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach
                    </td>
                    <td>



                    </td>
                    <td>
                         @foreach($dt['IMUNISASI'] as $n=>$imn)
                         @if($imn['code']=='VG107' || $imn['code']=='93001282')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi DPT-HB-HIB 2</td>
                    <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG17')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG17')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi DPT-HB-HIB 3</td>
                    <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG45')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG45')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


            <tr>
                    <td>Imunisasi IPV 1</td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG89' && $imn['display']=='IPV 1')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG89' && $imn['display']=='IPV 1')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi IPV 2</td>
                    <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG89' && $imn['display']=='IPV 2')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG89' && $imn['display']=='IPV 2')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                 <tr>
                    <td>Imunisasi ROTA 1</td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG122')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                         @if($imn['code']=='VG122')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
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
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG152' && $imn['display']=='PCV 1')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                         @if($imn['code']=='VG152' && $imn['display']=='PCV 1')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi PCV 2</td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG152' && $imn['display']=='PCV 2')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                         @if($imn['code']=='VG152' && $imn['display']=='PCV 2')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi JE 1</td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG129')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                         @if($imn['code']=='VG129')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                 <tr>
                    <td>Imunisasi MR 1</td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG03')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                         @if($imn['code']=='VG03')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Polio</td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                        @if($imn['code']=='VG89' && $imn['display']=='POLIO')
                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                        @endif


                        @endforeach</td>
                    <td></td>
                     <td>@foreach($dt['IMUNISASI'] as $n=>$imn)
                          @if($imn['code']=='VG89' && $imn['display']=='POLIO')
                        {{ $imn['pos'] }}

                        @endif


                        @endforeach</td>
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
