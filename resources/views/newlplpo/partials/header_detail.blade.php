<div class="card mb-3 shadow-sm">

    <div class="card-header bg-success text-white">
        <strong>Informasi Laporan</strong>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <table class="table table-sm table-borderless">

                    <tr>
                        <th width="180">Nomor LPLPO</th>
                        <td>{{ $report->nomor_lplpo }}</td>
                    </tr>

                    <tr>
                        <th>Nama Faskes</th>
                        <td>{{ $faskes->namaFaskes ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Bulan</th>
                        <td>{{ bulan($report->bulan) }}</td>
                    </tr>

                    <tr>
                        <th>Tahun</th>
                        <td>{{ $report->tahun }}</td>
                    </tr>

                </table>

            </div>

            <div class="col-md-6">

                <table class="table table-sm table-borderless">

                    <tr>
                        <th width="180">Status</th>
                        <td>
                            <span class="badge bg-primary">
                                @php
                                    $status = match ($report->report_status) {
                                         'DRAFT'=>'DRAFT' ,
                                         'SUBMITED'=>'TERKIRIM' ,
                                         'VERIFIED'=>'DIVERIFIKASI',
                                         'REJECTED'=>'DITOLAK',
                                         'FINAL'=>'SELESAI',
                                         default=>'NEW'
                                    }
                                @endphp
                                {{ $status }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ $report->created_at }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>
