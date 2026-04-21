@extends('layouts.mainlogin')
@section('container')
    <div class="row">
        <div class="col-md-5 offset-md-3">
            <div class="card">
                <div class="card-body text-center">
                     Login - Dashboard MNCH
                      <hr>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label>Username </label>
                        <input type="text" class="form-control" id="username" placeholder="Masukkan User Name">
                    </div>

                    <div class="form-group mt-3">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Masukkan Password">
                    </div>
                    <div class="form-group mt-3">

                    <button class="btn btn-login btn-block btn-success form-control" id="btn_login">LOGIN</button>





                    </div>


                </div>

            </div>

        </div>
    </div>




<script>
    $(document).ready(function() {
        $('#btnLoading').hide();


      $(".btn-login").click( function() {
           let btn = $(this);
           btn.prop('disabled', true);


            var username = $("#username").val();
            var password = $("#password").val();
            var token = $("meta[name='csrf-token']").attr("content");

            if(username.length == "") {

                Swal.fire({
                    icon:'warning',
                    type: 'warning',
                    title: 'Alert',
                    text: 'Username Wajib Diisi !'
                });
                btn.prop('disabled', false);

            } else if(password.length == "") {

                Swal.fire({
                     icon:'warning',
                    type: 'warning',
                    title: 'Alert',
                    text: 'Password Wajib Diisi !'
                });
                btn.prop('disabled', false);

            } else {


                Swal.fire({
                                type: 'success',
                                icon:'info',
                                title: 'Proses authentifikasi.. Mohon ditunggu',
                                html: '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                                showCancelButton: false,
                                showConfirmButton: false
                            })


                $.ajax({
    url: "/login",
    type: "POST",
    dataType: "JSON",
    cache: false,
    data: {
        userName: username,
        password: password,
        _token: token
    },
    xhrFields: {
        withCredentials: true
    },

    success: function(response){

        if (response.success === true) {

            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: 'Anda akan diarahkan dalam 3 detik',
                timer: 3000,
                showConfirmButton: false
            }).then(function () {
                window.location.href = response.redirect;
            });

        } else {

          $('#btnLoading').hide();
            btn.prop('disabled', false);

            Swal.fire({
                icon: 'error',
                title: 'Login Gagal!',
                text: response.message || 'Silakan coba lagi!'
            });
        }

    },

    error: function(xhr){

        $('#btnLoading').hide();
        btn.prop('disabled', false);

        let message = 'Username dan Password tidak sesuai!';

        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        }

        Swal.fire({
            icon: 'error',
            title: 'Login Gagal!',
            text: message
        });

        console.log(xhr);
    }
});

            }

        });

    });
</script>

</body>


@endsection
