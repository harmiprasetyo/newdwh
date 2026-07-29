@extends('layouts.admin')

@section('content')

<div class="container">

    <h4>Master Posyandu</h4>

    <form id="frmPosyandu">

        @csrf

        <div class="mb-3">
            <label>Provinsi</label>
            <select id="provinsi"
                    name="province_code"
                    class="form-control">
            </select>
        </div>

        <div class="mb-3">
            <label>Kabupaten/Kota</label>
            <select id="kota"
                    name="city_code"
                    class="form-control"
                    disabled>
                <option value="">Pilih Kota</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Kecamatan</label>
            <select id="kecamatan"
                    name="district_code"
                    class="form-control"
                    disabled>
                <option value="">Pilih Kecamatan</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Desa</label>
            <select id="desa"
                    name="village_code"
                    class="form-control"
                    disabled>
                <option value="">Pilih Desa</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Fasyankes</label>
            <select id="faskes"
                    name="kodeFaskes"
                    class="form-control"
                    disabled>
                <option value="">Pilih Fasyankes</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Kode Posyandu</label>
            <input type="text"
                   name="kodePosyandu"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Nama Posyandu</label>
            <input type="text"
                   name="namaPosyandu"
                   class="form-control">
        </div>

        <button type="submit"
                class="btn btn-primary">
            Simpan
        </button>

    </form>

</div>

@endsection
@push('scripts')
<script>
    $(function(){

   $.get('/propinsi', function(res){

    $('#provinsi').html(
        '<option value="">-- pilih provinsi --</option>'
    );

    res.forEach(function(item){

        $('#provinsi').append(
            `<option value="${item.code}">
                ${item.name}
            </option>`
        );

    });

});

});


$('#provinsi').change(function(){

    let code = $(this).val();

    $('#kota').prop('disabled',false);

    $.get(
        '/adminpanel/wilayah/listkota?province_code='+code,
        function(res){

            $('#kota').html('');

            res.data.forEach(function(item){

                $('#kota').append(
                    `<option value="${item.code}">
                        ${item.name}
                    </option>`
                );

            });

        }
    );

});


$('#kota').change(function(){

    let code = $(this).val();

    $('#kecamatan').prop('disabled',false);

    $.get(
        '/adminpanel/wilayah/listkecamatan?city_code='+code,
        function(res){

            $('#kecamatan').html('');

            res.data.forEach(function(item){

                $('#kecamatan').append(
                    `<option value="${item.code}">
                        ${item.name}
                    </option>`
                );

            });

        }
    );

});


$('#kecamatan').change(function(){

    let code = $(this).val();

    $('#desa').prop('disabled',false);
    $('#faskes').prop('disabled',false);

    $.get(
        '/adminpanel/wilayah/listdesa?district_code='+code,
        function(res){

            $('#desa').html('');

            res.data.forEach(function(item){

                $('#desa').append(
                    `<option value="${item.code}">
                        ${item.name}
                    </option>`
                );

            });

        }
    );

    $.get(
        '/adminpanel/faskes/list?district_code='+code,
        function(res){

            $('#faskes').html('');

            res.data.forEach(function(item){

                $('#faskes').append(
                    `<option value="${item.kodeFaskes}">
                        ${item.namaFaskes}
                    </option>`
                );

            });

        }
    );

});


$('#frmPosyandu').submit(function(e){

    e.preventDefault();

    $.ajax({

        url:'/adminpanel/posyandu/store',
        method:'POST',

        data:$(this).serialize(),

        success:function(res){

            alert(res.message);
            window.location.href="/adminpanel/posyandu";

            $('#frmPosyandu')[0].reset();
        }

    });

});
    </script>

@endpush

