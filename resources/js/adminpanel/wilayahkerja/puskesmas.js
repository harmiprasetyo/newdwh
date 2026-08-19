let wilayahTable = null;
let wilayahModal = null;

/*
|--------------------------------------------------------------------------
| CACHE
|--------------------------------------------------------------------------
*/

let masterFaskesCache = [];

let provinceCache = [];
let cityCache = [];
let districtCache = [];
let villageCache = [];


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

$.ajaxSetup({

    headers: {
        'X-CSRF-TOKEN':
            $('meta[name="csrf-token"]').attr('content')
    }

});


/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    console.log('==========================================');
    console.log('INIT WILAYAH KERJA PUSKESMAS');
    console.log('CONFIG:',
        window.WilayahKerjaPuskesmasConfig
    );
    console.log('==========================================');


    const modalElement =
        document.getElementById(
            'wilayahPuskesmasModal'
        );


    if (modalElement) {

        wilayahModal =
            new bootstrap.Modal(
                modalElement
            );

    }


    initSelect2();

    bindEvents();

    loadTable();

    loadFaskes();

    /*
    |--------------------------------------------------------------------------
    | Jangan load semua desa
    |--------------------------------------------------------------------------
    |
    | Desa akan dimuat berdasarkan Kecamatan Puskesmas.
    |
    */

    // loadFilterDesa();


    console.log(
        'WILAYAH KERJA PUSKESMAS READY'
    );

});


/*
|--------------------------------------------------------------------------
| USER GROUP
|--------------------------------------------------------------------------
*/

function getGroupId()
{
    return String(
        window.WilayahKerjaPuskesmasConfig
            .groupId || ''
    );
}


function getUserKodeFaskes()
{
    return String(
        window.WilayahKerjaPuskesmasConfig
            .userKodeFaskes || ''
    );
}


function isGroup3()
{
    return getGroupId() === '3';
}


function isGroup12()
{
    return (
        getGroupId() === '1' ||
        getGroupId() === '2'
    );
}


/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/

