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

