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
        <div class="form-group  mb-6">
          <input type="text" id="nik" class="form-control" name="nik" placeholder="NIK" required>

        </div>
        </form>

      </div>
      <div class="modal-footer bg-light">

        <button class="btn btn-success form-control" id="btnSearch">
            Cari
          </button>
          <button class="btn btn-primary form-control" id="loaderbtn" type="button" disabled>
  <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
  <span role="status">Loading...</span>
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
           <input type="hidden" id="identifier" name="identifier" value="">

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



<div class="modal fade" id="uploadModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-success">
        <h5 class="modal-title">Upload Inform Consent</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form id="uploadForm" enctype="multipart/form-data">



          <!-- FILE UPLOAD -->
          <div class="mb-3">
            <label>Upload File (PDF / Image)</label>
             <input type="hidden" id="updnik" name="updnik">
            <input type="file" id="file" name="file" class="form-control">
          </div>

        </form>

      </div>

      <div class="modal-footer bg-light">
        <button class="btn btn-success w-100" id="btnUpload">
          Upload
        </button>
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
    $('#loaderbtn').hide();

     $('#searchForm').validate({
        errorPlacement: function(error, element) {
        error.addClass('text-danger mt-2');
        error.appendTo(element.closest('.form-group'));
    },
                rules:{
                    nik:{
                        required:true
                    }
                },
                messages:{
                    nik:{
                        required:"Nik Wajib diisi"
                    }

                },
                submitHandler:function(form){
                    $('#btnSearch').hide();
                    $('#loaderbtn').show();

                            $.ajax({
            url: "/datarme/search",
            type: "POST",
            data:{
                "nik":$('#nik').val(),
                "_token": "{{ csrf_token() }}"

            },
            dataType: "json",
           success: async function(response){

    $('#loaderbtn').hide();
    $('#btnSearch').show();

    // ❌ NOT FOUND
    if(response.status === "not_found"){
        Swal.fire({
            title: 'Not Found',
            text: 'Data Pasien Tidak ditemukan',
            icon: 'info'
        });
        return;
    }

    // ✅ SUCCESS
    if(response.status === "success"){

        let data = response.data;

        const result = await Swal.fire({
            title: 'Data Tersedia',
            text: 'Pilih metode akses data pasien',
            icon: 'info',
            showDenyButton: true,
            confirmButtonText: 'ENTER OTP',
            denyButtonText: 'INFORM CONSENT'
        });

        // =========================
        // OTP FLOW
        // =========================
        if(result.isConfirmed){

            if(!data.phone){
                Swal.fire({
                    title: 'No Phone',
                    text: 'Nomor HP tidak tersedia',
                    icon: 'warning'
                });
                return;
            }

            // kirim OTP
            await $.post('/send-otp', {
                identifier: data.phone,
                nama: data.name,
                _token: $('meta[name="csrf-token"]').attr('content')
            });

            // input OTP langsung
            const { value: otp } = await Swal.fire({
                title: 'Input OTP',
                input: 'text',
                inputLabel: 'Kode OTP',
                inputPlaceholder: 'Masukkan OTP',
                showCancelButton: true,
                confirmButtonText: 'Verify',
                inputAttributes: {
                    maxlength: 6
                },
                inputValidator: (value) => {
                    if (!value) return 'OTP wajib diisi';
                }
            });

            if(!otp) return;

            // verifikasi OTP
            $.post('/verify-otp', {
                identifier: data.phone,
                otp: otp,
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function(res){

                if(res.success){

                    Swal.fire({
                        title: 'Berhasil',
                        text: res.message,
                        icon: 'success'
                    }).then(()=>{
                        window.location.href="/datarme/search?nik="+data.nik;
                    });

                }else{
                    Swal.fire({
                        title: 'Gagal',
                        text: res.message,
                        icon: 'error'
                    });
                }

            });

        }

        // =========================
        // CONSENT FLOW
        // =========================
        else if(result.isDenied){

            $('#uploadModal').modal('show');
            $('#updnik').val(data.nik);

        }
    }
},

            //end success response
            error: function(xhr, status, error){
                console.log(error);
                $("#result").html("AJAX error");
            }

        });



                }
            })

        $('#searchModal').modal({
        backdrop: "static",
        keyboard: false
    }).modal('show');


           $("#btnSearch").on("click", function(){
            $('#searchForm').submit();
        });

$('#btnOTP').click(function(){


$.ajax({
    url:'/verify-otp',
    type:'post',
    dataType:'json',
    data:{
    "identifier": $('#identifier').val(),
    "otp": $('#otp').val(),
    "_token": $('meta[name="csrf-token"]').attr('content')

    },
    success:function(response){

        if(response.success==true){

            Swal.fire({
                title: response.message,
                icon: "success",
                draggable: true,
                confirmButtonText: 'OK',
            }).then((result)=>{

   if(result.isConfirmed){

  window.location.href="/datarme/search?nik="+$('#otpnik').val();

   }

})

        }else{
             Swal.fire({
                title: response.message,
                icon: "error",
                draggable: true
            });
        }

    }

})


})


$('#btnUpload').click(function(){
    window.location.href="/datarme/search?nik="+$('#updnik').val();
})
    });
    </script>

@endsection()
