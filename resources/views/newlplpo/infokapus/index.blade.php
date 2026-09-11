@extends('newlplpo.layouts.master')
@section('title', 'Info Kepala Puskesmas')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Data Kepala Puskesmas
            </h4>

            <div class="text-muted">
                Pengaturan Kepala Puskesmas dan Kode E-Sign
            </div>
        </div>

        @if (!$kapus)
            <a
                href="{{ route('newlplpo.infokapus.create') }}"
                class="btn btn-success"
            >
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Data Kapus
            </a>
        @endif

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- VALIDATION ERROR --}}
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


    {{-- DATA --}}
    <div class="card shadow-sm">

        <div class="card-body">

            @if($kapus)

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">
                                    #
                                </th>

                                <th>
                                    Kode Faskes
                                </th>

                                <th>
                                    Nama Kapus
                                </th>

                                <th>
                                    NIP
                                </th>

                                <th>
                                    Email
                                </th>

                                <th width="220">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>
                                    1
                                </td>

                                <td>
                                    {{ $kapus->kodeFaskes }}
                                </td>

                                <td>
                                    {{ $kapus->namaKapus }}
                                </td>

                                <td>
                                    {{ $kapus->nipKapus }}
                                </td>

                                <td>
                                    {{ $kapus->emailKapus }}
                                </td>

                                <td>

                                    <div class="d-flex gap-1">

                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route(
                                                'newlplpo.infokapus.edit',
                                                $kapus->id
                                            ) }}"
                                            class="btn btn-sm btn-primary"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>


                                        {{-- RESET E-SIGN --}}
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            id="btnResetEsign"
                                            data-url="{{ route(
                                                'newlplpo.infokapus.reset-esign',
                                                $kapus->id
                                            ) }}"
                                            title="Reset Kode E-Sign"
                                        >
                                            <i class="bi bi-key"></i>
                                        </button>


                                        {{-- DELETE --}}
                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'newlplpo.infokapus.destroy',
                                                $kapus->id
                                            ) }}"
                                            class="formDelete"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Hapus"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-person-badge"
                            style="font-size: 48px;"
                        ></i>

                    </div>

                    <h5>
                        Data Kepala Puskesmas belum tersedia
                    </h5>

                    <p class="text-muted">
                        Silakan tambahkan data Kepala Puskesmas
                        terlebih dahulu.
                    </p>

                    <a
                        href="{{ route('newlplpo.infokapus.create') }}"
                        class="btn btn-success"
                    >
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Data Kapus
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection




@push('script')

<script src="{{ mix('js/newlplpo/infokapus.js') }}"></script>

@endpush

