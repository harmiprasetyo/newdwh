

@extends('layouts.dashboard.maindash')
@section('content')

   <h3 class="mb-3">📊 Dashboard</h3>

<!-- FILTER -->
<div class="card mb-3">
    <div class="card-body">
        <div class="row">

            <div class="col-md-2">
                <select id="bulan" class="form-control">
                    <option value="">Bulan</option>
                    @for($i=1;$i<=12;$i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <select id="tahun" class="form-control">
                    <option value="">Tahun</option>
                    @for($i=date('Y');$i>=2020;$i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>



            @if($groupId == 2 || $groupId == 1)
<div class="col-md-4">
    <select id="faskes" class="form-control" style="width:100%">
        <option value="">-- Pilih Faskes --</option>
    </select>
</div>
@endif

            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="loadData()">🔍 Filter</button>
            </div>

        </div>
    </div>
</div>

<!-- DASHBOARD GRID -->
<div class="row">

    <!-- LOCATION -->
    <div class="col-md-6">

        <!-- CHART LOCATION -->
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h6 class="mb-2">Kunjungan per Location</h6>
                <div id="chartLocation"></div>
            </div>
        </div>

        <!-- TABLE LOCATION -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Rekap Location</h6>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Location</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="tableLocation"></tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- PROVIDER (ONLY GROUP 2) -->
    @if($groupId == 2 || $groupId== 1)
    <div class="col-md-6">

        <!-- CHART PROVIDER -->
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h6 class="mb-2">Kunjungan per Service Provider</h6>
                <div id="chartProvider"></div>
            </div>
        </div>

        <!-- TABLE PROVIDER -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Rekap Service Provider</h6>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Provider</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="tableProvider"></tbody>
                </table>
            </div>
        </div>

    </div>
    @endif

</div>
@endsection

@push('scripts')


<script>
    window.API_KEY = '{{ config("app.api_key") }}';
let chartLocation, chartProvider;



function loadData() {

    let bulan = document.getElementById('bulan').value;
    let tahun = document.getElementById('tahun').value;
    let faskes = document.getElementById('faskes')?.value;

    let url = `/api/dashboard?bulan=${bulan}&tahun=${tahun}`;

    if (faskes) {
        url += `&faskes=${faskes}`;
    }

    fetch(url, {
        headers: {
            'X-API-KEY': window.API_KEY,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(res => {

        let data = res.data;

        // ======================
        // LOCATION
        // ======================
        let labels = data.per_location.map(i => i.location ?? '-');
        let values = data.per_location.map(i => i.total ?? 0);

        if (chartLocation) chartLocation.destroy();

        chartLocation = new ApexCharts(document.querySelector("#chartLocation"), {
            chart: {
                type: 'bar',
                height: 280 // 🔥 diperkecil
            },
            title: {
                text: 'Kunjungan per Location',
                align: 'left'
            },
            series: [{
                name: 'Total',
                data: values
            }],
            xaxis: {
                categories: labels
            }
        });

        chartLocation.render();

        // ======================
        // TABLE LOCATION
        // ======================
        let htmlLoc = '';
        data.per_location.forEach(i => {
            htmlLoc += `<tr>
                <td>${i.location ?? '-'}</td>
                <td>${i.total ?? 0}</td>
            </tr>`;
        });

        document.getElementById('tableLocation').innerHTML = htmlLoc;

        // ======================
        // PROVIDER
        // ======================
        if (data.per_provider) {

            let pLabel = data.per_provider.map(i => i.service_provider ?? '-');
            let pVal = data.per_provider.map(i => i.total ?? 0);

            if (chartProvider) chartProvider.destroy();

            chartProvider = new ApexCharts(document.querySelector("#chartProvider"), {
                chart: {
                    type: 'bar',
                    height: 280 // 🔥 sama biar konsisten
                },
                title: {
                    text: 'Kunjungan per Service Provider',
                    align: 'left'
                },
                series: [{
                    name: 'Total',
                    data: pVal
                }],
                xaxis: {
                    categories: pLabel
                }
            });

            chartProvider.render();

            // ======================
            // TABLE PROVIDER
            // ======================
            let htmlProv = '';
            data.per_provider.forEach(i => {
                htmlProv += `<tr>
                    <td>${i.service_provider ?? '-'}</td>
                    <td>${i.total ?? 0}</td>
                </tr>`;
            });

            document.getElementById('tableProvider').innerHTML = htmlProv;
        }

    });
}

// load awal
loadData();


$(function () {

    $('#faskes').select2({

        placeholder: 'Cari Faskes...',

        allowClear: true,

        ajax: {

            url: '/api/organizations',

            dataType: 'json',

            delay: 300,
            headers: {
            'X-API-KEY': window.API_KEY,
            'Accept': 'application/json'
        },

            data: function(params){

                return {

                    search: params.term

                };

            },

            processResults: function(data){

                return {

                    results: $.map(data.data,function(item){

                        return {

                            id: item.name,
                            text: item.name

                        }

                    })

                };

            },

            cache:true

        }

    });


        // otomatis reload ketika memilih faskes
    $('#faskes').on('select2:select', function (e) {
        loadData();
    });

    // otomatis reload ketika pilihan dihapus
    $('#faskes').on('select2:clear', function (e) {
        loadData();
    });


});
</script>
@endpush


