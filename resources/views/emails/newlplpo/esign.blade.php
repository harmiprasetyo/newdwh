<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Kode E-Sign LPLPO</title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #f4f6f8;
    font-family: Arial, Helvetica, sans-serif;
">

<div style="
    max-width: 600px;
    margin: 30px auto;
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
">

    {{-- HEADER --}}
    <div style="
        background-color: #198754;
        color: #ffffff;
        padding: 24px;
        text-align: center;
    ">

        <h2 style="
            margin: 0;
            font-size: 22px;
        ">
            Kode E-Sign LPLPO
        </h2>

    </div>

    {{-- CONTENT --}}
    <div style="
        padding: 30px;
        color: #333333;
    ">

        <p>
            Yth. Bapak/Ibu
            <strong>{{ $kapus->namaKapus }}</strong>,
        </p>

        <p>
            Sistem LPLPO telah membuat
            <strong>Kode E-Sign</strong>
            untuk digunakan dalam proses persetujuan laporan LPLPO.
        </p>

        <div style="
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            text-align: center;
        ">

            <div style="
                font-size: 13px;
                color: #6c757d;
                margin-bottom: 10px;
            ">
                KODE E-SIGN
            </div>

            <div style="
                font-size: 32px;
                font-weight: bold;
                letter-spacing: 6px;
                color: #198754;
            ">
                {{ $kodeEsign }}
            </div>

        </div>

        <p>
            Kode ini bersifat rahasia dan digunakan untuk
            melakukan persetujuan LPLPO.
        </p>

        <p>
            Jangan membagikan kode E-Sign kepada pihak lain.
        </p>

        <p>
            Jika Anda tidak merasa melakukan permintaan ini,
            silakan hubungi administrator sistem.
        </p>

        <br>

        <p>
            Terima kasih.
        </p>

        <strong>
            Sistem LPLPO
        </strong>

    </div>

    {{-- FOOTER --}}
    <div style="
        background: #f8f9fa;
        padding: 15px;
        text-align: center;
        color: #6c757d;
        font-size: 12px;
    ">

        Email ini dikirim secara otomatis oleh sistem.
        <br>
        Mohon tidak membalas email ini.

    </div>

</div>

</body>

</html>