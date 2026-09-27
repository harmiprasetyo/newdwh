   {{-- =====================================================
             IMUNISASI
        ====================================================== --}}

        <div class="rme-tab-content" id="imunisasi">

            <div class="table-responsive">

                <table class="table rme-data-table">

                    <thead>

                        <tr>

                            <th>Imunisasi</th>
                            <th>Tanggal Imunisasi</th>
                            <th>Tanggal Input</th>
                            <th>POS Imunisasi</th>
                            <th>PKM Pemberi Imunisasi</th>
                            <th>Status</th>
                            <th>Sumber Pencatatan</th>

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

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG19')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG19')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>


                        <tr>

                            <td>Imunisasi DPT-HB-HIB 1</td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG107' || $imn['code']=='93001282')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

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

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG17')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG17')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>


                        <tr>

                            <td>Imunisasi DPT-HB-HIB 3</td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG45')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG45')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>


                        <tr>

                            <td>Imunisasi IPV 1</td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG89' && $imn['display']=='IPV 1')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG89' && $imn['display']=='IPV 1')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>


                        <tr>

                            <td>Imunisasi IPV 2</td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG89' && $imn['display']=='IPV 2')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG89' && $imn['display']=='IPV 2')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>


                        <tr>

                            <td>Imunisasi ROTA 1</td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG122')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG122')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

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

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG152' && $imn['display']=='PCV 1')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG152' && $imn['display']=='PCV 1')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>


                        <tr>

                            <td>Imunisasi PCV 2</td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG152' && $imn['display']=='PCV 2')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG152' && $imn['display']=='PCV 2')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>


                        <tr>

                            <td>Imunisasi JE 1</td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG129')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG129')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>


                        <tr>

                            <td>Imunisasi MR 1</td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG03')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG03')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>


                        <tr>

                            <td>Polio</td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG89' && $imn['display']=='POLIO')

                                        {{ \Carbon\Carbon::parse($imn['tglImunisasi'])->format('d M Y') }}

                                    @endif

                                @endforeach

                            </td>

                            <td></td>

                            <td>

                                @foreach($dt['IMUNISASI'] as $n=>$imn)

                                    @if($imn['code']=='VG89' && $imn['display']=='POLIO')
                                        {{ $imn['pos'] }}
                                    @endif

                                @endforeach

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>
