@extends('layouts.admin')

@section('content')

<div class="container-fluid mt-3">

    <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">👤 User Management</h5>
        <button class="btn btn-primary"  onclick="openModal()">+ Tambah User</button>
    </div>

    <div class="card-body">

            <div class="row mb-3">
    <div class="col-md-4">
        <label>Provinsi</label>
        <select id="fProvinsi" class="form-control">
            <option value="">-- pilih provinsi --</option>
        </select>
    </div>

    <div class="col-md-4">
        <label>Kota</label>
        <select id="fKota" class="form-control">
            <option value="">-- pilih kota --</option>
        </select>
    </div>
</div>

        <table id="userTable" class="table table-hover table-bordered">
             <thead>
        <tr>
            <th>Username</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Group</th>
             <th>Tugas/Fungsi</th>
            <th>Faskes</th>
            <th>Kab/Kota</th>
            <th>Aksi</th>
        </tr>



        </thead>
        </table>
    </div>
</div>








</div>


<div class="modal fade" id="userModal">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<form id="userForm">

<div class="modal-header">
    <h5>Form User</h5>
</div>

<div class="modal-body">

<input type="hidden" id="userid">

<div class="row">

    <!-- LEFT -->
    <div class="col-md-6">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input id="username" class="form-control" placeholder="Masukkan username">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input id="email" type="email" class="form-control" placeholder="Masukkan email">
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input id="namalengkap" class="form-control" placeholder="Nama lengkap user">
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input id="password" type="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
        </div>

        <div class="mb-3">
            <label class="form-label">Group User</label>
            <select id="groupid" class="form-select"></select>
        </div>

        <div class="form-group mt-2">
    <label>Tugas/Fungsi</label>
    <select id="role_id" name="role_id" class="form-control">
        <option value="">Pilih Role</option>
    </select>
</div>

    </div>

    <!-- RIGHT -->
    <div class="col-md-6">

        <div class="mb-3">
            <label class="form-label">Provinsi</label>
            <select id="kodePropinsi" class="form-select"></select>
        </div>

        <div class="mb-3">
            <label class="form-label">Kota / Kabupaten</label>
            <select id="kodeKota" class="form-select"></select>
        </div>

        <div class="mb-3">
            <label class="form-label">Kecamatan</label>
            <select id="kodeKecamatan" class="form-select"></select>
        </div>

        <div class="mb-3">
            <label class="form-label">Faskes</label>
            <select id="kodeFaskes" class="form-select"></select>
        </div>

    </div>

</div>

</div>

<div class="modal-footer">
    <button class="btn btn-primary">Simpan</button>
</div>

</form>

</div>
</div>
</div>

<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function toggleFaskesField() {
    let groupid = document.getElementById('groupid').value;

    let isDisable = (groupid == 1 || groupid == 2);

    // disable select
    document.getElementById('kodeKecamatan').disabled = isDisable;
    document.getElementById('kodeFaskes').disabled = isDisable;

    // hide field (container .mb-3)
    document.getElementById('kodeKecamatan').closest('.mb-3').style.display = isDisable ? 'none' : 'block';
    document.getElementById('kodeFaskes').closest('.mb-3').style.display = isDisable ? 'none' : 'block';

    // reset value kalau disable
    if (isDisable) {
        document.getElementById('kodeKecamatan').value = '';
        document.getElementById('kodeFaskes').value = '';
    }
}

// trigger saat dropdown berubah
document.getElementById('groupid').addEventListener('change', toggleFaskesField);

// trigger saat pertama load
window.addEventListener('load', toggleFaskesField);

window.API_KEY = '{{ config("app.api_key") }}';
    let table;

