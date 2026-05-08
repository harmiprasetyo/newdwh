@extends('layouts.admin')

@section('content')
<h4>Data Kecamatan</h4>

<div class="row mb-3">
    <div class="col-md-4">
        <label>Provinsi</label>
        <select id="provinsi" class="form-control">
            <option value="">-- pilih provinsi --</option>
        </select>
    </div>

    <div class="col-md-4">
        <label>Kota</label>
        <select id="kota" class="form-control">
            <option value="">-- pilih kota --</option>
        </select>
    </div>
</div>

<table id="tableKecamatan" class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Kecamatan</th>
            <th>Latitude</th>
            <th>Longitude</th>
        </tr>
    </thead>
</table>

<script>
   $(function(){
$('#kota').prop('disabled', true);

$('#provinsi').change(function(){
    let code = $(this).val();

    $('#kota').prop('disabled', !code);
});
    // 🔥 load provinsi
    $.get('/api/wilayah/provinsi', function(res){
        res.forEach(p=>{
            $('#provinsi').append(`<option value="${p.code}">${p.name}</option>`);
        });
    });

    // 🔥 load kota berdasarkan provinsi
    $('#provinsi').change(function(){

        let code = $(this).val();

        $('#kota').html('<option value="">-- pilih kota --</option>');

        if(!code) return;

        $.get('/adminpanel/wilayah/listkota?province_code='+code, function(res){
            res.data.forEach(k=>{
                $('#kota').append(`<option value="${k.code}">${k.name}</option>`);
            });
        });

        table.ajax.reload();
    });

    // 🔥 datatable kecamatan
    let table = $('#tableKecamatan').DataTable({
        processing: true,
        ajax: {
            url: '/adminpanel/wilayah/listkecamatan',
            data: function(d){
                 let prov = $('#provinsi').val();
        let kota = $('#kota').val();

        // 🔥 STOP kalau dua-duanya kosong
        if(!prov && !kota){
            return false;
        }else if(prov && !kota){

        return false; // 🔥 STOP kalau hanya provinsi yang dipilih (kota wajib)
        }


                d.province_code = $('#provinsi').val();
                d.city_code     = $('#kota').val();
            }
        },
        columns: [
            { data: 'code' },
            { data: 'name' },
            { data: 'lat' },
            { data: 'lon' }
        ]
    });

    // 🔥 filter kota
    $('#kota').change(function(){
        table.ajax.reload();
    });

});
    </script>

    @endsection
