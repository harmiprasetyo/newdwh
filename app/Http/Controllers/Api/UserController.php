<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsersApp as UserApp;

class UserController extends Controller
{
    public function datatables(Request $request)
    {
        $query = UserApp::with('group');

        return datatables()->of($query)
            ->addColumn('group', fn($u) => $u->group->namagroup ?? '-')
            ->make(true);
    }

    public function store(Request $request)
    {
        UserApp::create($request->all());
        return response()->json(['message' => 'Created']);
    }

    public function show($id)
    {
        return UserApp::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = UserApp::findOrFail($id);

        if ($request->password) {
            $request->merge([
                'password' => bcrypt($request->password)
            ]);
        } else {
            unset($request['password']);
        }

        $user->update($request->all());

        return response()->json(['message' => 'Updated']);
    }

    public function destroy($id)
    {
        UserApp::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
