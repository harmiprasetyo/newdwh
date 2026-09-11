@extends('newlplpo.layouts.master')

@section('title', 'Tambah Kepala Puskesmas')


@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h4 class="mb-1">
            Tambah Kepala Puskesmas
        </h4>

        <div class="text-muted">
            Data Kode E-Sign akan dibuat otomatis oleh sistem.
        </div>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('newlplpo.infokapus.store') }}"
            >

                @csrf


                {{-- KODE FASKES --}}
                <div class="mb-3">

                    <label class="form-label">
                        Kode Faskes
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ auth()->user()->kodeFaskes }}"
                        readonly
                    >

                    <div class="form-text">
                        Kode Faskes mengikuti user yang sedang login.
                    </div>

                </div>


                {{-- NAMA --}}
                <div class="mb-3">

                    <label
                        for="namaKapus"
                        class="form-label"
                    >
                        Nama Kepala Puskesmas
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="namaKapus"
                        name="namaKapus"
                        class="form-control"
                        value="{{ old('namaKapus') }}"
                        maxlength="150"
                        required
                    >

                </div>


                {{-- NIP --}}
                <div class="mb-3">

                    <label
                        for="nipKapus"
                        class="form-label"
                    >
                        NIP Kepala Puskesmas
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="nipKapus"
                        name="nipKapus"
                        class="form-control"
                        value="{{ old('nipKapus') }}"
                        maxlength="50"
                        required
                    >

                </div>


                {{-- EMAIL --}}
                <div class="mb-3">

                    <label
                        for="emailKapus"
                        class="form-label"
                    >
                        Email Kepala Puskesmas
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="email"
                        id="emailKapus"
                        name="emailKapus"
                        class="form-control"
                        value="{{ old('emailKapus') }}"
                        maxlength="150"
                        required
                    >

                    <div class="form-text">
                        Kode E-Sign akan dikirim ke email ini.
                    </div>

                </div>


                {{-- INFO --}}
                <div class="alert alert-info">

                    <i class="bi bi-info-circle me-1"></i>

                    Kode E-Sign 8 digit akan dibuat otomatis
                    oleh sistem setelah data disimpan.
                    Kode tidak disimpan dalam bentuk plaintext.

                </div>


                {{-- ACTION --}}
                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan
                    </button>

                    <a
                        href="{{ route('newlplpo.infokapus.index') }}"
                        class="btn btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection