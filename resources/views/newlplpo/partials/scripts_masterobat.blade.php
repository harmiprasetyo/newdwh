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

        processing:true,
        serverSide:true,
        searching:true,
        ordering:true,
        responsive:true,

        ajax:{
            url:"{{ route('newlplpo.masterobat.datatable') }}"
        },

        columns:[

            {data:'kode_obat'},

            {data:'nama_obat'},

            {data:'satuan'},

            {
                data:null,
                orderable:false,
                searchable:false,
                className:'text-center',

                render:function(data){

                    return `
                        <button
                            class="btn btn-success btn-sm pilih-obat"
                            data-kode="${data.kode_obat}"
                            data-nama="${data.nama_obat}"
                            data-satuan="${data.satuan}"
                            data-min="${data.stok_minimum ?? 0}"
                            data-opt="${data.stok_optimum ?? 0}">
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
      PILIH OBAT
    ==================================================*/

   /* $(document).on('click','.pilih-obat',function(){

        $('#kode_obat').val($(this).data('kode'));

        $('#nama_obat').val($(this).data('nama'));

        $('#satuan').val($(this).data('satuan'));

        $('#stok_minimum').val($(this).data('min'));

        $('#stok_optimum').val($(this).data('opt'));

        $('#previewKode').html($(this).data('kode'));

        $('#previewNama').html($(this).data('nama'));

    }); */

    $(document).on('click','.pilih-obat',function(){

    selectedObat = {

        kode: $(this).data('kode'),
        nama: $(this).data('nama'),
        satuan: $(this).data('satuan'),
        min: $(this).data('min'),
        opt: $(this).data('opt')

    };

    $('#kode_obat').val(selectedObat.kode);
    $('#nama_obat').val(selectedObat.nama);
    $('#satuan').val(selectedObat.satuan);

    $('#stok_minimum').val(selectedObat.min);
    $('#stok_optimum').val(selectedObat.opt);

    $('#previewKode').html(selectedObat.kode);
    $('#previewNama').html(selectedObat.nama);

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

    let expired = angka('item_expired');

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
        persediaanPKD - pemakaianPKD - expired,
        stokAkhirPKD
    );

    cekPersediaan(
        '[name=stok_akhir_jkn]',
        persediaanJKN - pemakaianJKN,
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
