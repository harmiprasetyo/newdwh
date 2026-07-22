@extends('newlplpo.layouts.master')

@section('content')

<div class="card">

<div class="card-header">

Master Obat

</div>

<div class="card-body">

<table
id="tblObat"
class="table table-bordered">

<thead>

<tr>

<th>Kode</th>

<th>Nama Obat</th>

<th>Satuan</th>

<th>Stok Minimum</th>

<th>Stok Optimum</th>

<th>Pilih</th>

</tr>

</thead>

</table>

</div>

</div>

@endsection

@push('script')

<script>

$('#tblObat').DataTable({

processing:true,

serverSide:true,

ajax:"{{ route('newlplpo.masterobat.datatable') }}",

columns:[

{
data:'kode_obat'
},

{
data:'nama_obat'
},

{
data:'satuan'
},

{
data:'stok_minimum'
},

{
data:'stok_optimum'
},

{
data:null,

orderable:false,

searchable:false,

render:function(data){

return `<button
class="btn btn-success btn-sm btnPilih"
data-id="${data.id}">
Pilih
</button>`;

}

}

]

});

</script>

@endpush
