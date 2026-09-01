@push('script')

<script>

$(function () {

    let selectedObat = null;

    /* ==================================================
       RESET FORM ITEM
    ================================================== */

    function resetFormItem() {

        const form = $('#frmItem')[0];

        if (!form) {
            return;
        }

        form.reset();

        // Hapus status EDIT
        delete form.dataset.editId;

        selectedObat = null;

        $('#kode_obat').val('');
        $('#nama_obat').val('');
        $('#satuan').val('');

        $('#stok_minimum').val(0);
        $('#stok_optimum').val(0);

        $('#previewKode').html('-');
        $('#previewNama').html('-');
        $('#previewProgram').html('-');

        // Bersihkan validasi
        $('#frmItem .is-valid')
            .removeClass('is-valid');

        $('#frmItem .is-invalid')
            .removeClass('is-invalid');

        // Kembalikan tombol ke mode CREATE
        $('#btnSaveItem')
            .prop('disabled', false)
            .html('<i class="bi bi-check-circle"></i> Simpan Item')
            .removeAttr('data-mode');
    }


    /* ==================================================
       OFFCANVAS CLOSED
    ================================================== */

    const offcanvasElement =
        document.getElementById('offcanvasObat');

    if (offcanvasElement) {

        offcanvasElement.addEventListener(
            'hidden.bs.offcanvas',
            function () {

                resetFormItem();

            }
        );

    }


    /* ==================================================
       DATATABLE MASTER OBAT
    ================================================== */

    let tableObat = $('#tblMasterObat').DataTable({

        processing: true,

        serverSide: true,

        searching: true,

        ordering: true,

        responsive: true,

        ajax: {

            url: "{{ route('newlplpo.masterdataobat.datatableforcanvas') }}",

            data: function (d) {

                d.tahun =
                    $('#tahunCanvas').val();

                d.kodeFaskes =
                    $('#kodeFaskes').val() || '';

            }

        },

        columns: [

            {
                data: 'kode_obat',
                name: 'master_obat.kode_obat'
            },

            {
                data: 'nama_obat',
                name: 'master_obat.nama_obat',

                render: function (data, type, row) {

                    if (row.obat_napza === 'ya') {

                        return `
                            <span class="text-danger fw-bold">
                                ${data}
                                <span class="badge bg-danger ms-1">
                                    NAPZA
                                </span>
                            </span>
                        `;

                    }

                    return data;

                }

            },

            {
                data: 'satuan',
                name: 'master_obat.satuan'
            },

            {
                data: null,

                orderable: false,

                searchable: false,

                className: 'text-center',

                render: function (data) {

                    return `
                        <button
                            type="button"
                            class="btn btn-success btn-sm pilih-obat"

                            data-id="${data.id}"

                            data-kode="${data.kode_obat}"

                            data-nama="${data.nama_obat}"

                            data-satuan="${data.satuan}"

                            data-min="${data.stok_minimal ?? 0}"

                            data-opt="${data.stok_optimum ?? 0}"

                            data-napza="${data.obat_napza ?? 'tidak'}"

                            data-esensial="${data.obat_esensial ?? 'tidak'}"

                            data-formularium="${data.obat_formularium_puskesmas ?? 'tidak'}"
                        >

                            <i class="bi bi-check-circle me-1"></i>
                            Pilih

                        </button>
                    `;

                }

            }

        ]

    });


    /* ==================================================
       TAMBAH OBAT
    ================================================== */

    $('#btnTambah').on('click', function () {

        resetFormItem();

        const el =
            document.getElementById('offcanvasObat');

        if (el) {

            bootstrap.Offcanvas
                .getOrCreateInstance(el)
                .show();

        }

    });


    /* ==================================================
       SEARCH MASTER OBAT
    ================================================== */

    $('#keywordObat').on('keyup', function () {

        tableObat
            .search($(this).val())
            .draw();

    });


    /* ==================================================
       LOAD STOK SETTING
    ================================================== */

    function loadStokSetting() {

        if (!selectedObat) {
            return;
        }

        let kodeFaskes =
            $('#kodeFaskes').val();

        let tahun =
            $('#tahunCanvas').val() ||
            $('#tahun').val();

        if (!kodeFaskes || !tahun) {
            return;
        }

        $.ajax({

            url:
                "{{ route('newlplpo.stok-esensial.setting') }}",

            type:
                "GET",

            data: {

                kode_obat:
                    selectedObat.kode,

                kodeFaskes:
                    kodeFaskes,

                tahun:
                    tahun

            },

            success: function (res) {

                let stokMinimal = 0;

                let stokOptimum = 0;

                let obatEsensial = 'noe';

                let formularium = 'false';


                if (res.exists) {

                    stokMinimal =
                        res.stok_minimal ?? 0;

                    stokOptimum =
                        res.stok_optimum ?? 0;

                    obatEsensial =
                        res.obat_esensial ?? 'noe';

                    formularium =
                        res.obat_formularium_puskesmas ?? 'false';

                }


                $('#stok_minimum')
                    .val(stokMinimal);

                $('#stok_optimum')
                    .val(stokOptimum);

                $('#obat_esensial')
                    .val(obatEsensial);

                $('#obat_formularium_puskesmas')
                    .val(formularium);


                console.log(
                    'Setting obat:',
                    {
                        kode_obat:
                            selectedObat.kode,

                        kodeFaskes:
                            kodeFaskes,

                        tahun:
                            tahun,

                        stok_minimal:
                            stokMinimal,

                        stok_optimum:
                            stokOptimum,

                        obat_esensial:
                            obatEsensial,

                        formularium:
                            formularium
                    }
                );

            },

            error: function (xhr) {

                console.error(
                    'LOAD STOK SETTING ERROR:',
                    xhr.responseText
                );

                $('#stok_minimum')
                    .val(0);

                $('#stok_optimum')
                    .val(0);

                $('#obat_esensial')
                    .val('noe');

                $('#obat_formularium_puskesmas')
                    .val('false');

            }

        });

    }


    /* ==================================================
       PILIH OBAT
    ================================================== */

    $(document).on(
        'click',
        '.pilih-obat',
        function () {

            const button = $(this);

            const kode =
                button.data('kode');

            const nama =
                button.data('nama');

            const satuan =
                button.data('satuan');

            const stokMinimal =
                parseInt(
                    button.attr('data-min')
                ) || 0;

            const stokOptimum =
                parseInt(
                    button.attr('data-opt')
                ) || 0;


            /*
             * Isi informasi obat
             */

            $('#kode_obat')
                .val(kode);

            $('#nama_obat')
                .val(nama);

            $('#satuan')
                .val(satuan);


            /*
             * Isi parameter stok
             */

            $('#stok_minimum')
                .val(stokMinimal);

            $('#stok_optimum')
                .val(stokOptimum);


            /*
             * Preview
             */

            $('#previewKode')
                .text(kode);

            $('#previewNama')
                .text(nama);


            /*
             * Simpan obat terpilih
             */

            selectedObat = {

                kode: kode,

                nama: nama,

                satuan: satuan,

                min: stokMinimal,

                opt: stokOptimum,

                napza:
                    button.attr('data-napza')
                    || 'tidak',

                esensial:
                    button.attr('data-esensial')
                    || 'tidak',

                formularium:
                    button.attr('data-formularium')
                    || 'tidak'

            };


            /*
             * Load setting stok
             */

            loadStokSetting();


            /*
             * Load default item
             */

            if (
                $('[name="program_id"]').val()
            ) {

                loadDefaultItem();

            }

        }
    );


    /* ==================================================
       PROGRAM CHANGE
    ================================================== */

    $('[name="program_id"]')
        .on('change', function () {

            $('#previewProgram')
                .html(
                    $(this)
                        .find('option:selected')
                        .text()
                );

            if (selectedObat) {

                loadDefaultItem();

            }

        })
        .trigger('change');


    /* ==================================================
       HITUNG / VALIDASI
    ================================================== */

    $(document).on(
        'keyup change',
        '.hitung',
        function () {

            validasiPerhitungan();

        }
    );


    function angka(name) {

        return parseFloat(
            $('[name="' + name + '"]').val()
        ) || 0;

    }


    /* ==================================================
       LOAD DEFAULT ITEM
    ================================================== */

    function loadDefaultItem() {

        if (!selectedObat) {
            return;
        }

        const reportId =
            $('#report_id').val();

        const programId =
            $('[name="program_id"]').val();

        if (!reportId || !programId) {
            return;
        }


        console.log(
            'LOAD DEFAULT ITEM:',
            {
                report_id: reportId,
                kode_obat: selectedObat.kode,
                program_id: programId
            }
        );


        $.get(

            "{{ route('newlplpo.item.default') }}",

            {

                report_id:
                    reportId,

                kode_obat:
                    selectedObat.kode,

                program_id:
                    programId

            },

            function (res) {

                /*
                 * Jangan menimpa field
                 * ketika sedang EDIT.
                 *
                 * Default hanya digunakan
                 * ketika CREATE.
                 */

                const form =
                    document.getElementById('frmItem');

                const editId =
                    form?.dataset.editId;

                if (editId) {

                    console.log(
                        'EDIT MODE - default item tidak diterapkan'
                    );

                    return;

                }


                $('[name="stok_awal_progam_pkd"]')
                    .val(
                        res.stok_awal_program_pkd ?? 0
                    );

                $('[name="stok_awal_jkn"]')
                    .val(
                        res.stok_awal_jkn ?? 0
                    );

                $('[name="penerimaan_program_pkd"]')
                    .val(
                        res.penerimaan_program_pkd ?? 0
                    );

                $('[name="penerimaan_jkn"]')
                    .val(
                        res.penerimaan_jkn ?? 0
                    );


                /*
                 * Setting stok minimum/optimum
                 * jika tersedia
                 */

                if (
                    res.stok_minimum !== undefined
                ) {

                    $('#stok_minimum')
                        .val(
                            res.stok_minimum ?? 0
                        );

                }

                if (
                    res.stok_optimum !== undefined
                ) {

                    $('#stok_optimum')
                        .val(
                            res.stok_optimum ?? 0
                        );

                }

            }

        );

    }


    /* ==================================================
       VALIDASI PERHITUNGAN
    ================================================== */

    function validasiPerhitungan() {

        let stokAwalPKD =
            angka('stok_awal_progam_pkd');

        let stokAwalJKN =
            angka('stok_awal_jkn');

        let penerimaanPKD =
            angka('penerimaan_program_pkd');

        let penerimaanJKN =
            angka('penerimaan_jkn');

        let persediaanPKD =
            angka('persediaan_program_pkd');

        let persediaanJKN =
            angka('persediaan_jkn');

        let pemakaianPKD =
            angka('pemakaian_program_pkd');

        let pemakaianJKN =
            angka('pemakaian_jkn');

        let expiredPKD =
            angka('item_expired_pkd');

        let expiredJKN =
            angka('item_expired_jkn');

        let stokAkhirPKD =
            angka('stok_akhir_program_pkd');

        let stokAkhirJKN =
            angka('stok_akhir_jkn');


        cekPersediaan(
            '[name="persediaan_program_pkd"]',
            stokAwalPKD + penerimaanPKD,
            persediaanPKD
        );


        cekPersediaan(
            '[name="persediaan_jkn"]',
            stokAwalJKN + penerimaanJKN,
            persediaanJKN
        );


        cekPersediaan(
            '[name="stok_akhir_program_pkd"]',
            persediaanPKD -
            pemakaianPKD -
            expiredPKD,
            stokAkhirPKD
        );


        cekPersediaan(
            '[name="stok_akhir_jkn"]',
            persediaanJKN -
            pemakaianJKN -
            expiredJKN,
            stokAkhirJKN
        );

    }


    /* ==================================================
       CHECK PERSEDIAAN
    ================================================== */

    function cekPersediaan(
        selector,
        hasil,
        input
    ) {

        $(selector)
            .removeClass(
                'is-valid is-invalid'
            );


        if (input == hasil) {

            $(selector)
                .addClass('is-valid');

        } else {

            $(selector)
                .addClass('is-invalid');

        }

    }


    /* ==================================================
       RESET BUTTON
    ================================================== */

    $('#frmItem').on(
        'reset',
        function () {

            const form = this;

            setTimeout(function () {

                delete form.dataset.editId;

                selectedObat = null;

                $('#kode_obat').val('');
                $('#nama_obat').val('');
                $('#satuan').val('');

                $('#stok_minimum').val(0);
                $('#stok_optimum').val(0);

                $('#previewKode').html('-');
                $('#previewNama').html('-');
                $('#previewProgram').html('-');

                $('#frmItem .is-valid')
                    .removeClass('is-valid');

                $('#frmItem .is-invalid')
                    .removeClass('is-invalid');

                $('#btnSaveItem')
                    .prop('disabled', false)
                    .html(
                        '<i class="bi bi-check-circle"></i> Simpan Item'
                    )
                    .removeAttr('data-mode');

            }, 0);

        }
    );


    /* ==================================================
       EDIT ITEM
    ================================================== */

    $(document).on(
        'click',
        '.btnEditItem',
        function (e) {

            e.preventDefault();

            const id =
                $(this).data('id');

            if (!id) {

                Swal.fire(
                    'Error',
                    'ID item tidak ditemukan.',
                    'error'
                );

                return;

            }


            console.log(
                'EDIT ITEM ID:',
                id
            );


            const url =
                "{{ url('newlplpo/item') }}/"
                + id
                + "/edit";


            $.ajax({

                url: url,

                type: 'GET',

                dataType: 'json',

                headers: {

                    'Accept':
                        'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'

                },

                success: function (res) {

                    console.log(
                        'DATA EDIT FULL:',
                        res
                    );


                    if (!res.success) {

                        Swal.fire(
                            'Error',
                            res.message ||
                            'Data item tidak ditemukan.',
                            'error'
                        );

                        return;

                    }


                    const item =
                        res.data;

                    const form =
                        document.getElementById('frmItem');


                    if (!form) {

                        console.error(
                            '#frmItem tidak ditemukan'
                        );

                        return;

                    }


                    /*
                     * PENTING:
                     * Tandai FORM sebagai EDIT.
                     */

                    form.dataset.editId =
                        item.id;


                    /*
                     * Simpan obat yang sedang diedit
                     */

                    selectedObat = {

                        kode:
                            item.kode_obat,

                        nama:
                            item.nama_obat,

                        satuan:
                            item.satuan,

                        min:
                            item.stok_minimum ?? 0,

                        opt:
                            item.stok_optimum ?? 0,

                        napza:
                            item.obat_napza ?? 'tidak',

                        esensial:
                            item.obat_esensial ?? 'tidak',

                        formularium:
                            item.obat_formularium_puskesmas
                            ?? 'tidak'

                    };


                    /*
                     * Isi form
                     */

                    setValue(
                        'program_id',
                        item.program_id
                    );

                    setValue(
                        'kode_obat',
                        item.kode_obat
                    );

                    setValue(
                        'nama_obat',
                        item.nama_obat
                    );

                    setValue(
                        'satuan',
                        item.satuan
                    );

                    setValue(
                        'stok_awal_progam_pkd',
                        item.stok_awal_progam_pkd
                    );

                    setValue(
                        'stok_awal_jkn',
                        item.stok_awal_jkn
                    );

                    setValue(
                        'penerimaan_program_pkd',
                        item.penerimaan_program_pkd
                    );

                    setValue(
                        'penerimaan_jkn',
                        item.penerimaan_jkn
                    );

                    setValue(
                        'persediaan_program_pkd',
                        item.persediaan_program_pkd
                    );

                    setValue(
                        'persediaan_jkn',
                        item.persediaan_jkn
                    );

                    setValue(
                        'pemakaian_program_pkd',
                        item.pemakaian_program_pkd
                    );

                    setValue(
                        'pemakaian_jkn',
                        item.pemakaian_jkn
                    );

                    setValue(
                        'item_expired_pkd',
                        item.item_expired_pkd
                    );

                    setValue(
                        'item_expired_jkn',
                        item.item_expired_jkn
                    );

                    setValue(
                        'stok_akhir_program_pkd',
                        item.stok_akhir_program_pkd
                    );

                    setValue(
                        'stok_akhir_jkn',
                        item.stok_akhir_jkn
                    );

                    setValue(
                        'stok_minimum',
                        item.stok_minimum
                    );

                    setValue(
                        'stok_optimum',
                        item.stok_optimum
                    );

                    setValue(
                        'permintaan',
                        item.permintaan
                    );

                    setValue(
                        'pemberian_program_pkd',
                        item.pemberian_program_pkd
                    );

                    setValue(
                        'pemberian_jkn',
                        item.pemberian_jkn
                    );


                    /*
                     * Preview
                     */

                    $('#previewKode')
                        .text(
                            item.kode_obat ?? '-'
                        );

                    $('#previewNama')
                        .text(
                            item.nama_obat ?? '-'
                        );

                    $('#previewProgram')
                        .text(
                            $('#program_id option:selected')
                                .text()
                        );


                    /*
                     * Tombol menjadi UPDATE
                     */

                    $('#btnSaveItem')
                        .html(
                            '<i class="bi bi-save"></i> Update Item'
                        )
                        .attr(
                            'data-mode',
                            'edit'
                        );


                    /*
                     * Tampilkan offcanvas
                     */

                    const canvas =
                        document.getElementById(
                            'offcanvasObat'
                        );


                    if (canvas) {

                        bootstrap.Offcanvas
                            .getOrCreateInstance(canvas)
                            .show();

                    }

                },

                error: function (xhr) {

                    console.error(
                        'EDIT ITEM ERROR:',
                        xhr
                    );

                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal',

                        text:
                            xhr.responseJSON?.message
                            ??
                            'Gagal mengambil data item.'

                    });

                }

            });

        }
    );


    /* ==================================================
       SET VALUE HELPER
    ================================================== */

    function setValue(id, value) {

        const element =
            document.getElementById(id);

        if (!element) {

            console.warn(
                'Element #' + id +
                ' tidak ditemukan.'
            );

            return;

        }

        element.value =
            value ?? '';

    }


    /* ==================================================
       SIMPAN / UPDATE ITEM
    ================================================== */

    $('#btnSaveItem').on(
        'click',
        function () {

            const form =
                document.getElementById('frmItem');


            if (!form) {

                console.error(
                    '#frmItem tidak ditemukan'
                );

                return;

            }


            /*
             * Validasi perhitungan
             */

            validasiPerhitungan();


            if (
                $('#frmItem .is-invalid').length
            ) {

                Swal.fire({

                    icon: 'warning',

                    title:
                        'Perhitungan belum sesuai',

                    text:
                        'Periksa kembali nilai Persediaan dan Stok Akhir.'

                });

                return;

            }


            /*
             * Tentukan MODE
             */

            const editId =
                form.dataset.editId || null;


            let url;

            let method;


            if (editId) {

                /*
                 * ==========================
                 * UPDATE
                 * ==========================
                 */

                url =
                    "{{ url('newlplpo/item') }}/"
                    + editId;

                method =
                    'POST';

            } else {

                /*
                 * ==========================
                 * CREATE
                 * ==========================
                 */

                url =
                    "{{ route('newlplpo.item.store') }}";

                method =
                    'POST';

            }


            /*
             * Serialize form
             */

            let data =
                $('#frmItem').serialize();


            /*
             * Laravel method spoofing
             */

            if (editId) {

                data +=
                    '&_method=PUT';

            }


            console.log(
                'SAVE ITEM:',
                {
                    mode:
                        editId
                        ? 'UPDATE'
                        : 'CREATE',

                    id:
                        editId,

                    url:
                        url,

                    method:
                        editId
                        ? 'PUT'
                        : 'POST'
                }
            );


            /*
             * AJAX
             */

            $.ajax({

                url: url,

                method: method,

                data: data,

                beforeSend: function () {

                    $('#btnSaveItem')

                        .prop(
                            'disabled',
                            true
                        )

                        .html(
                            '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...'
                        );

                },


                success: function (res) {

                    console.log(
                        'SAVE RESPONSE:',
                        res
                    );


                    if (!res.success) {

                        Swal.fire({

                            icon: 'error',

                            title: 'Gagal',

                            text:
                                res.message
                                ??
                                'Gagal menyimpan item.'

                        });

                        return;

                    }


                    Swal.fire({

                        icon: 'success',

                        title: 'Berhasil',

                        text:
                            res.message
                            ??
                            (
                                editId
                                ? 'Item berhasil diupdate.'
                                : 'Item berhasil ditambahkan.'
                            ),

                        timer: 1000,

                        showConfirmButton: false

                    });


                    /*
                     * Reload ke halaman edit report
                     */

                    setTimeout(function () {

                        window.location.href =
                            "{{ route('newlplpo.edit', $report->id) }}";

                    }, 1000);

                },


                error: function (xhr) {

                    console.error(
                        'SAVE/UPDATE ITEM ERROR:',
                        xhr
                    );


                    let message =
                        'Terjadi kesalahan.';


                    if (
                        xhr.responseJSON
                    ) {

                        if (
                            xhr.responseJSON.message
                        ) {

                            message =
                                xhr.responseJSON.message;

                        }


                        if (
                            xhr.responseJSON.errors
                        ) {

                            message =
                                Object
                                    .values(
                                        xhr.responseJSON.errors
                                    )
                                    .flat()
                                    .join('<br>');

                        }

                    }


                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal',

                        html: message

                    });

                },


                complete: function () {

                    $('#btnSaveItem')

                        .prop(
                            'disabled',
                            false
                        )

                        .html(

                            editId

                            ? '<i class="bi bi-save"></i> Update Item'

                            : '<i class="bi bi-check-circle"></i> Simpan Item'

                        );

                }

            });

        }
    );


    /* ==================================================
       DELETE ITEM
    ================================================== */

    $(document).on(
        'click',
        '.btnDeleteItem',
        function (e) {

            e.preventDefault();


            const id =
                $(this).data('id');


            if (!id) {

                Swal.fire({

                    icon: 'error',

                    title: 'Gagal',

                    text:
                        'ID item tidak ditemukan.'

                });

                return;

            }


            Swal.fire({

                title:
                    'Hapus Item?',

                text:
                    'Data item ini akan dihapus.',

                icon:
                    'warning',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Ya, Hapus',

                cancelButtonText:
                    'Batal'

            }).then(function (result) {

                if (!result.isConfirmed) {

                    return;

                }


                console.log(
                    'DELETE ITEM ID:',
                    id
                );


                $.ajax({

                    url:
                        "{{ url('newlplpo/item') }}/"
                        + id,

                    type:
                        'POST',

                    data: {

                        _token:
                            "{{ csrf_token() }}",

                        _method:
                            'DELETE'

                    },


                    beforeSend: function () {

                        /*
                         * Bisa ditambahkan loading
                         * pada tombol jika diperlukan.
                         */

                    },


                    success: function (res) {

                        console.log(
                            'DELETE RESPONSE:',
                            res
                        );


                        if (!res.success) {

                            Swal.fire({

                                icon:
                                    'error',

                                title:
                                    'Gagal',

                                text:
                                    res.message
                                    ??
                                    'Gagal menghapus item.'

                            });

                            return;

                        }


                        Swal.fire({

                            icon:
                                'success',

                            title:
                                'Berhasil',

                            text:
                                res.message
                                ??
                                'Item berhasil dihapus.',

                            timer:
                                1000,

                            showConfirmButton:
                                false

                        });


                        /*
                         * Reload halaman
                         */

                        setTimeout(function () {

                            window.location.href =
                                "{{ route('newlplpo.edit', $report->id) }}";

                        }, 1000);

                    },


                    error: function (xhr) {

                        console.error(
                            'DELETE ITEM ERROR:',
                            xhr
                        );


                        Swal.fire({

                            icon:
                                'error',

                            title:
                                'Gagal',

                            text:
                                xhr.responseJSON?.message
                                ??
                                'Gagal menghapus item.'

                        });

                    }

                });

            });

        }
    );

});

</script>

@endpush
