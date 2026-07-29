@extends('layouts.admin')

@section('content')

<div class="row mb-3">

    <div class="col-md-3">
        <label>Provinsi</label>
        <select id="provinsi" class="form-control">
            <option value="">-- pilih provinsi --</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>Kota</label>
        <select id="kota" class="form-control" disabled>
            <option value="">-- pilih kota --</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>Kecamatan</label>
        <select id="kecamatan" class="form-control" disabled>
            <option value="">-- pilih kecamatan --</option>
        </select>
    </div>

</div>

<table id="tableDesa" class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Desa</th>
            <th>Latitude</th>
            <th>Longitude</th>
        </tr>
    </thead>
</table>

<script>
$(function(){

    // 🔥 LOAD PROVINSI
     $.get('/propinsi', function(res){
        res.forEach(p=>{
            $('#provinsi').append(`<option value="${p.code}">${p.name}</option>`);
        });
    });

    // 🔥 DATATABLE DESA
    let table = $('#tableDesa').DataTable({
        processing: true,
        ajax: {
            url: '/adminpanel/wilayah/listdesa',
            data: function(d){

                let prov = $('#provinsi').val();
                let kota = $('#kota').val();
                let kec  = $('#kecamatan').val();

                if(!prov && !kota && !kec){
                    return false;
                }

                d.province_code = prov;
                d.city_code     = kota;
                d.district_code = kec;

            }
        },
        columns: [
            { data: 'code' },
            { data: 'name' },
            { data: 'lat' },
            { data: 'lon'}
        ]
    });

    // 🔥 PROVINSI → KOTA
    $('#provinsi').change(function(){

        let code = $(this).val();

        $('#kota').html('<option value="">-- pilih kota --</option>').prop('disabled', !code);
        $('#kecamatan').html('<option value="">-- pilih kecamatan --</option>').prop('disabled', true);

        if(!code){
            table.clear().draw();
            return;
        }

        $.get('/adminpanel/wilayah/listkota?province_code='+code, function(res){
            res.data.forEach(k=>{
                $('#kota').append(`<option value="${k.code}">${k.name}</option>`);
            });
        });

        table.ajax.reload();
    });

    // 🔥 KOTA → KECAMATAN
    $('#kota').change(function(){

        let code = $(this).val();

        $('#kecamatan').html('<option value="">-- pilih kecamatan --</option>').prop('disabled', !code);

        if(!code){
            table.ajax.reload();
            return;
        }

        $.get('/adminpanel/wilayah/listkecamatan?city_code='+code, function(res){
            res.data.forEach(k=>{
                $('#kecamatan').append(`<option value="${k.code}">${k.name}</option>`);
            });
        });

        table.ajax.reload();
    });

    // 🔥 KECAMATAN → DESA
    $('#kecamatan').change(function(){
        table.ajax.reload();
    });

});
</script>
@endsection
