<!DOCTYPE html>
<html>
<head>
    <title>Data Wilayah (Tabel)</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</head>
<body>

<h2>📊 Data Wilayah Indonesia</h2>

<table id="wilayahTable" class="display">
    <thead>
        <tr>
            <th>Provinsi</th>
            <th>Kota</th>
            <th>Kecamatan</th>
            <th>Desa</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
$(async function () {

    let table = $('#wilayahTable').DataTable();

    // 🔥 Load semua provinsi
    let provinsi = await $.get('/api/wilayah/provinsi');

    for (let prov of provinsi) {

        let kotaList = await $.get('/api/wilayah/kota?province_code=' + prov.code);

        for (let kota of kotaList) {

            let kecList = await $.get('/api/wilayah/kecamatan?city_code=' + kota.code);

            for (let kec of kecList) {

                let desaList = await $.get('/api/wilayah/desa?district_code=' + kec.code);

                for (let desa of desaList) {

                    table.row.add([
                        prov.name,
                        kota.name,
                        kec.name,
                        desa.name
                    ]).draw(false);

                }
            }
        }
    }

});
</script>

</body>
</html>
