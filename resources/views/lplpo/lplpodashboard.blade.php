@extends('layouts.lplpo')

@section('content')
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard LPLPO</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <h3 class="mb-4">📊 Dashboard LPLPO</h3>

    <!-- FILTER -->
    <div class="row mb-4">
        <div class="col-md-3">
            <select id="bulan" class="form-control">
                <option value="">Semua Bulan</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>

        <div class="col-md-3">
            <input type="number" id="tahun" class="form-control" placeholder="Tahun">
        </div>

        <div class="col-md-3">
            <input type="text" id="faskes" class="form-control" placeholder="Kode Faskes">
        </div>

        <div class="col-md-3">
            <button onclick="loadDashboard()" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>

    <!-- KPI -->
    <div class="row text-center mb-4">

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Total Obat</h6>
                    <h3 id="total_obat">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Total Pemakaian</h6>
                    <h3 id="total_pemakaian">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Stok Akhir</h6>
                    <h3 id="total_stok">0</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- CHART -->
    <div class="card mb-4">
        <div class="card-body">
            <canvas id="chart"></canvas>
        </div>
    </div>

    <!-- TOP OBAT -->
    <div class="card">
        <div class="card-header">Top 5 Obat</div>
        <div class="card-body">
            <ul id="top_obat"></ul>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let chart;

function loadDashboard() {

    fetch(`/lplpo/dashboard-data?bulan=${bulan.value}&tahun=${tahun.value}&faskes=${faskes.value}`)
        .then(res => res.json())
        .then(data => {

            document.getElementById('total_obat').innerText = data.total_obat;
            document.getElementById('total_pemakaian').innerText = data.total_pemakaian;
            document.getElementById('total_stok').innerText = data.total_stok_akhir;

            // TOP OBAT
            let list = '';
            data.top_obat.forEach(o => {
                list += `<li>${o.nama_obat} (${o.total})</li>`;
            });
            document.getElementById('top_obat').innerHTML = list;

            // CHART
            let labels = data.chart.map(x => x.bulan);
            let values = data.chart.map(x => x.total);

            if(chart) chart.destroy();

            chart = new Chart(document.getElementById('chart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pemakaian Obat',
                        data: values
                    }]
                }
            });
        });
}

// load awal
loadDashboard();
</script>

</body>
</html>
@endsection
