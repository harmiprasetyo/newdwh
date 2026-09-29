$(document).ready(function () {

    // =========================================================
    // INITIALIZATION
    // =========================================================

    $('#loaderbtn').hide();

    $('#searchModal')
        .modal({
            backdrop: 'static',
            keyboard: false
        })
        .modal('show');


    // =========================================================
    // CHANGE PLACEHOLDER BASED ON SEARCH TYPE
    // =========================================================

    $('#search_type').on('change', function () {

        const type = $(this).val();

        if (type === 'nik_ibu') {

            $('#nik').attr(
                'placeholder',
                'Masukkan NIK Ibu'
            );

        } else {

            $('#nik').attr(
                'placeholder',
                'Masukkan NIK'
            );
        }

        $('#nik').val('');
    });


    // =========================================================
    // VALIDATION
    // =========================================================

    $('#searchForm').validate({

        errorPlacement: function (error, element) {

            error.addClass('text-danger mt-2');

            if (element.attr('id') === 'nik') {

                error.insertAfter(
                    element.closest('.input-group')
                );

            } else {

                error.insertAfter(element);
            }
        },

        rules: {

            nik: {
                required: true
            },

            search_type: {
                required: true
            }

        },

        messages: {

            nik: {
                required: 'NIK wajib diisi'
            },

            search_type: {
                required: 'Jenis identitas wajib dipilih'
            }

        },

        submitHandler: function () {

            searchPatient();

        }

    });


    // =========================================================
    // SEARCH BUTTON
    // =========================================================

    $('#btnSearch').on('click', function () {

        $('#searchForm').submit();

    });


    // =========================================================
    // SEARCH PATIENT
    // =========================================================

    function searchPatient() {

        const searchType = $('#search_type').val();
        const nik = $('#nik').val().trim();

        $('#btnSearch').hide();
        $('#loaderbtn').show();


        $.ajax({

            url: '/datarme/search',

            type: 'POST',

            data: {

                nik: nik,

                search_type: searchType,

                _token:
                    $('meta[name="csrf-token"]').attr('content')

            },

            dataType: 'json',


            // =================================================
            // SUCCESS
            // =================================================

            success: async function (response) {

                $('#loaderbtn').hide();
                $('#btnSearch').show();


                // =============================================
                // NOT FOUND
                // =============================================

                if (response.status === 'not_found') {

                    await Swal.fire({

                        title: 'Data Tidak Ditemukan',

                        text:
                            'Data pasien tidak ditemukan.',

                        icon: 'info',

                        confirmButtonText: 'OK'

                    });

                    return;
                }


                // =============================================
                // SUCCESS
                // =============================================

                if (response.status === 'success') {

                    let patients =
                        Array.isArray(response.data)
                            ? response.data
                            : [];

                    let selectedPatient = null;


                    // =========================================
                    // ONE PATIENT
                    // =========================================

                    if (patients.length === 1) {

                        selectedPatient =
                            patients[0];

                    }


                    // =========================================
                    // MULTIPLE PATIENTS
                    // =========================================

                    else if (patients.length > 1) {

                        selectedPatient =
                            await selectPatient(patients);


                        // User cancel
                        if (!selectedPatient) {

                            return;
                        }
                    }


                    // =========================================
                    // NO PATIENT
                    // =========================================

                    if (!selectedPatient) {

                        await Swal.fire({

                            title: 'Data Tidak Ditemukan',

                            text:
                                'Tidak ada pasien yang dapat dipilih.',

                            icon: 'warning'

                        });

                        return;
                    }


                    // =========================================
                    // SIMPAN SEARCH TYPE
                    // =========================================

                    /*
                     * Pastikan pasien yang dipilih mengetahui
                     * jenis pencarian yang digunakan.
                     */

                    selectedPatient.search_type =
                        response.search_type ||
                        searchType;


                    // =========================================
                    // CONTINUE ACCESS FLOW
                    // =========================================

                    await accessPatient(
                        selectedPatient
                    );
                }
            },


            // =================================================
            // AJAX ERROR
            // =================================================

            error: function (
                xhr,
                status,
                error
            ) {

                console.error(
                    'AJAX Error:',
                    error
                );

                $('#loaderbtn').hide();
                $('#btnSearch').show();

                let message =
                    'Terjadi kesalahan saat mencari data pasien.';

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;
                }


                Swal.fire({

                    title: 'Error',

                    text: message,

                    icon: 'error'

                });

            }

        });
    }


    // =========================================================
    // PATIENT ACCESS FLOW
    // =========================================================

    async function accessPatient(data) {

        const result =
            await Swal.fire({

                title: 'Data Tersedia',

                html: `
                    <div class="text-center">

                        <strong>
                            ${escapeHtml(
                                data.name || '-'
                            )}
                        </strong>

                        <br>

                        <small>
                            Patient ID:
                            ${escapeHtml(
                                data.patient_id || '-'
                            )}
                        </small>

                        <br>

                        <small>
                            NIK:
                            ${escapeHtml(
                                data.nik || '-'
                            )}
                        </small>

                        <br>

                        <small>
                            NIK Ibu:
                            ${escapeHtml(
                                data.nik_ibu || '-'
                            )}
                        </small>

                    </div>

                    <hr>

                    <div>
                        Pilih metode akses data pasien
                    </div>
                `,

                icon: 'info',

                showDenyButton: true,

                confirmButtonText:
                    'ENTER OTP',

                denyButtonText:
                    'INFORM CONSENT',

                confirmButtonColor:
                    '#198754',

                denyButtonColor:
                    '#0d6efd'

            });


        // =====================================================
        // OTP
        // =====================================================

        if (result.isConfirmed) {

            await processOtp(data);

            return;
        }


        // =====================================================
        // INFORM CONSENT
        // =====================================================


if (result.isDenied) {

    console.log('PATIENT YANG DIPILIH:', data);

    $('#patient_id').val(data.patient_id || '');
    $('#updnik').val(data.nik || '');
    $('#nikibu').val(data.nik_ibu || '');
    $('#search_type').val(data.search_type || '');

    console.log('patient_id:', $('#patient_id').val());
    console.log('nik:', $('#updnik').val());
    console.log('nik_ibu:', $('#nikibu').val());
    console.log('search_type:', $('#search_type').val());

    $('#uploadModal').modal('show');
}
    }




    // =========================================================
    // OTP PROCESS
    // =========================================================

    async function processOtp(data) {

        // -----------------------------------------------------
        // CHECK PHONE
        // -----------------------------------------------------

        if (!data.phone) {

            await Swal.fire({

                title:
                    'Nomor HP Tidak Tersedia',

                text:
                    'Nomor HP pasien tidak tersedia.',

                icon: 'warning'

            });

            return;
        }


        // -----------------------------------------------------
        // SEND OTP
        // -----------------------------------------------------

        try {

            await $.post(

                '/send-otp',

                {

                    identifier: data.phone,

                    nama: data.name,

                    _token:
                        $('meta[name="csrf-token"]')
                            .attr('content')

                }

            );

        } catch (error) {

            console.error(
                'Send OTP Error:',
                error
            );

            await Swal.fire({

                title: 'Gagal',

                text:
                    'OTP gagal dikirim.',

                icon: 'error'

            });

            return;
        }


        // -----------------------------------------------------
        // INPUT OTP
        // -----------------------------------------------------

        const result =
            await Swal.fire({

                title: 'Input OTP',

                input: 'text',

                inputLabel: 'Kode OTP',

                inputPlaceholder:
                    'Masukkan OTP',

                showCancelButton: true,

                confirmButtonText:
                    'Verifikasi',

                cancelButtonText:
                    'Batal',

                inputAttributes: {

                    maxlength: 6,

                    autocomplete:
                        'one-time-code',

                    inputmode:
                        'numeric'

                },

                inputValidator:
                    function (value) {

                        if (!value) {

                            return 'OTP wajib diisi';
                        }

                        if (
                            !/^\d{6}$/.test(value)
                        ) {

                            return
                                'OTP harus terdiri dari 6 digit';
                        }

                    }

            });


        if (
            !result.isConfirmed ||
            !result.value
        ) {

            return;
        }


        // -----------------------------------------------------
        // VERIFY OTP
        // -----------------------------------------------------

        await verifyOtp(
            data,
            result.value
        );
    }


    // =========================================================
    // VERIFY OTP
    // =========================================================

    function verifyOtp(
        data,
        otp
    ) {

        $.ajax({

            url: '/verify-otp',

            type: 'POST',

            dataType: 'json',

            data: {

                identifier: data.phone,

                otp: otp,

                _token:
                    $('meta[name="csrf-token"]')
                        .attr('content')

            },


            success: function (response) {

                if (
                    response.success === true
                ) {

                    Swal.fire({

                        title: 'Berhasil',

                        text:
                            response.message,

                        icon: 'success',

                        confirmButtonText:
                            'OK'

                    }).then(function (result) {

                        if (
                            result.isConfirmed
                        ) {

                            redirectToPatient(
                                data
                            );
                        }

                    });

                } else {

                    Swal.fire({

                        title: 'Gagal',

                        text:
                            response.message,

                        icon: 'error',

                        confirmButtonText:
                            'OK'

                    });

                }

            },


            error: function (xhr) {

                console.error(
                    'Verify OTP Error:',
                    xhr
                );

                let message =
                    'Verifikasi OTP gagal.';

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;
                }

                Swal.fire({

                    title: 'Error',

                    text: message,

                    icon: 'error'

                });

            }

        });
    }


    // =========================================================
    // OLD OTP MODAL BUTTON
    // =========================================================

    $('#btnOTP').on('click', function () {

        const identifier =
            $('#identifier').val();

        const otp =
            $('#otp').val();

        const patientId =
            $('#otppatientid').val();

        const nik =
            $('#otpnik').val();

        const nikIbu =
            $('#otpnikiibu').val();


        if (!otp) {

            Swal.fire({

                title: 'OTP',

                text:
                    'OTP wajib diisi.',

                icon: 'warning'

            });

            return;
        }


        $.ajax({

            url: '/verify-otp',

            type: 'POST',

            dataType: 'json',

            data: {

                identifier: identifier,

                otp: otp,

                _token:
                    $('meta[name="csrf-token"]')
                        .attr('content')

            },


            success: function (response) {

                if (
                    response.success === true
                ) {

                    Swal.fire({

                        title:
                            response.message,

                        icon: 'success',

                        confirmButtonText:
                            'OK'

                    }).then(function (result) {

                        if (
                            result.isConfirmed
                        ) {

                            const params =
                                new URLSearchParams();


                            if (patientId) {

                                params.set(
                                    'patient_id',
                                    patientId
                                );
                            }

                            if (nik) {

                                params.set(
                                    'nik',
                                    nik
                                );
                            }

                            if (nikIbu) {

                                params.set(
                                    'nik_ibu',
                                    nikIbu
                                );
                            }


                            window.location.href =
                                '/datarme/search?' +
                                params.toString();
                        }

                    });

                } else {

                    Swal.fire({

                        title:
                            response.message,

                        icon: 'error',

                        confirmButtonText:
                            'OK'

                    });

                }

            },


            error: function (xhr) {

                console.error(
                    'OTP Error:',
                    xhr
                );

                Swal.fire({

                    title: 'Error',

                    text:
                        'Terjadi kesalahan saat verifikasi OTP.',

                    icon: 'error'

                });

            }

        });

    });


    // =========================================================
    // UPLOAD INFORM CONSENT
    // =========================================================

