<div class="card border-success shadow-sm h-100">

    <div class="card-header bg-success text-white">

        <strong>

            <i class="bi bi-hospital"></i>

            Informasi Fasilitas Kesehatan

        </strong>

    </div>

    <div class="card-body">

        <table class="table table-borderless table-sm mb-0">

            <tr>

                <th width="35%">Nama Faskes</th>

                <td>

                    <strong>

                        {{ $faskes->namaFaskes }}

                    </strong>

                </td>

            </tr>

            <tr>

                <th>Propinsi</th>

                <td>

                    {{ $faskes->provinsi->name ?? '-' }}

                </td>

            </tr>

            <tr>

                <th>Kabupaten</th>

                <td>

                    {{ $faskes->kota->name ?? '-' }}

                </td>

            </tr>

            <tr>

                <th>Kecamatan</th>

                <td>

                    {{ $faskes->kecamatan->name ?? '-' }}

                </td>

            </tr>

        </table>

    </div>

</div>
