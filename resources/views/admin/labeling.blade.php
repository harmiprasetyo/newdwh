@extends('layouts.admin');
@section('content')
<!-- resources/views/label_lplpo/index.blade.php -->

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- FORM -->
    <div class="card mb-3">
        <div class="card-header">Input Label LPLPO</div>
        <div class="card-body">

            <form id="frmLabel">
                @csrf

                <div class="row">

                    <!-- PROVINSI -->
                    <div class="col-md-3">
                        <label>Provinsi</label>
                        <select id="provinsi" class="form-control">
                            <option value="">-- pilih --</option>
                            @foreach($provinsi as $p)
                                <option value="{{ $p->code }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- KABUPATEN -->
                    <div class="col-md-3">
                        <label>Kabupaten</label>
                        <select name="kodeKab" id="kabupaten" class="form-control" required>
                            <option value="">-- pilih provinsi dulu --</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Kolom 1</label>
                        <input type="text" name="field1" class="form-control" required>
                    </div>

                    <div class="col-md-2">
                        <label>Kolom 2</label>
                        <input type="text" name="field2" class="form-control" required>
                    </div>

                    <div class="col-md-2">
                        <label>Kolom 3</label>
                        <input type="text" name="field3" class="form-control" required>
                    </div>

                    <div class="col-md-2 mt-3">
                        <button class="btn btn-primary w-100">Simpan</button>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card">
        <div class="card-header">Data Label</div>
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Provinsi</th>
                        <th>Kabupaten</th>
                        <th>Kolom 1</th>
                        <th>Kolom 2</th>
                        <th>Kolom 3</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($data as $d)
                    <tr>
                        <td>{{ substr($d->kodeKab, 0, 2) }}</td>
                        <td>{{ $d->kabupaten->name ?? '-' }}</td>
                        <td>{{ $d->field1 }}</td>
                        <td>{{ $d->field2 }}</td>
                        <td>{{ $d->field3 }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>

</div>


<script>

   $('#frmLabel').submit(function(e){
    e.preventDefault();

    let formData = $(this).serialize();

    $.ajax({
        url: 'label-lplpo',
        method: 'POST',
        data: formData,

        beforeSend: function() {
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        },

        success: function(res){
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message
            }).then(() => {
                $('#frmLabel')[0].reset();
                $('#kabupaten').html('<option value="">-- pilih provinsi dulu --</option>');
                location.reload();
            });
        },

        error: function(err){
            let errors = err.responseJSON.errors;

            let html = '<ul>';
            for (let key in errors) {
                html += `<li>${errors[key][0]}</li>`;
            }
            html += '</ul>';

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                html: html
            });
        }
    });
});


    $('#provinsi').change(function(){

    let code = $(this).val();

    $('#kabupaten').html('<option>Loading...</option>');

    if (code) {
        $.get('/get-kabupaten/' + code, function(data){

            let html = '<option value="">-- pilih kabupaten --</option>';

            data.forEach(function(item){
                html += `<option value="${item.code}">${item.name}</option>`;
            });

            $('#kabupaten').html(html);
        });
    } else {
        $('#kabupaten').html('<option value="">-- pilih provinsi dulu --</option>');
    }

});
    </script>

@endsection