$('#btnUpload').on('click', function () {

    const patientId = $('#patient_id').val();
    const nik = $('#updnik').val();
    const nikIbu = $('#nikibu').val();
    const searchType = $('#search_type').val();

    console.log('=== UPLOAD ===');
    console.log('patient_id:', patientId);
    console.log('nik:', nik);
    console.log('nik_ibu:', nikIbu);
    console.log('search_type:', searchType);


    if (!patientId) {

        Swal.fire({
            title: 'Error',
            text: 'Pasien belum dipilih.',
            icon: 'error'
        });

        return;
    }


    if (!nik && !nikIbu) {

        Swal.fire({
            title: 'Error',
            text: 'NIK pasien atau NIK-IBU tidak tersedia.',
            icon: 'error'
        });

        return;
    }


    const params = new URLSearchParams();

    params.set('patient_id', patientId);

    if (nik) {
        params.set('nik', nik);
    }

    if (nikIbu) {
        params.set('nik_ibu', nikIbu);
    }

    if (searchType) {
        params.set('search_type', searchType);
    }


    window.location.href =
        '/datarme/search?' +
        params.toString();
});



    // =========================================================
    // SELECT PATIENT WHEN MULTIPLE RESULTS
    // =========================================================

    async function selectPatient(
        patients
    ) {

        let html = `
            <div class="text-start">

                <div class="mb-3">

                    <strong>
                        Ditemukan
                        ${patients.length}
                        pasien.
                    </strong>

                </div>
        `;


        patients.forEach(
            function (patient, index) {

                const birthDate =
                    patient.birth_date
                        ? formatDate(
                            patient.birth_date
                        )
                        : '-';

                const nik =
                    patient.nik || '-';

                const nikIbu =
                    patient.nik_ibu || '-';


                html += `
                    <div
                        class="form-check border rounded p-3 mb-2"
                    >

                        <input
                            class="
                                form-check-input
                                patient-option
                            "
                            type="radio"
                            name="selected_patient"
                            id="patient_${index}"
                            value="${index}"
                        >

                        <label
                            class="
                                form-check-label
                                w-100
                            "
                            for="patient_${index}"
                        >

                            <strong>
                                ${escapeHtml(
                                    patient.name || '-'
                                )}
                            </strong>

                            <br>

                            <small>
                                Patient ID:
                                ${escapeHtml(
                                    patient.patient_id || '-'
                                )}
                            </small>

                            <br>

                            <small>
                                NIK:
                                ${escapeHtml(nik)}
                            </small>

                            <br>

                            <small>
                                NIK Ibu:
                                ${escapeHtml(nikIbu)}
                            </small>

                            <br>

                            <small>
                                Tanggal Lahir:
                                ${escapeHtml(
                                    birthDate
                                )}
                            </small>

                        </label>

                    </div>
                `;
            }
        );


        html += `
            </div>
        `;


        const result =
            await Swal.fire({

                title:
                    'Pilih Pasien',

                html:
                    html,

                width:
                    600,

                showCancelButton:
                    true,

                confirmButtonText:
                    'Pilih Pasien',

                cancelButtonText:
                    'Batal',

                confirmButtonColor:
                    '#198754',

                preConfirm:
                    function () {

                        const selected =
                            $(
                                'input[name="selected_patient"]:checked'
                            ).val();


                        if (
                            selected === undefined
                        ) {

                            Swal.showValidationMessage(
                                'Silakan pilih pasien terlebih dahulu.'
                            );

                            return false;
                        }


                        return patients[selected];
                    }

            });


        if (!result.isConfirmed) {

            return null;
        }


        return result.value;
    }


    // =========================================================
    // REDIRECT TO PATIENT
    // =========================================================

    function redirectToPatient(data) {

        const params =
            new URLSearchParams();


        /*
         * patient_id adalah identifier utama.
         */

        if (data.patient_id) {

            params.set(
                'patient_id',
                data.patient_id
            );
        }


        if (data.nik) {

            params.set(
                'nik',
                data.nik
            );
        }


        if (data.nik_ibu) {

            params.set(
                'nik_ibu',
                data.nik_ibu
            );
        }


        if (data.search_type) {

            params.set(
                'search_type',
                data.search_type
            );
        }


        window.location.href =
            '/datarme/search?' +
            params.toString();
    }


    // =========================================================
    // RESET SEARCH MODAL
    // =========================================================

    $('#searchModal').on(
        'shown.bs.modal',
        function () {

            $('#nik').trigger('focus');

        }
    );


    // =========================================================
    // FORMAT DATE
    // =========================================================

    function formatDate(date) {

        if (!date) {

            return '-';
        }


        const parts =
            date.split('-');


        if (parts.length !== 3) {

            return date;
        }


        return `
            ${parts[2]}-
            ${parts[1]}-
            ${parts[0]}
        `.replace(/\s/g, '');
    }


    // =========================================================
    // ESCAPE HTML
    // =========================================================

    function escapeHtml(value) {

        return $('<div>')
            .text(value ?? '')
            .html();
    }

});

