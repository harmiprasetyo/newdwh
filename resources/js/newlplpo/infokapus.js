$(function () {

    /**
     * =========================================================
     * DELETE
     * =========================================================
     */

    $('.formDelete').on('submit', function (e) {

        const confirmed = confirm(
            'Apakah Anda yakin ingin menghapus data Kepala Puskesmas?'
        );

        if (!confirmed) {
            e.preventDefault();
        }

    });


    /**
     * =========================================================
     * RESET E-SIGN
     * =========================================================
     */

    $('#btnResetEsign').on('click', function () {

        const button = $(this);

        const url = button.data('url');

        const confirmed = confirm(
            'Reset Kode E-Sign?\n\n' +
            'Kode E-Sign lama akan langsung tidak berlaku ' +
            'dan sistem akan mengirim kode baru ke email Kapus.'
        );

        if (!confirmed) {
            return;
        }


        button.prop('disabled', true);


        $.ajax({

            url: url,

            type: 'POST',

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            dataType: 'json',

            success: function (response) {

                if (response.success) {

                    alert(response.message);

                    window.location.reload();

                    return;
                }


                alert(
                    response.message ||
                    'Kode E-Sign gagal direset.'
                );

                button.prop('disabled', false);
            },

            error: function (xhr) {

                console.error(xhr);

                let message =
                    'Terjadi kesalahan saat reset Kode E-Sign.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;
                }


                alert(message);

                button.prop('disabled', false);
            }

        });

    });

});