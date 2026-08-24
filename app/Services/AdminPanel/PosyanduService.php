<?php

namespace App\Services\AdminPanel;

use App\Models\MasterPosyandu;
use App\Models\Master\MasterFaskes as MasterFasyankes;

use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use App\Services\ActivityLogService;

class PosyanduService
{
    /*
    |--------------------------------------------------------------------------
    | USER GROUP
    |--------------------------------------------------------------------------
    */

    public function isGroup3($user): bool
    {
        return (int) ($user->groupid ?? 0) === 3;
    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    public function getDatatableQuery($user)
    {
        $query = MasterPosyandu::query()

            ->leftJoin(
                'indonesia_provinces',
                'master_posyandu.province_code',
                '=',
                'indonesia_provinces.code'
            )

            ->leftJoin(
                'indonesia_cities',
                'master_posyandu.city_code',
                '=',
                'indonesia_cities.code'
            )

            ->leftJoin(
                'indonesia_districts',
                'master_posyandu.district_code',
                '=',
                'indonesia_districts.code'
            )

            ->leftJoin(
                'indonesia_villages',
                'master_posyandu.village_code',
                '=',
                'indonesia_villages.code'
            )

            ->leftJoin(
                'master_faskes',
                'master_posyandu.kodeFaskes',
                '=',
                'master_faskes.kodeFaskes'
            )

            ->select([
                'master_posyandu.*',

                'indonesia_provinces.name as province_name',
                'indonesia_cities.name as city_name',
                'indonesia_districts.name as district_name',
                'indonesia_villages.name as village_name',

                'master_faskes.namaFaskes',
            ]);

        /*
        |--------------------------------------------------------------------------
        | GROUP 3
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3($user)) {

            $query->where(
                'master_posyandu.kodeFaskes',
                $user->kodeFaskes
            );
        }

        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | MASTER FASKES USER LOGIN
    |--------------------------------------------------------------------------
    */

    public function getUserFaskes($user)
    {
        return MasterFasyankes::where(
            'kodeFaskes',
            $user->kodeFaskes
        )->first();
    }


    /*
    |--------------------------------------------------------------------------
    | PROVINCES
    |--------------------------------------------------------------------------
    */

    public function getProvinces()
    {
        return Province::orderBy('name')
            ->get([
                'code',
                'name',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CITIES
    |--------------------------------------------------------------------------
    */

    public function getCities(string $provinceCode)
    {
        return City::where(
            'province_code',
            $provinceCode
        )
        ->orderBy('name')
        ->get([
            'code',
            'name',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DISTRICTS
    |--------------------------------------------------------------------------
    */

    public function getDistricts(string $cityCode)
    {
        return District::where(
            'city_code',
            $cityCode
        )
        ->orderBy('name')
        ->get([
            'code',
            'name',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VILLAGES
    |--------------------------------------------------------------------------
    |
    | Ini penting:
    |
    | Group 3 hanya boleh melihat desa yang berada pada
    | kecamatan milik faskes user login.
    |
    */

    public function getVillages(
        string $districtCode,
        $user
    ) {
        if ($this->isGroup3($user)) {

            $faskes = $this->getUserFaskes($user);

            if (!$faskes) {
                return collect();
            }

            /*
            |--------------------------------------------------------------------------
            | Pastikan district yang diminta memang kecamatan faskes
            |--------------------------------------------------------------------------
            */

            if (
                (string) $districtCode !==
                (string) $faskes->kodeKecamatan
            ) {
                return collect();
            }

            $districtCode = $faskes->kodeKecamatan;
        }

        return Village::where(
            'district_code',
            $districtCode
        )
        ->orderBy('name')
        ->get([
            'code',
            'name',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    public function getFaskes(
        ?string $districtCode,
        $user
    ) {
        $query = MasterFasyankes::query();

        /*
        |--------------------------------------------------------------------------
        | GROUP 3
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3($user)) {

            $query->where(
                'kodeFaskes',
                $user->kodeFaskes
            );

        } else {

            if ($districtCode) {

                $query->where(
                    'kodeKecamatan',
                    $districtCode
                );
            }
        }

        return $query
            ->orderBy('namaFaskes')
            ->get([
                'kodeFaskes',
                'namaFaskes',
                'kodePropinsi',
                'kodeKota',
                'kodeKecamatan',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE DATA GROUP 3
    |--------------------------------------------------------------------------
    */




    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
public function store(array $data, $user)
{
    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if ($this->isGroup3($user)) {

        $faskes = $this->getUserFaskes($user);

        if (!$faskes) {

            throw new \RuntimeException(
                'Fasyankes user tidak ditemukan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan desa berada di kecamatan faskes user
        |--------------------------------------------------------------------------
        */

        $villageExists = Village::where(
                'code',
                $data['village_code']
            )
            ->where(
                'district_code',
                $faskes->kodeKecamatan
            )
            ->exists();

        if (!$villageExists) {

            throw new \InvalidArgumentException(
                'Desa tidak berada pada wilayah kerja fasyankes user.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Jangan percaya data wilayah dari browser
        |--------------------------------------------------------------------------
        */

        $data['province_code'] =
            $faskes->kodePropinsi;

        $data['city_code'] =
            $faskes->kodeKabupaten;

        $data['district_code'] =
            $faskes->kodeKecamatan;

        $data['kodeFaskes'] =
            $faskes->kodeFaskes;
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    return DB::transaction(function () use ($data) {

        $posyandu = MasterPosyandu::create([

            'province_code' =>
                $data['province_code'],

            'city_code' =>
                $data['city_code'],

            'district_code' =>
                $data['district_code'],

            'village_code' =>
                $data['village_code'],

            'kodeFaskes' =>
                $data['kodeFaskes'],

            'kodePosyandu' =>
                $data['kodePosyandu'],

            'namaPosyandu' =>
                $data['namaPosyandu'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG - CREATE
        |--------------------------------------------------------------------------
        */

        ActivityLogService::log(

            action: 'create',

            module: 'Posyandu',

            description:
                'Menambahkan data Posyandu ' .
                $posyandu->namaPosyandu .
                ' (' .
                $posyandu->kodePosyandu .
                ')',

            subject: $posyandu,

            oldValues: null,

            newValues: $posyandu->toArray()

        );


        return $posyandu;
    });
}


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

   public function delete($id, $user)
{
    $query = MasterPosyandu::where(
        'id',
        $id
    );


    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if ($this->isGroup3($user)) {

        $query->where(
            'kodeFaskes',
            $user->kodeFaskes
        );
    }


    $posyandu = $query->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | Simpan data sebelum delete
    |--------------------------------------------------------------------------
    */

    $oldValues = $posyandu->toArray();


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $deleted = $posyandu->delete();


    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG - DELETE
    |--------------------------------------------------------------------------
    */

    if ($deleted) {

        ActivityLogService::log(

            action: 'delete',

            module: 'Posyandu',

            description:
                'Menghapus data Posyandu ' .
                $posyandu->namaPosyandu .
                ' (' .
                $posyandu->kodePosyandu .
                ')',

            subject: $posyandu,

            oldValues: $oldValues,

            newValues: null

        );
    }


    return $deleted;
}



    /*
|--------------------------------------------------------------------------
| WILAYAH FASKES
|--------------------------------------------------------------------------
*/

public function getFaskesWilayah($faskes)
{
    if (!$faskes) {
        return null;
    }

    $province = Province::where(
        'code',
        $faskes->kodePropinsi
    )->first([
        'code',
        'name'
    ]);

    $city = City::where(
        'code',
       $faskes->kodeKabupaten
    )->first([
        'code',
        'name'
    ]);

    $district = District::where(
        'code',
        $faskes->kodeKecamatan
    )->first([
        'code',
        'name'
    ]);

    return [
        'kodePropinsi' => $faskes->kodePropinsi,
        'namaPropinsi' => $province?->name,

        'kodeKota' =>$faskes->kodeKabupaten,
        'namaKota' => $city?->name,

        'kodeKecamatan' => $faskes->kodeKecamatan,
        'namaKecamatan' => $district?->name,

        'kodeFaskes' => $faskes->kodeFaskes,
        'namaFaskes' => $faskes->namaFaskes,
    ];
}



/*
|--------------------------------------------------------------------------
| CREATE DATA
|--------------------------------------------------------------------------
*/

public function getCreateData($user)
{
    if (!$this->isGroup3($user)) {

        return [
            'isGroup3' => false,
            'faskes' => null,
            'location' => null,
        ];
    }

    $faskes = $this->getUserFaskes($user);

    if (!$faskes) {

        return [
            'isGroup3' => true,
            'faskes' => null,
            'location' => null,
        ];
    }

    $province = Province::where(
        'code',
        $faskes->kodePropinsi
    )->first([
        'code',
        'name',
    ]);

    $city = City::where(
        'code',
       $faskes->kodeKabupaten
    )->first([
        'code',
        'name',
    ]);

    $district = District::where(
        'code',
        $faskes->kodeKecamatan
    )->first([
        'code',
        'name',
    ]);

    return [
        'isGroup3' => true,

        'faskes' => $faskes,

        'location' => [
            'province' => $province,
            'city' => $city,
            'district' => $district,
        ],
    ];
}




/*
|--------------------------------------------------------------------------
| EDIT DATA
|--------------------------------------------------------------------------
*/

public function getEditData($id, $user)
{
    $query = MasterPosyandu::query()
        ->where('master_posyandu.id', $id);

    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if ($this->isGroup3($user)) {

        $query->where(
            'master_posyandu.kodeFaskes',
            $user->kodeFaskes
        );
    }

    $posyandu = $query->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | LOCATION
    |--------------------------------------------------------------------------
    */

    $province = Province::where(
        'code',
        $posyandu->province_code
    )->first([
        'code',
        'name',
    ]);

    $city = City::where(
        'code',
        $posyandu->city_code
    )->first([
        'code',
        'name',
    ]);

    $district = District::where(
        'code',
        $posyandu->district_code
    )->first([
        'code',
        'name',
    ]);

    $village = Village::where(
        'code',
        $posyandu->village_code
    )->first([
        'code',
        'name',
    ]);

    $faskes = MasterFasyankes::where(
        'kodeFaskes',
        $posyandu->kodeFaskes
    )->first();


    return [

        'posyandu' => $posyandu,

        'isGroup3' => $this->isGroup3($user),

        'faskes' => $faskes,

        'location' => [

            'province' => $province,

            'city' => $city,

            'district' => $district,

            'village' => $village,

        ],

    ];
}

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

public function update(
    $id,
    array $data,
    $user
) {
    $query = MasterPosyandu::where(
        'id',
        $id
    );


    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    */

    if ($this->isGroup3($user)) {

        $query->where(
            'kodeFaskes',
            $user->kodeFaskes
        );
    }


    $posyandu = $query->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | OLD VALUES
    |--------------------------------------------------------------------------
    */

    $oldValues = $posyandu->getOriginal();


    /*
    |--------------------------------------------------------------------------
    | GROUP 3
    |--------------------------------------------------------------------------
    |
    | Wilayah tidak boleh diubah oleh browser.
    |
    */

    if ($this->isGroup3($user)) {

        $faskes = $this->getUserFaskes($user);

        if (!$faskes) {

            throw new \RuntimeException(
                'Fasyankes user tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi desa
        |--------------------------------------------------------------------------
        */

        $villageExists = Village::where(
                'code',
                $data['village_code']
            )
            ->where(
                'district_code',
                $faskes->kodeKecamatan
            )
            ->exists();

        if (!$villageExists) {

            throw new \InvalidArgumentException(
                'Desa tidak berada pada wilayah kerja fasyankes user.'
            );
        }


        $data['province_code'] =
            $faskes->kodePropinsi;

        $data['city_code'] =
            $faskes->kodeKabupaten;

        $data['district_code'] =
            $faskes->kodeKecamatan;

        $data['kodeFaskes'] =
            $faskes->kodeFaskes;
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    $posyandu->update([

        'province_code' =>
            $data['province_code'],

        'city_code' =>
            $data['city_code'],

        'district_code' =>
            $data['district_code'],

        'village_code' =>
            $data['village_code'],

        'kodeFaskes' =>
            $data['kodeFaskes'],

        'kodePosyandu' =>
            $data['kodePosyandu'],

        'namaPosyandu' =>
            $data['namaPosyandu'],
    ]);


    /*
    |--------------------------------------------------------------------------
    | NEW VALUES
    |--------------------------------------------------------------------------
    */

    $newValues = $posyandu->fresh()->toArray();


    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG - UPDATE
    |--------------------------------------------------------------------------
    */

    ActivityLogService::log(

        action: 'update',

        module: 'Posyandu',

        description:
            'Mengubah data Posyandu ' .
            $posyandu->namaPosyandu .
            ' (' .
            $posyandu->kodePosyandu .
            ')',

        subject: $posyandu,

        oldValues: $oldValues,

        newValues: $newValues

    );


    return $posyandu;
}
}
