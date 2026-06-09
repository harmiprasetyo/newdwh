@extends('layouts.lplpo')

@section('content')

<h4 class="mb-3">Dashboard LPLPO</h4>

<!-- FILTER -->
<div class="row mb-3">
    <div class="col-md-2">
        <select id="bulan" class="form-control">
            @for($i=1;$i<=12;$i++)
            <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div class="col-md-2">
        <select id="tahun" class="form-control">
            @for($i=date('Y');$i>=2020;$i--)
            <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary" onclick="loadDashboard()">Filter</button>
    </div>

    <div class="col-md-3">
        <button class="btn btn-success" onclick="exportExcel()">⬇ Export Excel</button>
    </div>
</div>

<!-- INFO BOX -->
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3">
            <h6>Obat Dimonitor</h6>
            <h3 id="obatMonitor">0</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-warning p-3">
            <h6>Risiko Stock Out</h6>
            <h3 id="stokOut">0</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-danger text-white p-3">
            <h6>Stok Kritis</h6>
            <h3 id="stokKritis">0</h3>
        </div>
    </div>
</div>

<!-- CHART -->
<div class="card mb-3">
    <div class="card-body">
        <h6>Distribusi Stok per Faskes</h6>
        <div id="chartFaskes"></div>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="card-body">
        <h6>Detail per Faskes</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Faskes</th>
                    <th>Kritis</th>
                    <th>Risk</th>
                    <th>Aman</th>
                </tr>
            </thead>
            <tbody id="tableFaskes"></tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>

let chart;

    window.API_KEY = '{{ config("app.api_key") }}';

function loadDashboard(){


    let bulan = $('#bulan').val();
    let tahun = $('#tahun').val();

    fetch(`/api/dashboard-lplpo?bulan=${bulan}&tahun=${tahun}`, {
    headers: {
        'X-API-KEY': window.API_KEY,
        'Accept': 'application/json'
    }
})
    .then(res => res.json())
    .then(res => {

        // BOX
        $('#obatMonitor').text(res.summary.obat_monitor);
        $('#stokOut').text(res.summary.stok_out);
        $('#stokKritis').text(res.summary.stok_kritis);

        let labels = Object.keys(res.chart);
        let kritis = [], risk = [], aman = [];

        let html = '';

        labels.forEach(f => {
            kritis.push(res.chart[f].kritis);
            risk.push(res.chart[f].risk);
            aman.push(res.chart[f].aman);

            html += `
                <tr>
                    <td>${f}</td>
                    <td>${res.chart[f].kritis}</td>
                    <td>${res.chart[f].risk}</td>
                    <td>${res.chart[f].aman}</td>
                </tr>
            `;
        });

        $('#tableFaskes').html(html);

        if(chart) chart.destroy();

        chart = new ApexCharts(document.querySelector("#chartFaskes"), {
            chart: { type: 'bar', height: 300 },
            series: [
                { name: 'Kritis', data: kritis },
                { name: 'Risk', data: risk },
                { name: 'Aman', data: aman }
            ],
            xaxis: { categories: labels }
        });

        chart.render();
    });
}

function exportExcel(){
    let bulan = $('#bulan').val();
    let tahun = $('#tahun').val();

    window.open(`/api/dashboard-lplpo/export?bulan=${bulan}&tahun=${tahun}`);
}

// load awal
loadDashboard();

</script>
@endpush
