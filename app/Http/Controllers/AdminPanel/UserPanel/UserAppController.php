<?php

namespace App\Http\Controllers\AdminPanel\UserPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\UserPanel\UserApp;
use App\Models\Master\MasterFaskes;
use App\Services\ActivityLogService;

class UserAppController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('adminpanel.userpanel.users.index');
    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    public function datatable(Request $request)
    {
        $query = UserApp::query()
            ->with([
                'group',
                'roleData',
                'province',
                'city',
                'district',
                'faskes',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'username',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'namalengkap',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | GROUP
        |--------------------------------------------------------------------------
        */

        if ($request->filled('groupid')) {

            $query->where(
                'groupid',
                $request->groupid
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role_id')) {

            $query->where(
                'role_id',
                $request->role_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PROVINSI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kodePropinsi')) {

            $query->where(
                'kodePropinsi',
                $request->kodePropinsi
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KOTA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kodeKota')) {

            $query->where(
                'kodeKota',
                $request->kodeKota
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KECAMATAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kodeKecamatan')) {

            $query->where(
                'kodeKecamatan',
                $request->kodeKecamatan
            );
        }


        $users = $query
            ->orderBy('namalengkap')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        $data = $users->map(function ($user) {

            return [

                'userid' =>
                    $user->userid,

                'username' =>
                    $user->username,

                'namalengkap' =>
                    $user->namalengkap,

                'email' =>
                    $user->email,


                /*
                |--------------------------------------------------------------------------
                | GROUP
                |--------------------------------------------------------------------------
                */

                'group_id' =>
                    $user->groupid,

                'group_name' =>
                    optional($user->group)->group_name,


                /*
                |--------------------------------------------------------------------------
                | ROLE
                |--------------------------------------------------------------------------
                */

                'role_id' =>
                    $user->role_id,

                'role_name' =>
                    optional($user->roleData)->role_name,


                /*
                |--------------------------------------------------------------------------
                | FASKES
                |--------------------------------------------------------------------------
                */

                'kodeFaskes' =>
                    $user->kodeFaskes,

                'namaFaskes' =>
                    optional($user->faskes)->namaFaskes
                    ?? $user->namaFaskes,


                /*
                |--------------------------------------------------------------------------
                | WILAYAH
                |--------------------------------------------------------------------------
                */

                'kodePropinsi' =>
                    $user->kodePropinsi,

                'provinsi_name' =>
                    optional($user->province)->name,

                'kodeKota' =>
                    $user->kodeKota,

                'kota_name' =>
                    optional($user->city)->name,

                'kodeKecamatan' =>
                    $user->kodeKecamatan,

                'kecamatan_name' =>
                    optional($user->district)->name,


                /*
                |--------------------------------------------------------------------------
                | LEGACY ROLE
                |--------------------------------------------------------------------------
                */

                'role' =>
                    $user->role,


                'created_at' =>
                    optional($user->created_at)
                        ->format('d-m-Y H:i'),

            ];
        });


        return response()->json([

            'success' => true,

            'total' =>
                $data->count(),

            'data' =>
                $data,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $user = UserApp::with([
            'group',
            'roleData',
            'province',
            'city',
            'district',
            'faskes',
        ])->findOrFail($id);


        return response()->json([

            'success' => true,

            'data' => [

                'userid' =>
                    $user->userid,

                'username' =>
                    $user->username,

                'namalengkap' =>
                    $user->namalengkap,

                'email' =>
                    $user->email,

                'groupid' =>
                    $user->groupid,

                'role_id' =>
                    $user->role_id,

                'role' =>
                    $user->role,

                'kodePropinsi' =>
                    $user->kodePropinsi,

                'kodeKota' =>
                    $user->kodeKota,

                'kodeKecamatan' =>
                    $user->kodeKecamatan,

                'kodeFaskes' =>
                    $user->kodeFaskes,

                'namaFaskes' =>
                    optional($user->faskes)->namaFaskes
                    ?? $user->namaFaskes,

            ],

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [

                'username' =>
                    'required|string|max:255|unique:users_app,username',

                'email' =>
                    'required|email|max:255|unique:users_app,email',

                'namalengkap' =>
                    'required|string|max:255',

                'groupid' =>
                    'required|exists:usergroups,group_id',

                'role_id' =>
                    'nullable|exists:user_roles,id',

                'password' =>
                    'required|string|min:6',

                'kodePropinsi' =>
                    'nullable',

                'kodeKota' =>
                    'nullable',

                'kodeKecamatan' =>
                    'nullable',

                'kodeFaskes' =>
                    'nullable|string|max:255',

                'role' =>
                    'nullable|string|max:255',

            ]
        );


        if ($validator->fails()) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Validasi gagal.',

                'errors' =>
                    $validator->errors(),

            ], 422);
        }


        $data = $validator->validated();


        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        $data['password'] =
            Hash::make(
                $data['password']
            );


        /*
        |--------------------------------------------------------------------------
        | NAMA FASKES
        |--------------------------------------------------------------------------
        */

        $this->setFaskesName($data);


        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = UserApp::create($data);


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        |
        | subject = UserApp MODEL
        | oldValues = null
        | newValues = array
        |
        */

        ActivityLogService::log(
            action: 'create',
            module: 'User',
            description:
                'Menambahkan user: ' .
                $user->namalengkap .
                ' (' .
                $user->username .
                ')',
            subject: $user,
            oldValues: null,
            newValues: $this->activityData($user)
        );


        return response()->json([

            'success' => true,

            'message' =>
                'User berhasil ditambahkan.',

            'data' =>
                $user,

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $user =
            UserApp::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA LAMA
        |--------------------------------------------------------------------------
        */

        $oldValues =
            $this->activityData($user);


        $validator = Validator::make(
            $request->all(),
            [

                'username' =>
                    'required|string|max:255|unique:users_app,username,' .
                    $user->userid .
                    ',userid',

                'email' =>
                    'required|email|max:255|unique:users_app,email,' .
                    $user->userid .
                    ',userid',

                'namalengkap' =>
                    'required|string|max:255',

                'groupid' =>
                    'required|exists:usergroups,group_id',

                'role_id' =>
                    'nullable|exists:user_roles,id',

                'password' =>
                    'nullable|string|min:6',

                'kodePropinsi' =>
                    'nullable',

                'kodeKota' =>
                    'nullable',

                'kodeKecamatan' =>
                    'nullable',

                'kodeFaskes' =>
                    'nullable|string|max:255',

                'role' =>
                    'nullable|string|max:255',

            ]
        );


        if ($validator->fails()) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Validasi gagal.',

                'errors' =>
                    $validator->errors(),

            ], 422);
        }


        $data =
            $validator->validated();


        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!empty($data['password'])) {

            $data['password'] =
                Hash::make(
                    $data['password']
                );

        } else {

            unset(
                $data['password']
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NAMA FASKES
        |--------------------------------------------------------------------------
        */

        $this->setFaskesName($data);


        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */

        $user->update($data);


        /*
        |--------------------------------------------------------------------------
        | REFRESH MODEL
        |--------------------------------------------------------------------------
        */

        $user->refresh();


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        ActivityLogService::log(
            action: 'update',
            module: 'User',
            description:
                'Mengubah user: ' .
                $user->namalengkap .
                ' (' .
                $user->username .
                ')',
            subject: $user,
            oldValues: $oldValues,
            newValues: $this->activityData($user)
        );


        return response()->json([

            'success' => true,

            'message' =>
                'User berhasil diperbarui.',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $user =
            UserApp::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA SEBELUM DELETE
        |--------------------------------------------------------------------------
        */

        $oldValues =
            $this->activityData($user);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN INFORMASI UNTUK DESKRIPSI
        |--------------------------------------------------------------------------
        */

        $userName =
            $user->username;

        $fullName =
            $user->namalengkap;


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        |
        | PENTING:
        | subject tetap MODEL UserApp.
        |
        | Jangan gunakan:
        |
        | subject: $oldValues
        |
        | karena $oldValues adalah ARRAY.
        |
        */

        ActivityLogService::log(
            action: 'delete',
            module: 'User',
            description:
                'Menghapus user: ' .
                $fullName .
                ' (' .
                $userName .
                ')',
            subject: $user,
            oldValues: $oldValues,
            newValues: null
        );


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        $user->delete();


        return response()->json([

            'success' => true,

            'message' =>
                'User berhasil dihapus.',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    public function faskes(Request $request)
    {
        $request->validate([
            'kecamatan' =>
                'required|string',
        ]);


        $query =
            MasterFaskes::query();


        $query->where(
            'kodeKecamatan',
            $request->kecamatan
        );


        $data =
            $query
                ->orderBy('namaFaskes')
                ->get([
                    'kodeFaskes',
                    'namaFaskes',
                    'kodeKecamatan',
                ]);


        return response()->json([

            'success' => true,

            'data' => $data,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SET FASKES NAME
    |--------------------------------------------------------------------------
    */

    private function setFaskesName(
        array &$data
    ): void {

        if (
            empty($data['kodeFaskes'])
        ) {

            $data['namaFaskes'] = null;

            return;
        }


        $faskes =
            MasterFaskes::where(
                'kodeFaskes',
                $data['kodeFaskes']
            )->first();


        if ($faskes) {

            $data['namaFaskes'] =
                $faskes->namaFaskes;

        } else {

            $data['namaFaskes'] =
                null;

        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG DATA
    |--------------------------------------------------------------------------
    |
    | Data yang disimpan ke old_values / new_values.
    |
    | Password dan api_token sengaja tidak dicatat.
    |
    */

    private function activityData(
        UserApp $user
    ): array {

        return [

            'userid' =>
                $user->userid,

            'username' =>
                $user->username,

            'namalengkap' =>
                $user->namalengkap,

            'email' =>
                $user->email,

            'groupid' =>
                $user->groupid,

            'role_id' =>
                $user->role_id,

            'role' =>
                $user->role,

            'kodePropinsi' =>
                $user->kodePropinsi,

            'kodeKota' =>
                $user->kodeKota,

            'kodeKecamatan' =>
                $user->kodeKecamatan,

            'kodeFaskes' =>
                $user->kodeFaskes,

            'namaFaskes' =>
                $user->namaFaskes,

        ];
    }
}
