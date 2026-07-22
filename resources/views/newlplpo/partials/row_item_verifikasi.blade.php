<tr>

    <td class="text-center">{{ $no++ }}</td>

    <td>{{ optional($item->program)->program_name }}</td>

    <td>{{ $item->kode_obat }}</td>

    <td>{{ $item->nama_obat }}</td>

    <td class="text-center">{{ $item->satuan }}</td>

    <td class="text-end">
        {{ number_format($item->stok_awal_progam_pkd) }}
    </td>

    <td class="text-end">
        {{ number_format($item->stok_awal_jkn) }}
    </td>

    <td class="text-end">
        {{ number_format($item->penerimaan_program_pkd) }}
    </td>

    <td class="text-end">
        {{ number_format($item->penerimaan_jkn) }}
    </td>

    <td class="text-end">
        {{ number_format($item->persediaan_program_pkd) }}
    </td>

    <td class="text-end">
        {{ number_format($item->persediaan_jkn) }}
    </td>

    <td class="text-end">
        {{ number_format($item->pemakaian_program_pkd) }}
    </td>

    <td class="text-end">
        {{ number_format($item->pemakaian_jkn) }}
    </td>

    <td class="text-end">
        {{ number_format($item->item_expired) }}
    </td>

    <td class="text-end">
        {{ number_format($item->stok_akhir_program_pkd) }}
    </td>

    <td class="text-end">
        {{ number_format($item->stok_akhir_jkn) }}
    </td>

    <td class="text-end">
        {{ number_format($item->stok_minimum) }}
    </td>

    <td class="text-end">
        {{ number_format($item->stok_optimum) }}
    </td>

    <td class="text-end fw-bold">
        {{ number_format($item->permintaan) }}
    </td>

    <td class="text-end fw-bold text-success">
        {{ number_format(($item->pemberian_program_pkd ?? 0) + ($item->pemberian_jkn ?? 0)) }}
    </td>

</tr>
