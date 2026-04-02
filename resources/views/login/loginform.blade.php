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

            } else if(password.length == "") {

                Swal.fire({
                     icon:'warning',
                    type: 'warning',
                    title: 'Alert',
                    text: 'Password Wajib Diisi !'
                });

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

                    url: "auth/usercheck",
                    type: "POST",
                    dataType: "JSON",
                    cache: false,
                    data: {
                        "username": username,
                        "password": password,
                        "_token": token
                    },

                    success:function(response){

                        if (response.success==true) {

                            Swal.fire({
                                icon:'success',
                                type: 'success',
                                title: 'Login Berhasil!',
                                text: 'Anda akan di arahkan dalam 3 Detik',
                                timer: 3000,
                                showCancelButton: false,
                                showConfirmButton: false
                            })
                                .then (function() {
                                    window.location.href = "home";
                                });

                        } else {
                            $(this).show(function(){
                                $('#btnLoading').hide();
                            })

                            console.log(response.success);

                            Swal.fire({
                                icon:'error',
                                type: 'error',
                                title: 'Login Gagal!',
                                text: 'silahkan coba lagi!'
                            });

                        }

                        console.log(response);

                    },

                    error:function(response){

                        Swal.fire({
                            icon:'error',
                            type: 'error',
                            title: 'Login Gagal!',
                            text: 'Username dan Password tidak sesuai!'

                        })


                        console.log(response);

                    }

                });

            }

        });

    });
</script>

</body>


@endsection
