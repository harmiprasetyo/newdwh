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

                    <button class="btn btn-login btn-block btn-success form-control">LOGIN</button>
                    </div>

                </div>
            </div>

        </div>
    </div>




<script>
    $(document).ready(function() {


      $(".btn-login").click( function() {

            var username = $("#username").val();
            var password = $("#password").val();
            var token = $("meta[name='csrf-token']").attr("content");

            if(username.length == "") {

                Swal.fire({
                    type: 'warning',
                    title: 'Alert',
                    text: 'Username Wajib Diisi !'
                });

            } else if(password.length == "") {

                Swal.fire({
                    type: 'warning',
                    title: 'Alert',
                    text: 'Password Wajib Diisi !'
                });

            } else {

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

                            console.log(response.success);

                            Swal.fire({
                                type: 'error',
                                title: 'Login Gagal!',
                                text: 'silahkan coba lagi!'
                            });

                        }

                        console.log(response);

                    },

                    error:function(response){

                        Swal.fire({
                            type: 'error',
                            title: 'Opps!',
                            text: 'server error!'
                        });

                        console.log(response);

                    }

                });

            }

        });

    });
</script>

</body>


@endsection
