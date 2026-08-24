$(function () {

    const config =
        window.PosyanduConfig || {};

    const isGroup3 =
        !!config.isGroup3;

    const faskes =
        config.faskes || null;

    const posyandu =
        config.posyandu || null;


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD DESA GROUP 3
    |--------------------------------------------------------------------------
    */

    function loadVillages(districtCode) {

        $('#desa')
            .html(`
                <option value="">
                    Memuat Desa...
                </option>
            `)
            .prop('disabled', true);


        if (!districtCode) {

            $('#desa')
                .html(`
                    <option value="">
                        Pilih Desa
                    </option>
                `);

            return;
        }


        $.get(

            config.villagesUrl,

            {
                district_code:
                    districtCode
            }

        )

        .done(function (response) {

            let html = `
                <option value="">
                    -- Pilih Desa --
                </option>
            `;


            if (
                response &&
                Array.isArray(response.data)
            ) {

                response.data.forEach(
                    function (item) {

                        const selected =
                            posyandu &&
                            String(
                                posyandu.village_code
                            ) ===
                            String(item.code)
                                ? 'selected'
                                : '';


                        html += `
                            <option
                                value="${escapeHtml(item.code)}"
                                ${selected}
                            >
                                ${escapeHtml(item.name)}
                            </option>
                        `;
                    }
                );
            }


            $('#desa')
                .html(html)
                .prop('disabled', false);

        })

        .fail(function (xhr) {

            console.error(
                'Gagal load desa:',
                xhr.responseText
            );


            $('#desa')
                .html(`
                    <option value="">
                        Gagal memuat Desa
                    </option>
                `)
                .prop('disabled', true);

        });
    }


    /*
    |--------------------------------------------------------------------------
    | GROUP 3 INITIALIZE
    |--------------------------------------------------------------------------
    */

    function initializeGroup3() {

        if (
            !isGroup3 ||
            !faskes
        ) {

            return;
        }


        loadVillages(
            faskes.kodeKecamatan
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#frmPosyanduEdit').on(
        'submit',
        function (e) {

            e.preventDefault();


            $.ajax({

                url:
                    config.updateUrl,

                type:
                    'PUT',

                data:
                    $(this).serialize(),

                headers: {

                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]')
                            .attr('content')

                },


                success: function (response) {

                    Swal.fire({

                        icon:
                            'success',

                        title:
                            'Berhasil',

                        text:
                            response.message ||
                            'Posyandu berhasil diperbarui.',

                        timer:
                            1500,

                        showConfirmButton:
                            false

                    });


                    setTimeout(
                        function () {

                            window.location.href =
                                config.indexUrl;

                        },
                        1500
                    );

                },


                error: function (xhr) {

                    let message =
                        'Terjadi kesalahan saat memperbarui data.';


                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.errors
                    ) {

                        message =
                            Object.values(
                                xhr.responseJSON.errors
                            )
                            .flat()
                            .join('<br>');

                    }

                    else if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {

                        message =
                            xhr.responseJSON.message;
                    }


                    Swal.fire({

                        icon:
                            'error',

                        title:
                            'Gagal',

                        html:
                            message

                    });

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    if (isGroup3) {

        initializeGroup3();

    }

});
