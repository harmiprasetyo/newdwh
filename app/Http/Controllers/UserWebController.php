<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserApp;
use App\Models\UserGroups;


class UserWebController extends Controller
{
    public function index()
    {
        return view('admin.user');
    }

    public function data()
    {
        return response()->json([
            'data' => UserApp::with('group')->get()
        ]);
    }

    public function show($id)
    {
        return UserApp::findOrFail($id);
    }

    public function store(Request $request)
    {
        UserApp::create($request->all());
        return response()->json(['ok']);
    }

    public function update(Request $request, $id)
    {
        $user = UserApp::findOrFail($id);

        if (!$request->password) {
            unset($request['password']);
        }

        $user->update($request->all());

        return response()->json(['ok']);
    }

    public function destroy($id)
    {
        UserApp::destroy($id);
        return response()->json(['ok']);
    }

    public function groups()
    {
        return UserGroups::all();
    }
}
