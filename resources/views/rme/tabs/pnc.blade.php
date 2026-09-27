{{-- =====================================================
             PNC
        ====================================================== --}}

        <div class="rme-tab-content" id="pnc">

            <table class="table rme-detail-form-table">

                <tbody>

                    <tr>
                        <th>Tanggal Persalinan</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['INC'][0]['delivery_time']))
                                {{ \Carbon\Carbon::parse($dt['INC'][0]['delivery_time'])->translatedFormat('d F Y H:i') }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Jenis Kunjungan</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['NewENC']['PNC']['jenis_kunjungan']))
                                {{ $dt['NewENC']['PNC']['jenis_kunjungan'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>G.P.A</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['PNC'][0]['gravida']))
                                {{ $dt['PNC'][0]['gravida'] }}
                                /
                                {{ $dt['PNC'][0]['parity'] }}
                                /
                                {{ $dt['PNC'][0]['abortus'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Tekanan Darah</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['sistole']))
                                {{ $dt['sistole'] }}
                            @endif
                            /
                            @if(isset($dt['diastole']))
                                {{ $dt['diastole'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Suhu</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['VS']['suhuBadan']))
                                {{ $dt['VS']['suhuBadan'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Nadi</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['VS']['nadi']))
                                {{ $dt['VS']['nadi'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Pernafasan</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['VS']['pernafasan']))
                                {{ $dt['VS']['pernafasan'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Diagnosis</th>
                        <td>:</td>
                        <td>

                            @if(isset($dt['NewENC']['PNC']['diagnosis']))

                                <ul class="rme-list">

                                    @foreach($dt['NewENC']['PNC']['diagnosis'] as $diagnose)

                                        <li>
                                            {{ $diagnose['diagnosa_kode'] }}
                                            -
                                            {{ $diagnose['diagnosa_display'] }}
                                        </li>

                                    @endforeach

                                </ul>

                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th>Kondisi Payudara</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['PNC'][0]['pemeriksaan_payudara']))
                                {{ $dt['PNC'][0]['pemeriksaan_payudara'] == 'Normal breast' ? 'Payudara Normal' : $dt['PNC'][0]['pemeriksaan_payudara'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Produksi ASI</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['PNC'][0]['produksi_asi']))
                                {{ $dt['PNC'][0]['produksi_asi'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Pendarahan Pervaginum</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['ANAMNESE'][0]['diagnosa_kode']) && $dt['ANAMNESE'][0]['diagnosa_kode']=='289530006')
                                Ada
                            @else
                                Tidak ada
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Infeksi Perineum</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['PNC'][0]['tanda_infeksi_perineum']))
                                @if($dt['PNC'][0]['tanda_infeksi_perineum']=="0")
                                    Tidak ada
                                @else
                                    Ada
                                @endif
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Konseling Perawat Bayi</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['PNCPROC'][0]['code']) && $dt['PNCPROC'][0]['code']=='408988007')
                                Ya
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Tindakan</th>
                        <td>:</td>
                        <td>

                            @if(!empty($dt['PNCPROC']))

                                <ul class="rme-list">

                                    @foreach($dt['PNCPROC'] as $procData)

                                        @if(($procData['category'] ?? null) == '103693007')

                                            @foreach($procData['procedure']['coding'] ?? [] as $proc)

                                                <li>
                                                    {{ $proc['code'] }}
                                                    -
                                                    {{ $proc['display'] }}
                                                </li>

                                            @endforeach

                                        @endif

                                    @endforeach

                                </ul>

                            @endif

                        </td>
                    </tr>

                 <!--   <tr>
                        <th>Obat</th>
                        <td>:</td>
                        <td></td>
                    </tr> -->

                    <tr>
                        <th>Rencana Tindak Lanjut</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['PLAN'][0]['RTL']))
                                {{ $dt['PLAN'][0]['RTL'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Kondisi Pulang</th>
                        <td>:</td>
                        <td>

                            @foreach($dt['ANAMNESE'] as $k=>$v)

                                @if($v['diagnosa_kode']=='359746009')
                                    {{ $v['diagnosa_display'] }}
                                @endif

                            @endforeach

                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

