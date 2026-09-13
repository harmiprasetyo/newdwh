@extends('newlplpo.layouts.headerapproval')

@section('title', 'LPLPO Ditolak')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-x"></i>
                Hasil Approval LPLPO
            </h5>
        </div>

        <div class="card-body">

            <div class="alert alert-danger">

                <i class="bi bi-x-circle"></i>

                <strong>
                    LPLPO Ditolak
                </strong>

                <div class="mt-2">
                    Laporan telah ditolak oleh Kepala Puskesmas
                    dan dikembalikan untuk diperbaiki.
                </div>

            </div>


            <div class="row">

                <div class="col-md-6 mb-3">

                    <strong>Fasilitas Kesehatan</strong>

                    <div>
                        {{ $report->nama_faskes }}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <strong>Kode Faskes</strong>

                    <div>
                        {{ $report->kode_faskes }}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <strong>Nomor LPLPO</strong>

                    <div>
                        {{ $report->nomor_lplpo }}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <strong>Periode</strong>

                    <div>
                        {{ $report->bulan }}/{{ $report->tahun }}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <strong>Kepala Puskesmas</strong>

                    <div>
                        {{ $kapus->namaKapus }}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <strong>Tanggal Penolakan</strong>

                    <div>
                        {{ optional($approval->updated_at)->format('d-m-Y H:i:s') }}
                    </div>

                </div>

            </div>


            <hr>


            <div class="mb-0">

                <strong>
                    Alasan Penolakan
                </strong>

                <div class="alert alert-warning mt-2 mb-0">

                    {{ $approval->rejectedReason }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

