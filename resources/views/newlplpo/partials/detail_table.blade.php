<div class="card shadow-sm mt-3">

    <div class="card-header bg-success text-white">

        <div class="d-flex justify-content-between">

            <h5 class="mb-0">

                Detail Item Obat

            </h5>

            <span class="badge bg-light text-dark">

                {{ $items->count() }} Item

            </span>

        </div>

    </div>

    <div class="card-body p-0">

        <div style="overflow:auto">

            <table class="table table-bordered table-hover table-sm mb-0">

                <thead class="table-success">

                <tr>

                    <th rowspan="2">No</th>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Nama Obat</th>
                    <th rowspan="2">Sat</th>

                    <th colspan="2">Stok Awal</th>
                    <th colspan="2">Penerimaan</th>
                    <th colspan="2">Persediaan</th>
                    <th colspan="2">Pemakaian</th>

                    <th rowspan="2">Expired</th>

                    <th colspan="2">Stok Akhir</th>

                    <th rowspan="2">Min</th>
                    <th rowspan="2">Opt</th>
                    <th rowspan="2">Permintaan</th>
                    <th colspan="2">Pemberian</th>

                    @if($mode=='pemberian')
                        <th rowspan="2" width="70">Aksi</th>
                    @endif

                </tr>

                <tr>

                    <th>PKD</th>
                    <th>JKN</th>

                    <th>PKD</th>
                    <th>JKN</th>

                    <th>PKD</th>
                    <th>JKN</th>

                    <th>PKD</th>
                    <th>JKN</th>

                    <th>PKD</th>
                    <th>JKN</th>
                    <th>PKD</th>
                    <th>JKN</th>

                </tr>

                </thead>

                <tbody>

                @php
                    $currentProgram = null;
                    $no = 1;
                @endphp

               @foreach($items as $program => $rows)

    @if(strtolower($program) != 'non program')
        <div class="alert alert-primary mt-3 mb-2">
            <strong>{{ $program }}</strong>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0">

                    <tbody>

                    @foreach($rows as $no => $item)

                        @include('newlplpo.partials.row_item')

                    @endforeach

                    </tbody>

                </table>
            </div>
        </div>
    </div>

@endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>
