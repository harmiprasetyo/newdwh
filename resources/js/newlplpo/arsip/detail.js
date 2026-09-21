/**
 * ==========================================================
 * DETAIL LPLPO
 * ==========================================================
 */

document.addEventListener('DOMContentLoaded', function () {

    /**
     * ======================================================
     * CETAK
     * ======================================================
     *
     * Menggunakan browser print.
     */
    const btnPrint = document.getElementById('btnPrint');

    if (btnPrint) {
        btnPrint.addEventListener('click', function () {
            window.print();
        });
    }


    /**
     * ======================================================
     * EXPORT EXCEL
     * ======================================================
     *
     * URL export akan diberikan melalui data-excel-url
     * pada tombol di Blade.
     */
    const btnExportExcel = document.getElementById('btnExportExcel');

    if (btnExportExcel) {
        btnExportExcel.addEventListener('click', function () {

            const url = this.dataset.excelUrl;

            if (!url) {
                console.error('URL export Excel belum tersedia.');
                return;
            }

            window.location.href = url;
        });
    }


    /**
     * ======================================================
     * EXPORT PDF
     * ======================================================
     *
     * URL export akan diberikan melalui data-pdf-url
     * pada tombol di Blade.
     */
    const btnExportPdf = document.getElementById('btnExportPdf');

    if (btnExportPdf) {
        btnExportPdf.addEventListener('click', function () {

            const url = this.dataset.pdfUrl;

            if (!url) {
                console.error('URL export PDF belum tersedia.');
                return;
            }

            window.location.href = url;
        });
    }

});
