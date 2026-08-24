<div class="card shadow-sm">

    <div class="card-header">

        <h5>

            Target Sasaran Posyandu

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <div class="mb-3">

                    <label>

                        Nama Posyandu

                    </label>

                   <select
    name="posyandu_id"
    id="posyandu_id"
    class="form-select"
    required
>
    <option value="">
        Pilih Posyandu
    </option>

    @foreach($posyandu as $item)

        <option
            value="{{ $item->id }}"
            @selected(
                old(
                    'posyandu_id',
                    $target_sasaran->posyandu_id ?? ''
                ) == $item->id
            )
        >
            {{ $item->namaPosyandu }}
        </option>

    @endforeach
</select>

                </div>

            </div>

            <div class="col-md-3">

                <div class="mb-3">

                    <label>Bulan</label>

                    <select
                        name="bulan"
                        class="form-select">

                        @foreach([
                        1=>"Januari",
                        2=>"Februari",
                        3=>"Maret",
                        4=>"April",
                        5=>"Mei",
                        6=>"Juni",
                        7=>"Juli",
                        8=>"Agustus",
                        9=>"September",
                        10=>"Oktober",
                        11=>"November",
                        12=>"Desember"
                        ] as $k=>$v)

                        <option
                            value="{{$k}}"

                            @selected(old('bulan',$target_sasaran->bulan ?? date('n'))==$k)

                        >

                            {{$v}}

                        </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="col-md-3">

                <div class="mb-3">

                    <label>Tahun</label>

                    <input
                        type="number"
                        name="tahun"
                        class="form-control"

                        value="{{ old('tahun',$target_sasaran->tahun ?? date('Y')) }}">

                </div>

            </div>

        </div>

        <hr>

        <div class="row">

            <div class="col-md-2">

                <div class="mb-3">

                    <label>RW</label>

                    <input
                        type="text"
                        name="rw"
                        class="form-control"

                        value="{{ old('rw',$target_sasaran->rw ?? '') }}">

                </div>

            </div>

            <div class="col-md-2">

                <div class="mb-3">

                    <label>RT</label>

                    <input
                        type="text"
                        name="rt"
                        class="form-control"

                        value="{{ old('rt',$target_sasaran->rt ?? '') }}">

                </div>

            </div>

        </div>

        <hr>

        <div class="row">

            <div class="col-md-4">

                <div class="mb-3">

                    <label>

                        Sasaran Ibu Hamil

                    </label>

                    <input
                        type="number"
                        min="0"

                        name="sasaran_ibu_hamil"

                        class="form-control"

                        value="{{ old('sasaran_ibu_hamil',$target_sasaran->sasaran_ibu_hamil ?? 0) }}">

                </div>

            </div>

            <div class="col-md-4">

                <div class="mb-3">

                    <label>

                        Sasaran Ibu Melahirkan

                    </label>

                    <input
                        type="number"

                        min="0"

                        name="sasaran_ibu_melahirkan"

                        class="form-control"

                        value="{{ old('sasaran_ibu_melahirkan',$target_sasaran->sasaran_ibu_melahirkan ?? 0) }}">

                </div>

            </div>

            <div class="col-md-4">

                <div class="mb-3">

                    <label>

                        Sasaran Bayi Baru Lahir

                    </label>

                    <input
                        type="number"

                        min="0"

                        name="sasaran_bayi_baru_lahir"

                        class="form-control"

                        value="{{ old('sasaran_bayi_baru_lahir',$target_sasaran->sasaran_bayi_baru_lahir ?? 0) }}">

                </div>

            </div>

        </div>

    </div>

  <div class="card-footer text-end">

    <a href="{{ route('master.target-sasaran.index') }}"
       class="btn btn-secondary">

        <i class="fas fa-arrow-left"></i>
        Kembali

    </a>

    <button type="submit"
            class="btn btn-primary">

        <i class="fas fa-save"></i>
        Simpan

    </button>

</div>

</div>
