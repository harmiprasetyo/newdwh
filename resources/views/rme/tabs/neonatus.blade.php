
        {{-- =====================================================
             NEONATUS
        ====================================================== --}}

        <div class="rme-tab-content" id="neonatus">

            <table class="table rme-detail-form-table">

                <tbody>

                    <tr>
                        <th>BB Saat Lahir</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['NEONATAL'][0]['berat_lahir']))
                                {{ $dt['NEONATAL'][0]['berat_lahir'] }} gram
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Panjang Badan</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['NEONATAL'][0]['panjang_badan']))
                                {{ $dt['NEONATAL'][0]['panjang_badan'] }} cm
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Lingkar Kepala</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['NEONATAL'][0]['lingkar_kepala']))
                                {{ $dt['NEONATAL'][0]['lingkar_kepala'] }} cm
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Skor APGAR (menit 1)</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['APGAR1']))
                                {{ $dt['APGAR1'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Skor APGAR (menit 5)</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['APGAR5']))
                                {{ $dt['APGAR5'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Skor APGAR (menit 10)</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['APGAR10']))
                                {{ $dt['APGAR10'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Vitamin K1 Injeksi</th>
                        <td>:</td>
                        <td>

                            @foreach($dt['PNCPROC'] as $k=>$val)

                                @if($val['code']=='448883004')

                                    {{ \Carbon\Carbon::parse($val['tglvitamin'])->format("d M Y") }}

                                @endif

                            @endforeach

                        </td>
                    </tr>

                    <tr>
                        <th>Imunisasi HB0</th>
                        <td>:</td>
                        <td>
                            @if(isset($dt['IMN_NN'][0]['tglImunisasi']))
                                {{ $dt['IMN_NN'][0]['tglImunisasi'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Tindakan</th>
                        <td>:</td>
                        <td>

                            @foreach($dt['PNCPROC'] as $k=>$val)

                                @if($val['code']!='448883004')

                                    <li>
                                        {{ $val['display'] }}
                                    </li>

                                @endif

                            @endforeach

                        </td>
                    </tr>

                    <tr>
                        <th>Rencana tindak Lanjut</th>
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

                    <tr class="rme-section-row text-center">
                        <th colspan="3">
                            Pemeriksaan Head To Toe
                        </th>
                    </tr>

                    <tr>

                        <td colspan="3">

                            <div class="table-responsive">

                                <table class="table rme-inner-table">

                                    <thead>

                                        <tr>
                                            <th>Kulit</th>
                                            <th>Kepala</th>
                                            <th>Mata</th>
                                            <th>Mulut</th>
                                            <th>Perut</th>
                                            <th>Punggung</th>
                                            <th>Alat Kelamin</th>
                                            <th>Lubang Anus</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>

                                            <td>
                                                @if(isset($dt['NN']['kulit']))
                                                    {{ $dt['NN']['kulit'] }}
                                                @endif
                                            </td>

                                            <td>
                                                @if(isset($dt['NN']['kepala']))
                                                    {{ $dt['NN']['kepala'] }}
                                                @endif
                                            </td>

                                            <td>
                                                @if(isset($dt['NN']['mata']))
                                                    {{ $dt['NN']['mata'] }}
                                                @endif
                                            </td>

                                            <td>
                                                @if(isset($dt['NN']['mulut']))
                                                    {{ $dt['NN']['mulut'] }}
                                                @endif
                                            </td>

                                            <td>
                                                @if(isset($dt['NN']['abdomen']))
                                                    {{ $dt['NN']['abdomen'] }}
                                                @endif
                                            </td>

                                            <td>
                                                @if(isset($dt['NN']['punggung']))
                                                    {{ $dt['NN']['punggung'] }}
                                                @endif
                                            </td>

                                            <td>
                                                @if(isset($dt['NN']['genitalia']))
                                                    {{ $dt['NN']['genitalia'] }}
                                                @endif
                                            </td>

                                            <td>
                                                @if(isset($dt['NN']['bokong']))
                                                    {{ $dt['NN']['bokong'] }}
                                                @endif
                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </td>

                    </tr>


                <!--    <tr class="rme-section-row">
                        <th colspan="3">
                            Skrining
                        </th>
                    </tr>

                    <tr>

                        <td colspan="3">

                            <div class="table-responsive">

                                <table class="table rme-inner-table">

                                    <thead>

                                        <tr>
                                            <th>Hipotiroid</th>
                                            <th>PJB</th>
                                            <th>G6PD</th>
                                            <th>HAK</th>
                                            <th>Atesa Biller</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>-->

                                    </tbody>

                                </table>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>
