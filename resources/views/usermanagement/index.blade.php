<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<table id="userTable" class="table table-bordered">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Group</th>
            <th>Aksi</th>
        </tr>
    </thead>
</table>

<button class="btn btn-primary" onclick="showForm()">Tambah</button>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
let modal = new bootstrap.Modal(document.getElementById('userModal'));

let table = $('#userTable').DataTable({
    ajax: '/users/data',
    columns: [
        { data: 'username' },
        { data: 'namalengkap' },
        { data: 'email' },
        { data: 'group.namagroup' },
        {
            data: 'userid',
            render: function(id){
                return `
                    <button class="btn btn-sm btn-warning" onclick="edit('${id}')">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="del('${id}')">Hapus</button>
                `;
            }
        }
    ]
});

// 🔥 Load Group
$.get('/groups', function(data){
    data.forEach(g => {
        $('#groupid').append(`<option value="${g.idgroup}">${g.namagroup}</option>`);
    });
});

// 🔥 Load Provinsi
$.get('/api/wilayah/provinsi', function(data){
    data.forEach(p => {
        $('#provinsi').append(`<option value="${p.code}">${p.name}</option>`);
    });
});

// cascading wilayah
$('#provinsi').change(function(){
    let code = $(this).val();
    $('#kota').empty();
    $.get('/api/wilayah/kota?province_code='+code, res=>{
        res.forEach(r=> $('#kota').append(`<option value="${r.code}">${r.name}</option>`));
    });
});

$('#kota').change(function(){
    let code = $(this).val();
    $('#kecamatan').empty();
    $.get('/api/wilayah/kecamatan?city_code='+code, res=>{
        res.forEach(r=> $('#kecamatan').append(`<option value="${r.code}">${r.name}</option>`));
    });
});

// create
function createUser(){
    $('#userForm')[0].reset();
    $('#userid').val('');
    modal.show();
}

// edit
function edit(id){
    $.get('/users/'+id, function(data){
        $('#userid').val(data.userid);
        $('#username').val(data.username);
        $('#email').val(data.email);
        $('#namalengkap').val(data.namalengkap);
        $('#groupid').val(data.groupid);
        modal.show();
    });
}

// save
$('#userForm').submit(function(e){
    e.preventDefault();

    let id = $('#userid').val();
    let url = id ? '/users/'+id : '/users';
    let method = id ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: method,
        data: {
            username: $('#username').val(),
            email: $('#email').val(),
            namalengkap: $('#namalengkap').val(),
            password: $('#password').val(),
            groupid: $('#groupid').val(),
            kodePropinsi: $('#provinsi').val(),
            kodeKota: $('#kota').val(),
            kodeKecamatan: $('#kecamatan').val(),
            namaFaskes: $('#namaFaskes').val(),
            _token: '{{ csrf_token() }}'
        },
        success: function(){
            modal.hide();
            table.ajax.reload();
        }
    });
});

// delete
function del(id){
    if(confirm('Hapus user?')){
        $.ajax({
            url: '/users/'+id,
            type: 'DELETE',
            data: {_token:'{{ csrf_token() }}'},
            success: ()=> table.ajax.reload()
        });
    }
}
</script>
