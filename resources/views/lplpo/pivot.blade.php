@extends('layouts.lplpo')

@section('content')
<div class="container mt-4">

    <h4>📊 Pivot LPLPO per Faskes</h4>

    <div class="row mb-3">
        <div class="col-md-3">
            <input type="number" id="tahun" class="form-control" value="2026">
        </div>

        <div class="col-md-3">
            <button onclick="loadPivot()" class="btn btn-primary">Load</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm" id="pivotTable">
            <thead class="table-light">
                <tr>
                    <th>Faskes</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>Mei</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Agu</th>
                    <th>Sep</th>
                    <th>Okt</th>
                    <th>Nov</th>
                    <th>Des</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</div>
<script>
    function loadPivot() {

    let tahun = document.getElementById('tahun').value;

    fetch(`/lplpo/dinkes/pivot-data?tahun=${tahun}`)
        .then(res => res.json())
        .then(data => {

            let html = '';

            data.forEach(row => {
                html += `
                <tr>
                    <td>${row.namaFaskes}</td>
                    <td>${row.jan}</td>
                    <td>${row.feb}</td>
                    <td>${row.mar}</td>
                    <td>${row.apr}</td>
                    <td>${row.mei}</td>
                    <td>${row.jun}</td>
                    <td>${row.jul}</td>
                    <td>${row.agu}</td>
                    <td>${row.sep}</td>
                    <td>${row.okt}</td>
                    <td>${row.nov}</td>
                    <td>${row.des}</td>
                    <td><b>${row.total}</b></td>
                </tr>`;
            });

            document.querySelector('#pivotTable tbody').innerHTML = html;
        });
}

// auto load
loadPivot();
</script>

@endsection
