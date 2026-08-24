<?php

namespace App\Http\Controllers\AdminPanel\UserPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\UserPanel\UserApp;
use App\Models\Master\MasterFaskes;
use App\Models\UserGroups;
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
    | CURRENT USER
    |--------------------------------------------------------------------------
    */

    private function currentUser()
    {
        return Auth::user();
    }


    /*
    |--------------------------------------------------------------------------
    | IS GROUP 3
    |--------------------------------------------------------------------------
    */

    private function isGroup3(): bool
    {
        $user = $this->currentUser();

        return $user && (int) $user->groupid === 3;
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
        | RESTRICTION GROUP 3
        |--------------------------------------------------------------------------
        |
        | Group 3 hanya boleh melihat:
        |
        | group 3, 4, 5
        |
        | dengan kodeFaskes yang sama dengan user login.
        |
        */

        if ($this->isGroup3()) {

            $authUser = $this->currentUser();

            $query->whereIn(
                'groupid',
                [3, 4, 5, 6]
            );

            $query->where(
                'kodeFaskes',
                $authUser->kodeFaskes
            );
        }


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
        | GROUP FILTER
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
        | ROLE FILTER
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


        /*
        |--------------------------------------------------------------------------
        | EXECUTE
        |--------------------------------------------------------------------------
        */

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

                'group_id' =>
                    $user->groupid,

                'group_name' =>
                    optional($user->group)->group_name,

                'role_id' =>
                    $user->role_id,

                'role_name' =>
                    optional($user->roleData)->role_name,

                'kodeFaskes' =>
                    $user->kodeFaskes,

                'namaFaskes' =>
                    optional($user->faskes)->namaFaskes
                    ?? $user->namaFaskes,

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


        /*
        |--------------------------------------------------------------------------
        | SECURITY GROUP 3
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3()) {

            $authUser = $this->currentUser();

            abort_unless(
                in_array((int) $user->groupid, [3, 4, 5, 6], true)
                &&
                $user->kodeFaskes === $authUser->kodeFaskes,
                403,
                'Anda tidak memiliki akses ke user ini.'
            );
        }


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
    | GROUP OPTIONS
    |--------------------------------------------------------------------------
    */

    public function groups()
    {



    $query = UserGroups::query();

        /*
        |--------------------------------------------------------------------------
        | GROUP 3
        |--------------------------------------------------------------------------
        |
        | Group 3 hanya boleh membuat group 4 dan 5.
        |
        */

        if ($this->isGroup3()) {

            $query->whereIn(
                'group_id',
                [4, 5, 6]
            );
        }

        return response()->json([

            'success' => true,

            'data' =>
                $query
                    ->orderBy('group_id')
                    ->get(),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        if ((int) auth()->user()->groupid === 3) {

    if (!in_array((int) $request->groupid, [4, 5, 6], true)) {

        return response()->json([
            'message' => 'User Group 3 hanya dapat mengelola user Group 4, Group 5, atau Group 6.'
        ], 403);
    }

    $request->merge([
        'kodePropinsi'  => auth()->user()->kodePropinsi,
        'kodeKota'      => auth()->user()->kodeKota,
        'kodeKecamatan' => auth()->user()->kodeKecamatan,
        'kodeFaskes'    => auth()->user()->kodeFaskes,
    ]);
}


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


        $data =
            $validator->validated();


        /*
        |--------------------------------------------------------------------------
        | SECURITY GROUP 3
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3()) {

            $authUser =
                $this->currentUser();


            /*
            |--------------------------------------------------------------------------
            | GROUP
            |--------------------------------------------------------------------------
            */

            if (!in_array(
                (int) $data['groupid'],
                [4, 5, 6],
                true
            )) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'User Group 3 hanya dapat membuat user Group 4 atau Group 5.',

                ], 403);
            }


            /*
            |--------------------------------------------------------------------------
            | PENEMPATAN
            |--------------------------------------------------------------------------
            |
            | Paksa menggunakan penempatan user login.
            |
            */

            $data['kodePropinsi'] =
                $authUser->kodePropinsi;

            $data['kodeKota'] =
                $authUser->kodeKota;

            $data['kodeKecamatan'] =
                $authUser->kodeKecamatan;

            $data['kodeFaskes'] =
                $authUser->kodeFaskes;
        }


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
        | CREATE
        |--------------------------------------------------------------------------
        */

        $user =
            UserApp::create($data);


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
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
            newValues:
                $this->activityData($user)
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
        | SECURITY
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3()) {

            $authUser =
                $this->currentUser();


            abort_unless(
                in_array(
                    (int) $user->groupid,
                    [3, 4, 5, 6],
                    true
                )
                &&
                $user->kodeFaskes ===
                    $authUser->kodeFaskes,
                403,
                'Anda tidak memiliki akses untuk mengubah user ini.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | OLD VALUES
        |--------------------------------------------------------------------------
        */

        $oldValues =
            $this->activityData($user);


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

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
        | SECURITY GROUP 3
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3()) {

            $authUser =
                $this->currentUser();


            /*
            |--------------------------------------------------------------------------
            | GROUP YANG BOLEH DIKELOLA
            |--------------------------------------------------------------------------
            */

            if (!in_array(
                (int) $data['groupid'],
                [4, 5, 6],
                true
            )
            &&
            (int) $user->groupid !== 3) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'User Group 3 hanya dapat mengelola Group 4, Group 5, atau Group 6.',

                ], 403);
            }


            /*
            |--------------------------------------------------------------------------
            | PENEMPATAN DIPAKSA
            |--------------------------------------------------------------------------
            */

            $data['kodePropinsi'] =
                $authUser->kodePropinsi;

            $data['kodeKota'] =
                $authUser->kodeKota;

            $data['kodeKecamatan'] =
                $authUser->kodeKecamatan;

            $data['kodeFaskes'] =
                $authUser->kodeFaskes;
        }


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
        | FASKES NAME
        |--------------------------------------------------------------------------
        */

        $this->setFaskesName($data);


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $user->update($data);

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
            newValues:
                $this->activityData($user)
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
        | SECURITY GROUP 3
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3()) {

            $authUser =
                $this->currentUser();


            abort_unless(
                in_array(
                    (int) $user->groupid,
                    [3, 4, 5, 6],
                    true
                )
                &&
                $user->kodeFaskes ===
                    $authUser->kodeFaskes,
                403,
                'Anda tidak memiliki akses untuk menghapus user ini.'
            );
        }


        $oldValues =
            $this->activityData($user);


        $userName =
            $user->username;

        $fullName =
            $user->namalengkap;


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


        /*
        |--------------------------------------------------------------------------
        | GROUP 3
        |--------------------------------------------------------------------------
        |
        | Hanya boleh melihat faskes miliknya.
        |
        */

        if ($this->isGroup3()) {

            $authUser =
                $this->currentUser();


            $query->where(
                'kodeFaskes',
                $authUser->kodeFaskes
            );
        }
        else {

            $query->where(
                'kodeKecamatan',
                $request->kecamatan
            );
        }


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
