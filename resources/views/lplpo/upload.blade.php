@extends('layouts.lplpo')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card border-0 shadow-lg rounded-4">

                {{-- Header --}}
                <div class="card-header bg-primary text-white rounded-top-4 py-3">
                    <h4 class="mb-0 fw-semibold">
                        Upload Data LPLPO
                    </h4>
                    <small class="text-white-50">
                        Silakan pilih periode dan file Excel untuk diupload
                    </small>
                </div>

                {{-- Body --}}
                <div class="card-body p-4">



@if(session('error_file'))
    <a href="{{ asset(session('error_file')) }}" class="btn btn-danger">
        Download File Error
    </a>
@endif



                   @if(session('import_error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    let errors = @json(session('import_error'));

    let html = '<ul style="text-align:left">';
    errors.forEach(function(err){
        html += '<li>'+err+'</li>';
    });
    html += '</ul>';

    Swal.fire({
        icon: 'error',
        title: 'Import Gagal',
        html: html,
        width: 600
    });
});
</script>
@endif


               @if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    }).then(() => {
        window.location.href = '/lplpo/dataview';
    });
});
</script>
@endif

                    <form action="/lplpo/import" method="POST" enctype="multipart/form-data">
                        @csrf

                        @php
                            $bulanList = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ];
                        @endphp

                        <div class="row">

                            {{-- Bulan --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    Bulan
                                </label>

                                <select name="bulan" class="form-select shadow-sm" required>
                                    <option value="">-- Pilih Bulan --</option>

                                    @foreach($bulanList as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ date('n') == $key ? 'selected' : '' }}>
                                            {{ $val }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tahun --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    Tahun
                                </label>

                                <input
                                    type="number"
                                    name="tahun"
                                    class="form-control shadow-sm"
                                    value="{{ date('Y') }}"
                                    required
                                >
                            </div>

                        </div>

                        {{-- Upload File --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                File Excel
                            </label>

                            <input
                                type="file"
                                name="file"
                                class="form-control shadow-sm"
                                accept=".xls,.xlsx"
                                required
                            >

                            <small class="text-muted">
                                Format file yang didukung: .xls dan .xlsx
                            </small>
                        </div>

                        {{-- Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg rounded-3">
                                Upload Data
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
