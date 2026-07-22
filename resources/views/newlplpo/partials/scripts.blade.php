@push('scripts')


<script>


$(document).on('click','#btnTambah',function(e){

    e.preventDefault();

    console.log('BTN CLICK');

});


$(document).on('click', '#btnTambah', function (e) {

    e.preventDefault();

    console.log('BTN TAMBAH DIKLIK');

    const el = document.getElementById('offcanvasObat');

    console.log(el);

    const canvas = bootstrap.Offcanvas.getOrCreateInstance(el);

    canvas.show();

});


    var tableObat=$('#tblMasterObat').DataTable({

processing:true,

serverSide:true,

ajax:{
url:"{{route('newlplpo.masterobat.datatable')}}"
},

columns:[

{data:'kode_obat'},

{data:'nama_obat'},

{data:'satuan'},

{

data:null,

render:function(data){

return`

<button

class="btn btn-primary btnPilih"

data-id="${data.id}"

data-kode="${data.kode_obat}"

data-nama="${data.nama_obat}"

data-satuan="${data.satuan}"

data-min="${data.stok_minimum}"

data-opt="${data.stok_optimum}">

Pilih

</button>

`;

}

}

]

});

$(document).on(

'click',

'.btnPilih',

function(){

$('#kode_obat').val(

$(this).data('kode')

);

$('#nama_obat').val(

$(this).data('nama')

);

$('#satuan').val(

$(this).data('satuan')

);

$('#stok_minimum').val(

$(this).data('min')

);

$('#stok_optimum').val(

$(this).data('opt')

);

$('html, .offcanvas-body').animate({

scrollTop:600

},300);

});


$("#btnSaveItem").click(function(){

$.ajax({

url:"{{route('newlplpo.item.store')}}",

method:"POST",

data:$("#frmItem").serialize(),

success:function(){

Swal.fire(

"Berhasil",

"Item disimpan",

"success"

);

table.ajax.reload();

tableObat.ajax.reload();

}

});


$(function(){

    $('#btnEditHeader').click(function(){

        $('select[name=bulan]').prop('disabled',false);

        $('input[name=tahun]').prop('readonly',false);

    });

});

});

</script>

@endpush
