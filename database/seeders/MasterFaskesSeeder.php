<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterFaskesSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kodeFaskes'=>'10041702','kodeKecamatan'=>'320105','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'BABAKAN MADANG'],
            ['kodeFaskes'=>'10041705','kodeKecamatan'=>'320105','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIJAYANTI'],
            ['kodeFaskes'=>'10041706','kodeKecamatan'=>'320105','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SENTUL'],

            ['kodeFaskes'=>'10041601','kodeKecamatan'=>'320113','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'BOJONG GEDE'],
            ['kodeFaskes'=>'10041604','kodeKecamatan'=>'320113','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'RAGAJAYA'],
            ['kodeFaskes'=>'10041605','kodeKecamatan'=>'320113','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'KEMUNING'],

            ['kodeFaskes'=>'10042801','kodeKecamatan'=>'320127','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CARINGIN'],
            ['kodeFaskes'=>'10042802','kodeKecamatan'=>'320127','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CINAGARA'],
            ['kodeFaskes'=>'10042803','kodeKecamatan'=>'320127','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIDERUM'],

            ['kodeFaskes'=>'10042401','kodeKecamatan'=>'320108','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CARIU'],
            ['kodeFaskes'=>'10042403','kodeKecamatan'=>'320108','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'KARYAMEKAR'],

            ['kodeFaskes'=>'10040601','kodeKecamatan'=>'320115','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIAMPEA'],
            ['kodeFaskes'=>'10040602','kodeKecamatan'=>'320115','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'PASIR'],
            ['kodeFaskes'=>'10040604','kodeKecamatan'=>'320115','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIAMPEA UDIK'],
            ['kodeFaskes'=>'10040605','kodeKecamatan'=>'320115','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIHIDEUNG UDIK'],

            ['kodeFaskes'=>'10040201','kodeKecamatan'=>'320124','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIAWI'],
            ['kodeFaskes'=>'10040202','kodeKecamatan'=>'320124','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'BANJARSARI'],
            ['kodeFaskes'=>'10040203','kodeKecamatan'=>'320124','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CITAPEN'],

            ['kodeFaskes'=>'10042001','kodeKecamatan'=>'320101','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIBINONG'],
            ['kodeFaskes'=>'10042002','kodeKecamatan'=>'320101','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIRIMEKAR'],
            ['kodeFaskes'=>'10042003','kodeKecamatan'=>'320101','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'KARADENAN'],
            ['kodeFaskes'=>'10042004','kodeKecamatan'=>'320101','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'PABUARAN INDAH'],

            ['kodeFaskes'=>'10040701','kodeKecamatan'=>'320116','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SITU UDIK'],
            ['kodeFaskes'=>'10040703','kodeKecamatan'=>'320116','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIBUNGBULANG'],
            ['kodeFaskes'=>'10040705','kodeKecamatan'=>'320116','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIJUJUNG'],

            ['kodeFaskes'=>'10040303','kodeKecamatan'=>'320138','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIBURAYUT'],
            ['kodeFaskes'=>'10040305','kodeKecamatan'=>'320138','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIGOMBONG'],

            ['kodeFaskes'=>'10040901','kodeKecamatan'=>'320122','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIGUDEG'],
            ['kodeFaskes'=>'10040902','kodeKecamatan'=>'320122','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'LEBAKWANGI'],
            ['kodeFaskes'=>'10040904','kodeKecamatan'=>'320122','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'BUNAR'],

            ['kodeFaskes'=>'10040301','kodeKecamatan'=>'320128','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIJERUK'],
            ['kodeFaskes'=>'10040304','kodeKecamatan'=>'320128','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SUKAHARJA'],

            ['kodeFaskes'=>'10042201','kodeKecamatan'=>'320107','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CILEUNGSI'],
            ['kodeFaskes'=>'10042203','kodeKecamatan'=>'320107','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'GANDOANG'],
            ['kodeFaskes'=>'10042204','kodeKecamatan'=>'320107','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'PASIR ANGIN'],

            ['kodeFaskes'=>'10040404','kodeKecamatan'=>'320129','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIOMAS'],
            ['kodeFaskes'=>'10040411','kodeKecamatan'=>'320129','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIAPUS'],
            ['kodeFaskes'=>'10040412','kodeKecamatan'=>'320129','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'KOTABATU'],
            ['kodeFaskes'=>'10040413','kodeKecamatan'=>'320129','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'LALADON'],

            ['kodeFaskes'=>'10040101','kodeKecamatan'=>'320125','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CISARUA'],
            ['kodeFaskes'=>'10040104','kodeKecamatan'=>'320125','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIBULAN'],

            ['kodeFaskes'=>'10041302','kodeKecamatan'=>'320133','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CISEENG'],
            ['kodeFaskes'=>'10041304','kodeKecamatan'=>'320133','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIBEUTEUNG UDIK'],

            ['kodeFaskes'=>'10041701','kodeKecamatan'=>'320103','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CITEUREUP'],
            ['kodeFaskes'=>'10041703','kodeKecamatan'=>'320103','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'LEUWINUTUG'],
            ['kodeFaskes'=>'10041704','kodeKecamatan'=>'320103','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'TAJUR'],

            ['kodeFaskes'=>'10040402','kodeKecamatan'=>'320130','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'DRAMAGA'],
            ['kodeFaskes'=>'10040408','kodeKecamatan'=>'320130','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CANGKURAWOK'],
            ['kodeFaskes'=>'10040409','kodeKecamatan'=>'320130','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'KAMPUNG MANGGIS'],
            ['kodeFaskes'=>'10040410','kodeKecamatan'=>'320130','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'PURWASARI'],

            ['kodeFaskes'=>'10042101','kodeKecamatan'=>'320102','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'GUNUNG PUTRI'],
            ['kodeFaskes'=>'10042102','kodeKecamatan'=>'320102','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIANGSANA'],
            ['kodeFaskes'=>'10042103','kodeKecamatan'=>'320102','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'KARANGGAN'],
            ['kodeFaskes'=>'10042104','kodeKecamatan'=>'320102','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'BOJONGNANGKA'],

            ['kodeFaskes'=>'10041201','kodeKecamatan'=>'320111','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'GUNUNG SINDUR'],
            ['kodeFaskes'=>'10041202','kodeKecamatan'=>'320111','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SULIWER'],

            ['kodeFaskes'=>'10041001','kodeKecamatan'=>'320119','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'JASINGA'],
            ['kodeFaskes'=>'10041002','kodeKecamatan'=>'320119','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CURUG'],
            ['kodeFaskes'=>'10041003','kodeKecamatan'=>'320119','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'BAGOANG'],

            ['kodeFaskes'=>'10042301','kodeKecamatan'=>'320106','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'JONGGOL'],
            ['kodeFaskes'=>'10042303','kodeKecamatan'=>'320106','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'BALEKAMBANG'],
            ['kodeFaskes'=>'10042304','kodeKecamatan'=>'320106','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SUKANEGARA'],

            ['kodeFaskes'=>'10041801','kodeKecamatan'=>'320112','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'KEMANG'],
            ['kodeFaskes'=>'10041804','kodeKecamatan'=>'320112','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'JAMPANG'],

            ['kodeFaskes'=>'10042202','kodeKecamatan'=>'320132','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'KLAPANUNGGAL'],
            ['kodeFaskes'=>'10042205','kodeKecamatan'=>'320132','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'BOJONG'],

            ['kodeFaskes'=>'10040801','kodeKecamatan'=>'320114','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'LEUWILIANG'],
            ['kodeFaskes'=>'10040803','kodeKecamatan'=>'320114','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'PURASEDA'],

            ['kodeFaskes'=>'10040802','kodeKecamatan'=>'320139','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'LEUWISADENG'],
            ['kodeFaskes'=>'10040804','kodeKecamatan'=>'320139','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SADENGPASAR'],

            ['kodeFaskes'=>'10040102','kodeKecamatan'=>'320126','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SUKAMANAH'],
            ['kodeFaskes'=>'10040103','kodeKecamatan'=>'320126','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'MEGAMENDUNG'],

            ['kodeFaskes'=>'10042901','kodeKecamatan'=>'320121','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'NANGGUNG'],
            ['kodeFaskes'=>'10042902','kodeKecamatan'=>'320121','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CURUG BITUNG'],

            ['kodeFaskes'=>'10040702','kodeKecamatan'=>'320117','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIBENING'],
            ['kodeFaskes'=>'10040704','kodeKecamatan'=>'320117','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'PAMIJAHAN'],
            ['kodeFaskes'=>'10040706','kodeKecamatan'=>'320117','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIASMARA'],

            ['kodeFaskes'=>'10041301','kodeKecamatan'=>'320110','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'PARUNG'],
            ['kodeFaskes'=>'10041305','kodeKecamatan'=>'320110','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'COGREG'],

            ['kodeFaskes'=>'10041101','kodeKecamatan'=>'320120','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'PARUNG PANJANG'],
            ['kodeFaskes'=>'10041103','kodeKecamatan'=>'320120','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'DAGO'],

            ['kodeFaskes'=>'10041802','kodeKecamatan'=>'320134','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'RANCABUNGUR'],
            ['kodeFaskes'=>'10041803','kodeKecamatan'=>'320134','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'BANTARJAYA'],

            ['kodeFaskes'=>'10040501','kodeKecamatan'=>'320118','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'RUMPIN'],
            ['kodeFaskes'=>'10040502','kodeKecamatan'=>'320118','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CICANGKAL'],
            ['kodeFaskes'=>'10040503','kodeKecamatan'=>'320118','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'GOBANG'],

            ['kodeFaskes'=>'10040903','kodeKecamatan'=>'320135','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SUKAJAYA'],
            ['kodeFaskes'=>'10040905','kodeKecamatan'=>'320135','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'KIARAPANDAK'],

            ['kodeFaskes'=>'10042302','kodeKecamatan'=>'320109','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SUKAMAKMUR'],
            ['kodeFaskes'=>'10042305','kodeKecamatan'=>'320109','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SUKADAMAI'],

            ['kodeFaskes'=>'10041901','kodeKecamatan'=>'320104','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CIMANDALA'],
            ['kodeFaskes'=>'10041902','kodeKecamatan'=>'320104','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SUKARAJA'],
            ['kodeFaskes'=>'10041903','kodeKecamatan'=>'320104','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'CILEBUT'],

            ['kodeFaskes'=>'10041603','kodeKecamatan'=>'320137','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'TAJUR HALANG'],

            ['kodeFaskes'=>'10040403','kodeKecamatan'=>'320131','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SIRNAGALIH'],
            ['kodeFaskes'=>'10040407','kodeKecamatan'=>'320131','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'TAMAN SARI'],
            ['kodeFaskes'=>'10040414','kodeKecamatan'=>'320131','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'SUKARESMI'],

            ['kodeFaskes'=>'10042402','kodeKecamatan'=>'320136','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'TANJUNG SARI'],

            ['kodeFaskes'=>'10041102','kodeKecamatan'=>'320123','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'TENJO'],
            ['kodeFaskes'=>'10041104','kodeKecamatan'=>'320123','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'PASAR REBO'],

            ['kodeFaskes'=>'10040603','kodeKecamatan'=>'320140','kodeKabupaten'=>'3201','kodePropinsi'=>'32','typeFaskes'=>1,'kepemilikan'=>'Pemerintah','namaFaskes'=>'TENJOLAYA'],
        ];

        foreach ($data as $item) {
            DB::table('master_faskes')->updateOrInsert(
                [
                    'kodeFaskes' => $item['kodeFaskes'],
                ],
                [
                    'typeFaskes'    => $item['typeFaskes'],
                    'kodePropinsi'  => $item['kodePropinsi'],
                    'kodeKabupaten' => $item['kodeKabupaten'],
                    'kodeKecamatan' => $item['kodeKecamatan'],
                    'kepemilikan'   => $item['kepemilikan'],
                    'namaFaskes'    => $item['namaFaskes'],
                    'updated_at'    => now(),
                    'created_at'    => now(),
                ]
            );
        }
    }
}