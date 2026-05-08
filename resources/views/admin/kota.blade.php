@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<h4>Map Provinsi</h4>

<div id="map" style="height: 500px; border-radius:10px;"></div>

<h4>Data Kota</h4>

<div class="row mb-3">
    <div class="col-md-4">
        <label>Pilih Provinsi</label>
        <select id="provinsi" class="form-control">
            <option value="">-- pilih --</option>
        </select>
    </div>
</div>

<table id="tableKota" class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Kota</th>
            <th>Latitude</th>
            <th>Longitude</th>
        </tr>
    </thead>
</table>

<script>
$(function(){



    // 🔥 Load provinsi
    $.get('/propinsi', function(res){
        res.forEach(p=>{
            $('#provinsi').append(`<option value="${p.code}">${p.name}</option>`);
        });
    });

    // 🔥 DataTable
  let table = $('#tableKota').DataTable({
    processing: true,
    ajax: {
        url: '/adminpanel/wilayah/listkota',
        data: function(d){
            d.province_code = $('#provinsi').val();
        }
    },
    columns: [
        { data: 'code' },
        { data: 'name' },
        { data:'lat' },
        { data: 'lon' }
    ]
});

// 🔥 trigger reload
$('#provinsi').change(function(){
    table.ajax.reload();
});

});



$('#tableKota tbody').on('click', 'tr', function(){

    let data = $('#tableKota').DataTable().row(this).data();

    // 🔥 simpan city_code global
    window.selectedCity = data.code;

    // reload kecamatan
    tableKecamatan.ajax.reload();

});

</script>



<script>
$(function(){

      $.get('/adminpanel/geojson/provinsi', function(data){

    let geoLayer = L.geoJSON(data, {

        style: {
        color: 'red',      // 🔥 warna garis
        weight: 2,         // tebal garis
        fillColor: 'blue', // warna isi
        fillOpacity: 0.3
        },

        onEachFeature: function(feature, layer){

            let kode = feature.properties.KODE_PROV; // 🔥 langsung ada
            let nama = feature.properties.PROVINSI || feature.properties.name;

            layer.bindPopup(`<b>${nama}</b>`);

            layer.on('click', function(){

                // 🔥 langsung sync ke dropdown
                $('#provinsi').val(kode).trigger('change');

                map.fitBounds(this.getBounds());
            });

              layer.on('mouseover', function(){
            this.setStyle({
                fillColor: 'yellow',   // 🔥 garis berubah
                weight: 3
            });
        });

        layer.on('mouseout', function(){
            geoLayer.resetStyle(this); // balik normal
        });

        }

    }).addTo(map);

});

    let map = L.map('map').setView([-2.5,118],5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    let bounds = [];

    $.get('/adminpanel/wilayah/mapprovince', function(res){

        res.forEach(p => {

            let lat = parseFloat(p.lat);
            let lon = parseFloat(p.lon);

            if(!lat || !lon) return;

            let marker = L.marker([lat, lon]).addTo(map)
                .bindPopup(`<b>${p.name}</b>`);

            // 🔥 KLIK MAP → FILTER TABLE
           let activeMarker;

marker.on('click', function(){

    if(activeMarker){
        activeMarker.setOpacity(0.5);
    }

    this.setOpacity(1);
    activeMarker = this;

    $('#provinsi').val(p.code).trigger('change');
});

            bounds.push([lat, lon]);

        });

        if(bounds.length){
            map.fitBounds(bounds);
        }

    });

});
</script>

@endsection