$(document).ready(function(){

    $.ajaxSetup({
    headers: {
        'X-API-KEY': window.API_KEY
    }
});



$('#userForm').on('submit', function(e){
    e.preventDefault();

    let id = $('#userid').val();

    let url = '/api/usersapp';
    let method = 'POST';

    if(id){
        url = `/api/usersapp/${id}`;
        method = 'PUT';
    }

    $.ajax({
        url: url,
        type: method,
        data: {
            username: $('#username').val(),
            email: $('#email').val(),
            namalengkap: $('#namalengkap').val(),
            password: $('#password').val(),
            groupid: $('#groupid').val(),
            kodePropinsi: $('#kodePropinsi').val(),
            kodeKota: $('#kodeKota').val(),
            kodeKecamatan: $('#kodeKecamatan').val(),
            kodeFaskes: $('#kodeFaskes').val(),
            role_id: $('#role_id').val() // 🔥 kirim role_id
        },
        success: function(){

            $('#userModal').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data tersimpan',
                timer: 1500,
                showConfirmButton: false
            });

            table.ajax.reload();

        },
        error: function(err){

            console.log(err.responseText);

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err.responseText
            });

        }
    });
});


     initSelect2();
    loadProvinsi();
     loadGroupUser();

table = $('#userTable').DataTable({
    ajax: {
        url: '/api/usersapp',
        data: function(d){
            d.username = $('#fUsername').val();
            d.provinsi = $('#fProvinsi').val();
            d.kota = $('#fKota').val();
            d.kecamatan = $('#fKecamatan').val();
            d.faskes = $('#fFaskes').val();
        }
    },
    columns: [
        {data:'username', width:'10%'},
        {data:'namalengkap', width:'15%'},
        {data:'email', width:'15%'},
       {
    data: 'group.group_name', width: '10%',
    render: function(data) {
        return `<span class="badge bg-primary">${data}</span>`;
    }
},
{
    data: 'role.role_name',width:'10%',
    render: function(data) {
        return data
            ? `<span class="badge bg-success">${data}</span>`
            : `<span class="badge bg-secondary">-</span>`;
    }
},
        {data:'faskes.namaFaskes',width:'15%', defaultContent:'-'},
        {
    data: 'kota.name',width:'15%',
    defaultContent: '-'
},
      {
    data: 'userid',
    width: '20%',
    render: function(id) {
        return `
            <div class="d-flex gap-1">
                <button class="btn btn-sm btn-info btn-edit" onclick="edit('${id}')">
                    ✏️ EDIT
                </button>
                <button class="btn btn-sm btn-danger btn-delete" onclick="hapus('${id}')">
                    🗑️ HAPUS
                </button>
            </div>
        `;
    }
}
    ]
});




});

$('#fUsername').keyup(()=> table.ajax.reload());
$('#fFaskes, #fProvinsi, #fKota, #fKecamatan').change(()=> table.ajax.reload());

$('#fProvinsi').change(function(){

let prov = $(this).val();

$.get(`/adminpanel/wilayah/listkota?province_code=${prov}`, res=>{
    let opt='<option value="">Semua</option>';
    res.data.forEach(i=> opt+=`<option value="${i.code}">${i.name}</option>`);
    $('#fKota').html(opt);
});

});

//Init select2
function initSelect2() {

    $('#kodePropinsi, #kodeKota, #kodeKecamatan, #kodeFaskes').select2({
        dropdownParent: $('#userModal'),
        width: '100%',
        placeholder: 'Pilih'
    });

    $('#fProvinsi, #fKota, #fKecamatan, #fFaskes').select2({
        width: '100%',
        placeholder: 'Filter'
    });


}
//load propinsi
function loadProvinsi() {

    $.get('/adminpanel/wilayah/listpropinsi', function(res){

        let opt = '<option value="">Pilih</option>';

        res.data.forEach(i=>{
            opt += `<option value="${i.code}">${i.name}</option>`;
        });

        $('#kodePropinsi').html(opt).trigger('change');
        $('#fProvinsi').html('<option value="">Semua</option>' + opt).trigger('change');

    }).fail(()=>{
        console.error('Gagal load provinsi');
    });

}

//cascade load kota
$('#kodePropinsi').on('change', function(){

    let prov = $(this).val();
    if(!prov) return;

    $.get(`/adminpanel/wilayah/listkota?province_code=${prov}`, function(res){

        let opt = '<option value="">Pilih</option>';

        res.data.forEach(i=>{
            opt += `<option value="${i.code}">${i.name}</option>`;
        });

        $('#kodeKota').html(opt).trigger('change');
    });

});

