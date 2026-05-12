@extends('layouts.lplpo')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<select id="faskes" multiple class="form-control">
    <!-- isi dari DB -->
</select>
<input type="number" id="tahun" value="2026">

<!-- <div class="row text-center mb-4">

    <div class="col">
        <div class="card shadow">
            <div class="card-body">
                <h6>Total Faskes</h6>
                <h3 id="total_faskes"></h3>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card shadow">
            <div class="card-body">
                <h6>Total Pemakaian</h6>
                <h3 id="total_pemakaian"></h3>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card shadow">
            <div class="card-body">
                <h6>Total Stok</h6>
                <h3 id="total_stok"></h3>
            </div>
        </div>
    </div>

</div> -->

<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        Grafik Tren LPLPO per Faskes
    </div>
    <div class="card-body">
        <canvas id="chartPivot" height="100"></canvas>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-danger text-white">
        ⚠️ Stok Kritis
    </div>
    <div class="card-body">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Obat</th>
                    <th>Faskes</th>
                    <th>Stok</th>
                    <th>Optimum</th>
                </tr>
            </thead>
            <tbody id="stok_kritis"></tbody>
        </table>
    </div>
</div>


<script>
let chart;

function loadChart() {

    let tahun = document.getElementById('tahun').value;

    fetch(`/lplpo/dinkes/pivot-chart?tahun=${tahun}`)
        .then(res => res.json())
        .then(data => {

            let labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

            let grouped = {};

            // grouping data per faskes
            data.forEach(item => {
                if(!grouped[item.namaFaskes]){
                    grouped[item.namaFaskes] = Array(12).fill(0);
                }

                grouped[item.namaFaskes][item.bulan - 1] = item.total;
            });

            let datasets = [];
            let colors = [
                '#FF6384','#36A2EB','#FFCE56',
                '#4CAF50','#9C27B0','#FF9800'
            ];

            let i = 0;

            for (let faskes in grouped) {
                datasets.push({
                    label: faskes,
                    data: grouped[faskes],
                    borderColor: colors[i % colors.length],
                    fill: false,
                    tension: 0.3
                });
                i++;
            }

            // destroy chart lama
            if(chart){
                chart.destroy();
            }

            const ctx = document.getElementById('chartPivot').getContext('2d');

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

        });
}

// load pertama
loadChart();
function applyFilter(){
    loadPivot(); // tabel
    loadChart(); // grafik
}
</script>
@endsection
