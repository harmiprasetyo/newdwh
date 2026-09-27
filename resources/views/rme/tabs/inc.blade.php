{{-- =====================================================
             INC
        ====================================================== --}}

        <div class="rme-tab-content" id="inc">

            <table class="table rme-detail-form-table">

                <tbody>

                    <tr>
                        <th>Tanggal Persalinan</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['INC']['inc_dtd']))
                                {{ $ndt['INC']['inc_dtd'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>GPA</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['gravida']))
                                {{ $ndt['gradiva'] }}
                                /
                                {{ $ndt['parity'] }}
                                /
                                {{ $ndt['abortions'] }}
                            @elseif(isset($dt['ANC']['gravida']))
                                {{ $dt['ANC']['gravida'] }}
                                /
                                {{ $dt['ANC']['parity'] }}
                                /
                                {{ $dt['ANC']['abortions'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Usia Kehamilan (minggu)</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['ANC']['anc_usia_kehamilan']))
                                {{ str_replace('wk','mg',$ndt['ANC']['anc_usia_kehamilan']) }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Penolong Persalinan</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['INC']['inc_penolong']))
                                {{ $ndt['INC']['inc_penolong'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Lokasi Kelahiran</th>
                        <td>:</td>
                        <td>
                            {{ $dt['ENCOUNTER'][0]['service_provider_name'] }}
                        </td>
                    </tr>

                    <tr>
                        <th>Cara Persalinan</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['INC']['inc_cara_persalinan']))
                                {{ $ndt['INC']['inc_cara_persalinan'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Kala #1</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['INC']['inc_kala1']))
                                {{ $ndt['INC']['inc_kala1'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Kala #2</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['INC']['inc_kala2']))
                                {{ $ndt['INC']['inc_kala2'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Kala #3</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['INC']['inc_kala3']))
                                {{ $ndt['INC']['inc_kala3'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Kala #4</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['INC']['inc_kala4']))
                                {{ $ndt['INC']['inc_kala4'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Keadaan Ibu</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['INC']['inc_keadaan_ibu']))
                                {{ $ndt['INC']['inc_keadaan_ibu'] }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Diagnosis</th>
                        <td>:</td>
                        <td>

                            @if(!empty($dt['ANC']['anc_diagnosa']))

                                <ul class="rme-list">

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
                    </tr>

                    <tr>
                        <th>Tindakan</th>
                        <td>:</td>
                        <td>
                            @if(isset($ndt['INC']['inc_tindakan']))
                                {{ $ndt['INC']['inc_tindakan'] }}
                            @endif
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>
