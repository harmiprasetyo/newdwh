@if($report->kunjungan)

<div class="card shadow-sm border-0 mt-4 mb-4">

    {{-- HEADER --}}
    <div class="card-header bg-primary text-white py-3">

        <div class="d-flex align-items-center justify-content-between">

            <div>
                <h5 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>
                    Laporan Kunjungan
                </h5>

                <small class="opacity-75">
                    Rekapitulasi kunjungan pasien
                </small>
            </div>

            <span class="badge bg-white text-primary px-3 py-2">
                Bulan {{ $report->bulan }}
                {{ $report->tahun }}
            </span>

        </div>

    </div>


    <div class="card-body p-3">

        <div class="row g-3">


            {{-- ================================================= --}}
            {{-- KATEGORI KUNJUNGAN --}}
            {{-- ================================================= --}}

            <div class="col-lg-4">

                <div class="card h-100 border">

                    <div class="card-header bg-success text-white">

                        <strong>
                            <i class="bi bi-wallet2 me-2"></i>
                            Berdasarkan Kategori
                        </strong>

                    </div>

                    <div class="card-body p-0">

                        <table class="table table-sm table-bordered mb-0">

                            <tbody>

                                <tr>
                                    <td>
                                        <i class="bi bi-credit-card text-primary me-2"></i>
                                        JKN
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_jkn ?? 0) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <i class="bi bi-cash-stack text-success me-2"></i>
                                        Tunai
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_tunai ?? 0) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <i class="bi bi-gift text-warning me-2"></i>
                                        Gratis
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_gratis ?? 0) }}
                                    </td>
                                </tr>

                            </tbody>

                            <tfoot class="table-success">

                                <tr>

                                    <th>
                                        Total
                                    </th>

                                    <th class="text-end">
                                        {{ number_format($report->kunjungan->total_kunjungan_perkategori ?? 0) }}
                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- GENDER / KELOMPOK UMUR --}}
            {{-- ================================================= --}}

            <div class="col-lg-4">

                <div class="card h-100 border">

                    <div class="card-header bg-info text-white">

                        <strong>
                            <i class="bi bi-person-vcard me-2"></i>
                            Berdasarkan Kelompok
                        </strong>

                    </div>

                    <div class="card-body p-0">

                        <table class="table table-sm table-bordered mb-0">

                            <tbody>

                                <tr>

                                    <td>
                                        <i class="bi bi-person text-primary me-2"></i>
                                        Anak
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_anak ?? 0) }}
                                    </td>

                                </tr>

                                <tr>

                                    <td>
                                        <i class="bi bi-person-standing text-success me-2"></i>
                                        Dewasa
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_dewasa ?? 0) }}
                                    </td>

                                </tr>

                            </tbody>

                            <tfoot class="table-info">

                                <tr>

                                    <th>
                                        Total
                                    </th>

                                    <th class="text-end">
                                        {{ number_format($report->kunjungan->total_kunjungan_pergender ?? 0) }}
                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- JENIS PELAYANAN --}}
            {{-- ================================================= --}}

            <div class="col-lg-4">

                <div class="card h-100 border">

                    <div class="card-header bg-warning">

                        <strong>
                            <i class="bi bi-hospital me-2"></i>
                            Jenis Pelayanan
                        </strong>

                    </div>

                    <div class="card-body p-0">

                        <table class="table table-sm table-bordered mb-0">

                            <tbody>

                                <tr>

                                    <td>
                                        <i class="bi bi-droplet text-danger me-2"></i>
                                        Lab
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_lab ?? 0) }}
                                    </td>

                                </tr>

                                <tr>

                                    <td>
                                        <i class="bi bi-emoji-smile text-primary me-2"></i>
                                        Gigi
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_gigi ?? 0) }}
                                    </td>

                                </tr>

                                <tr>

                                    <td>
                                        <i class="bi bi-heart-pulse text-danger me-2"></i>
                                        PONED
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_poned ?? 0) }}
                                    </td>

                                </tr>

                                <tr>

                                    <td>
                                        <i class="bi bi-hospital text-warning me-2"></i>
                                        Rawat Inap
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_rawatinap ?? 0) }}
                                    </td>

                                </tr>

                                <tr>

                                    <td>
                                        <i class="bi bi-person-walking text-success me-2"></i>
                                        Rawat Jalan
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($report->kunjungan->kunjungan_rawatjalan ?? 0) }}
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- VALIDASI TOTAL --}}
        {{-- ================================================= --}}

        @php

            $totalKategori =
                $report->kunjungan->total_kunjungan_perkategori ?? 0;

            $totalGender =
                $report->kunjungan->total_kunjungan_pergender ?? 0;

            $valid =
                $totalKategori == $totalGender;

        @endphp


        <div class="mt-3">

            @if($valid)

                <div class="alert alert-success mb-0 py-2">

                    <i class="bi bi-check-circle-fill me-2"></i>

                    <strong>Valid.</strong>

                    Total kunjungan berdasarkan kategori
                    dan kelompok pasien sama-sama

                    <strong>
                        {{ number_format($totalKategori) }}
                    </strong>
                    kunjungan.

                </div>

            @else

                <div class="alert alert-danger mb-0 py-2">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    <strong>Perhatian!</strong>

                    Total kategori
                    <strong>
                        {{ number_format($totalKategori) }}
                    </strong>

                    tidak sama dengan total kelompok pasien
                    <strong>
                        {{ number_format($totalGender) }}
                    </strong>.

                </div>

            @endif

        </div>

    </div>

</div>

@else

<div class="card shadow-sm border-danger mt-4 mb-4">

    <div class="card-body">

        <div class="alert alert-danger mb-0">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <strong>Data kunjungan belum tersedia.</strong>

            Laporan ini belum memiliki data kunjungan.

        </div>

    </div>

</div>

@endif
