<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        body { display: flex; }
        .sidebar {
            width: 220px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            margin: 10px 0;
            text-decoration: none;
        }
        .sidebar a.active { font-weight: bold; }

        .content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4>Menu</h4>
    <a href="/home">🏠 Beranda</a>
    <a href="/dashboard" class="active">📊 Dashboard</a>
    <a href="/logout">🚪 Logout</a>
</div>

<!-- CONTENT -->
<div class="content">

    <h3>Dashboard</h3>

    <!-- FILTER -->
    <div class="row mb-3">
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

        @if($groupId == 2)
        <div class="col-md-3">
            <input type="text" id="faskes" class="form-control" placeholder="Faskes">
        </div>
        @endif

        <div class="col-md-2">
            <button class="btn btn-primary" onclick="loadData()">Filter</button>
        </div>
    </div>

    <!-- CHART -->
    <div class="card mb-3">
        <div class="card-body">
            <div id="chartLocation"></div>
        </div>
    </div>

    @if($groupId == 2)
    <div class="card mb-3">
        <div class="card-body">
            <div id="chartProvider"></div>
        </div>
    </div>
    @endif

    <!-- TABLE -->
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
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

<script>
    window.API_KEY = '{{ config("app.api_key") }}';

let chartLocation, chartProvider;

function loadData() {

    let bulan = document.getElementById('bulan').value;
    let tahun = document.getElementById('tahun').value;
    let faskes = document.getElementById('faskes')?.value;

    let url = '/api/dashboard';

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

        // LOCATION
        let labels = data.per_location.map(i => i.location);
        let values = data.per_location.map(i => i.total);

        if (chartLocation) chartLocation.destroy();

        chartLocation = new ApexCharts(document.querySelector("#chartLocation"), {
            chart: { type: 'bar' },
            series: [{ data: values }],
            xaxis: { categories: labels }
        });

        chartLocation.render();

        // TABLE
        let html = '';
        data.per_location.forEach(i => {
            html += `<tr>
                <td>${i.location}</td>
                <td>${i.total}</td>
            </tr>`;
        });

        document.getElementById('tableLocation').innerHTML = html;

        // PROVIDER
        if (data.per_provider) {

            let pLabel = data.per_provider.map(i => i.service_provider);
            let pVal = data.per_provider.map(i => i.total);

            if (chartProvider) chartProvider.destroy();

            chartProvider = new ApexCharts(document.querySelector("#chartProvider"), {
                chart: { type: 'bar' },
                series: [{ data: pVal }],
                xaxis: { categories: pLabel }
            });

            chartProvider.render();
        }

    });
}

// load awal
loadData();
</script>

</body>
</html>
