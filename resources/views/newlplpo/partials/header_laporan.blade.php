<div class="card border-primary shadow-sm h-100">

    <div class="card-header bg-primary text-white">

        <strong>

            <i class="bi bi-file-earmark-text"></i>

            Informasi Laporan

        </strong>

    </div>

    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-6">

                <label class="form-label">

                    Bulan

                </label>

                <select
                    name="bulan"
                    class="form-select"
                    {{ isset($report) ? 'disabled' : '' }}>

                    @for($i=1;$i<=12;$i++)

                        <option
                            value="{{ $i }}"
                            {{ (old('bulan',$report->bulan ?? date('n'))==$i)?'selected':'' }}>

                            {{ DateTime::createFromFormat('!m',$i)->format('F') }}

                        </option>

                    @endfor

                </select>

            </div>

            <div class="col-md-6">

                <label class="form-label">

                    Tahun

                </label>

                <input
                    type="number"
                    class="form-control"
                    name="tahun"
                    value="{{ old('tahun',$report->tahun ?? date('Y')) }}"
                    {{ isset($report) ? 'readonly' : '' }}>

            </div>

        </div>

        <div class="mb-3">

            <label class="form-label">

                Nomor LPLPO

            </label>

            <input
                readonly
                class="form-control"
                name="nomor_lplpo"
                value="{{ old('nomor_lplpo',$report->nomor_lplpo ?? $nomorLplpo) }}">

        </div>

        <div class="mb-3">

            <label class="form-label">

                Status

            </label>

            <div>



                @php

                    $status = $report->report_status ?? 'Draft';

                @endphp

                @switch($status)

                    @case('Draft')

                        <span class="badge bg-secondary">

                            Draft

                        </span>

                    @break
                      @case('SUBMITED')

                        <span class="badge bg-success">

                            Terkirim

                        </span>

                    @break

                    @case('Verified')

                        <span class="badge bg-warning">

                            Terverifikasi

                        </span>

                    @break

                      @case('FINAL')

                        <span class="badge bg-success">

                            Selesai/Final

                        </span>

                    @break



                    @default

                        <span class="badge bg-danger">

                            {{ $status }}

                        </span>

                @endswitch

            </div>

        </div>

        @isset($report)

        <div class="text-end">

            <button
                type="button"
                class="btn btn-warning"
                id="btnEditHeader">

                <i class="bi bi-pencil-square"></i>

                Edit Header

            </button>

        </div>

        @endisset

    </div>

</div>
