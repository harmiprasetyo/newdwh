@extends('layouts.mainrme')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/rme/detailpasien.css') }}">
@endpush
@section('container')

<div class="rme-page">

    {{-- =========================================================
         PATIENT PROFILE
    ========================================================== --}}
    <div class="patient-card">

        <div class="patient-header">

            <div class="patient-avatar">
                <i class="bi bi-person-vcard"></i>
            </div>

            <div>
                <h2 class="patient-name">
                    {{ $dt['PATIENTID']['name'] }}
                </h2>

                <div class="patient-id">
                    ID Pasien :
                    <strong>{{ $dt['PATIENTID']['patient_id'] }}</strong>
                </div>
            </div>

        </div>


        <div class="patient-body">

            {{-- Kolom kiri --}}
            <div class="patient-column">

                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-person"></i>
                        Nama Pasien
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['PATIENTID']['name'] }}
                    </div>

                </div>


                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-card-text"></i>
                        NIK
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['PATIENTID']['nik'] }}
                    </div>

                </div>


                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-calendar3"></i>
                        Tanggal Lahir
                    </div>

                    <div class="patient-info-value">
                        {{ \Carbon\Carbon::parse($dt['PATIENTID']['birth_date'])->format('d M Y') }}
                    </div>

                </div>

            </div>


            {{-- Kolom kanan --}}
            <div class="patient-column">

                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-gender-ambiguous"></i>
                        Jenis Kelamin
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['PATIENTID']['gender'] }}
                    </div>

                </div>


                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-telephone"></i>
                        No. Telp
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['PATIENTID']['phone'] }}
                    </div>

                </div>


                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-hospital"></i>
                        Fasilitas Kesehatan
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['ENCOUNTER'][0]['service_provider_name'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Informasi kunjungan --}}
        <div class="patient-body">

            <div class="patient-column">

                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-calendar-check"></i>
                        Tanggal Kunjungan
                    </div>

                    <div class="patient-info-value">
                        {{ $dt['ENCOUNTER'][0]['start'] }}
                    </div>

                </div>

            </div>


            <div class="patient-column">

                <div class="patient-info-item">

                    <div class="patient-info-label">
                        <i class="bi bi-heart-pulse"></i>
                        Status G/P/A
                    </div>

                    <div class="patient-info-value">

                        G : {{ $dt['ANC']['gravida'] }}

                        <span class="mx-2">|</span>

                        P : {{ $dt['ANC']['parity'] }}

                        <span class="mx-2">|</span>

                        A : {{ $dt['ANC']['abortions'] }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         DETAIL RME
    ========================================================== --}}
    <div class="rme-content-card">


        {{-- Header --}}
        <div class="rme-content-header">

            <div>

                <h3 class="rme-content-title">
                    Detail Rekam Medis Elektronik
                </h3>

                <div class="rme-content-subtitle">
                    Informasi pelayanan dan pemeriksaan pasien
                </div>

            </div>


            <div class="rme-content-badge">

                <i class="bi bi-file-medical"></i>

                RME Pasien

            </div>

        </div>


        {{-- =====================================================
             TAB NAVIGATION
        ====================================================== --}}

        @include('partials.tabpasien')

        @include('rme.tabs.vital')

@include('rme.tabs.layanan-umum')

@include('rme.tabs.anc')

@include('rme.tabs.inc')

@include('rme.tabs.pnc')

@include('rme.tabs.neonatus')

@include('rme.tabs.imunisasi')
















<<<<<<< HEAD
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
                    <th> @if(isset($dt['ANC']['anc_jantung'])){{ $dt['ANC']['anc_jantung'] }} @endif</th>
                    <th></th>
                </tr>
                  <tr>
                    <th>Pemeriksaan Fisik Paru-paru</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_paru'])){{ $dt['ANC']['anc_paru'] }} @endif</th>
                    <th></th>
                </tr>

                <tr>
                    <th>Pemeriksaan Fisik Perut</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_perut'])){{ $dt['ANC']['anc_perut'] }} @endif</th>
                    <th></th>
                </tr>


                 <tr>
                    <th>Pemeriksaan Fisik Tungkai</th>
                    <th>:</th>
                    <th> @if(isset($dt['ANC']['anc_tungkai'])){{ $dt['ANC']['anc_tungkai'] }} @endif</th>
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


@if(!empty($dt['ANC']['anc_education']))
    <ul>
        @foreach($dt['ANC']['anc_education'] as $education)
            <li>{{ $education }}</li>
        @endforeach
    </ul>
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
                                    <td>Tindakan INC</td><td>:</td>
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
                    <td>
                        @if(isset($dt['NewENC']['PNC']['jenis_kunjungan']))
                        {{ $dt['NewENC']['PNC']['jenis_kunjungan'] }}
                        @endif


                    </td>
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
                    <td> @if(isset($dt['NewENC']['PNC']['diagnosis']))
                        @foreach($dt['NewENC']['PNC']['diagnosis'] as $diagnose)

                        <li>{{ $diagnose['diagnosa_kode'] }} - {{ $diagnose['diagnosa_display'] }}</li>

                        @endforeach
                        @endif
</td>
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
                    <td>@if(isset($dt['ANAMNESE'][0]['diagnosa_kode']) && $dt['ANAMNESE'][0]['diagnosa_kode']=='289530006') Ada @else Tidak ada @endif</td>
                   </tr>
                   <tr>
                    <td>Infeksi Perineum</td>
                    <td>:</td>
                    <td>@if(isset($dt['PNC'][0]['tanda_infeksi_perineum']))
                        @if($dt['PNC'][0]['tanda_infeksi_perineum']=="0")
                        Tidak ada
                        @else
                        Ada
                        @endif




                        @endif</td>
                   </tr>
                   <tr>
                    <td>Konseling Perawat Bayi</td>
                    <td>:</td>
                    <td>



                        @if(isset($dt['PNCPROC'][0]['code']) && $dt['PNCPROC'][0]['code']=='408988007') Ya @endif</td>
                   </tr>
                   <tr>
                  <!--  <td>Skrining Kesehatan Jiwa</td>
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
                   <tr> -->
                    <td>Tindakan</td>
                    <td>:</td>
                    <td>


                       @if(!empty($dt['PNCPROC']))
    @foreach($dt['PNCPROC'] as $procData)

        @if(($procData['category'] ?? null) == '103693007')

            @foreach($procData['procedure']['coding'] ?? [] as $proc)
                <li>
                    {{ $proc['code'] }} - {{ $proc['display'] }}
                </li>
            @endforeach

        @endif

    @endforeach
@endif


                    </td>
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
                    <td> {{ $dt['kondisipulang'] }}
                        </td>
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
=======
>>>>>>> origin/modul/rme
    </div>

</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/rme/detailpasien.js') }}"></script>

@endpush
