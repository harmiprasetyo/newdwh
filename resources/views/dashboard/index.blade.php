@extends('layouts.dashboard.maindash')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
         HEADER
    =========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                📊 Dashboard
            </h3>

            <small class="text-muted">
                Monitoring data pelayanan
            </small>
        </div>

    </div>


    {{-- ==========================================================
         FILTER
    =========================================================== --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('dashboard') }}"
                id="dashboardFilterForm"
            >

                <div class="row g-3 align-items-end">

                    {{-- BULAN --}}
                    <div class="col-md-3">

                        <label
                            for="bulan"
                            class="form-label"
                        >
                            Bulan
                        </label>

                        <select
                            name="bulan"
                            id="bulan"
                            class="form-select"
                        >
                            <option value="">
                                Semua Bulan
                            </option>

                            @foreach(range(1, 12) as $month)

                                <option
                                    value="{{ $month }}"
                                    @selected((int) $bulan === $month)
                                >
                                    {{ \Carbon\Carbon::create()
                                        ->month($month)
                                        ->translatedFormat('F') }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TAHUN --}}
                    <div class="col-md-3">

                        <label
                            for="tahun"
                            class="form-label"
                        >
                            Tahun
                        </label>

                        <select
                            name="tahun"
                            id="tahun"
                            class="form-select"
                        >

                            @for(
                                $year = now()->year;
                                $year >= 2020;
                                $year--
                            )

                                <option
                                    value="{{ $year }}"
                                    @selected((int) $tahun === $year)
                                >
                                    {{ $year }}
                                </option>

                            @endfor

                        </select>

                    </div>


                    {{-- FASKES --}}
                  @if(in_array((int) $groupId, [1, 2]))

    <div class="col-md-4">

        <label
            for="faskes"
            class="form-label"
        >
            Faskes
        </label>

        <select
            name="faskes"
            id="faskes"
            class="form-select"
        >

            <option value="">
                Semua Faskes
            </option>

            @foreach($faskesList as $item)

                <option
                    value="{{ $item['name'] }}"
                    @selected($faskes === $item['name'])
                >
                    {{ $item['name'] }}
                </option>

            @endforeach

        </select>

    </div>

@endif


                    {{-- BUTTON --}}
                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            🔍 Tampilkan
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ==========================================================
         ANC K1
    =========================================================== --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            <strong>
                📈 Cakupan ANC K1
            </strong>
        </div>

        <div class="card-body">

            <div
                id="chartAncK1"
                style="min-height: 350px;"
            ></div>

        </div>

    </div>


    {{-- ==========================================================
         LOCATION
    =========================================================== --}}
    @if(isset($dashboardData['per_location']))

        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>
                    📍 Data Berdasarkan Lokasi
                </strong>
            </div>

            <div class="card-body">

                <div
                    id="chartLocation"
                    style="min-height: 350px;"
                ></div>

                <div class="table-responsive mt-4">

                    <table
                        id="tableLocation"
                        class="table table-bordered table-striped"
                    >

                        <thead>

                            <tr>
                                <th>Lokasi</th>
                                <th>Total</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach(
                                $dashboardData['per_location']
                                as $item
                            )

                                <tr>

                                    <td>
                                        {{ $item->location ?: '-' }}
                                    </td>

                                    <td>
                                        {{ number_format($item->total) }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- ==========================================================
         PROVIDER
    =========================================================== --}}
    @if(isset($dashboardData['per_provider']))

        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>
                    🏥 Data Berdasarkan Provider
                </strong>
            </div>

            <div class="card-body">

                <div
                    id="chartProvider"
                    style="min-height: 400px;"
                ></div>

                <div class="table-responsive mt-4">

                    <table
                        id="tableProvider"
                        class="table table-bordered table-striped"
                    >

                        <thead>

                            <tr>
                                <th>Provider</th>
                                <th>Total</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach(
                                $dashboardData['per_provider']
                                as $item
                            )

                                <tr>

                                    <td>
                                        {{ $item->service_provider ?: '-' }}
                                    </td>

                                    <td>
                                        {{ number_format($item->total) }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif

</div>


{{-- ==============================================================
     DATA UNTUK JAVASCRIPT
================================================================ --}}
<script>
    window.dashboardData = @json($dashboardData);
    window.dashboardFilter = @json($dashboardFilter);
</script>
@endsection


@push('scripts')

    <script src="{{ mix('js/dashboard/index.js') }}"></script>

@endpush
