{{-- ===================================================== LAYANAN ANC ====================================================== --}}
 <div class="rme-tab-content" id="layananAnc">
    <table class="table rme-detail-form-table">
        <tbody>
            <tr> <th>Trimester Ke</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_trimester'])) {{ $dt['ANC']['anc_trimester'] }} @endif </td> </tr>
            <tr> <th>Jarak Kehamilan</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_jarak_hamil'])) {{ $dt['ANC']['anc_jarak_hamil'] }} @endif </td> </tr>
             <tr> <th>HPL</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_hpl'])) {{ $dt['ANC']['anc_hpl'] }} @endif </td> </tr>
              <tr> <th>HPHT</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_hpht'])) {{ $dt['ANC']['anc_hpht'] }} @endif </td> </tr>
               <tr> <th>Usia Kehamilan</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_usia_kehamilan'])) {{ $dt['ANC']['anc_usia_kehamilan'] }} @endif </td> </tr>
               <tr> <th>Tinggi Badan</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_body_heigh'])) {{ $dt['ANC']['anc_body_heigh'] }} @endif </td> </tr>
               <tr> <th>BB Sebelum Hamil</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_bb_pre'])) {{ $dt['ANC']['anc_bb_pre'] }} @endif </td> </tr>
               <tr> <th>LILA</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_lila'])) {{ $dt['ANC']['anc_lila'] }} @endif </td> </tr>
               <tr> <th>Status Imunisasi</th> <td>:</td> <td>-</td> </tr>
                <tr> <th>Skrining TBC</th> <td>:</td> <td>-</td> </tr>
                <tr> <th>Merokok</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_smooking'])) {{ $dt['ANC']['anc_smooking'] }} @endif </td> </tr>
                <tr> <th>Riwayat Alkohol</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_smooking'])) {{ $dt['ANC']['anc_alch'] }} @endif </td> </tr> {{-- Pemeriksaan Fisik --}}
                 <tr class="rme-section-row"> <th colspan="3"> Pemeriksaan Fisik </th> </tr>
                 <tr> <th>Pemeriksaan Fisik Konjungtiva</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_conjungtiva'])) {{ $dt['ANC']['anc_conjungtiva'] }} @endif </td> </tr>
                 <tr> <th>Pemeriksaan Fisik Skelra</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_sklera'])) {{ $dt['ANC']['anc_sklera'] }} @endif </td> </tr>
                 <tr> <th>Pemeriksaan Fisik Leher</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_leher'])) {{ $dt['ANC']['anc_leher'] }} @endif </td> </tr>
                 <tr> <th>Pemeriksaan Fisik Mulut</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_mulut'])) {{ $dt['ANC']['anc_leher'] }} @endif </td> </tr>
                  <tr> <th>Pemeriksaan Fisik THT</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_tht'])) {{ $dt['ANC']['anc_leher'] }} @endif </td> </tr>
                  <tr> <th>Pemeriksaan Fisik Jantung</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_jantung'])) {{ $dt['ANC']['anc_leher'] }} @endif </td> </tr>
                   <tr> <th>Pemeriksaan Fisik Paru-paru</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_paru'])) {{ $dt['ANC']['anc_paru'] }} @endif </td> </tr>
                    <tr> <th>Pemeriksaan Fisik Perut</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_perut'])) {{ $dt['ANC']['anc_perut'] }} @endif </td> </tr>
                     <tr> <th>Pemeriksaan Fisik Tungkai</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_tungkai'])) {{ $dt['ANC']['anc_tungkai'] }} @endif </td> </tr> {{-- Pemeriksaan Janin --}}
                     <tr class="rme-section-row"> <th colspan="3"> Pemeriksaan Janin </th> </tr>
                     <tr> <th>Jumlah Janin</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_jumlah_janin'])) {{ $dt['ANC']['anc_jumlah_janin'] }} @endif </td> </tr>
                      <tr> <th>TBJ</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_tbj'])) {{ $dt['ANC']['anc_tbj'] }} @endif </td> </tr>
                       <tr> <th>TFU</th> <td>:</td> <td> @if(isset($ndt['ANC']['anc_tfu'])) {{ $ndt['ANC']['anc_tfu'] }} @endif </td> </tr>
                       <tr> <th>DJJ</th> <td>:</td> <td> >@if(isset($dt['ANC']['anc_djj'])) {{ $dt['ANC']['anc_djj'] }} @endif </td> </tr>
                       <tr> <th>Posisi Kepala</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_head'])) {{ $dt['ANC']['anc_head'] }} @endif </td> </tr>
                       <tr> <th>Presentasi</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_presentasi'])) {{ $dt['ANC']['anc_presentasi'] }} @endif </td> </tr> {{-- Diagnosis --}}
                        <tr class="rme-section-row"> <th>Diagnosis</th> <td>:</td> <td> @if(!empty($dt['ANC']['anc_diagnosa'])) <ul class="rme-list"> @foreach($dt['ANC']['anc_diagnosa'] as $diagnosa) <li> {{ $diagnosa['display'] }} @if(!empty($diagnosa['code'])) ({{ $diagnosa['code'] }}) @endif </li> @endforeach </ul> @else - @endif </td> </tr>
                        <tr> <th>Pemeriksaan USG</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_usg'])) {{ $dt['ANC']['anc_usg'] }} @endif </td> </tr>
                        <tr> <th>Edukasi</th> <td>:</td> <td> @if(isset($dt['ANC']['anc_education'])) {{ $dt['ANC']['anc_education'] }} @endif </td> </tr>
                    </tr> {{-- Laboratorium --}} <tr class="rme-section-row"> <th colspan="3"> Pemeriksaan Laboratorium </th> </tr>
                     <tr> <th>HB</th> <td>:</td> <td> @if(isset($dt['lab_hb'])) {{ $dt['lab']['lab_hb']['val'] }} @endif </td> </tr>
                     <tr> <th>Gol Darah</th> <td>:</td> <td> @if(isset($ndt['ANC']['anc_gol_darah'])) {{ $ndt['ANC']['anc_gol_darah'] }} @endif </td> </tr>
                     <tr> <th>Rhesus</th> <td>:</td> <td> @if(isset($dt['lab_rh'])) {{ $dt['lab']['lab_rh']['val'] }} @endif </td> </tr>
                      <tr> <th>Urin Protein</th> <td>:</td> <td> @if(isset($dt['lab'])) {{ $dt['lab']['lab_urin_protein']['val'] }} @endif </td> </tr>
                       <tr> <th>Glukosa</th> <td>:</td> <td> @if(isset($dt['lab'])) {{ $dt['lab']['lab_gula_darah']['val'] }} @endif </td> </tr>
                       <tr> <th>HIV</th> <td>:</td> <td> @if(isset($dt['lab'])) {{ $dt['lab']['lab_hiv']['val'] }} @endif </td> </tr>
                       <tr> <th>Sifilis</th> <td>:</td> <td>-</td> </tr>
                        <tr> <th>Hepatitis B</th> <td>:</td> <td> @if(isset($dt['lab'])) {{ $dt['lab']['lab_hepatitis_b']['val'] }} @endif </td> </tr> <tr> <th>TBC</th> <td>:</td> <td>-</td> </tr> <tr> <th>Malaria</th> <td>:</td> <td>-</td> </tr>
        </tbody>
    </table>
</div>
