<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Persetujuan LPLPO</title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#f4f6f8;
    font-family:Arial, Helvetica, sans-serif;
    color:#333;
">

<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="background:#f4f6f8; padding:30px 0;">

    <tr>
        <td align="center">

            <table width="600"
                   cellpadding="0"
                   cellspacing="0"
                   style="
                        background:#ffffff;
                        border-radius:8px;
                        overflow:hidden;
                        max-width:600px;
                        width:100%;
                   ">

                {{-- HEADER --}}
                <tr>
                    <td style="
                        background:#198754;
                        color:#ffffff;
                        padding:25px;
                        text-align:center;
                    ">

                        <h1 style="
                            margin:0;
                            font-size:24px;
                        ">
                            LPLPO
                        </h1>

                        <p style="
                            margin:8px 0 0;
                            font-size:14px;
                        ">
                            Laporan Pemakaian dan Lembar Permintaan Obat
                        </p>

                    </td>
                </tr>


                {{-- CONTENT --}}
                <tr>
                    <td style="padding:30px;">

                        <p>
                            Yth.
                            <strong>
                                {{ $approval->kapus->namaKapus }}
                            </strong>
                        </p>

                        <p>
                            Terdapat laporan LPLPO dari fasilitas kesehatan
                            yang membutuhkan persetujuan Kepala Puskesmas.
                        </p>


                        {{-- INFO LAPORAN --}}
                        <table width="100%"
                               cellpadding="8"
                               cellspacing="0"
                               style="
                                    margin:20px 0;
                                    border-collapse:collapse;
                                    font-size:14px;
                               ">

                            <tr>
                                <td style="
                                    width:40%;
                                    background:#f8f9fa;
                                    border:1px solid #dee2e6;
                                ">
                                    Fasilitas Kesehatan
                                </td>

                                <td style="
                                    border:1px solid #dee2e6;
                                ">
                                    <strong>
                                        {{ $approval->report->nama_faskes }}
                                    </strong>
                                </td>
                            </tr>


                            <tr>
                                <td style="
                                    background:#f8f9fa;
                                    border:1px solid #dee2e6;
                                ">
                                    Nomor LPLPO
                                </td>

                                <td style="
                                    border:1px solid #dee2e6;
                                ">
                                    {{ $approval->report->nomor_lplpo }}
                                </td>
                            </tr>


                            <tr>
                                <td style="
                                    background:#f8f9fa;
                                    border:1px solid #dee2e6;
                                ">
                                    Periode
                                </td>

                                <td style="
                                    border:1px solid #dee2e6;
                                ">
                                    {{ $approval->report->bulan }}
                                    /
                                    {{ $approval->report->tahun }}
                                </td>
                            </tr>


                            <tr>
                                <td style="
                                    background:#f8f9fa;
                                    border:1px solid #dee2e6;
                                ">
                                    Status
                                </td>

                                <td style="
                                    border:1px solid #dee2e6;
                                ">
                                    Menunggu Persetujuan
                                </td>
                            </tr>

                        </table>


                        <p>
                            Silakan membuka laporan tersebut untuk
                            melakukan pemeriksaan dan proses persetujuan.
                        </p>


                        {{-- BUTTON --}}
                        <div style="
                            text-align:center;
                            margin:30px 0;
                        ">

                            <a href="{{ route(
                                'newlplpo.approval.show',
                                $approval->approvalToken
                            ) }}"
                               style="
                                    display:inline-block;
                                    background:#198754;
                                    color:#ffffff;
                                    text-decoration:none;
                                    padding:13px 25px;
                                    border-radius:6px;
                                    font-weight:bold;
                               ">

                                Buka Laporan LPLPO

                            </a>

                        </div>


                        <p style="
                            font-size:13px;
                            color:#6c757d;
                            line-height:1.6;
                        ">

                            Link ini bersifat khusus untuk proses
                            persetujuan laporan LPLPO.
                            Mohon tidak meneruskan email ini kepada pihak
                            yang tidak berkepentingan.

                        </p>

                    </td>
                </tr>


                {{-- FOOTER --}}
                <tr>
                    <td style="
                        background:#f8f9fa;
                        padding:20px;
                        text-align:center;
                        font-size:12px;
                        color:#6c757d;
                    ">

                        Email ini dikirim secara otomatis oleh sistem LPLPO.
                        <br>
                        Mohon tidak membalas email ini.

                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>