/**
 * Dashboard JavaScript
 *
 * Data berasal dari:
 *
 * window.dashboardData
 *
 * Tidak menggunakan API.
 */

document.addEventListener('DOMContentLoaded', function () {

    const data = window.dashboardData || {};

    /*
    |--------------------------------------------------------------------------
    | ANC K1
    |--------------------------------------------------------------------------
    */

    renderAncK1(data.anc_k1 || []);


    /*
    |--------------------------------------------------------------------------
    | LOCATION
    |--------------------------------------------------------------------------
    */

    renderLocation(data.per_location || []);


    /*
    |--------------------------------------------------------------------------
    | PROVIDER
    |--------------------------------------------------------------------------
    */

    renderProvider(data.per_provider || []);

});


/**
 * ================================================================
 * ANC K1
 * ================================================================
 */
function renderAncK1(items) {

    const element = document.querySelector('#chartAncK1');

    if (!element) {
        return;
    }

    const months = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Agu',
        'Sep',
        'Okt',
        'Nov',
        'Des'
    ];

    /*
    |--------------------------------------------------------------------------
    | Buat 12 bulan
    |--------------------------------------------------------------------------
    */

    const values = Array(12).fill(0);

    items.forEach(function (item) {

        const month = parseInt(item.bulan);

        if (month >= 1 && month <= 12) {

            values[month - 1] =
                parseFloat(item.percentage || 0);

        }

    });


    const options = {

        chart: {
            type: 'line',
            height: 350,
            toolbar: {
                show: true
            }
        },

        series: [
            {
                name: 'Cakupan ANC K1',
                data: values
            }
        ],

        xaxis: {
            categories: months,
            title: {
                text: 'Bulan'
            }
        },

        yaxis: {
            title: {
                text: 'Persentase (%)'
            },

            labels: {
                formatter: function (value) {
                    return value.toFixed(2) + '%';
                }
            }
        },

        dataLabels: {
            enabled: true,

            formatter: function (value) {
                return value.toFixed(2) + '%';
            }
        },

        tooltip: {

            y: {

                formatter: function (value) {
                    return value.toFixed(2) + '%';
                }

            }

        },

        stroke: {
            curve: 'smooth',
            width: 3
        }

    };


    const chart = new ApexCharts(
        element,
        options
    );

    chart.render();
}


/**
 * ================================================================
 * LOCATION
 * ================================================================
 */
function renderLocation(items) {

    const element = document.querySelector('#chartLocation');

    if (!element) {
        return;
    }

    if (!items.length) {

        element.innerHTML =
            '<div class="text-muted text-center py-5">' +
            'Tidak ada data lokasi' +
            '</div>';

        return;
    }


    const categories = items.map(function (item) {

        return item.location || '-';

    });


    const values = items.map(function (item) {

        return parseInt(item.total || 0);

    });


    const options = {

        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: true
            }
        },

        series: [
            {
                name: 'Total',
                data: values
            }
        ],

        xaxis: {
            categories: categories,
            title: {
                text: 'Lokasi'
            }
        },

        yaxis: {
            title: {
                text: 'Total Encounter'
            }
        },

        plotOptions: {

            bar: {

                horizontal: false,

                columnWidth: '50%',

                borderRadius: 4

            }

        },

        dataLabels: {
            enabled: true
        },

        tooltip: {

            y: {

                formatter: function (value) {

                    return Number(value)
                        .toLocaleString('id-ID');

                }

            }

        }

    };


    const chart = new ApexCharts(
        element,
        options
    );

    chart.render();
}


/**
 * ================================================================
 * PROVIDER
 * ================================================================
 */
function renderProvider(items) {

    const element = document.querySelector('#chartProvider');

    if (!element) {
        return;
    }

    if (!items.length) {

        element.innerHTML =
            '<div class="text-muted text-center py-5">' +
            'Tidak ada data provider' +
            '</div>';

        return;
    }


    const categories = items.map(function (item) {

        return item.service_provider || '-';

    });


    const values = items.map(function (item) {

        return parseInt(item.total || 0);

    });


    const options = {

        chart: {
            type: 'bar',
            height: Math.max(
                400,
                items.length * 35
            ),
            toolbar: {
                show: true
            }
        },

        series: [
            {
                name: 'Total',
                data: values
            }
        ],

        xaxis: {
            categories: categories
        },

        yaxis: {
            title: {
                text: 'Total Encounter'
            }
        },

        plotOptions: {

            bar: {

                horizontal: true,

                barHeight: '70%',

                borderRadius: 4

            }

        },

        dataLabels: {
            enabled: true
        },

        tooltip: {

            y: {

                formatter: function (value) {

                    return Number(value)
                        .toLocaleString('id-ID');

                }

            }

        }

    };


    const chart = new ApexCharts(
        element,
        options
    );

    chart.render();
}
