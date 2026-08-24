$(function () {

    const config = window.PosyanduConfig || {};

    const isGroup3 = !!config.isGroup3;

    const faskes = config.faskes || null;


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
    | GROUP 3
    |--------------------------------------------------------------------------
    */

   function initializeGroup3() {

    if (!isGroup3 || !faskes) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | PROVINSI
    |--------------------------------------------------------------------------
    */

    $('#provinsi')
        .html(`
            <option value="${escapeHtml(faskes.kodePropinsi)}">
                ${escapeHtml(faskes.namaPropinsi)}
            </option>
        `)
        .val(faskes.kodePropinsi)
        .prop('disabled', true);


    /*
    |--------------------------------------------------------------------------
    | KOTA
    |--------------------------------------------------------------------------
    */

    $('#kota')
        .html(`
            <option value="${escapeHtml(faskes.kodeKota)}">
                ${escapeHtml(faskes.namaKota)}
            </option>
        `)
        .val(faskes.kodeKota)
        .prop('disabled', true);


    /*
    |--------------------------------------------------------------------------
    | KECAMATAN
    |--------------------------------------------------------------------------
    */

    $('#kecamatan')
        .html(`
            <option value="${escapeHtml(faskes.kodeKecamatan)}">
                ${escapeHtml(faskes.namaKecamatan)}
            </option>
        `)
        .val(faskes.kodeKecamatan)
        .prop('disabled', true);


    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    $('#faskes')
        .html(`
            <option value="${escapeHtml(faskes.kodeFaskes)}">
                ${escapeHtml(faskes.namaFaskes)}
            </option>
        `)
        .val(faskes.kodeFaskes)
        .prop('disabled', true);


    /*
    |--------------------------------------------------------------------------
    | DESA
    |--------------------------------------------------------------------------
    |
    | Desa TETAP AKTIF.
    |
    | Data desa berasal dari:
    |
    | indonesia_villages
    |
    | WHERE district_code =
    | master_faskes.kodeKecamatan
    |
    */

    loadVillages(
        faskes.kodeKecamatan
    );
}


    /*
    |--------------------------------------------------------------------------
    | LOAD VILLAGES
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
                district_code: districtCode
            }
        )

        .done(function (response) {

            let html = `
                <option value="">
                    Pilih Desa
                </option>
            `;


            if (
                response &&
                Array.isArray(response.data)
            ) {

                response.data.forEach(function (item) {

                    html += `
                        <option value="${escapeHtml(item.code)}">
                            ${escapeHtml(item.name)}
                        </option>
                    `;

                });
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
    | NON GROUP 3
    |--------------------------------------------------------------------------
    */

    function loadProvinces() {

        $.get(
            config.provincesUrl
        )

        .done(function (response) {

            let html = `
                <option value="">
                    Pilih Provinsi
                </option>
            `;

            response.data.forEach(function (item) {

                html += `
                    <option value="${escapeHtml(item.code)}">
                        ${escapeHtml(item.name)}
                    </option>
                `;

            });

            $('#provinsi')
                .html(html)
                .prop('disabled', false);

        });
    }


    /*
    |--------------------------------------------------------------------------
    | PROVINCE CHANGE
    |--------------------------------------------------------------------------
    */

    $('#provinsi').on(
        'change',
        function () {

            if (isGroup3) {
                return;
            }

            const code = $(this).val();

            $('#kota')
                .html(
                    '<option value="">Pilih Kota</option>'
                )
                .prop('disabled', true);

            $('#kecamatan')
                .html(
                    '<option value="">Pilih Kecamatan</option>'
                )
                .prop('disabled', true);

            $('#desa')
                .html(
                    '<option value="">Pilih Desa</option>'
                )
                .prop('disabled', true);

            $('#faskes')
                .html(
                    '<option value="">Pilih Fasyankes</option>'
                )
                .prop('disabled', true);


            if (!code) {
                return;
            }


            $.get(
                config.citiesUrl,
                {
                    province_code: code
                }
            )

            .done(function (response) {

                let html = `
                    <option value="">
                        Pilih Kota
                    </option>
                `;

                response.data.forEach(function (item) {

                    html += `
                        <option value="${escapeHtml(item.code)}">
                            ${escapeHtml(item.name)}
                        </option>
                    `;

                });

                $('#kota')
                    .html(html)
                    .prop('disabled', false);
            });
        }
    );


    /*
    |--------------------------------------------------------------------------
    | CITY CHANGE
    |--------------------------------------------------------------------------
    */

    $('#kota').on(
        'change',
        function () {

            if (isGroup3) {
                return;
            }

            const code = $(this).val();

            $('#kecamatan')
                .html(
                    '<option value="">Pilih Kecamatan</option>'
                )
                .prop('disabled', true);

            $('#desa')
                .html(
                    '<option value="">Pilih Desa</option>'
                )
                .prop('disabled', true);

            $('#faskes')
                .html(
                    '<option value="">Pilih Fasyankes</option>'
                )
                .prop('disabled', true);


            if (!code) {
                return;
            }


            $.get(
                config.districtsUrl,
                {
                    city_code: code
                }
            )

            .done(function (response) {

                let html = `
                    <option value="">
                        Pilih Kecamatan
                    </option>
                `;

                response.data.forEach(function (item) {

                    html += `
                        <option value="${escapeHtml(item.code)}">
                            ${escapeHtml(item.name)}
                        </option>
                    `;

                });

                $('#kecamatan')
                    .html(html)
                    .prop('disabled', false);
            });
        }
    );


    /*
    |--------------------------------------------------------------------------
    | DISTRICT CHANGE
    |--------------------------------------------------------------------------
    */

    $('#kecamatan').on(
        'change',
        function () {

            if (isGroup3) {
                return;
            }

            const code = $(this).val();

            loadVillages(code);

            $('#faskes')
                .html(
                    '<option value="">Memuat Fasyankes...</option>'
                )
                .prop('disabled', true);


            if (!code) {
                return;
            }


            $.get(
                config.faskesUrl,
                {
                    district_code: code
                }
            )

            .done(function (response) {

                let html = `
                    <option value="">
                        Pilih Fasyankes
                    </option>
                `;

                response.data.forEach(function (item) {

                    html += `
                        <option value="${escapeHtml(item.kodeFaskes)}">
                            ${escapeHtml(item.namaFaskes)}
                        </option>
                    `;

                });

                $('#faskes')
                    .html(html)
                    .prop('disabled', false);
            });
        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#frmPosyandu').on(
        'submit',
        function (e) {

            e.preventDefault();

            const form = this;

            const data = $(form).serialize();


            $.ajax({

                url: config.storeUrl,

                type: 'POST',

                data: data,

                headers: {

                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]')
                            .attr('content')

                },

                success: function (response) {

                    Swal.fire({

                        icon: 'success',

                        title: 'Berhasil',

                        text:
                            response.message ||
                            'Posyandu berhasil disimpan.',

                        timer: 1500,

                        showConfirmButton: false

                    });

                    setTimeout(function () {

                        window.location.href =
                            config.indexUrl;

                    }, 1500);

                },

                error: function (xhr) {

                    let message =
                        'Terjadi kesalahan saat menyimpan data.';


                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.errors
                    ) {

                        message = Object.values(
                            xhr.responseJSON.errors
                        )
                        .flat()
                        .join('<br>');

                    } else if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {

                        message =
                            xhr.responseJSON.message;
                    }


                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal',

                        html: message

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

    } else {

        loadProvinces();

    }

});
