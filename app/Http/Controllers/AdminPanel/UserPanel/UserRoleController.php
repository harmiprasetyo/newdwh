<?php

namespace App\Http\Controllers\AdminPanel\UserPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPanel\UserRole;
use App\Models\UserPanel\UserGroup;
use App\Services\ActivityLogService;

class UserRoleController extends Controller
{
    /**
     * Halaman utama User Role
     */
    public function index()
    {
        return view('adminpanel.userpanel.roles.index');
    }


    /**
     * DataTable
     */
    public function datatable(Request $request)
    {
        $query = UserRole::with('group');

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'role_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('group', function ($q) use ($search) {

                    $q->where(
                        'group_name',
                        'like',
                        "%{$search}%"
                    );

                });

            });

        }


        $data = $query
            ->orderBy('groupId')
            ->orderBy('role_name')
            ->get();


        return response()->json([

            'data' => $data,

            'total' => $data->count(),

        ]);
    }


    /**
     * Detail role
     */
    public function show($id)
    {
        $role = UserRole::with('group')
            ->findOrFail($id);

        return response()->json([

            'data' => $role

        ]);
    }


    /**
     * Store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'role_name' => [
                'required',
                'string',
                'max:255'
            ],

            'groupId' => [
                'required',
                'integer',
                'exists:usergroups,group_id'
            ],

        ], [

            'role_name.required' =>
                'Nama role wajib diisi.',

            'groupId.required' =>
                'Group wajib dipilih.',

            'groupId.exists' =>
                'Group yang dipilih tidak ditemukan.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        $role = UserRole::create([

            'role_name' =>
                $validated['role_name'],

            'groupId' =>
                $validated['groupId'],

        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        ActivityLogService::log(

            action: 'create',

            module: 'User Role',

            description:
                'Menambahkan user role "' .
                $role->role_name .
                '"',

            subject: $role,

            newValues: $role->toArray()

        );


        return response()->json([

            'status' => true,

            'message' =>
                'User role berhasil ditambahkan.',

            'data' => $role,

        ], 201);
    }


    /**
     * Update
     */
    public function update(
        Request $request,
        $id
    ) {

        $role = UserRole::findOrFail($id);


        $validated = $request->validate([

            'role_name' => [
                'required',
                'string',
                'max:255'
            ],

            'groupId' => [
                'required',
                'integer',
                'exists:usergroups,group_id'
            ],

        ], [

            'role_name.required' =>
                'Nama role wajib diisi.',

            'groupId.required' =>
                'Group wajib dipilih.',

            'groupId.exists' =>
                'Group yang dipilih tidak ditemukan.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA LAMA
        |--------------------------------------------------------------------------
        */

        $oldValues =
            $role->getOriginal();


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $role->update([

            'role_name' =>
                $validated['role_name'],

            'groupId' =>
                $validated['groupId'],

        ]);


        /*
        |--------------------------------------------------------------------------
        | DATA BARU
        |--------------------------------------------------------------------------
        */

        $newValues =
            $role->fresh()->toArray();


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        ActivityLogService::log(

            action: 'update',

            module: 'User Role',

            description:
                'Mengubah user role "' .
                $role->role_name .
                '"',

            subject: $role,

            oldValues: $oldValues,

            newValues: $newValues

        );


        return response()->json([

            'status' => true,

            'message' =>
                'User role berhasil diperbarui.',

            'data' =>
                $role->fresh(),

        ]);
    }


    /**
     * Delete
     */
    public function destroy($id)
    {
        $role = UserRole::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA SEBELUM DELETE
        |--------------------------------------------------------------------------
        */

        $oldValues =
            $role->toArray();


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        $role->delete();


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        |
        | Jangan mengirim $role sebagai subject setelah delete
        | jika model sudah tidak ada secara database.
        |
        */

        ActivityLogService::log(

            action: 'delete',

            module: 'User Role',

            description:
                'Menghapus user role ' .
                ($oldValues['role_name'] ?? '-'),

            subject: null,

            oldValues: $oldValues,

            newValues: null

        );


        return response()->json([

            'status' => true,

            'message' =>
                'User role berhasil dihapus.',

        ]);
    }


    /**
     * List Group
     *
     * Digunakan oleh combo Group
     */
    public function groups()
    {
        return response()->json([

            'data' =>
                UserGroup::orderBy('group_name')
                    ->get([
                        'group_id',
                        'group_name'
                    ])

        ]);
    }




    /**
 * Get roles by group
 */
public function rolesByGroup(Request $request)
{
    $request->validate([
        'groupid' => [
            'required',
            'exists:usergroups,group_id'
        ],
    ]);

    $roles = UserRole::query()
        ->where('groupId', $request->groupid)
        ->orderBy('role_name')
        ->get([
            'id',
            'role_name',
            'groupId',
        ]);

    return response()->json([
        'success' => true,
        'data' => $roles,
    ]);
}
}
