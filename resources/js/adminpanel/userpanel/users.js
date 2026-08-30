$(function () {

    let table = null;

    const config =
        window.UserPanelConfig || {};

    const currentUser =
        config.currentUser || {};

    const isGroup3 =
        parseInt(currentUser.groupid) === 3;


    console.log('USER PANEL DEBUG', {
        groupid: currentUser.groupid,
        isGroup3: isGroup3,
        kodePropinsi: currentUser.kodePropinsi,
        kodeKota: currentUser.kodeKota,
        kodeKecamatan: currentUser.kodeKecamatan,
        kodeFaskes: currentUser.kodeFaskes
    });

    console.log('USER.JS BERHASIL DIJALANKAN');


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
    | SELECT2
    |--------------------------------------------------------------------------
    */

    function initSelect2() {

        $('#groupid, #role_id, #kodePropinsi, #kodeKota, #kodeKecamatan, #kodeFaskes')
            .select2({

                dropdownParent:
                    $('#userModal'),

                width:
                    '100%',

                placeholder:
                    'Pilih',

                allowClear:
                    true

            });


        $('#filterGroup, #filterRole, #filterProvinsi, #filterKota')
            .select2({

                width:
                    '100%',

                placeholder:
                    'Filter',

                allowClear:
                    true

            });

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD GROUP
    |--------------------------------------------------------------------------
    */

    function loadGroups(selected = null) {

        $.ajax({

            url:
                config.groupsUrl,

            type:
                'GET',

            success:
                function (res) {

                    let html =
                        '<option value="">Pilih Group</option>';


                    if (
                        !res ||
                        !Array.isArray(res.data)
                    ) {

                        console.error(
                            'Format response group tidak valid.',
                            res
                        );

                        return;
                    }


                    res.data.forEach(function (group) {

                        const groupId =
                            parseInt(group.group_id);


                        /*
                        |--------------------------------------------------------------------------
                        | GROUP 3
                        |--------------------------------------------------------------------------
                        | Group 3 hanya boleh membuat user Group 4, 5, 6.
                        */

                        if (
                            isGroup3 &&
                            groupId !== 4 &&
                            groupId !== 5 &&
                            groupId !== 6
                        ) {

                            return;

                        }


                        html += `
                            <option value="${escapeHtml(group.group_id)}">
                                ${escapeHtml(group.group_name)}
                            </option>
                        `;

                    });


                    $('#groupid')
                        .html(html)
                        .val(
                            selected !== null
                                ? String(selected)
                                : ''
                        )
                        .trigger('change.select2');


                    /*
                    |--------------------------------------------------------------------------
                    | FILTER GROUP
                    |--------------------------------------------------------------------------
                    */

                    let filterHtml =
                        '<option value="">Semua Group</option>';


                    res.data.forEach(function (group) {

                        filterHtml += `
                            <option value="${escapeHtml(group.group_id)}">
                                ${escapeHtml(group.group_name)}
                            </option>
                        `;

                    });


                    $('#filterGroup')
                        .html(filterHtml)
                        .trigger('change.select2');

                },

            error:
                function (xhr) {

                    console.error(
                        'Gagal load group:',
                        xhr.responseText
                    );

                }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD ROLES
    |--------------------------------------------------------------------------
    */

    function loadRoles(
        groupId,
        selectedRole = null
    ) {

        console.log('LOAD ROLE', {
            groupId: groupId,
            selectedRole: selectedRole,
            url: config.rolesByGroupUrl
        });


        const $role =
            $('#role_id');


        $role
            .empty()
            .append(
                $('<option>', {
                    value: '',
                    text: 'Pilih Role'
                })
            )
            .val('')
            .trigger('change.select2');


        if (!groupId) {

            console.warn(
                'Group belum dipilih.'
            );

            return;

        }


        $.ajax({

            url:
                config.rolesByGroupUrl,

            type:
                'GET',

            data: {
                groupid: groupId
            },

            dataType:
                'json',

            success:
                function (res) {

                    console.log(
                        'ROLE RESPONSE:',
                        res
                    );


                    if (
                        !res ||
                        !Array.isArray(res.data)
                    ) {

                        console.error(
                            'Format response role tidak valid:',
                            res
                        );

                        return;

                    }


                    let html =
                        '<option value="">Pilih Role</option>';


                    res.data.forEach(function (role) {

                        html += `
                            <option value="${escapeHtml(role.id)}">
                                ${escapeHtml(role.role_name)}
                            </option>
                        `;

                    });


                    $role
                        .html(html)
                        .val(
                            selectedRole !== null
                                ? String(selectedRole)
                                : ''
                        )
                        .trigger('change.select2');


                    console.log(
                        'ROLE LOADED:',
                        res.data
                    );

                },

            error:
                function (xhr) {

                    console.error(
                        'Gagal load role:',
                        xhr.status,
                        xhr.responseText
                    );


                    $role
                        .empty()
                        .append(
                            $('<option>', {
                                value: '',
                                text: 'Gagal memuat Role'
                            })
                        )
                        .trigger('change.select2');

                }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD PROVINSI
    |--------------------------------------------------------------------------
    */

    function loadProvinsi(
        selected = null,
        callback = null
    ) {

        $.get(
            '/adminpanel/wilayah/listpropinsi',
            function (res) {

                let html =
                    '<option value="">Pilih Provinsi</option>';


                if (
                    !res ||
                    !Array.isArray(res.data)
                ) {

                    console.error(
                        'Format response provinsi tidak valid.',
                        res
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | GROUP 3
                |--------------------------------------------------------------------------
                */

                if (isGroup3) {

                    const kodePropinsi =
                        currentUser.kodePropinsi;


                    const item =
                        res.data.find(function (item) {

                            return String(item.code) ===
                                String(kodePropinsi);

                        });


                    if (item) {

                        html += `
                            <option value="${escapeHtml(item.code)}">
                                ${escapeHtml(item.name)}
                            </option>
                        `;

                    }

                }
                else {

                    res.data.forEach(function (item) {

                        html += `
                            <option value="${escapeHtml(item.code)}">
                                ${escapeHtml(item.name)}
                            </option>
                        `;

                    });

                }


                const value =
                    selected !== null
                        ? selected
                        : (
                            isGroup3
                                ? currentUser.kodePropinsi
                                : ''
                        );


                $('#kodePropinsi')
                    .html(html)
                    .val(
                        value
                            ? String(value)
                            : ''
                    )
                    .trigger('change.select2');


                /*
                |--------------------------------------------------------------------------
                | FILTER PROVINSI
                |--------------------------------------------------------------------------
                */

                let filterHtml =
                    '<option value="">Semua Provinsi</option>';


                res.data.forEach(function (item) {

                    filterHtml += `
                        <option value="${escapeHtml(item.code)}">
                            ${escapeHtml(item.name)}
                        </option>
                    `;

                });


                $('#filterProvinsi')
                    .html(filterHtml)
                    .val('')
                    .trigger('change.select2');


                if (
                    typeof callback ===
                    'function'
                ) {

                    callback();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD KOTA
    |--------------------------------------------------------------------------
    */

    function loadKota(
        provinceCode,
        selected = null,
        callback = null
    ) {

        $('#kodeKota')
            .html(
                '<option value="">Pilih Kota / Kabupaten</option>'
            )
            .val('')
            .trigger('change.select2');


        if (!provinceCode) {

            if (
                typeof callback ===
                'function'
            ) {

                callback();

            }

            return;

        }


        $.get(
            `/adminpanel/wilayah/listkota?province_code=${encodeURIComponent(provinceCode)}`,
            function (res) {

                let html =
                    '<option value="">Pilih Kota / Kabupaten</option>';


                if (
                    !res ||
                    !Array.isArray(res.data)
                ) {

                    console.error(
                        'Format response kota tidak valid.',
                        res
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | GROUP 3
                |--------------------------------------------------------------------------
                */

                if (isGroup3) {

                    const kodeKota =
                        currentUser.kodeKota;


                    const item =
                        res.data.find(function (item) {

                            return String(item.code) ===
                                String(kodeKota);

                        });


                    if (item) {

                        html += `
                            <option value="${escapeHtml(item.code)}">
                                ${escapeHtml(item.name)}
                            </option>
                        `;

                    }

                }
                else {

                    res.data.forEach(function (item) {

                        html += `
                            <option value="${escapeHtml(item.code)}">
                                ${escapeHtml(item.name)}
                            </option>
                        `;

                    });

                }


                const value =
                    selected !== null
                        ? selected
                        : (
                            isGroup3
                                ? currentUser.kodeKota
                                : ''
                        );


                $('#kodeKota')
                    .html(html)
                    .val(
                        value
                            ? String(value)
                            : ''
                    )
                    .trigger('change.select2');


                if (
                    typeof callback ===
                    'function'
                ) {

                    callback();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD KECAMATAN
    |--------------------------------------------------------------------------
    */

    function loadKecamatan(
        cityCode,
        selected = null,
        callback = null
    ) {

        $('#kodeKecamatan')
            .html(
                '<option value="">Pilih Kecamatan</option>'
            )
            .val('')
            .trigger('change.select2');


        $('#kodeFaskes')
            .html(
                '<option value="">Pilih Faskes</option>'
            )
            .val('')
            .trigger('change.select2');


        if (!cityCode) {

            if (
                typeof callback ===
                'function'
            ) {

                callback();

            }

            return;

        }


        $.get(
            `/adminpanel/wilayah/listkecamatan?city_code=${encodeURIComponent(cityCode)}`,
            function (res) {

                let html =
                    '<option value="">Pilih Kecamatan</option>';


                if (
                    !res ||
                    !Array.isArray(res.data)
                ) {

                    console.error(
                        'Format response kecamatan tidak valid.',
                        res
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | GROUP 3
                |--------------------------------------------------------------------------
                */

                if (isGroup3) {

                    const kodeKecamatan =
                        currentUser.kodeKecamatan;


                    const item =
                        res.data.find(function (item) {

                            return String(item.code) ===
                                String(kodeKecamatan);

                        });


                    if (item) {

                        html += `
                            <option value="${escapeHtml(item.code)}">
                                ${escapeHtml(item.name)}
                            </option>
                        `;

                    }

                }
                else {

                    res.data.forEach(function (item) {

                        html += `
                            <option value="${escapeHtml(item.code)}">
                                ${escapeHtml(item.name)}
                            </option>
                        `;

                    });

                }


                const value =
                    selected !== null
                        ? selected
                        : (
                            isGroup3
                                ? currentUser.kodeKecamatan
                                : ''
                        );


                $('#kodeKecamatan')
                    .html(html)
                    .val(
                        value
                            ? String(value)
                            : ''
                    )
                    .trigger('change.select2');


                if (
                    typeof callback ===
                    'function'
                ) {

                    callback();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD FASKES
    |--------------------------------------------------------------------------
    */

    function loadFaskes(
        kecamatan,
        selected = null,
        callback = null
    ) {

        $('#kodeFaskes')
            .html(
                '<option value="">Pilih Faskes</option>'
            )
            .val('')
            .trigger('change.select2');


        if (!kecamatan) {

            if (
                typeof callback ===
                'function'
            ) {

                callback();

            }

            return;

        }


        $.get(
            `${config.faskesUrl}?kecamatan=${encodeURIComponent(kecamatan)}`,
            function (res) {

                let html =
                    '<option value="">Pilih Faskes</option>';


                if (
                    !res ||
                    !Array.isArray(res.data)
                ) {

                    console.error(
                        'Format response faskes tidak valid.',
                        res
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | GROUP 3
                |--------------------------------------------------------------------------
                */

                if (isGroup3) {

                    const kodeFaskes =
                        currentUser.kodeFaskes;


                    const item =
                        res.data.find(function (item) {

                            return String(item.kodeFaskes) ===
                                String(kodeFaskes);

                        });


                    if (item) {

                        html += `
                            <option value="${escapeHtml(item.kodeFaskes)}">
                                ${escapeHtml(item.namaFaskes)}
                            </option>
                        `;

                    }

                }
                else {

                    res.data.forEach(function (item) {

                        html += `
                            <option value="${escapeHtml(item.kodeFaskes)}">
                                ${escapeHtml(item.namaFaskes)}
                            </option>
                        `;

                    });

                }


                const value =
                    selected !== null
                        ? selected
                        : (
                            isGroup3
                                ? currentUser.kodeFaskes
                                : ''
                        );


                $('#kodeFaskes')
                    .html(html)
                    .val(
                        value
                            ? String(value)
                            : ''
                    )
                    .trigger('change.select2');


                if (
                    typeof callback ===
                    'function'
                ) {

                    callback();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FORM PROVINSI -> KOTA
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '#kodePropinsi',
        function () {

            const provinceCode =
                $(this).val();


            console.log(
                'PROVINSI CHANGE:',
                provinceCode
            );


            /*
            |--------------------------------------------------------------------------
            | Reset Kota
            |--------------------------------------------------------------------------
            */

            $('#kodeKota')
                .html(
                    '<option value="">Pilih Kota / Kabupaten</option>'
                )
                .val('')
                .trigger('change.select2');


            /*
            |--------------------------------------------------------------------------
            | Reset Kecamatan
            |--------------------------------------------------------------------------
            */

            $('#kodeKecamatan')
                .html(
                    '<option value="">Pilih Kecamatan</option>'
                )
                .val('')
                .trigger('change.select2');


            /*
            |--------------------------------------------------------------------------
            | Reset Faskes
            |--------------------------------------------------------------------------
            */

            $('#kodeFaskes')
                .html(
                    '<option value="">Pilih Faskes</option>'
                )
                .val('')
                .trigger('change.select2');


            if (!provinceCode) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | GROUP 3
            |--------------------------------------------------------------------------
            */

            if (isGroup3) {

                console.log(
                    'Group 3 - lokasi dikunci.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | LOAD KOTA
            |--------------------------------------------------------------------------
            */

            loadKota(
                provinceCode
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM KOTA -> KECAMATAN
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '#kodeKota',
        function () {

            const cityCode =
                $(this).val();


            console.log(
                'KOTA CHANGE:',
                cityCode
            );


            /*
            |--------------------------------------------------------------------------
            | Reset Kecamatan
            |--------------------------------------------------------------------------
            */

            $('#kodeKecamatan')
                .html(
                    '<option value="">Pilih Kecamatan</option>'
                )
                .val('')
                .trigger('change.select2');


            /*
            |--------------------------------------------------------------------------
            | Reset Faskes
            |--------------------------------------------------------------------------
            */

            $('#kodeFaskes')
                .html(
                    '<option value="">Pilih Faskes</option>'
                )
                .val('')
                .trigger('change.select2');


            if (!cityCode) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | GROUP 3
            |--------------------------------------------------------------------------
            */

            if (isGroup3) {

                console.log(
                    'Group 3 - lokasi dikunci.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | LOAD KECAMATAN
            |--------------------------------------------------------------------------
            */

            loadKecamatan(
                cityCode
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM KECAMATAN -> FASKES
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '#kodeKecamatan',
        function () {

            const kecamatan =
                $(this).val();


            console.log(
                'KECAMATAN CHANGE:',
                kecamatan
            );


            /*
            |--------------------------------------------------------------------------
            | Reset Faskes
            |--------------------------------------------------------------------------
            */

            $('#kodeFaskes')
                .html(
                    '<option value="">Pilih Faskes</option>'
                )
                .val('')
                .trigger('change.select2');


            if (!kecamatan) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | GROUP 3
            |--------------------------------------------------------------------------
            */

            if (isGroup3) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | LOAD FASKES
            |--------------------------------------------------------------------------
            */

            loadFaskes(
                kecamatan
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FILTER PROVINSI -> KOTA
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '#filterProvinsi',
        function () {

            const province =
                $(this).val();


            console.log(
                'FILTER PROVINSI CHANGE:',
                province
            );


            /*
            |--------------------------------------------------------------------------
            | Reset Kota
            |--------------------------------------------------------------------------
            */

            $('#filterKota')
                .html(
                    '<option value="">Semua Kota</option>'
                )
                .val('')
                .trigger('change.select2');


            if (!province) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | LOAD KOTA
            |--------------------------------------------------------------------------
            */

            $.get(
                `/adminpanel/wilayah/listkota?province_code=${encodeURIComponent(province)}`,
                function (res) {

                    let html =
                        '<option value="">Semua Kota</option>';


                    if (
                        !res ||
                        !Array.isArray(res.data)
                    ) {

                        console.error(
                            'Format response filter kota tidak valid.',
                            res
                        );

                        return;

                    }


                    res.data.forEach(function (item) {

                        html += `
                            <option value="${escapeHtml(item.code)}">
                                ${escapeHtml(item.name)}
                            </option>
                        `;

                    });


                    $('#filterKota')
                        .html(html)
                        .val('')
                        .trigger('change.select2');

                }
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FILTER GROUP / ROLE / PROVINSI / KOTA
    |--------------------------------------------------------------------------
    */

    $('#filterGroup, #filterRole, #filterProvinsi, #filterKota')
        .on(
            'change',
            function () {

                if (table) {

                    table.ajax.reload(
                        null,
                        false
                    );

                }

            }
        );


    /*
    |--------------------------------------------------------------------------
    | FILTER SEARCH
    |--------------------------------------------------------------------------
    */

    $('#searchUser').on(
        'keyup',
        function () {

            if (table) {

                table.ajax.reload(
                    null,
                    false
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | GROUP CHANGE
    |--------------------------------------------------------------------------
    */

    $('#groupid').on(
        'change',
        function () {

            const groupId =
                $(this).val();


            console.log(
                'GROUP CHANGED:',
                groupId
            );


            loadRoles(
                groupId
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DEFAULT LOCATION GROUP 3
    |--------------------------------------------------------------------------
    */

    function loadDefaultLocation() {

        if (!isGroup3) {

            return;

        }


        const province =
            currentUser.kodePropinsi;

        const city =
            currentUser.kodeKota;

        const district =
            currentUser.kodeKecamatan;

        const faskes =
            currentUser.kodeFaskes;


        if (
            !province ||
            !city ||
            !district ||
            !faskes
        ) {

            console.warn(
                'Data lokasi user Group 3 tidak lengkap.',
                currentUser
            );

            return;

        }


        loadProvinsi(
            province,
            function () {

                loadKota(
                    province,
                    city,
                    function () {

                        loadKecamatan(
                            city,
                            district,
                            function () {

                                loadFaskes(
                                    district,
                                    faskes,
                                    function () {

                                        lockLocationForGroup3();

                                    }
                                );

                            }
                        );

                    }
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOCK LOCATION GROUP 3
    |--------------------------------------------------------------------------
    */

    function lockLocationForGroup3() {

        if (!isGroup3) {

            return;

        }


        $('#kodePropinsi')
            .prop('disabled', true);

        $('#kodeKota')
            .prop('disabled', true);

        $('#kodeKecamatan')
            .prop('disabled', true);

        $('#kodeFaskes')
            .prop('disabled', true);


        $('#group3LocationInfo')
            .remove();


        $('#kodeFaskes')
            .closest('.mb-3')
            .append(`
                <small
                    id="group3LocationInfo"
                    class="text-muted"
                >
                    <i class="fas fa-lock me-1"></i>
                    Penempatan mengikuti faskes user login.
                </small>
            `);

    }


    /*
    |--------------------------------------------------------------------------
    | UNLOCK LOCATION
    |--------------------------------------------------------------------------
    */

    function unlockLocation() {

        $('#kodePropinsi')
            .prop('disabled', false);

        $('#kodeKota')
            .prop('disabled', false);

        $('#kodeKecamatan')
            .prop('disabled', false);

        $('#kodeFaskes')
            .prop('disabled', false);


        $('#group3LocationInfo')
            .remove();

    }


    /*
    |--------------------------------------------------------------------------
    | RESET FORM
    |--------------------------------------------------------------------------
    */

    function resetForm() {

        $('#userForm')[0].reset();


        $('#userid')
            .val('');


        /*
        |--------------------------------------------------------------------------
        | Reset validation
        |--------------------------------------------------------------------------
        */

        $('.is-invalid')
            .removeClass('is-invalid');

        $('.invalid-feedback')
            .text('');


        /*
        |--------------------------------------------------------------------------
        | Reset Group
        |--------------------------------------------------------------------------
        */

        $('#groupid')
            .html(
                '<option value="">Pilih Group</option>'
            )
            .val('')
            .trigger('change.select2');


        /*
        |--------------------------------------------------------------------------
        | Reset Role
        |--------------------------------------------------------------------------
        */

        $('#role_id')
            .html(
                '<option value="">Pilih Role</option>'
            )
            .val('')
            .trigger('change.select2');


        /*
        |--------------------------------------------------------------------------
        | Reset Provinsi
        |--------------------------------------------------------------------------
        */

        $('#kodePropinsi')
            .html(
                '<option value="">Pilih Provinsi</option>'
            )
            .val('')
            .trigger('change.select2');


        /*
        |--------------------------------------------------------------------------
        | Reset Kota
        |--------------------------------------------------------------------------
        */

        $('#kodeKota')
            .html(
                '<option value="">Pilih Kota / Kabupaten</option>'
            )
            .val('')
            .trigger('change.select2');


        /*
        |--------------------------------------------------------------------------
        | Reset Kecamatan
        |--------------------------------------------------------------------------
        */

        $('#kodeKecamatan')
            .html(
                '<option value="">Pilih Kecamatan</option>'
            )
            .val('')
            .trigger('change.select2');


        /*
        |--------------------------------------------------------------------------
        | Reset Faskes
        |--------------------------------------------------------------------------
        */

        $('#kodeFaskes')
            .html(
                '<option value="">Pilih Faskes</option>'
            )
            .val('')
            .trigger('change.select2');


        unlockLocation();

    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    function initTable() {

        table =
            $('#userTable').DataTable({

                processing:
                    true,

                ajax: {

                    url:
                        config.datatableUrl,

                    data:
                        function (d) {

                            d.search =
                                $('#searchUser').val();

                            d.groupid =
                                $('#filterGroup').val();

                            d.role_id =
                                $('#filterRole').val();

                            d.kodePropinsi =
                                $('#filterProvinsi').val();

                            d.kodeKota =
                                $('#filterKota').val();

                        },

                    dataSrc:
                        'data'

                },

                columns: [

                    /*
                    |--------------------------------------------------------------------------
                    | #
                    |--------------------------------------------------------------------------
                    */

                    {
                        data:
                            null,

                        orderable:
                            false,

                        render:
                            function (
                                data,
                                type,
                                row,
                                meta
                            ) {

                                return meta.row + 1;

                            }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | USER
                    |--------------------------------------------------------------------------
                    */

                    {
                        data:
                            null,

                        render:
                            function (data) {

                                return `
                                    <div>

                                        <strong>
                                            ${escapeHtml(
                                                data.namalengkap || '-'
                                            )}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            ${escapeHtml(
                                                data.username || '-'
                                            )}
                                        </small>

                                        <br>

                                        <small>
                                            ${escapeHtml(
                                                data.email || '-'
                                            )}
                                        </small>

                                    </div>
                                `;

                            }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | GROUP
                    |--------------------------------------------------------------------------
                    */

                    {
                        data:
                            null,

                        render:
                            function (data) {

                                return `
                                    <span class="badge bg-primary">
                                        ${escapeHtml(
                                            data.group_name || '-'
                                        )}
                                    </span>
                                `;

                            }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | ROLE
                    |--------------------------------------------------------------------------
                    */

                    {
                        data:
                            null,

                        render:
                            function (data) {

                                return data.role_name
                                    ? `
                                        <span class="badge bg-success">
                                            ${escapeHtml(
                                                data.role_name
                                            )}
                                        </span>
                                    `
                                    : '-';

                            }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | FASKES
                    |--------------------------------------------------------------------------
                    */

                    {
                        data:
                            null,

                        render:
                            function (data) {

                                return `
                                    <strong>
                                        ${escapeHtml(
                                            data.namaFaskes || '-'
                                        )}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        ${escapeHtml(
                                            data.kodeFaskes || ''
                                        )}
                                    </small>
                                `;

                            }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | WILAYAH
                    |--------------------------------------------------------------------------
                    */

                    {
                        data:
                            null,

                        render:
                            function (data) {

                                return `
                                    <small>
                                        ${escapeHtml(
                                            data.provinsi_name || '-'
                                        )}

                                        <br>

                                        ${escapeHtml(
                                            data.kota_name || '-'
                                        )}

                                        <br>

                                        ${escapeHtml(
                                            data.kecamatan_name || '-'
                                        )}

                                    </small>
                                `;

                            }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | ACTION
                    |--------------------------------------------------------------------------
                    */

                    {
                        data:
                            null,

                        orderable:
                            false,

                        className:
                            'text-center',

                        render:
                            function (data) {

                                /*
                                |--------------------------------------------------------------------------
                                | GROUP 3
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    isGroup3 &&
                                    parseInt(data.group_id) === 3
                                ) {

                                    return `
                                        <span
                                            class="text-muted"
                                            title="User Group 3 tidak dapat diubah"
                                        >
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    `;

                                }


                                return `
                                    <div class="btn-group">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-info btn-edit"
                                            data-id="${escapeHtml(
                                                data.userid
                                            )}"
                                            title="Edit"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger btn-delete"
                                            data-id="${escapeHtml(
                                                data.userid
                                            )}"
                                            title="Hapus"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                `;

                            }

                    }

                ]

            });

    }


    /*
    |--------------------------------------------------------------------------
    | ADD USER
    |--------------------------------------------------------------------------
    */

    $('#btnAddUser').on(
        'click',
        function () {

            resetForm();


            $('#userModalTitle')
                .text('Tambah User');


            $('#passwordHelp')
                .text(
                    'Minimal 6 karakter'
                );


            /*
            |--------------------------------------------------------------------------
            | GROUP
            |--------------------------------------------------------------------------
            */

            loadGroups();


            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */

            if (isGroup3) {

                loadDefaultLocation();

            }
            else {

                loadProvinsi();

            }


            /*
            |--------------------------------------------------------------------------
            | SHOW MODAL
            |--------------------------------------------------------------------------
            */

            $('#userModal')
                .modal('show');

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT USER
    |--------------------------------------------------------------------------
    */

    $('#userTable').on(
        'click',
        '.btn-edit',
        function () {

            const id =
                $(this).data('id');


            $.ajax({

                url:
                    `${config.baseUrl}/${id}`,

                type:
                    'GET',

                success:
                    function (res) {

                        const data =
                            res.data;


                        resetForm();


                        /*
                        |--------------------------------------------------------------------------
                        | BASIC DATA
                        |--------------------------------------------------------------------------
                        */

                        $('#userid')
                            .val(data.userid);

                        $('#username')
                            .val(data.username);

                        $('#namalengkap')
                            .val(data.namalengkap);

                        $('#email')
                            .val(data.email);

                        $('#password')
                            .val('');


                        /*
                        |--------------------------------------------------------------------------
                        | GROUP
                        |--------------------------------------------------------------------------
                        */

                        loadGroups(
                            data.groupid
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | ROLE
                        |--------------------------------------------------------------------------
                        */

                        loadRoles(
                            data.groupid,
                            data.role_id
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | LOCATION
                        |--------------------------------------------------------------------------
                        */

                        const province =
                            isGroup3
                                ? currentUser.kodePropinsi
                                : data.kodePropinsi;

                        const city =
                            isGroup3
                                ? currentUser.kodeKota
                                : data.kodeKota;

                        const district =
                            isGroup3
                                ? currentUser.kodeKecamatan
                                : data.kodeKecamatan;

                        const faskes =
                            isGroup3
                                ? currentUser.kodeFaskes
                                : data.kodeFaskes;


                        loadProvinsi(
                            province,
                            function () {

                                loadKota(
                                    province,
                                    city,
                                    function () {

                                        loadKecamatan(
                                            city,
                                            district,
                                            function () {

                                                loadFaskes(
                                                    district,
                                                    faskes,
                                                    function () {

                                                        if (isGroup3) {

                                                            lockLocationForGroup3();

                                                        }

                                                    }
                                                );

                                            }
                                        );

                                    }
                                );

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | TITLE
                        |--------------------------------------------------------------------------
                        */

                        $('#userModalTitle')
                            .text('Edit User');


                        /*
                        |--------------------------------------------------------------------------
                        | PASSWORD
                        |--------------------------------------------------------------------------
                        */

                        $('#passwordHelp')
                            .text(
                                'Kosongkan jika password tidak ingin diubah'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | SHOW MODAL
                        |--------------------------------------------------------------------------
                        */

                        $('#userModal')
                            .modal('show');

                    },

                error:
                    function (xhr) {

                        console.error(
                            'Gagal mengambil data user:',
                            xhr.responseText
                        );


                        Swal.fire({

                            icon:
                                'error',

                            title:
                                'Gagal',

                            text:
                                xhr.responseJSON?.message ||
                                'Data user tidak dapat diambil.'

                        });

                    }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $('#userTable').on(
        'click',
        '.btn-delete',
        function () {

            const id =
                $(this).data('id');


            Swal.fire({

                title:
                    'Hapus user?',

                text:
                    'Data user akan dihapus.',

                icon:
                    'warning',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Ya, hapus',

                cancelButtonText:
                    'Batal'

            }).then(
                function (result) {

                    if (!result.isConfirmed) {

                        return;

                    }


                    $.ajax({

                        url:
                            `${config.baseUrl}/${id}`,

                        type:
                            'DELETE',

                        success:
                            function (res) {

                                table.ajax.reload(
                                    null,
                                    false
                                );


                                Swal.fire({

                                    icon:
                                        'success',

                                    title:
                                        'Berhasil',

                                    text:
                                        res.message ||
                                        'User berhasil dihapus.',

                                    timer:
                                        1500,

                                    showConfirmButton:
                                        false

                                });

                            },

                        error:
                            function (xhr) {

                                Swal.fire({

                                    icon:
                                        'error',

                                    title:
                                        'Gagal',

                                    text:
                                        xhr.responseJSON?.message ||
                                        'User tidak dapat dihapus.'

                                });

                            }

                    });

                }
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SAVE USER
    |--------------------------------------------------------------------------
    */

    $('#userForm').on(
        'submit',
        function (e) {

            e.preventDefault();


            /*
            |--------------------------------------------------------------------------
            | CLEAR VALIDATION
            |--------------------------------------------------------------------------
            */

            $('.is-invalid')
                .removeClass('is-invalid');

            $('.invalid-feedback')
                .text('');


            const id =
                $('#userid').val();


            const data = {

                username:
                    $('#username').val(),

                namalengkap:
                    $('#namalengkap').val(),

                email:
                    $('#email').val(),

                password:
                    $('#password').val(),

                groupid:
                    $('#groupid').val(),

                role_id:
                    $('#role_id').val(),

                kodePropinsi:
                    $('#kodePropinsi').val(),

                kodeKota:
                    $('#kodeKota').val(),

                kodeKecamatan:
                    $('#kodeKecamatan').val(),

                kodeFaskes:
                    $('#kodeFaskes').val()

            };


            /*
            |--------------------------------------------------------------------------
            | GROUP 3
            |--------------------------------------------------------------------------
            */

            if (isGroup3) {

                data.kodePropinsi =
                    currentUser.kodePropinsi;

                data.kodeKota =
                    currentUser.kodeKota;

                data.kodeKecamatan =
                    currentUser.kodeKecamatan;

                data.kodeFaskes =
                    currentUser.kodeFaskes;


                if (
                    ![4, 5, 6].includes(
                        parseInt(data.groupid)
                    )
                ) {

                    Swal.fire({

                        icon:
                            'warning',

                        title:
                            'Group tidak valid',

                        text:
                            'User Group 3 hanya dapat membuat user Group 4, Group 5, atau Group 6.'

                    });

                    return;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | URL
            |--------------------------------------------------------------------------
            */

            const url =
                id
                    ? `${config.baseUrl}/${id}`
                    : config.baseUrl;


            const method =
                id
                    ? 'PUT'
                    : 'POST';


            /*
            |--------------------------------------------------------------------------
            | BUTTON
            |--------------------------------------------------------------------------
            */

            $('#btnSaveUser')
                .prop(
                    'disabled',
                    true
                );


            /*
            |--------------------------------------------------------------------------
            | AJAX
            |--------------------------------------------------------------------------
            */

            $.ajax({

                url:
                    url,

                type:
                    method,

                data:
                    data,

                success:
                    function (res) {

                        $('#userModal')
                            .modal('hide');


                        table.ajax.reload(
                            null,
                            false
                        );


                        Swal.fire({

                            icon:
                                'success',

                            title:
                                'Berhasil',

                            text:
                                res.message ||
                                'User berhasil disimpan.',

                            timer:
                                1500,

                            showConfirmButton:
                                false

                        });

                    },

                error:
                    function (xhr) {

                        console.error(
                            xhr.responseText
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | VALIDATION 422
                        |--------------------------------------------------------------------------
                        */

                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON?.errors
                        ) {

                            showValidationErrors(
                                xhr.responseJSON.errors
                            );

                            return;

                        }


                        Swal.fire({

                            icon:
                                'error',

                            title:
                                'Gagal',

                            text:
                                xhr.responseJSON?.message ||
                                'Terjadi kesalahan.'

                        });

                    },

                complete:
                    function () {

                        $('#btnSaveUser')
                            .prop(
                                'disabled',
                                false
                            );

                    }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | VALIDATION ERROR
    |--------------------------------------------------------------------------
    */

    function showValidationErrors(errors) {

        $('.is-invalid')
            .removeClass('is-invalid');

        $('.invalid-feedback')
            .text('');


        Object.keys(errors)
            .forEach(
                function (field) {

                    const input =
                        $('#' + field);


                    input.addClass(
                        'is-invalid'
                    );


                    const errorElement =
                        $('#' + field + 'Error');


                    if (
                        errorElement.length
                    ) {

                        errorElement.text(
                            errors[field][0]
                        );

                    }

                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    initSelect2();

    loadGroups();

    loadProvinsi();

    initTable();
})