function bindEvents()
{

    /*
    |--------------------------------------------------------------------------
    | ADD
    |--------------------------------------------------------------------------
    */

    $('#btnAddWilayah').on(
        'click',
        openModal
    );


    /*
    |--------------------------------------------------------------------------
    | RESET FILTER
    |--------------------------------------------------------------------------
    */

    $('#btnResetFilter').on(
        'click',
        function () {

            $('#filterFaskes')
                .val('')
                .trigger('change');

            $('#filterDesa')
                .val('')
                .trigger('change');

            reloadTable();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FASKES CHANGE
    |--------------------------------------------------------------------------
    |
    | Delegated event agar aman terhadap Select2.
    |
    */

    $(document).on(
        'change',
        '#kodeFaskes',
        async function () {

            console.log(
                '------------------------------------------'
            );

            console.log(
                'FASKES CHANGE'
            );


            const kodeFaskes =
                String(
                    $(this).val() || ''
                );


            console.log(
                'kodeFaskes:',
                kodeFaskes
            );


            if (!kodeFaskes) {

                resetLocation();

                return;

            }


            const faskes =
                findFaskesFromCache(
                    kodeFaskes
                );


            console.log(
                'FASKES DARI CACHE:',
                faskes
            );


            if (!faskes) {

                resetLocation();

                showError(
                    'Data Puskesmas tidak ditemukan.'
                );

                return;

            }


            await applyFaskesLocation(
                kodeFaskes,
                faskes
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    $('#filterFaskes').on(
        'change',
        async function () {

            const kodeFaskes =
                $(this).val();


            /*
            |--------------------------------------------------------------------------
            | Filter Desa
            |--------------------------------------------------------------------------
            */

            await loadFilterDesa(
                kodeFaskes
            );


            reloadTable();

        }
    );


    $('#filterDesa').on(
        'change',
        reloadTable
    );


    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    $('#wilayahPuskesmasForm').on(
        'submit',
        function (e) {

            e.preventDefault();

            saveWilayah();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-edit',
        function () {

            editWilayah(
                $(this).data('id')
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-delete',
        function () {

            deleteWilayah(
                $(this).data('id')
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| GROUP 3 SETUP
|--------------------------------------------------------------------------
*/

function setupGroup3()
{

    $('#faskesContainer')
        .hide();


    const kodeFaskes =
        getUserKodeFaskes();


    if (!kodeFaskes) {

        console.error(
            'GROUP 3 TIDAK MEMILIKI kodeFaskes'
        );

        return;

    }


    $('#kodeFaskes')
        .val(kodeFaskes)
        .prop('disabled', true)
        .trigger('change');

}


/*
|--------------------------------------------------------------------------
| DATATABLE
|--------------------------------------------------------------------------
*/

function loadTable()
{

    console.log(
        'LOAD DATATABLE'
    );


    wilayahTable =
        $('#wilayahPuskesmasTable')
            .DataTable({

                processing: true,

                serverSide: true,

                searching: false,

                responsive: true,

                pageLength: 25,

                ajax: {

                    url:
                        window
                            .WilayahKerjaPuskesmasConfig
                            .datatableUrl,

                    type: 'GET',

                    data: function (d) {

                        d.kodeFaskes =
                            $('#filterFaskes')
                                .val();

                        d.kodeDesa =
                            $('#filterDesa')
                                .val();

                    },

                    error: function (xhr) {

                        console.error(
                            'DATATABLE ERROR:',
                            xhr.responseText
                        );

                    }

                },

                columns: [

                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className:
                            'text-center',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'namaFaskes',
                        name: 'namaFaskes',
                        defaultContent: '-'
                    },

                    {
                        data: 'namaDesa',
                        name: 'namaDesa',
                        defaultContent: '-'
                    },

                    {
                        data: 'kecamatan',
                        name: 'kecamatan',
                        defaultContent: '-'
                    },

                    {
                        data: 'kota',
                        name: 'kota',
                        defaultContent: '-'
                    },

                    {
                        data: 'provinsi',
                        name: 'provinsi',
                        defaultContent: '-'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        className:
                            'text-center',
                        orderable: false,
                        searchable: false
                    }

                ],

                order: [
                    [1, 'asc']
                ],

                language: {

                    emptyTable:
                        'Belum ada wilayah kerja.',

                    zeroRecords:
                        'Data tidak ditemukan.',

                    processing:
                        'Memuat data...',

                    lengthMenu:
                        '_MENU_ data',

                    info:
                        'Menampilkan _START_ - _END_ dari _TOTAL_ data',

                    paginate: {

                        previous:
                            '<i class="fas fa-chevron-left"></i>',

                        next:
                            '<i class="fas fa-chevron-right"></i>'

                    }

                }

            });

}


/*
|--------------------------------------------------------------------------
| LOAD MASTER FASKES
|--------------------------------------------------------------------------
*/

function loadFaskes()
{

    console.log(
        'LOAD MASTER FASKES'
    );


    $.ajax({

        url:
            window
                .WilayahKerjaPuskesmasConfig
                .faskesUrl,

        type: 'GET',

        success: function (response) {

            console.log(
                'RESPONSE MASTER FASKES:',
                response
            );


            masterFaskesCache =
                response.data || [];


            console.log(
                'MASTER FASKES CACHE:',
                masterFaskesCache
            );


            /*
            |--------------------------------------------------------------------------
            | FORM FASKES
            |--------------------------------------------------------------------------
            */

            if (isGroup12()) {

                let options =
                    '<option value="">Pilih Puskesmas</option>';


                masterFaskesCache
                    .forEach(function (item) {

                        options += `
                            <option value="${escapeHtml(
                                item.kodeFaskes
                            )}">
                                ${escapeHtml(
                                    item.namaFaskes
                                )}
                            </option>
                        `;

                    });


                $('#kodeFaskes')
                    .html(options)
                    .trigger('change.select2');

            }


            /*
            |--------------------------------------------------------------------------
            | FILTER FASKES
            |--------------------------------------------------------------------------
            */

            let filterOptions =
                '<option value="">Semua Puskesmas</option>';


            masterFaskesCache
                .forEach(function (item) {

                    filterOptions += `
                        <option value="${escapeHtml(
                            item.kodeFaskes
                        )}">
                            ${escapeHtml(
                                item.namaFaskes
                            )}
                        </option>
                    `;

                });


            $('#filterFaskes')
                .html(filterOptions)
                .trigger('change.select2');


            /*
            |--------------------------------------------------------------------------
            | GROUP 3
            |--------------------------------------------------------------------------
            */

            if (isGroup3()) {

                setupGroup3();

            }

        },

        error: function (xhr) {

            console.error(
                'LOAD MASTER FASKES ERROR:',
                xhr.responseJSON ||
                xhr.responseText
            );


            showError(
                'Gagal mengambil data Master Faskes.'
            );

        }

    });

}


/*
|--------------------------------------------------------------------------
| FIND FASKES CACHE
|--------------------------------------------------------------------------
*/

function findFaskesFromCache(
    kodeFaskes
)
{

    return masterFaskesCache.find(
        function (item) {

            return String(
                item.kodeFaskes
            ) === String(
                kodeFaskes
            );

        }
    ) || null;

}


/*
|--------------------------------------------------------------------------
| APPLY FASKES LOCATION
|--------------------------------------------------------------------------
*/

async function applyFaskesLocation(
    kodeFaskes,
    faskesData = null,
    selectedDesa = null
)
{

    console.log(
        '=========================================='
    );

    console.log(
        'APPLY FASKES LOCATION'
    );

    console.log(
        'kodeFaskes:',
        kodeFaskes
    );


    try {

        let faskes =
            faskesData;


        if (!faskes) {

            faskes =
                findFaskesFromCache(
                    kodeFaskes
                );

        }


        if (!faskes) {

            throw new Error(
                'Data Puskesmas tidak ditemukan.'
            );

        }


        console.log(
            'DATA MASTER FASKES:',
            faskes
        );


        const provinceCode =
            String(
                faskes.kodePropinsi || ''
            );


        const cityCode =
            String(
                faskes.kodeKabupaten || ''
            );


        const districtCode =
            String(
                faskes.kodeKecamatan || ''
            );


        console.log(
            'KODE WILAYAH:',
            {
                provinceCode,
                cityCode,
                districtCode
            }
        );


        if (!provinceCode) {

            throw new Error(
                'Kode provinsi tidak tersedia.'
            );

        }


        if (!cityCode) {

            throw new Error(
                'Kode kabupaten/kota tidak tersedia.'
            );

        }


        if (!districtCode) {

            throw new Error(
                'Kode kecamatan tidak tersedia.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PROVINSI
        |--------------------------------------------------------------------------
        */

        await loadProvinceByCode(
            provinceCode
        );


        /*
        |--------------------------------------------------------------------------
        | KOTA
        |--------------------------------------------------------------------------
        */

        await loadCityByCode(
            provinceCode,
            cityCode
        );


        /*
        |--------------------------------------------------------------------------
        | KECAMATAN
        |--------------------------------------------------------------------------
        */

        await loadDistrictByCode(
            cityCode,
            districtCode
        );


        /*
        |--------------------------------------------------------------------------
        | DESA
        |--------------------------------------------------------------------------
        */

        await loadVillages(
            districtCode,
            selectedDesa
        );


        console.log(
            'LOKASI FASKES SELESAI'
        );


    }
    catch (error) {

        console.error(
            'APPLY FASKES LOCATION ERROR:',
            error
        );


        resetLocation();


        showError(
            error.message ||
            'Gagal mengambil wilayah Puskesmas.'
        );

    }

}


/*
|--------------------------------------------------------------------------
| LOAD PROVINCE
|--------------------------------------------------------------------------
*/

function loadProvinceByCode(
    provinceCode
)
{

    return new Promise(
        function (
            resolve,
            reject
        ) {

            const select =
                $('#kodePropinsi');


            select
                .html(
                    '<option value="">Memuat Provinsi...</option>'
                )
                .prop(
                    'disabled',
                    true
                )
                .trigger(
                    'change.select2'
                );


            $.ajax({

                url:
                    window
                        .WilayahKerjaPuskesmasConfig
                        .provinceUrl,

                type: 'GET',

                success: function (response) {

                    console.log(
                        'RESPONSE PROVINSI:',
                        response
                    );


                    provinceCache =
                        response.data || [];


                    const found =
                        findRegion(
                            provinceCache,
                            provinceCode,
                            'province'
                        );


                    if (!found) {

                        console.error(
                            'PROVINSI TIDAK DITEMUKAN:',
                            provinceCode
                        );


                        reject(
                            new Error(
                                'Provinsi tidak ditemukan.'
                            )
                        );

                        return;

                    }


                    const code =
                        getRegionCode(
                            found
                        );


                    const name =
                        getRegionName(
                            found
                        );


                    console.log(
                        'PROVINSI TERPILIH:',
                        {
                            code,
                            name
                        }
                    );


                    select
                        .html(`
                            <option value="${escapeHtml(code)}">
                                ${escapeHtml(name)}
                            </option>
                        `)
                        .val(code)
                        .prop(
                            'disabled',
                            true
                        )
                        .trigger(
                            'change.select2'
                        );


                    resolve();

                },

                error: function (xhr) {

                    console.error(
                        'LOAD PROVINSI ERROR:',
                        xhr.responseJSON ||
                        xhr.responseText
                    );


                    reject(xhr);

                }

            });

        }
    );

}


/*
|--------------------------------------------------------------------------
| LOAD CITY
|--------------------------------------------------------------------------
*/

function loadCityByCode(
    provinceCode,
    cityCode
)
{

    return new Promise(
        function (
            resolve,
            reject
        ) {

            const select =
                $('#kodeKota');


            select
                .html(
                    '<option value="">Memuat Kota / Kabupaten...</option>'
                )
                .prop(
                    'disabled',
                    true
                )
                .trigger(
                    'change.select2'
                );


            $.ajax({

                url:
                    window
                        .WilayahKerjaPuskesmasConfig
                        .cityUrl,

                type: 'GET',

                data: {

                    province_code:
                        provinceCode

                },

                success: function (response) {

                    console.log(
                        'RESPONSE KOTA:',
                        response
                    );


                    cityCache =
                        response.data || [];


                    const found =
                        findRegion(
                            cityCache,
                            cityCode,
                            'city'
                        );


                    if (!found) {

                        console.error(
                            'KOTA TIDAK DITEMUKAN:',
                            cityCode
                        );


                        reject(
                            new Error(
                                'Kota/Kabupaten tidak ditemukan.'
                            )
                        );

                        return;

                    }


                    const code =
                        getRegionCode(
                            found
                        );


                    const name =
                        getRegionName(
                            found
                        );


                    console.log(
                        'KOTA TERPILIH:',
                        {
                            code,
                            name
                        }
                    );


                    select
                        .html(`
                            <option value="${escapeHtml(code)}">
                                ${escapeHtml(name)}
                            </option>
                        `)
                        .val(code)
                        .prop(
                            'disabled',
                            true
                        )
                        .trigger(
                            'change.select2'
                        );


                    resolve();

                },

                error: function (xhr) {

                    console.error(
                        'LOAD KOTA ERROR:',
                        xhr.responseJSON ||
                        xhr.responseText
                    );


                    reject(xhr);

                }

            });

        }
    );

}


/*
|--------------------------------------------------------------------------
| LOAD DISTRICT
|--------------------------------------------------------------------------
*/

function loadDistrictByCode(
    cityCode,
    districtCode
)
{

    return new Promise(
        function (
            resolve,
            reject
        ) {

            const select =
                $('#kodeKecamatan');


            select
                .html(
                    '<option value="">Memuat Kecamatan...</option>'
                )
                .prop(
                    'disabled',
                    true
                )
                .trigger(
                    'change.select2'
                );


            $.ajax({

                url:
                    window
                        .WilayahKerjaPuskesmasConfig
                        .districtUrl,

                type: 'GET',

                data: {

                    city_code:
                        cityCode

                },

                success: function (response) {

                    console.log(
                        'RESPONSE KECAMATAN:',
                        response
                    );


                    districtCache =
                        response.data || [];


                    const found =
                        findRegion(
                            districtCache,
                            districtCode,
                            'district'
                        );


                    if (!found) {

                        console.error(
                            'KECAMATAN TIDAK DITEMUKAN:',
                            districtCode
                        );


                        reject(
                            new Error(
                                'Kecamatan tidak ditemukan.'
                            )
                        );

                        return;

                    }


                    const code =
                        getRegionCode(
                            found
                        );


                    const name =
                        getRegionName(
                            found
                        );


                    console.log(
                        'KECAMATAN TERPILIH:',
                        {
                            code,
                            name
                        }
                    );


                    select
                        .html(`
                            <option value="${escapeHtml(code)}">
                                ${escapeHtml(name)}
                            </option>
                        `)
                        .val(code)
                        .prop(
                            'disabled',
                            true
                        )
                        .trigger(
                            'change.select2'
                        );


                    resolve();

                },

                error: function (xhr) {

                    console.error(
                        'LOAD KECAMATAN ERROR:',
                        xhr.responseJSON ||
                        xhr.responseText
                    );


                    reject(xhr);

                }

            });

        }
    );

}


/*
|--------------------------------------------------------------------------
| REGION CODE
|--------------------------------------------------------------------------
*/

function getRegionCode(
    item
)
{

    return String(

        item.code ??
        item.kode ??
        item.kodePropinsi ??
        item.kodeProvinsi ??
        item.kodeKabupaten ??
        item.kodeKota ??
        item.kodeKecamatan ??
        item.id ??
        ''

    );

}


/*
|--------------------------------------------------------------------------
| REGION NAME
|--------------------------------------------------------------------------
*/

function getRegionName(
    item
)
{

    /*
    |--------------------------------------------------------------------------
    | Prioritas field nama
    |--------------------------------------------------------------------------
    */

    const name =

        item.name ??
        item.nama ??
        item.namaPropinsi ??
        item.namaProvinsi ??
        item.namaKabupaten ??
        item.namaKota ??
        item.namaKecamatan ??
        item.namapropinsi ??
        item.namaprovinsi ??
        item.namakabupaten ??
        item.namakota ??
        item.namakecamatan ??
        item.label ??
        item.text ??
        '';


    return String(
        name || ''
    );

}


/*
|--------------------------------------------------------------------------
| FIND REGION
|--------------------------------------------------------------------------
*/

function findRegion(
    data,
    code,
    type
)
{

    if (!Array.isArray(data)) {

        return null;

    }


    const target =
        String(code);


    return data.find(
        function (item) {

            /*
            |--------------------------------------------------------------------------
            | Semua kemungkinan kode
            |--------------------------------------------------------------------------
            */

            const possibleCodes = [

                item.code,

                item.kode,

                item.id,

                item.kodePropinsi,

                item.kodeProvinsi,

                item.kodeKabupaten,

                item.kodeKota,

                item.kodeKecamatan

            ];


            return possibleCodes.some(
                function (value) {

                    return (
                        value !== undefined &&
                        value !== null &&
                        String(value) === target
                    );

                }
            );

        }
    ) || null;

}


/*
|--------------------------------------------------------------------------
| LOAD VILLAGES
|--------------------------------------------------------------------------
*/

function loadVillages(
    districtCode,
    selected = null
)
{

    return new Promise(
        function (
            resolve,
            reject
        ) {

            console.log(
                'LOAD DESA'
            );

            console.log(
                'districtCode:',
                districtCode
            );


            const select =
                $('#kodeDesa');


            select
                .html(
                    '<option value="">Memuat desa...</option>'
                )
                .prop(
                    'disabled',
                    true
                )
                .trigger(
                    'change.select2'
                );


            if (!districtCode) {

                select
                    .html(
                        '<option value="">Pilih Desa / Kelurahan</option>'
                    )
                    .prop(
                        'disabled',
                        true
                    )
                    .val('')
                    .trigger(
                        'change.select2'
                    );


                resolve();

                return;

            }


            $.ajax({

                url:
                    window
                        .WilayahKerjaPuskesmasConfig
                        .villageUrl,

                type: 'GET',

                data: {

                    district_code:
                        districtCode

                },

                success: function (response) {

                    console.log(
                        'RESPONSE DESA:',
                        response
                    );


                    villageCache =
                        response.data || [];


                    let options =
                        '<option value="">Pilih Desa / Kelurahan</option>';


                    villageCache
                        .forEach(
                            function (item) {

                                const code =
                                    getRegionCode(
                                        item
                                    );


                                const name =
                                    getRegionName(
                                        item
                                    );


                                options += `
                                    <option value="${escapeHtml(code)}">
                                        ${escapeHtml(name)}
                                    </option>
                                `;

                            }
                        );


                    select
                        .html(options)
                        .prop(
                            'disabled',
                            false
                        );


                    if (
                        selected !== null &&
                        selected !== undefined &&
                        selected !== ''
                    ) {

                        select.val(
                            String(selected)
                        );

                    }


                    select.trigger(
                        'change.select2'
                    );


                    console.log(
                        'DESA BERHASIL DIMUAT:',
                        villageCache.length
                    );


                    resolve();

                },

                error: function (xhr) {

                    console.error(
                        'LOAD DESA ERROR:',
                        xhr.responseJSON ||
                        xhr.responseText
                    );


                    select
                        .html(
                            '<option value="">Gagal memuat desa</option>'
                        );


                    reject(xhr);

                }

            });

        }
    );

}


/*
|--------------------------------------------------------------------------
| FILTER DESA
|--------------------------------------------------------------------------
*/

function loadFilterDesa(
    kodeFaskes = ''
)
{

    const select =
        $('#filterDesa');


    select
        .html(
            '<option value="">Semua Desa</option>'
        )
        .val('')
        .trigger(
            'change.select2'
        );


    if (!kodeFaskes) {

        return;

    }


    const faskes =
        findFaskesFromCache(
            kodeFaskes
        );


    if (!faskes) {

        return;

    }


    const districtCode =
        faskes.kodeKecamatan;


    if (!districtCode) {

        return;

    }


    $.ajax({

        url:
            window
                .WilayahKerjaPuskesmasConfig
                .villageUrl,

        type: 'GET',

        data: {

            district_code:
                districtCode

        },

        success: function (response) {

            console.log(
                'FILTER DESA RESPONSE:',
                response
            );


            let options =
                '<option value="">Semua Desa</option>';


            (response.data || [])
                .forEach(
                    function (item) {

                        const code =
                            getRegionCode(
                                item
                            );


                        const name =
                            getRegionName(
                                item
                            );


                        options += `
                            <option value="${escapeHtml(code)}">
                                ${escapeHtml(name)}
                            </option>
                        `;

                    }
                );


            select
                .html(options)
                .trigger(
                    'change.select2'
                );

        },

        error: function (xhr) {

            console.error(
                'FILTER DESA ERROR:',
                xhr.responseJSON ||
                xhr.responseText
            );

        }

    });

}


/*
|--------------------------------------------------------------------------
| OPEN MODAL
|--------------------------------------------------------------------------
*/

function openModal()
{

    console.log(
        'OPEN ADD MODAL'
    );


    $('#wilayahPuskesmasForm')[0]
        .reset();


    $('#wilayahId')
        .val('');


    clearValidation();


    $('#wilayahPuskesmasModalTitle')
        .text(
            'Tambah Wilayah Kerja Puskesmas'
        );


    resetLocation();


    if (isGroup3()) {

        $('#faskesContainer')
            .hide();


        const kodeFaskes =
            getUserKodeFaskes();


        $('#kodeFaskes')
            .val(kodeFaskes)
            .prop(
                'disabled',
                true
            )
            .trigger(
                'change'
            );

    }
    else {

        $('#faskesContainer')
            .show();


        $('#kodeFaskes')
            .prop(
                'disabled',
                false
            )
            .val('')
            .trigger(
                'change.select2'
            );

    }


    wilayahModal.show();

}


/*
|--------------------------------------------------------------------------
| RESET LOCATION
|--------------------------------------------------------------------------
*/

function resetLocation()
{

    $('#kodePropinsi')
        .html(
            '<option value="">Pilih Provinsi</option>'
        )
        .val('')
        .prop(
            'disabled',
            true
        )
        .trigger(
            'change.select2'
        );


    $('#kodeKota')
        .html(
            '<option value="">Pilih Kota / Kabupaten</option>'
        )
        .val('')
        .prop(
            'disabled',
            true
        )
        .trigger(
            'change.select2'
        );


    $('#kodeKecamatan')
        .html(
            '<option value="">Pilih Kecamatan</option>'
        )
        .val('')
        .prop(
            'disabled',
            true
        )
        .trigger(
            'change.select2'
        );


    $('#kodeDesa')
        .html(
            '<option value="">Pilih Desa / Kelurahan</option>'
        )
        .val('')
        .prop(
            'disabled',
            true
        )
        .trigger(
            'change.select2'
        );

}


/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

function editWilayah(
    id
)
{

    Swal.fire({

        title:
            'Memuat data...',

        allowOutsideClick:
            false,

        allowEscapeKey:
            false,

        didOpen:
            function () {

                Swal.showLoading();

            }

    });


    $.ajax({

        url:
            window
                .WilayahKerjaPuskesmasConfig
                .baseUrl +
            '/' +
            id,

        type:
            'GET',

        success:
            async function (response) {

                try {

                    const data =
                        response.data;


                    $('#wilayahId')
                        .val(data.id);


                    $('#wilayahPuskesmasModalTitle')
                        .text(
                            'Edit Wilayah Kerja Puskesmas'
                        );


                    if (isGroup3()) {

                        $('#faskesContainer')
                            .hide();

                        $('#kodeFaskes')
                            .val(
                                getUserKodeFaskes()
                            )
                            .prop(
                                'disabled',
                                true
                            );

                    }
                    else {

                        $('#faskesContainer')
                            .show();

                        $('#kodeFaskes')
                            .prop(
                                'disabled',
                                false
                            );

                    }


                    $('#kodeFaskes')
                        .val(
                            String(
                                data.kodeFaskes ||
                                ''
                            )
                        )
                        .trigger(
                            'change.select2'
                        );


                    await applyFaskesLocation(

                        data.kodeFaskes,

                        data.faskes,

                        data.kodeDesa

                    );


                    Swal.close();


                    wilayahModal.show();

                }
                catch (error) {

                    console.error(
                        'EDIT ERROR:',
                        error
                    );


                    Swal.close();


                    showError(
                        'Gagal memproses data wilayah.'
                    );

                }

            },

        error:
            function (xhr) {

                Swal.close();


                console.error(
                    'GET WILAYAH ERROR:',
                    xhr.responseJSON ||
                    xhr.responseText
                );


                showError(
                    xhr.responseJSON?.message ||
                    'Gagal mengambil data wilayah.'
                );

            }

    });

}


/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/

function saveWilayah()
{

    const id =
        $('#wilayahId')
            .val();


    clearValidation();


    const kodeFaskes =
        isGroup3()
            ? getUserKodeFaskes()
            : $('#kodeFaskes')
                .val();


    const kodeDesa =
        $('#kodeDesa')
            .val();


    const data = {

        kodeFaskes:
            kodeFaskes,

        kodeDesa:
            kodeDesa

    };


    if (!data.kodeFaskes) {

        showError(
            'Puskesmas belum ditentukan.'
        );

        return;

    }


    if (!data.kodeDesa) {

        showError(
            'Desa wajib dipilih.'
        );

        return;

    }


    const url =
        id
            ? `${window.WilayahKerjaPuskesmasConfig.baseUrl}/${id}`
            : window.WilayahKerjaPuskesmasConfig.baseUrl;


    const method =
        id
            ? 'PUT'
            : 'POST';


    const button =
        $('#btnSaveWilayah');


    button
        .prop(
            'disabled',
            true
        )
        .html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Menyimpan...
        `);


    $.ajax({

        url:
            url,

        type:
            method,

        data:
            data,

        success:
            function (response) {

                wilayahModal.hide();


                reloadTable();


                Swal.fire({

                    icon:
                        'success',

                    title:
                        'Berhasil',

                    text:
                        response.message ||
                        'Data berhasil disimpan.',

                    timer:
                        1500,

                    showConfirmButton:
                        false

                });

            },

        error:
            function (xhr) {

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    showValidationErrors(
                        xhr.responseJSON.errors
                    );

                    return;

                }


                showError(
                    xhr.responseJSON?.message ||
                    'Terjadi kesalahan.'
                );

            },

        complete:
            function () {

                button
                    .prop(
                        'disabled',
                        false
                    )
                    .html(`
                        <i class="fas fa-save me-2"></i>
                        Simpan
                    `);

            }

    });

}


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

function deleteWilayah(
    id
)
{

    Swal.fire({

        title:
            'Hapus Wilayah?',

        text:
            'Mapping wilayah kerja ini akan dihapus.',

        icon:
            'warning',

        showCancelButton:
            true,

        confirmButtonText:
            'Ya, Hapus',

        cancelButtonText:
            'Batal',

        reverseButtons:
            true

    })
    .then(
        function (result) {

            if (!result.isConfirmed) {

                return;

            }


            $.ajax({

                url:
                    `${window.WilayahKerjaPuskesmasConfig.baseUrl}/${id}`,

                type:
                    'DELETE',

                success:
                    function (response) {

                        reloadTable();


                        Swal.fire({

                            icon:
                                'success',

                            title:
                                'Berhasil',

                            text:
                                response.message ||
                                'Data berhasil dihapus.',

                            timer:
                                1500,

                            showConfirmButton:
                                false

                        });

                    },

                error:
                    function (xhr) {

                        showError(
                            xhr.responseJSON?.message ||
                            'Data tidak dapat dihapus.'
                        );

                    }

            });

        }
    );

}


/*
|--------------------------------------------------------------------------
| RELOAD TABLE
|--------------------------------------------------------------------------
*/

function reloadTable()
{

    if (wilayahTable) {

        wilayahTable.ajax.reload(
            null,
            false
        );

    }

}


/*
|--------------------------------------------------------------------------
| SELECT2
|--------------------------------------------------------------------------
*/

function initSelect2()
{

    $(
        '#kodeFaskes,' +
        '#kodePropinsi,' +
        '#kodeKota,' +
        '#kodeKecamatan,' +
        '#kodeDesa'
    )
    .select2({

        dropdownParent:
            $('#wilayahPuskesmasModal'),

        width:
            '100%'

    });


    $('#filterFaskes, #filterDesa')
        .select2({

            width:
                '100%'

        });

}


/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
*/

function clearValidation()
{

    $('.is-invalid')
        .removeClass(
            'is-invalid'
        );


    $('.invalid-feedback')
        .text('');

}


function showValidationErrors(
    errors
)
{

    Object.keys(errors)
        .forEach(
            function (field) {

                const input =
                    $('#' + field);


                input.addClass(
                    'is-invalid'
                );


                $('#' + field + 'Error')
                    .text(
                        errors[field][0]
                    );

            }
        );

}


/*
|--------------------------------------------------------------------------
| ERROR
|--------------------------------------------------------------------------
*/

function showError(
    message
)
{

    Swal.fire({

        icon:
            'error',

        title:
            'Gagal',

        text:
            message

    });

}


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(
    value
)
{

    return $('<div>')
        .text(
            value ?? ''
        )
        .html();

}
