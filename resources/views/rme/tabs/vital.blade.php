 {{-- =====================================================
             VITAL SIGN
        ====================================================== --}}

        <div class="rme-tab-content" id="maincard">

            @if(!isset($dt['OBS']))

                <div class="rme-empty-state">

                    <i class="bi bi-heart-pulse"></i>

                    <div>
                        Data Pemeriksaan Vital Sign Tidak ditemukan
                    </div>

                </div>

            @else

                <div class="table-responsive">

                    <table class="table rme-data-table">

                        <thead>

                            <tr>

                                @if(isset($dt['OBS']))

                                    @foreach ($dt['OBS'] as $n)

                                        <th>
                                            {{ $n['param_name'] }}
                                        </th>

                                    @endforeach

                                @endif

                            </tr>

                            <tr>

                                @if(isset($dt['OBS']))

                                    @foreach ($dt['OBS'] as $ndata)

                                        <td>

                                            <span class="table-main-text">
                                                {{ $ndata['valueQty'] }}
                                            </span>

                                            {{ $ndata['valueUnit'] }}

                                        </td>

                                    @endforeach

                                @endif

                            </tr>

                        </thead>

                    </table>

                </div>

            @endif

        </div>
