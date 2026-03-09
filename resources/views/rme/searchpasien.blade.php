@extends('layouts.mainrme')
@section('container')



<div class="modal fade" id="searchModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-success">
        <h5 class="modal-title">Pencarian Pasien</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="searchForm">

        <!-- Search Form -->
        <div class="input-group mb-6">
          <input type="text" id="nik" class="form-control" name="nik" placeholder="NIK">

        </div>
        </form>

      </div>
      <div class="modal-footer bg-light">

        <button class="btn btn-success form-control" id="btnSearch">
            Cari
          </button>
    &nbsp;
    </div>

    </div>
  </div>
</div>


<div class="modal fade" id="nextModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-success">
        <h5 class="modal-title">Verifikasi OTP</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="searchForm">

        <!-- Search Form -->
        <div class="input-group mb-6">
          <input type="text" id="otp" class="form-control" name="otp" placeholder="OTP">
          <input type="hidden" id="otpnik" name="otpnik" value="">

        </div>
        </form>

      </div>
      <div class="modal-footer bg-light">

        <button class="btn btn-success form-control" id="btnOTP">
            Verifikasi
          </button>
    &nbsp;
    </div>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
async function requestOTP(nik) {
  const { value: otp } = await Swal.fire({
    title: 'Input OTP',
    input: 'text',
    inputLabel: 'OTP Code',
    inputPlaceholder: 'Masukkan OTP dari pasien',
    showCancelButton: true,
    confirmButtonText: 'Verify',
    inputAttributes: {
      maxlength: 6,
      disabled: false,
      readonly:false

    },
    inputValidator: (value) => {
      if (!value) {
        return 'Mohon masukkan Otp anda!'
      }
    }
  });

  if (otp) {
    console.log("OTP entered:", otp);
  }
}

    $(document).ready(function(){
        $('#searchModal').modal('show');


           $("#btnSearch").on("click", function(){

        $.ajax({
            url: "/datarme/search",
            type: "POST",
            data:{
                "nik":$('#nik').val(),
                "_token": "{{ csrf_token() }}"

            },
            dataType: "json",
            success: function(response){

                if(response.status === "success"){
                    if(response.total=="0"){
                         Swal.fire({
  title: 'Not Found',
  text: 'Data Pasien Tidak ditemukan',
  icon: 'info',
  confirmButtonText: 'Ok'
})

                    }else{

 Swal.fire({
  title: 'Data Tersedia',
  text: 'Data Pasien ditemukan silahkan input OTP untuk akses data',
  icon: 'info',
  confirmButtonText: 'ENTER OTP'
}).then((result)=>{

    $('#nextModal').modal('show');
    $('#otpnik').val(response.nik);

})

                    }


                }else{
                    $("#result").html(response.message);
                }

            },
            error: function(xhr, status, error){
                console.log(error);
                $("#result").html("AJAX error");
            }

        });

    });

$('#btnOTP').click(function(){
    window.location.href="/datarme/search?nik="+$('#otpnik').val();
})
    });
    </script>

@endsection()
