@extends('layouts.dashboard.maindash')

@section('content')

<h4>Live Monitoring Pasien</h4>

<!-- STATUS BOX -->
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-info text-white p-3">
            <h6>Arrived</h6>
            <h3 id="arrived">0</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-warning p-3">
            <h6>In Progress</h6>
            <h3 id="progress">0</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white p-3">
            <h6>Finished</h6>
            <h3 id="finished">0</h3>
        </div>
    </div>
</div>

<!-- CHART -->
<div class="card mb-3">
    <div class="card-body">

        <div id="chartJam"></div>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="card-body">
        <h6>Pasien Terbaru</h6>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama</th>
                    <th>Faskes</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="tableRealtime"></tbody>
        </table>
    </div>
</div>



@endsection

@push('scripts')
<script>

let chart;
 window.API_KEY = '{{ config("app.api_key") }}';
function loadRealtime(){

    fetch('/api/encounter/realtime', {
        headers: {
            'X-API-KEY': window.API_KEY
        }
    })
    .then(res => res.json())
    .then(res => {

        // STATUS
        $('#arrived').text(res.stats.arrived || 0);
        $('#progress').text(res.stats['in-progress'] || 0);
        $('#finished').text(res.stats.finished || 0);

        // CHART
      /*  let labels = Object.keys(res.chart);
        let values = Object.values(res.chart);

        if(chart) chart.destroy();

        chart = new ApexCharts(document.querySelector("#chartJam"), {
            chart: { type: 'line', height: 250 },
            series: [{ name: 'Kunjungan', data: values }],
            xaxis: { categories: labels }
        });

        chart.render();
        */

        let labels = res.chart.map(i => i.time);
let values = res.chart.map(i => i.total);

if (chart) chart.destroy();

chart = new ApexCharts(document.querySelector("#chartJam"), {
    chart: {
        type: 'line',
        height: 250,
        animations: {
            enabled: true,
            easing: 'linear',
            dynamicAnimation: {
                speed: 1000
            }
        }
    },
    stroke: {
        curve: 'smooth'
    },
    series: [{
        name: 'Kunjungan',
        data: values
    }],
    xaxis: {
        categories: labels,
        labels: {
            rotate: -45
        }
    },
    title: {
        text: 'Kunjungan 60 Menit Terakhir',
        align: 'left'
    }
});

chart.render();

        // TABLE
        let html = '';

        res.latest.forEach(d => {

            let statusColor = {
                'arrived': 'secondary',
                'in-progress': 'warning',
                'finished': 'success'
            };

            html += `
                <tr>
                    <td>${d.encounter_date}</td>
                    <td>${d.patient?.name ?? '-'}</td>
                    <td>${d.service_provider ?? '-'}</td>
                    <td>${d.location ?? '-'}</td>
                    <td>
                        <span class="badge bg-${statusColor[d.status] ?? 'dark'}">
                            ${d.status}
                        </span>
                    </td>
                </tr>
            `;
        });

        $('#tableRealtime').html(html);

    });
}

// AUTO REFRESH 5 DETIK
setInterval(loadRealtime, 3000);

// LOAD AWAL
loadRealtime();

</script>
@endpush
