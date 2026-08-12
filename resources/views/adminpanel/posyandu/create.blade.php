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
$(function () {

    // =====================================================
    // INITIAL STATE
    // =====================================================

    $('#kota').prop('disabled', true);
    $('#kecamatan').prop('disabled', true);
    $('#desa').prop('disabled', true);
    $('#faskes').prop('disabled', true);


    // =====================================================
    // LOAD PROVINSI
    // =====================================================

    $.get('/propinsi', function (res) {

        $('#provinsi').html(
            '<option value="">-- Pilih Provinsi --</option>'
        );

        res.forEach(function (item) {

            $('#provinsi').append(`
                <option value="${item.code}">
                    ${item.name}
                </option>
            `);

        });

    });


    // =====================================================
    // PROVINSI → KOTA
    // =====================================================

    $('#provinsi').on('change', function () {

        let code = $(this).val();

        // Reset semua level di bawahnya
        $('#kota')
            .html('<option value="">-- Pilih Kota --</option>')
            .prop('disabled', true);

        $('#kecamatan')
            .html('<option value="">-- Pilih Kecamatan --</option>')
            .prop('disabled', true);

        $('#desa')
            .html('<option value="">-- Pilih Desa --</option>')
            .prop('disabled', true);

        $('#faskes')
            .html('<option value="">-- Pilih Fasyankes --</option>')
            .prop('disabled', true);


        if (!code) {
            return;
        }


        $.get(
            '/adminpanel/wilayah/listkota?province_code=' + code,
            function (res) {

                let html =
                    '<option value="">-- Pilih Kota --</option>';

                res.data.forEach(function (item) {

                    html += `
                        <option value="${item.code}">
                            ${item.name}
                        </option>
                    `;

                });

                $('#kota')
                    .html(html)
                    .prop('disabled', false);

            }
        );

    });


    // =====================================================
    // KOTA → KECAMATAN
    // =====================================================

    $('#kota').on('change', function () {

        let code = $(this).val();

        // Reset level bawah
        $('#kecamatan')
            .html('<option value="">-- Pilih Kecamatan --</option>')
            .prop('disabled', true);

        $('#desa')
            .html('<option value="">-- Pilih Desa --</option>')
            .prop('disabled', true);

        $('#faskes')
            .html('<option value="">-- Pilih Fasyankes --</option>')
            .prop('disabled', true);


        if (!code) {
            return;
        }


        $.get(
            '/adminpanel/wilayah/listkecamatan?city_code=' + code,
            function (res) {

                let html =
                    '<option value="">-- Pilih Kecamatan --</option>';

                res.data.forEach(function (item) {

                    html += `
                        <option value="${item.code}">
                            ${item.name}
                        </option>
                    `;

                });

                $('#kecamatan')
                    .html(html)
                    .prop('disabled', false);

            }
        );

    });


    // =====================================================
    // KECAMATAN → DESA + FASKES
    // =====================================================

    $('#kecamatan').on('change', function () {

        let code = $(this).val();


        // Reset Desa
        $('#desa')
            .html('<option value="">-- Pilih Desa --</option>')
            .prop('disabled', true);


        // Reset Faskes
        $('#faskes')
            .html('<option value="">-- Pilih Fasyankes --</option>')
            .prop('disabled', true);


        if (!code) {
            return;
        }


        // =================================================
        // LOAD DESA
        // =================================================

        $.get(
            '/adminpanel/wilayah/listdesa?district_code=' + code,
            function (res) {

                let html =
                    '<option value="">-- Pilih Desa --</option>';

                res.data.forEach(function (item) {

                    html += `
                        <option value="${item.code}">
                            ${item.name}
                        </option>
                    `;

                });

                $('#desa')
                    .html(html)
                    .prop('disabled', false);

            }
        );


        // =================================================
        // LOAD FASKES
        // =================================================

        $.get(
            '/adminpanel/faskes/list?district_code=' + code,
            function (res) {

                let html =
                    '<option value="">-- Pilih Fasyankes --</option>';

                res.data.forEach(function (item) {

                    html += `
                        <option value="${item.kodeFaskes}">
                            ${item.namaFaskes}
                        </option>
                    `;

                });

                $('#faskes')
                    .html(html)
                    .prop('disabled', false);

            }
        );

    });


    // =====================================================
    // SUBMIT
    // =====================================================

    $('#frmPosyandu').on('submit', function (e) {

        e.preventDefault();

        $.ajax({

            url: '/adminpanel/posyandu/store',

            method: 'POST',

            data: $(this).serialize(),

            success: function (res) {

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(function () {
                    window.location.href =
                        '/adminpanel/posyandu';
                }, 1500);

            },

            error: function (xhr) {

                console.error(xhr);

                let message =
                    xhr.responseJSON?.message ??
                    'Terjadi kesalahan saat menyimpan data.';

                if (xhr.responseJSON?.errors) {

                    message = Object.values(
                        xhr.responseJSON.errors
                    )
                    .flat()
                    .join('<br>');

                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: message
                });

            }

        });

    });

});
</script>
@endpush

