const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */



mix.js(
    'resources/js/app.js',
    'public/js'
)

.js(
    'resources/js/newlplpo/masterdataobat.js',
    'public/js/newlplpo'
)

.js(
    'resources/js/newlplpo/bekasi/lplpo.js',
    'public/js/newlplpo/bekasi'
)

.js(
    'resources/js/adminpanel/activitylog.js',
    'public/js/adminpanel'
)

.js('resources/js/newlplpo/bekasi/rekap.js', 'public/js/newlplpo/bekasi')
 .js(
        'resources/js/adminpanel/userpanel/groups.js',
        'public/js/adminpanel/userpanel'
    )

    .js(
    'resources/js/adminpanel/userpanel/roles.js',
    'public/js/adminpanel/userpanel'
)

.js(
    'resources/js/adminpanel/userpanel/users.js',
    'public/js/adminpanel/userpanel'
)
.js(
    'resources/js/adminpanel/wilayahkerja/puskesmas.js',
    'public/js/adminpanel/wilayahkerja'
)
.js(
    'resources/js/adminpanel/master/masterfaskes.js',
    'public/js/adminpanel/master'
)
 .js(
        'resources/js/adminpanel/posyandu/index.js',
        'public/js/adminpanel/posyandu'
    )

    .js(
        'resources/js/adminpanel/posyandu/create.js',
        'public/js/adminpanel/posyandu'
    )
     .js(
        'resources/js/adminpanel/posyandu/edit.js',
        'public/js/adminpanel/posyandu'
    )
    .js(
    'resources/js/adminpanel/wilayahkerja/posyandu.js',
    'public/js/adminpanel/wilayahkerja'
)

.js(
    'resources/js/newlplpo/stokesensial.js',
    'public/js/newlplpo'
)
.js(
    'resources/js/newlplpo/buatlplpo.js',
    'public/js/newlplpo'
)
.js(
    'resources/js/newlplpo/item.js',
    'public/js/newlplpo'
)

.postCss(
    'resources/css/app.css',
    'public/css',
    [
        //
    ]
);

