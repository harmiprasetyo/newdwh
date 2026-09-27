  {{-- =====================================================
             LAYANAN UMUM
        ====================================================== --}}

        <div class="rme-tab-content" id="layananUmum">

            @if (!isset($dt['INFO']['total']))

                <div class="rme-empty-state">

                    <i class="bi bi-info-circle"></i>

                    <div>
                        Data Tidak ditemukan
                    </div>

                </div>

            @else

                <div class="table-responsive">

                    <table class="table rme-data-table rme-detail-table">

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

                                <td>

                                    @if(isset($dt['sistole']))
                                        {{ $dt['sistole'] }}
                                    @endif

                                    /

                                    @if(isset($dt['diastole']))
                                        {{ $dt['diastole'] }}
                                    @endif

                                </td>


                                <td>

                                    @if(isset($dt['VS']))
                                        {{ $dt['VS']['suhuBadan'] }}
                                    @endif

                                </td>


                                <td>

                                    @if(isset($dt['VS']))
                                        {{ $dt['VS']['nadi'] }}
                                    @endif

                                </td>


                                <td>

                                    @if(isset($dt['VS']))
                                        {{ $dt['VS']['pernafasan'] }}
                                    @endif

                                </td>


                                <td>

                                    @if(isset($dt['ANAMNESE']))

                                        <ul class="rme-list">

                                            @foreach($dt['ANAMNESE'] as $diagnose)

                                                <li>
                                                    {{ $diagnose['diagnosa_kode'] }}
                                                    -
                                                    {{ $diagnose['diagnosa_display'] }}
                                                </li>

                                            @endforeach

                                        </ul>

                                    @endif

                                </td>


                                <td>

                                    @if(isset($dt['PNCPROC'][0]['procedure']))

                                        @foreach($dt['PNCPROC'][0]['procedure']['coding'] as $proc)

                                            <li>
                                                {{ $proc['code'] }}
                                                -
                                                {{ $proc['display'] }}
                                            </li>

                                        @endforeach

                                    @endif

                                </td>


                                <td>

                                    @if (isset($dt['lab']))

                                        @foreach($dt['lab'] as $key=>$val)

                                            {{ $val['label'] }}
                                            :
                                            {{ $val['val'] }}

                                            <br>

                                        @endforeach

                                    @endif

                                </td>


                                <td>
                                </td>


                                <td>

                                    @if(isset($dt['PLAN'][0]['RTL']))

                                        {{ $dt['PLAN'][0]['RTL'] }}

                                    @endif

                                </td>


                                <td>

                                    @foreach($dt['ANAMNESE'] as $k=>$v)

                                        @if(isset($v['code']) && $v['code']=='359746009')

                                            {{ $v['display'] }}

                                        @endif

                                    @endforeach

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            @endif

        </div>
