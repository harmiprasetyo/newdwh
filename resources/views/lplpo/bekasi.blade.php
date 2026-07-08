@extends('layouts.lplpo')

@section('content')

<div class="container-fluid">

<div class="card">

<div class="card-header">

<h5>Data LPLPO SISFOMEDIKA</h5>

</div>

<div class="card-body">

<table id="tblLplpo" class="table table-bordered table-striped">

<thead>

<tr>

<th>No</th>
<th>Kode Sarana</th>
<th>Nama Fasyankes</th>
<th>Puskesmas</th>
<th>No LPLPO</th>
<th>Tanggal</th>
<th>Nama Obat</th>
<th>Satuan</th>
<th>Kode KFA</th>
<th>Stok Awal</th>
<th>Penerimaan</th>
<th>Penggunaan</th>
<th>Expired</th>
<th>Stok Optimum</th>
<th>Permintaan</th>

</tr>

</thead>

</table>

</div>

</div>

</div>

@endsection

@push('scripts')

<script>

$(function(){

$('#tblLplpo').DataTable({

processing:true,

ajax:{
url:"{{ url('bekasi/lplpo/api/data') }}",
dataSrc:''
},

pageLength:25,

scrollX:true,

columns:[

{
data:null,
render:function(data,type,row,meta){
return meta.row+1;
}
},

{data:'smiley_kode_sarana'},
{data:'smiley_nama_fasyankes'},
{data:'nama_pkm'},
{data:'nomor_lplpo'},
{data:'tanggal'},
{data:'nama_obat'},
{data:'satuan'},
{data:'kode_obat_kfa'},

{
data:null,
render:function(r){

return (
parseFloat(r.stok_awal_rutin)+
parseFloat(r.stok_awal_program)+
parseFloat(r.stok_awal_jkn)
).toFixed(2);

}
},

{
data:null,
render:function(r){

return (
parseFloat(r.penerimaan_rutin_pkd)+
parseFloat(r.penerimaan_program)+
parseFloat(r.penerimaan_jkn)
).toFixed(2);

}
},

{
data:null,
render:function(r){

return (
parseFloat(r.penggunaan_rutin)+
parseFloat(r.penggunaan_program)+
parseFloat(r.penggunaan_jkn)
).toFixed(2);

}
},

{data:'expired_date'},
{data:'stok_optimum'},
{data:'permintaan'}

]

});

});

</script>

@endpush
