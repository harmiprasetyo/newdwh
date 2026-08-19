<?php

namespace App\Http\Controllers\AdminPanel\UserPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\UserPanel\UserGroup;
use App\Services\ActivityLogService;

class UserGroupController extends Controller
{
    /**
     * Halaman utama
     */
    public function index()
    {
        return view(
            'adminpanel.userpanel.groups.index'
        );
    }


    /**
     * Data untuk DataTables
     */
    public function datatable(Request $request)
    {
        $query = UserGroup::query()
            ->withCount([
                'roles',
                'users'
            ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(
                'group_name',
                'like',
                "%{$search}%"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ORDER
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            'group_id',
            'desc'
        );


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $limit = min(
            max((int) $request->get('limit', 25), 1),
            100
        );

        $page = max(
            (int) $request->get('page', 1),
            1
        );


        $total = $query->count();

        $data = $query
            ->forPage($page, $limit)
            ->get();


        return response()->json([

            'data' => $data,

            'current_page' => $page,

            'per_page' => $limit,

            'total' => $total,

            'last_page' => $total > 0
                ? (int) ceil($total / $limit)
                : 1,

        ]);
    }


    /**
     * Store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'group_name' => [
                'required',
                'string',
                'max:255',
                'unique:usergroups,group_name'
            ],

        ], [

            'group_name.required' =>
                'Nama group wajib diisi.',

            'group_name.unique' =>
                'Nama group sudah digunakan.',

        ]);


        DB::beginTransaction();

        try {

            $group = UserGroup::create([
                'group_name' => $validated['group_name'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(
                action: 'create',
                module: 'User Group',
                description: 'Menambahkan user group',
                subject: $group,
                newValues: $group->toArray()
            );


            DB::commit();


            return response()->json([

                'status' => true,

                'message' =>
                    'User group berhasil ditambahkan.',

                'data' => $group,

            ]);


        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([

                'status' => false,

                'message' =>
                    'Gagal menambahkan user group.',

            ], 500);
        }
    }


    /**
     * Show
     */
    public function show($id)
    {
        $group = UserGroup::findOrFail($id);

        return response()->json([

            'status' => true,

            'data' => $group,

        ]);
    }


    /**
     * Update
     */
    public function update(
        Request $request,
        $id
    ) {

        $group = UserGroup::findOrFail($id);


        $validated = $request->validate([

            'group_name' => [
                'required',
                'string',
                'max:255',
                'unique:usergroups,group_name,' .
                $group->group_id .
                ',group_id'
            ],

        ], [

            'group_name.required' =>
                'Nama group wajib diisi.',

            'group_name.unique' =>
                'Nama group sudah digunakan.',

        ]);


        $oldValues = $group->toArray();


        DB::beginTransaction();

        try {

            $group->update([

                'group_name' =>
                    $validated['group_name'],

            ]);


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(
                action: 'update',
                module: 'User Group',
                description: 'Mengubah user group',
                subject: $group,
                oldValues: $oldValues,
                newValues: $group->fresh()->toArray()
            );


            DB::commit();


            return response()->json([

                'status' => true,

                'message' =>
                    'User group berhasil diperbarui.',

                'data' => $group->fresh(),

            ]);


        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([

                'status' => false,

                'message' =>
                    'Gagal memperbarui user group.',

            ], 500);
        }
    }


    /**
     * Delete
     */
    public function destroy($id)
    {
        $group = UserGroup::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | CEK USER
        |--------------------------------------------------------------------------
        */

        $userCount = $group->users()->count();

        if ($userCount > 0) {

            return response()->json([

                'status' => false,

                'message' =>
                    "Group tidak dapat dihapus karena masih digunakan oleh {$userCount} user.",

            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | OLD VALUES
        |--------------------------------------------------------------------------
        */

        $oldValues = $group->toArray();


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | DELETE ROLES
            |--------------------------------------------------------------------------
            |
            | Karena user_roles.groupId memiliki ON DELETE CASCADE,
            | sebenarnya database akan menangani roles.
            |
            */

            $group->delete();


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(
                action: 'delete',
                module: 'User Group',
                description: 'Menghapus user group',
                subject: $group,
                oldValues: $oldValues
            );


            DB::commit();


            return response()->json([

                'status' => true,

                'message' =>
                    'User group berhasil dihapus.',

            ]);


        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([

                'status' => false,

                'message' =>
                    'Gagal menghapus user group.',

            ], 500);
        }
    }
}