<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserRoles;

class UserRoleController extends Controller
{
    // GET /api/user-roles
    public function index()
    {
        $data = UserRoles::with('group')->latest()->get();

        return response()->json($data);
    }

    public function byGroup($groupId)
{
    $roles = UserRoles::where('groupId', $groupId)->get();

    return response()->json([
        'data' => $roles
    ]);
}


    // POST /api/user-roles
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
            'groupId' => 'required|exists:usergroups,group_id'
        ]);

        $role = UserRoles::create($validated);

        return response()->json([
            'message' => 'User role created',
            'data' => $role
        ], 201);
    }

    // GET /api/user-roles/{id}
    public function show($id)
    {
        $role = UserRoles::with('group')->find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json($role);
    }

    // PUT /api/user-roles/{id}
    public function update(Request $request, $id)
    {
        $role = UserRoles::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        $validated = $request->validate([
            'role_name' => 'sometimes|required|string|max:255',
            'groupId' => 'sometimes|required|exists:usergroups,group_id'
        ]);

        $role->update($validated);

        return response()->json([
            'message' => 'User role updated',
            'data' => $role
        ]);
    }

    // DELETE /api/user-roles/{id}
    public function destroy($id)
    {
        $role = UserRoles::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        $role->delete();

        return response()->json([
            'message' => 'User role deleted'
        ]);
    }


    public function bulkStore(Request $request)
{
    $request->validate([
        'data' => 'required|array|min:1',
        'data.*.role_name' => 'required|string|max:255',
        'data.*.groupId' => 'required|exists:usergroups,group_id'
    ]);

    $now = now();

    $insertData = collect($request->data)->map(function ($item) use ($now) {
        return [
            'role_name' => $item['role_name'],
            'groupId' => $item['groupId'],
            'created_at' => $now,
            'updated_at' => $now
        ];
    })->toArray();

    UserRoles::insert($insertData);

    return response()->json([
        'message' => 'Bulk insert success',
        'total' => count($insertData)
    ]);
}
}
