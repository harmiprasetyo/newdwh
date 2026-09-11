@extends('newlplpo.layouts.master')

@section('title', 'Edit Kepala Puskesmas')


@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h4 class="mb-1">
            Edit Kepala Puskesmas
        </h4>

        <div class="text-muted">
            Perubahan data tidak mengubah Kode E-Sign.
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
                action="{{ route(
                    'newlplpo.infokapus.update',
                    $kapus->id
                ) }}"
            >

                @csrf

                @method('PUT')


                {{-- KODE FASKES --}}
                <div class="mb-3">

                    <label class="form-label">
                        Kode Faskes
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $kapus->kodeFaskes }}"
                        readonly
                    >

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
                        value="{{ old(
                            'namaKapus',
                            $kapus->namaKapus
                        ) }}"
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
                        value="{{ old(
                            'nipKapus',
                            $kapus->nipKapus
                        ) }}"
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
                        value="{{ old(
                            'emailKapus',
                            $kapus->emailKapus
                        ) }}"
                        maxlength="150"
                        required
                    >

                    <div class="form-text">
                        Perubahan email tidak mengubah Kode E-Sign.
                        Gunakan tombol Reset E-Sign jika kode perlu
                        dibuat ulang.
                    </div>

                </div>


                {{-- E-SIGN --}}
                <div class="alert alert-warning">

                    <i class="bi bi-shield-lock me-1"></i>

                    <strong>Kode E-Sign</strong>

                    <div class="mt-1">
                        Kode E-Sign tidak dapat dilihat atau diubah
                        dari halaman ini.
                    </div>

                    <div>
                        Untuk membuat kode baru gunakan fitur
                        <strong>Reset Kode E-Sign</strong>.
                    </div>

                </div>


                {{-- ACTION --}}
                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-save me-1"></i>
                        Simpan Perubahan
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