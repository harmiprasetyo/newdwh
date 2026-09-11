<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UsersAppSeeder extends Seeder
{
    /**
     * Seed users_app untuk seluruh Puskesmas.
     *
     * Mapping:
     * username       = namaFaskes lowercase tanpa spasi
     * groupid        = 3
     * role_id        = 3
     * namalengkap    = Puskesmas + namaFaskes
     * kodeFaskes     = kodeFaskes
     * kodePropinsi   = kodePropinsi
     * kodeKota       = kodeKabupaten
     * kodeKecamatan  = kodeKecamatan
     * password       = 1Sampai8
     * role           = faskes
     */
    public function run(): void
    {
        $faskes = [
            [
                'kodeFaskes' => '10041702',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320105',
                'namaFaskes' => 'BABAKAN MADANG',
            ],
            [
                'kodeFaskes' => '10041705',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320105',
                'namaFaskes' => 'CIJAYANTI',
            ],
            [
                'kodeFaskes' => '10041706',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320105',
                'namaFaskes' => 'SENTUL',
            ],
            [
                'kodeFaskes' => '10041601',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320113',
                'namaFaskes' => 'BOJONG GEDE',
            ],
            [
                'kodeFaskes' => '10041604',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320113',
                'namaFaskes' => 'RAGAJAYA',
            ],
            [
                'kodeFaskes' => '10041605',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320113',
                'namaFaskes' => 'KEMUNING',
            ],
            [
                'kodeFaskes' => '10042801',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320127',
                'namaFaskes' => 'CARINGIN',
            ],
            [
                'kodeFaskes' => '10042802',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320127',
                'namaFaskes' => 'CINAGARA',
            ],
            [
                'kodeFaskes' => '10042803',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320127',
                'namaFaskes' => 'CIDERUM',
            ],
            [
                'kodeFaskes' => '10042401',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320108',
                'namaFaskes' => 'CARIU',
            ],
            [
                'kodeFaskes' => '10042403',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320108',
                'namaFaskes' => 'KARYAMEKAR',
            ],
            [
                'kodeFaskes' => '10040601',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320115',
                'namaFaskes' => 'CIAMPEA',
            ],
            [
                'kodeFaskes' => '10040602',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320115',
                'namaFaskes' => 'PASIR',
            ],
            [
                'kodeFaskes' => '10040604',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320115',
                'namaFaskes' => 'CIAMPEA UDIK',
            ],
            [
                'kodeFaskes' => '10040605',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320115',
                'namaFaskes' => 'CIHIDEUNG UDIK',
            ],
            [
                'kodeFaskes' => '10040201',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320124',
                'namaFaskes' => 'CIAWI',
            ],
            [
                'kodeFaskes' => '10040202',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320124',
                'namaFaskes' => 'BANJARSARI',
            ],
            [
                'kodeFaskes' => '10040203',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320124',
                'namaFaskes' => 'CITAPEN',
            ],
            [
                'kodeFaskes' => '10042001',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320101',
                'namaFaskes' => 'CIBINONG',
            ],
            [
                'kodeFaskes' => '10042002',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320101',
                'namaFaskes' => 'CIRIMEKAR',
            ],
            [
                'kodeFaskes' => '10042003',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320101',
                'namaFaskes' => 'KARADENAN',
            ],
            [
                'kodeFaskes' => '10042004',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320101',
                'namaFaskes' => 'PABUARAN INDAH',
            ],
            [
                'kodeFaskes' => '10040701',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320116',
                'namaFaskes' => 'SITU UDIK',
            ],
            [
                'kodeFaskes' => '10040703',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320116',
                'namaFaskes' => 'CIBUNGBULANG',
            ],
            [
                'kodeFaskes' => '10040705',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320116',
                'namaFaskes' => 'CIJUJUNG',
            ],
            [
                'kodeFaskes' => '10040303',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320138',
                'namaFaskes' => 'CIBURAYUT',
            ],
            [
                'kodeFaskes' => '10040305',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320138',
                'namaFaskes' => 'CIGOMBONG',
            ],
            [
                'kodeFaskes' => '10040901',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320122',
                'namaFaskes' => 'CIGUDEG',
            ],
            [
                'kodeFaskes' => '10040902',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320122',
                'namaFaskes' => 'LEBAKWANGI',
            ],
            [
                'kodeFaskes' => '10040904',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320122',
                'namaFaskes' => 'BUNAR',
            ],
            [
                'kodeFaskes' => '10040301',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320128',
                'namaFaskes' => 'CIJERUK',
            ],
            [
                'kodeFaskes' => '10040304',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320128',
                'namaFaskes' => 'SUKAHARJA',
            ],
            [
                'kodeFaskes' => '10042201',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320107',
                'namaFaskes' => 'CILEUNGSI',
            ],
            [
                'kodeFaskes' => '10042203',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320107',
                'namaFaskes' => 'GANDOANG',
            ],
            [
                'kodeFaskes' => '10042204',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320107',
                'namaFaskes' => 'PASIR ANGIN',
            ],
            [
                'kodeFaskes' => '10040404',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320129',
                'namaFaskes' => 'CIOMAS',
            ],
            [
                'kodeFaskes' => '10040411',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320129',
                'namaFaskes' => 'CIAPUS',
            ],
            [
                'kodeFaskes' => '10040412',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320129',
                'namaFaskes' => 'KOTABATU',
            ],
            [
                'kodeFaskes' => '10040413',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320129',
                'namaFaskes' => 'LALADON',
            ],
            [
                'kodeFaskes' => '10040101',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320125',
                'namaFaskes' => 'CISARUA',
            ],
            [
                'kodeFaskes' => '10040104',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320125',
                'namaFaskes' => 'CIBULAN',
            ],
            [
                'kodeFaskes' => '10041302',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320133',
                'namaFaskes' => 'CISEENG',
            ],
            [
                'kodeFaskes' => '10041304',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320133',
                'namaFaskes' => 'CIBEUTEUNG UDIK',
            ],
            [
                'kodeFaskes' => '10041701',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320103',
                'namaFaskes' => 'CITEUREUP',
            ],
            [
                'kodeFaskes' => '10041703',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320103',
                'namaFaskes' => 'LEUWINUTUG',
            ],
            [
                'kodeFaskes' => '10041704',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320103',
                'namaFaskes' => 'TAJUR',
            ],
            [
                'kodeFaskes' => '10040402',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320130',
                'namaFaskes' => 'DRAMAGA',
            ],
            [
                'kodeFaskes' => '10040408',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320130',
                'namaFaskes' => 'CANGKURAWOK',
            ],
            [
                'kodeFaskes' => '10040409',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320130',
                'namaFaskes' => 'KAMPUNG MANGGIS',
            ],
            [
                'kodeFaskes' => '10040410',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320130',
                'namaFaskes' => 'PURWASARI',
            ],
            [
                'kodeFaskes' => '10042101',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320102',
                'namaFaskes' => 'GUNUNG PUTRI',
            ],
            [
                'kodeFaskes' => '10042102',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320102',
                'namaFaskes' => 'CIANGSANA',
            ],
            [
                'kodeFaskes' => '10042103',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320102',
                'namaFaskes' => 'KARANGGAN',
            ],
            [
                'kodeFaskes' => '10042104',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320102',
                'namaFaskes' => 'BOJONGNANGKA',
            ],
            [
                'kodeFaskes' => '10041201',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320111',
                'namaFaskes' => 'GUNUNG SINDUR',
            ],
            [
                'kodeFaskes' => '10041202',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320111',
                'namaFaskes' => 'SULIWER',
            ],
            [
                'kodeFaskes' => '10041001',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320119',
                'namaFaskes' => 'JASINGA',
            ],
            [
                'kodeFaskes' => '10041002',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320119',
                'namaFaskes' => 'CURUG',
            ],
            [
                'kodeFaskes' => '10041003',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320119',
                'namaFaskes' => 'BAGOANG',
            ],
            [
                'kodeFaskes' => '10042301',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320106',
                'namaFaskes' => 'JONGGOL',
            ],
            [
                'kodeFaskes' => '10042303',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320106',
                'namaFaskes' => 'BALEKAMBANG',
            ],
            [
                'kodeFaskes' => '10042304',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320106',
                'namaFaskes' => 'SUKANEGARA',
            ],
            [
                'kodeFaskes' => '10041801',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320112',
                'namaFaskes' => 'KEMANG',
            ],
            [
                'kodeFaskes' => '10041804',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320112',
                'namaFaskes' => 'JAMPANG',
            ],
            [
                'kodeFaskes' => '10042202',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320132',
                'namaFaskes' => 'KLAPANUNGGAL',
            ],
            [
                'kodeFaskes' => '10042205',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320132',
                'namaFaskes' => 'BOJONG',
            ],
            [
                'kodeFaskes' => '10040801',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320114',
                'namaFaskes' => 'LEUWILIANG',
            ],
            [
                'kodeFaskes' => '10040803',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320114',
                'namaFaskes' => 'PURASEDA',
            ],
            [
                'kodeFaskes' => '10040802',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320139',
                'namaFaskes' => 'LEUWISADENG',
            ],
            [
                'kodeFaskes' => '10040804',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320139',
                'namaFaskes' => 'SADENGPASAR',
            ],
            [
                'kodeFaskes' => '10040102',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320126',
                'namaFaskes' => 'SUKAMANAH',
            ],
            [
                'kodeFaskes' => '10040103',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320126',
                'namaFaskes' => 'MEGAMENDUNG',
            ],
            [
                'kodeFaskes' => '10042901',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320121',
                'namaFaskes' => 'NANGGUNG',
            ],
            [
                'kodeFaskes' => '10042902',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320121',
                'namaFaskes' => 'CURUG BITUNG',
            ],
            [
                'kodeFaskes' => '10040702',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320117',
                'namaFaskes' => 'CIBENING',
            ],
            [
                'kodeFaskes' => '10040704',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320117',
                'namaFaskes' => 'PAMIJAHAN',
            ],
            [
                'kodeFaskes' => '10040706',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320117',
                'namaFaskes' => 'CIASMARA',
            ],
            [
                'kodeFaskes' => '10041301',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320110',
                'namaFaskes' => 'PARUNG',
            ],
            [
                'kodeFaskes' => '10041305',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320110',
                'namaFaskes' => 'COGREG',
            ],
            [
                'kodeFaskes' => '10041101',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320120',
                'namaFaskes' => 'PARUNG PANJANG',
            ],
            [
                'kodeFaskes' => '10041103',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320120',
                'namaFaskes' => 'DAGO',
            ],
            [
                'kodeFaskes' => '10041802',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320134',
                'namaFaskes' => 'RANCABUNGUR',
            ],
            [
                'kodeFaskes' => '10041803',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320134',
                'namaFaskes' => 'BANTARJAYA',
            ],
            [
                'kodeFaskes' => '10040501',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320118',
                'namaFaskes' => 'RUMPIN',
            ],
            [
                'kodeFaskes' => '10040502',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320118',
                'namaFaskes' => 'CICANGKAL',
            ],
            [
                'kodeFaskes' => '10040503',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320118',
                'namaFaskes' => 'GOBANG',
            ],
            [
                'kodeFaskes' => '10040903',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320135',
                'namaFaskes' => 'SUKAJAYA',
            ],
            [
                'kodeFaskes' => '10040905',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320135',
                'namaFaskes' => 'KIARAPANDAK',
            ],
            [
                'kodeFaskes' => '10042302',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320109',
                'namaFaskes' => 'SUKAMAKMUR',
            ],
            [
                'kodeFaskes' => '10042305',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320109',
                'namaFaskes' => 'SUKADAMAI',
            ],
            [
                'kodeFaskes' => '10041901',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320104',
                'namaFaskes' => 'CIMANDALA',
            ],
            [
                'kodeFaskes' => '10041902',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320104',
                'namaFaskes' => 'SUKARAJA',
            ],
            [
                'kodeFaskes' => '10041903',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320104',
                'namaFaskes' => 'CILEBUT',
            ],
            [
                'kodeFaskes' => '10041603',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320137',
                'namaFaskes' => 'TAJUR HALANG',
            ],
            [
                'kodeFaskes' => '10040403',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320131',
                'namaFaskes' => 'SIRNAGALIH',
            ],
            [
                'kodeFaskes' => '10040407',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320131',
                'namaFaskes' => 'TAMAN SARI',
            ],
            [
                'kodeFaskes' => '10040414',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320131',
                'namaFaskes' => 'SUKARESMI',
            ],
            [
                'kodeFaskes' => '10042402',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320136',
                'namaFaskes' => 'TANJUNG SARI',
            ],
            [
                'kodeFaskes' => '10041102',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320123',
                'namaFaskes' => 'TENJO',
            ],
            [
                'kodeFaskes' => '10041104',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320123',
                'namaFaskes' => 'PASAR REBO',
            ],
            [
                'kodeFaskes' => '10040603',
                'kodePropinsi' => '32',
                'kodeKabupaten' => '3201',
                'kodeKecamatan' => '320140',
                'namaFaskes' => 'TENJOLAYA',
            ],
        ];

        $now = now();

        DB::transaction(function () use ($faskes, $now) {

            foreach ($faskes as $item) {

                /*
                 * Username:
                 * BABAKAN MADANG -> babakanmadang
                 * BOJONG GEDE    -> bojonggede
                 * PASIR ANGIN    -> pasirangin
                 */
                $username = strtolower(
                    preg_replace(
                        '/\s+/',
                        '',
                        trim($item['namaFaskes'])
                    )
                );

                /*
                 * Email wajib unik.
                 */
                $email = $username . '@faskes.local';

                /*
                 * Cari berdasarkan kodeFaskes.
                 * Jika sudah ada -> update.
                 * Jika belum ada -> insert.
                 */
                $existing = DB::table('users_app')
                    ->where(
                        'kodeFaskes',
                        $item['kodeFaskes']
                    )
                    ->first();

                $data = [
                    'username' => $username,
                    'groupid' => 3,
                    'role_id' => 3,
                    'email' => $email,
                    'namalengkap' => 'Puskesmas ' . $item['namaFaskes'],
                    'kodeFaskes' => $item['kodeFaskes'],
                    'namaFaskes' => $item['namaFaskes'],
                    'kodePropinsi' => $item['kodePropinsi'],
                    'kodeKota' => $item['kodeKabupaten'],
                    'kodeKecamatan' => $item['kodeKecamatan'],
                    'role' => 'faskes',
                    'updated_at' => $now,
                ];

                /*
                 * Password hanya dibuat saat user baru.
                 * Jika user sudah ada, password existing
                 * tidak diubah.
                 */
                if ($existing) {

                    DB::table('users_app')
                        ->where(
                            'userid',
                            $existing->userid
                        )
                        ->update($data);

                } else {

                    $data['userid'] = (string) Str::uuid();

                    $data['password'] = Hash::make(
                        '1Sampai8'
                    );

                    $data['api_token'] = null;

                    $data['created_at'] = $now;

                    DB::table('users_app')
                        ->insert($data);
                }
            }
        });

        $this->command?->info(
            'Seeder users_app berhasil memproses ' .
            count($faskes) .
            ' akun Puskesmas.'
        );
    }
}