//cascade load kecamatan
$('#kodeKota').on('change', function(){

    let kota = $(this).val();
    if(!kota) return;

    $.get(`/adminpanel/wilayah/listkecamatan?city_code=${kota}`, function(res){

        let opt = '<option value="">Pilih</option>';

        res.data.forEach(i=>{
            opt += `<option value="${i.code}">${i.name}</option>`;
        });

        $('#kodeKecamatan').html(opt).trigger('change');
    });

});

// cascade load faskes
$('#kodeKecamatan').on('change', function(){

    let kec = $(this).val();
    if(!kec) return;

    $.get(`/api/master/faskes?kecamatan=${kec}`, function(res){

        let opt = '<option value="">Pilih</option>';

        res.data.forEach(i=>{
            opt += `<option value="${i.kodeFaskes}">${i.namaFaskes}</option>`;
        });

        $('#kodeFaskes').html(opt).trigger('change');
    });

});


//filter cascade
$('#fProvinsi').on('change', function(){

    let prov = $(this).val();

    $.get(`/adminpanel/wilayah/listkota?province_code=${prov}`, function(res){

        let opt = '<option value="">Semua</option>';

        res.data.forEach(i=>{
            opt += `<option value="${i.code}">${i.name}</option>`;
        });

        $('#fKota').html(opt).trigger('change');
    });

    table.ajax.reload();
});


//Create & Edit

function openModal(){

    $('#userForm')[0].reset();
    $('#userid').val('');

    loadGroupUser(); // 🔥 cukup ini

    $('#userModal').modal('show');
}

function edit(id){

    $.get('/api/usersapp', res=>{

        let data = res.data.find(x=>x.userid==id);

        $('#userid').val(data.userid);
        $('#username').val(data.username);
        $('#email').val(data.email);
        $('#namalengkap').val(data.namalengkap);

        // 🔥 langsung load + select
        loadGroupUser(data.groupid);
        loadUserRoles(data.groupid, data.role_id);

        $('#userModal').modal('show');
    });
}
//hapus
function hapus(id){

Swal.fire({
    title:'Hapus?',
    showCancelButton:true
}).then(res=>{

if(res.isConfirmed){

$.ajax({
    url:`/api/users/${id}`,
    type:'DELETE',
    success:()=>{
        table.ajax.reload();
        Swal.fire('Deleted','','success');
    }
});

}

});
}

function loadGroupUser(selected = null) {

    $.get('/api/usergroups', function(res){

        let opt = '<option value="">Pilih Group User</option>';

        res.data.forEach(g => {
            opt += `<option value="${g.group_id}">${g.group_name}</option>`;
        });

        $('#groupid').html(opt);

        // 🔥 WAJIB BANGET
        $('#groupid').val(selected ?? '').trigger('change');

    });
}


function loadUserRoles(groupId, selectedRole = null) {

    $.ajax({
        url: `/api/user-roles/by-group/${groupId}`,
        headers: {
            'X-API-KEY': window.API_KEY
        },
        success: function(res) {

            let opt = '<option value="">Pilih Role</option>';

            res.data.forEach(role => {
                opt += `<option value="${role.id}">${role.role_name}</option>`;
            });

            $('#user_role_id').html(opt);

            if (selectedRole) {
                $('#user_role_id').val(selectedRole).trigger('change');
            }
        }
    });
}


$('#groupid').on('change', function () {

    let groupId = $(this).val();

    if (!groupId) {
        $('#role_id').html('<option value="">Pilih Role</option>');
        return;
    }

    $.ajax({
        url: `/api/user-roles/by-group/${groupId}`,
        type: 'GET',
        headers: {
            'X-API-KEY': window.API_KEY // kalau pakai API key
        },
        success: function(res) {

            let opt = '<option value="">Pilih Role</option>';

            res.data.forEach(role => {
                opt += `<option value="${role.id}">${role.role_name}</option>`;
            });

            $('#role_id').html(opt);
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });






});






</script>


@endsection
