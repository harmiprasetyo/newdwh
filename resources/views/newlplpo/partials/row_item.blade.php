<tr class="{{ $item->obat_napza === 'ya' ? 'row-napza' : '' }}">

    <td class="text-center">{{ $no + 1 }}</td>

    <td>{{ $item->kode_obat }}</td>

    <td>{{ $item->nama_obat }}</td>

    <td class="text-center">{{ $item->satuan }}</td>
    <td class="text-center">

    @if($item->obat_esensial === 'oe')

        <span class="badge bg-success">
            Esensial
        </span>

    @else

        <span class="badge bg-secondary">
            Non Esensial
        </span>

    @endif

</td>

    <td class="text-end">{{ number_format($item->stok_awal_progam_pkd) }}</td>
    <td class="text-end">{{ number_format($item->stok_awal_jkn) }}</td>

    <td class="text-end">{{ number_format($item->penerimaan_program_pkd) }}</td>
    <td class="text-end">{{ number_format($item->penerimaan_jkn) }}</td>

    <td class="text-end">{{ number_format($item->persediaan_program_pkd) }}</td>
    <td class="text-end">{{ number_format($item->persediaan_jkn) }}</td>

    <td class="text-end">{{ number_format($item->pemakaian_program_pkd) }}</td>
    <td class="text-end">{{ number_format($item->pemakaian_jkn) }}</td>

    <td class="text-end">{{ number_format($item->item_expired_pkd) }}</td>
     <td class="text-end">{{ number_format($item->item_expired_jkn) }}</td>

    <td class="text-end">{{ number_format($item->stok_akhir_program_pkd) }}</td>
    <td class="text-end">{{ number_format($item->stok_akhir_jkn) }}</td>

    <td class="text-end">{{ number_format($item->stok_minimum) }}</td>

    <td class="text-end">{{ number_format($item->stok_optimum) }}</td>

    <td class="text-end">{{ number_format($item->permintaan) }}</td>

    <td class="text-end">
        {{ number_format(($item->pemberian_program_pkd ?? 0)) }}
    </td>
    <td class="text-end">
        {{ number_format(($item->pemberian_jkn ?? 0)) }}
    </td>

    <td class="text-center" width="90">

       <button
    type="button"
    class="btn btn-warning btn-sm btnEditItem"
    data-id="{{ $item->id }}"
>
    <i class="bi bi-pencil"></i>
</button>

        <button
            type="button"
            class="btn btn-danger btn-sm btnDeleteItem"
            data-id="{{ $item->id }}">

            <i class="bi bi-trash"></i>

        </button>

    </td>

</tr>
