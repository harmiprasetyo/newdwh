@extends('newlplpo.layouts.headerapproval')

@section('content')

<div class="container py-5">

```
<div class="row justify-content-center">

    <div class="col-lg-8 col-xl-7">

        {{-- SUCCESS --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body p-4 p-md-5 text-center">

                {{-- ICON --}}
                <div class="mb-4">
                    <div
                        class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10"
                        style="width:80px;height:80px;"
                    >
                        <i
                            class="bi bi-check-circle-fill text-success"
                            style="font-size:3rem;"
                        ></i>
                    </div>
                </div>

                {{-- TITLE --}}
                <h2 class="fw-bold text-success mb-2">
                    LPLPO Berhasil Disetujui
                </h2>

                <p class="text-muted mb-4">
                    Laporan LPLPO telah disetujui oleh Kepala Puskesmas
                    dan telah tercatat secara resmi di sistem.
                </p>


                {{-- REPORT INFORMATION --}}
                <div class="card bg-light border-0 mb-4">

                    <div class="card-body text-start">

                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Informasi Laporan
                        </h5>

                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">
                                Nomor LPLPO
                            </div>

                            <div class="col-sm-7 fw-semibold">
                                {{ $report->nomor_lplpo ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">
                                Fasilitas Kesehatan
                            </div>

                            <div class="col-sm-7 fw-semibold">
                                {{ $report->nama_faskes ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">
                                Periode
                            </div>

                            <div class="col-sm-7 fw-semibold">
                                {{ $report->bulan ?? '-' }}
                                /
                                {{ $report->tahun ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">
                                Status Laporan
                            </div>

                            <div class="col-sm-7">
                                <span class="badge bg-success">
                                    {{ $report->report_status ?? 'SUBMITED' }}
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5 text-muted">
                                Status LPLPO
                            </div>

                            <div class="col-sm-7">
                                <span class="badge bg-success">
                                    {{ strtoupper($report->lplpo_status ?? 'approved') }}
                                </span>
                            </div>
                        </div>

                    </div>

                </div>


                {{-- APPROVAL INFORMATION --}}
                <div class="card border-0 bg-light mb-4">

                    <div class="card-body text-start">

                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-person-check me-2"></i>
                            Informasi Persetujuan
                        </h5>

                        <div class="row mb-2">

                            <div class="col-sm-5 text-muted">
                                Kepala Puskesmas
                            </div>

                            <div class="col-sm-7 fw-semibold">
                                {{ $kapus->namaKapus ?? '-' }}
                            </div>

                        </div>

                        <div class="row mb-2">

                            <div class="col-sm-5 text-muted">
                                NIP
                            </div>

                            <div class="col-sm-7 fw-semibold">
                                {{ $kapus->nipKapus ?? '-' }}
                            </div>

                        </div>

                        <div class="row mb-2">

                            <div class="col-sm-5 text-muted">
                                Status Approval
                            </div>

                            <div class="col-sm-7">

                                <span class="badge bg-success">
                                    APPROVED
                                </span>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-5 text-muted">
                                Disetujui Pada
                            </div>

                            <div class="col-sm-7 fw-semibold">
                                {{ $approval->approvedAt
                                    ? $approval->approvedAt->format('d-m-Y H:i:s')
                                    : '-' }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- VERIFICATION --}}
                <div class="alert alert-success text-start">

                    <div class="d-flex">

                        <div class="me-3">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>

                        <div>

                            <div class="fw-bold mb-1">
                                Persetujuan Terverifikasi
                            </div>

                            <div class="small">
                                Dokumen ini telah disetujui menggunakan
                                kode E-Sign Kepala Puskesmas.
                                Token verifikasi pada halaman ini merupakan
                                identitas unik untuk memverifikasi persetujuan
                                LPLPO.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- VERIFICATION TOKEN --}}
                <div class="mt-4">

                    <div class="text-muted small mb-1">
                        Verification Token
                    </div>

                    <div
                        class="font-monospace small text-break bg-light rounded p-3"
                    >
                        {{ $approval->verificationToken }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
```

</div>

@endsection
