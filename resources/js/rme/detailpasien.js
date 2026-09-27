$(document).ready(function () {

    $('#home').removeClass('active');
    $('#rme').addClass('active');

    $('#layananUmum, #layananAnc, #inc, #imunisasi, #neonatus, #pnc').hide();

    $('#tab2').on('click', function () {

        $('#tab2').addClass('active');
        $('#tab1, #tab3, #tab4, #tab5, #tab6, #tab7').removeClass('active');

        $('#layananUmum').show();

        $('#maincard, #layananAnc, #inc, #imunisasi, #neonatus, #pnc').hide();
    });

    $('#tab1').on('click', function () {

        $('#maincard').show();

        $('#tab1').addClass('active');
        $('#tab2, #tab3, #tab4, #tab5, #tab6, #tab7').removeClass('active');

        $('#layananUmum, #layananAnc, #inc, #imunisasi, #neonatus, #pnc').hide();
    });

    $('#tab3').on('click', function () {

        $('#tab3').addClass('active');
        $('#tab2, #tab1, #tab4, #tab5, #tab6, #tab7').removeClass('active');

        $('#layananAnc').show();

        $('#layananUmum, #maincard, #inc, #imunisasi, #neonatus, #pnc').hide();
    });

    $('#tab4').on('click', function () {

        $('#tab4').addClass('active');
        $('#tab2, #tab1, #tab3, #tab5, #tab6, #tab7').removeClass('active');

        $('#inc').show();

        $('#layananUmum, #maincard, #layananAnc, #imunisasi, #neonatus, #pnc').hide();
    });

    $('#tab5').on('click', function () {

        $('#tab5').addClass('active');
        $('#tab2, #tab1, #tab3, #tab4, #tab6, #tab7').removeClass('active');

        $('#pnc').show();

        $('#layananUmum, #maincard, #layananAnc, #imunisasi, #neonatus, #inc').hide();
    });

    $('#tab6').on('click', function () {

        $('#tab6').addClass('active');
        $('#tab2, #tab1, #tab3, #tab4, #tab5, #tab7').removeClass('active');

        $('#neonatus').show();

        $('#layananUmum, #maincard, #layananAnc, #imunisasi, #pnc, #inc').hide();
    });

    $('#tab7').on('click', function () {

        $('#tab7').addClass('active');
        $('#tab2, #tab1, #tab3, #tab4, #tab5, #tab6').removeClass('active');

        $('#imunisasi').show();

        $('#layananUmum, #maincard, #layananAnc, #neonatus, #pnc, #inc').hide();
    });

});
