@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<h4>Map Provinsi</h4>

<div id="map" style="height: 500px; border-radius:10px;"></div>

<table id="tableProvinsi" class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Provinsi</th>
            <th>Latitude</th>
            <th>Longitude</th>
        </tr>
    </thead>
</table>


<script>
    $('#tableProvinsi').DataTable({
    processing: true,
    ajax: '/adminpanel/wilayah/listpropinsi',
    columns: [
        { data: 'code' },
        { data: 'name' },
        { data: 'lat' },
        { data: 'lon' }
    ]
});



$(function(){

    $.get('/adminpanel/geojson/provinsi', function(data){

    let geoLayer = L.geoJSON(data, {

        style: {
            color: 'red',      // 🔥 warna garis
            weight: 1,
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


    let map = L.map('map').setView([-2.5, 118], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

  let icon = L.icon({
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
});

    let bounds = [];

    $.get('/adminpanel/wilayah/mapprovince', function(res){

        res.forEach(p => {

            if(p.lat && p.lon){

                let marker = L.marker([p.lat, p.lon], {icon: icon}).addTo(map);

                marker.bindPopup(`
                    <b>${p.name}</b><br>
                    Code: ${p.code}
                `);

                // ✅ HARUS DI SINI
                marker.on('click', function(){
                    window.open(`https://www.google.com/maps?q=${p.lat},${p.lon}`);
                });

                bounds.push([p.lat, p.lon]);
            }

        });

        if(bounds.length){
            map.fitBounds(bounds);
        }

    });
});

    </script>
@endsection
