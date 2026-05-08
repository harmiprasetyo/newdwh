<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserGroups;

class UserGroupController extends Controller
{
    // 🔥 LIST (DataTables friendly)
    public function index()
    {
        return response()->json([
            'data' => UserGroups::all()
        ]);
    }

    // 🔥 STORE
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|unique:usergroups,group_name'
        ]);

        $data = UserGroups::create([
            'group_name' => $request->group_name
        ]);

        return response()->json([
            'message' => 'Created',
            'data' => $data
        ]);
    }

    // 🔥 SHOW
    public function show($id)
    {
        $data = UserGroups::findOrFail($id);

        return response()->json($data);
    }

    // 🔥 UPDATE
    public function update(Request $request, $id)
    {
        $data = UserGroups::findOrFail($id);

        $request->validate([
            'group_name' => 'required|unique:usergroups,group_name,'.$id.',group_id'
        ]);

        $data->update([
            'group_name' => $request->group_name
        ]);

        return response()->json([
            'message' => 'Updated',
            'data' => $data
        ]);
    }

    // 🔥 DELETE
    public function destroy($id)
    {
        $data = UserGroups::findOrFail($id);
        $data->delete();

        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}
