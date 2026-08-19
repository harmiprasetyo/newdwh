<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UsersApp as UserApp;
use App\Models\UserGroups;

use App\Services\ActivityLogService;


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


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = UserApp::create(
            $request->all()
        );


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        $newValues = $user->toArray();

        // Jangan simpan password ke activity log
        unset(
            $newValues['password']
        );


        ActivityLogService::log(
            action: 'create',
            module: 'User',
            description: 'Menambahkan user baru',
            subject: $user,
            newValues: $newValues
        );


        return response()->json([
            'ok'
        ]);
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

        $user = UserApp::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | OLD VALUES
        |--------------------------------------------------------------------------
        */

        $oldValues = $user->toArray();


        // Jangan simpan password lama
        unset(
            $oldValues['password']
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        if (!$request->password) {

            unset(
                $request['password']
            );

        }


        $user->update(
            $request->all()
        );


        /*
        |--------------------------------------------------------------------------
        | NEW VALUES
        |--------------------------------------------------------------------------
        */

        $newValues = $user
            ->fresh()
            ->toArray();


        // Jangan simpan password baru
        unset(
            $newValues['password']
        );


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        ActivityLogService::log(
            action: 'update',
            module: 'User',
            description: 'Mengubah data user',
            subject: $user,
            oldValues: $oldValues,
            newValues: $newValues
        );


        return response()->json([
            'ok'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $user = UserApp::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | OLD VALUES
        |--------------------------------------------------------------------------
        */

        $oldValues = $user->toArray();


        // Jangan simpan password
        unset(
            $oldValues['password']
        );


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        $user->delete();


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        ActivityLogService::log(
            action: 'delete',
            module: 'User',
            description: 'Menghapus user',
            subject: $user,
            oldValues: $oldValues
        );


        return response()->json([
            'ok'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | USER GROUPS
    |--------------------------------------------------------------------------
    */

    public function groups()
    {
        return UserGroups::all();
    }
}