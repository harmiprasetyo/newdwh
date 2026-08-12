@push('script')

<script>

$(function(){

    let selectedObat = null;

    function resetFormItem(){

        $('#frmItem')[0].reset();

        selectedObat = null;

        $('#kode_obat').val('');
        $('#nama_obat').val('');
        $('#satuan').val('');

        $('#stok_minimum').val(0);
        $('#stok_optimum').val(0);

        $('#previewKode').html('-');
        $('#previewNama').html('-');
        $('#previewProgram').html('-');

    }


    document
    .getElementById('offcanvasObat')
    .addEventListener('hidden.bs.offcanvas',function(){

        resetFormItem();

    });




    /*==================================================
      DATATABLE MASTER OBAT
    ==================================================*/

  let tableObat = $('#tblMasterObat').DataTable({

    processing: true,

    serverSide: true,

    searching: true,

    ordering: true,

    responsive: true,

    ajax: {

        url: "{{ route('newlplpo.masterdataobat.datatableforcanvas') }}",

        data: function (d) {

            d.tahun = $('#tahunCanvas').val();

            /*
             * Untuk user faskes sebenarnya tidak wajib
             * karena backend mengambil dari auth()->user()
             */
            d.kodeFaskes = $('#kodeFaskes').val() || '';

        }

    },

    columns: [

        {
            data: 'kode_obat',
            name: 'master_obat.kode_obat'
        },

        {
            data: 'nama_obat',
            name: 'master_obat.nama_obat',

            render: function (data, type, row) {

                /*
                 * Tandai obat NAPZA
                 */
                if (row.obat_napza === 'ya') {

                    return `
                        <span class="text-danger fw-bold">
                            ${data}
                            <span class="badge bg-danger ms-1">
                                NAPZA
                            </span>
                        </span>
                    `;

                }

                return data;

            }

        },

        {
            data: 'satuan',
            name: 'master_obat.satuan'
        },

        {
            data: null,

            orderable: false,

            searchable: false,

            className: 'text-center',

            render: function (data) {

                return `
                    <button
                        type="button"
                        class="btn btn-success btn-sm pilih-obat"

                        data-id="${data.id}"

                        data-kode="${data.kode_obat}"

                        data-nama="${data.nama_obat}"

                        data-satuan="${data.satuan}"

                        data-min="${data.stok_minimal ?? 0}"

                        data-opt="${data.stok_optimum ?? 0}"

                        data-napza="${data.obat_napza ?? 'tidak'}"

                        data-esensial="${data.obat_esensial ?? 'tidak'}"

                        data-formularium="${data.obat_formularium_puskesmas ?? 'tidak'}"

                    >

                        <i class="bi bi-check-circle me-1"></i>

                        Pilih

                    </button>
                `;

            }

        }

    ]

});

    $('#btnTambah').on('click',function(){

    resetFormItem();

    bootstrap
        .Offcanvas
        .getOrCreateInstance(document.getElementById('offcanvasObat'))
        .show();

});

    /*==================================================
      SEARCH
    ==================================================*/

    $('#keywordObat').keyup(function(){

        tableObat.search($(this).val()).draw();

    });

/*==================================================
      Loadstok Setting
    ==================================================*/
    function loadStokSetting()
{

    if (!selectedObat) {
        return;
    }


    let kodeFaskes =
        $('#kodeFaskes').val();

    let tahun =
        $('#tahun').val();


    if (!kodeFaskes || !tahun) {

        return;

    }


    $.ajax({

        url:
            "{{ route('newlplpo.stok-esensial.setting') }}",

        type:
            "GET",

        data: {

            kode_obat:
                selectedObat.kode,

            kodeFaskes:
                kodeFaskes,

            tahun:
                tahun

        },

        success:
            function (res) {

                /*
                |--------------------------------------------------------------------------
                | DEFAULT
                |--------------------------------------------------------------------------
                */

                let stokMinimal = 0;

                let stokOptimum = 0;

                let obatEsensial = 'noe';

                let formularium = 'false';


                /*
                |--------------------------------------------------------------------------
                | JIKA SETTING DITEMUKAN
                |--------------------------------------------------------------------------
                */

                if (res.exists) {

                    stokMinimal =
                        res.stok_minimal ?? 0;

                    stokOptimum =
                        res.stok_optimum ?? 0;

                    obatEsensial =
                        res.obat_esensial ?? 'noe';

                    formularium =
                        res.obat_formularium_puskesmas ?? 'false';

                }


                /*
                |--------------------------------------------------------------------------
                | SET FORM
                |--------------------------------------------------------------------------
                */

                $('#stok_minimum')
                    .val(stokMinimal);

                $('#stok_optimum')
                    .val(stokOptimum);

                $('#obat_esensial')
                    .val(obatEsensial);

                $('#obat_formularium_puskesmas')
                    .val(formularium);


                /*
                |--------------------------------------------------------------------------
                | DEBUG
                |--------------------------------------------------------------------------
                */

                console.log(
                    'Setting obat:',
                    {
                        kode_obat:
                            selectedObat.kode,

                        kodeFaskes:
                            kodeFaskes,

                        tahun:
                            tahun,

                        stok_minimal:
                            stokMinimal,

                        stok_optimum:
                            stokOptimum,

                        obat_esensial:
                            obatEsensial,

                        formularium:
                            formularium
                    }
                );

            },

        error:
            function (xhr) {

                console.error(
                    xhr.responseText
                );


                /*
                |--------------------------------------------------------------------------
                | JIKA ERROR, TETAP DEFAULT
                |--------------------------------------------------------------------------
                */

                $('#stok_minimum')
                    .val(0);

                $('#stok_optimum')
                    .val(0);

                $('#obat_esensial')
                    .val('noe');

                $('#obat_formularium_puskesmas')
                    .val('false');

            }

    });

}

    /*==================================================
      PILIH OBAT
    ==================================================*/


   $(document).on('click', '.pilih-obat', function () {

    const button = $(this);

    const kode = button.data('kode');
    const nama = button.data('nama');
    const satuan = button.data('satuan');

    const stokMinimal = parseInt(
        button.attr('data-min')
    ) || 0;

    const stokOptimum = parseInt(
        button.attr('data-opt')
    ) || 0;


    /*
    |--------------------------------------------------------------------------
    | ISI INFORMASI OBAT
    |--------------------------------------------------------------------------
    */

    $('#kode_obat').val(kode);

    $('#nama_obat').val(nama);

    $('#satuan').val(satuan);


    /*
    |--------------------------------------------------------------------------
    | ISI PARAMETER STOK
    |--------------------------------------------------------------------------
    */

    $('#stok_minimum').val(stokMinimal);

    $('#stok_optimum').val(stokOptimum);


    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

    $('#previewKode').text(kode);

    $('#previewNama').text(nama);


    /*
    |--------------------------------------------------------------------------
    | SIMPAN OBAT YANG DIPILIH
    |--------------------------------------------------------------------------
    */

    selectedObat = {

        kode: kode,

        nama: nama,

        satuan: satuan,

        min: stokMinimal,

        opt: stokOptimum,

        napza: button.attr('data-napza') || 'tidak',

        esensial: button.attr('data-esensial') || 'tidak',

        formularium:
            button.attr('data-formularium') || 'tidak'

    };


    /*
    |--------------------------------------------------------------------------
    | LOAD DEFAULT ITEM
    |--------------------------------------------------------------------------
    */

    if (
        $('[name="program_id"]').val()
    ) {

        loadDefaultItem();

    }

});


    /*==================================================
      PREVIEW PROGRAM
    ==================================================*/

   /* $('[name=program_id]').change(function(){

        $('#previewProgram').html(
            $(this).find('option:selected').text()
        );

    }).trigger('change');
    */

    $('[name=program_id]').change(function(){

    $('#previewProgram').html(
        $(this).find('option:selected').text()
    );

    if(selectedObat){
        loadDefaultItem();
    }

}).trigger('change');


    /*==================================================
      HITUNG OTOMATIS
    ==================================================*/

   /* $(document).on('keyup change','.hitung',function(){

        hitung();

    });*/
    $(document).on('keyup change','.hitung',function(){

    validasiPerhitungan();

});


    function angka(name){

        return parseFloat(
            $('[name="'+name+'"]').val()
        ) || 0;

    }


    function loadDefaultItem(){
        console.log("{{ route('newlplpo.item.default') }}");

    $.get(



        "{{ route('newlplpo.item.default') }}",

        {

            report_id: $('#report_id').val(),
            kode_obat: selectedObat.kode,
            program_id: $('[name=program_id]').val()

        },

        function(res){

            $('[name=stok_awal_progam_pkd]')
                .val(res.stok_awal_program_pkd);

            $('[name=stok_awal_jkn]')
                .val(res.stok_awal_jkn);

            $('[name=penerimaan_program_pkd]')
                .val(res.penerimaan_program_pkd);

            $('[name=penerimaan_jkn]')
                .val(res.penerimaan_jkn);

        }

    );
    console.log({
    report_id: $('#report_id').val(),
    kode_obat: selectedObat.kode,
    program_id: $('[name=program_id]').val()
});

}



    function hitung(){

        let stokAwalPKD = angka('stok_awal_progam_pkd');

        let stokAwalJKN = angka('stok_awal_jkn');

        let penerimaanPKD = angka('penerimaan_program_pkd');

        let penerimaanJKN = angka('penerimaan_jkn');

        let pemakaianPKD = angka('pemakaian_program_pkd');

        let pemakaianJKN = angka('pemakaian_jkn');

      /*  let persediaanPKD =
            stokAwalPKD + penerimaanPKD;

        let persediaanJKN =
            stokAwalJKN + penerimaanJKN;

        $('[name=persediaan_program_pkd]')
            .val(persediaanPKD);

        $('[name=persediaan_jkn]')
            .val(persediaanJKN);

        let stokAkhirPKD =
            persediaanPKD - pemakaianPKD;

        let stokAkhirJKN =
            persediaanJKN - pemakaianJKN;

        $('[name=stok_akhir_program_pkd]')
            .val(stokAkhirPKD);

        $('[name=stok_akhir_jkn]')
            .val(stokAkhirJKN);
            */

        let optimum =
            parseFloat($('#stok_optimum').val()) || 0;

     /*   let totalAkhir =
            stokAkhirPKD + stokAkhirJKN;

        let permintaan =
            optimum - totalAkhir;

        if(permintaan < 0){

            permintaan = 0;

        }

        $('[name=permintaan]').val(permintaan); */

    }

    /*============================================
    validasi perhitungan
    ==============================================*/

    function validasiPerhitungan(){

    let stokAwalPKD   = angka('stok_awal_progam_pkd');
    let stokAwalJKN   = angka('stok_awal_jkn');

    let penerimaanPKD = angka('penerimaan_program_pkd');
    let penerimaanJKN = angka('penerimaan_jkn');

    let persediaanPKD = angka('persediaan_program_pkd');
    let persediaanJKN = angka('persediaan_jkn');

    let pemakaianPKD = angka('pemakaian_program_pkd');
    let pemakaianJKN = angka('pemakaian_jkn');

    let expiredPKD = angka('item_expired_pkd');
      let expiredJKN = angka('item_expired_jkn');

    let stokAkhirPKD = angka('stok_akhir_program_pkd');
    let stokAkhirJKN = angka('stok_akhir_jkn');

    cekPersediaan(
        '[name=persediaan_program_pkd]',
        stokAwalPKD + penerimaanPKD,
        persediaanPKD
    );

    cekPersediaan(
        '[name=persediaan_jkn]',
        stokAwalJKN + penerimaanJKN,
        persediaanJKN
    );

    cekPersediaan(
        '[name=stok_akhir_program_pkd]',
        persediaanPKD - pemakaianPKD - expiredPKD,
        stokAkhirPKD
    );

    cekPersediaan(
        '[name=stok_akhir_jkn]',
        persediaanJKN - pemakaianJKN - expiredJKN,
        stokAkhirJKN
    );

}


    /*==================================================
      RESET
    ==================================================*/

    $('button[type=reset]').click(function(){

        $('#frmItem')[0].reset();

        $('#previewKode').html('-');

        $('#previewNama').html('-');

        $('#previewProgram').html('-');

    });

    /*=======================================
    Check Persediaaan
    =========================================*/
    function cekPersediaan(selector, hasil, input){

    $(selector).removeClass('is-valid is-invalid');

    if(input == hasil){

        $(selector).addClass('is-valid');

    }else{

        $(selector).addClass('is-invalid');

    }

}

    /*==================================================
      SIMPAN ITEM
    ==================================================*/

    $('#btnSaveItem').click(function(){

        validasiPerhitungan();

if($('#frmItem .is-invalid').length){

    Swal.fire({

        icon:'warning',

        title:'Perhitungan belum sesuai',

        text:'Periksa kembali nilai Persediaan dan Stok Akhir.'

    });

    return;

}


        $.ajax({

            url:"{{ route('newlplpo.item.store') }}",

            method:"POST",

            data:$('#frmItem').serialize(),

            beforeSend:function(){

                $('#btnSaveItem')

                    .prop('disabled',true)

                    .html('Menyimpan...');

            },

/*          success:function(res){

    const el = document.getElementById('offcanvasObat');

    bootstrap.Offcanvas
        .getOrCreateInstance(el)
        .hide();

    $(el).one('hidden.bs.offcanvas', function(){

        Swal.fire({
            icon:'success',
            title:'Berhasil',
            text:'Item berhasil ditambahkan',
            timer:1000,
            showConfirmButton:false
        }).then(function(){

            location.reload();

        });

    });

},*/
success:function(res){

    Swal.fire({
        icon:'success',
        title:'Berhasil',
        text:'Item berhasil ditambahkan',
        timer:1000,
        showConfirmButton:false
    });

    setTimeout(function(){

        window.location.href = "{{ route('newlplpo.edit', $report->id) }}";

    },1000);

},

            error:function(xhr){

                Swal.fire({

                    icon:'error',

                    title:'Gagal',

                    text:xhr.responseJSON?.message ?? 'Terjadi kesalahan'

                });

            },

            complete:function(){

                $('#btnSaveItem')

                    .prop('disabled',false)

                    .html('<i class="bi bi-check-circle"></i> Simpan Item');

            }

        });

    });

});



</script>

@endpush
