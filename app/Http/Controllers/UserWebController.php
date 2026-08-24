<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UsersApp as UserApp;
use App\Models\UserGroups;

use App\Services\ActivityLogService;


class UserWebController extends Controller
{

private function currentUser()
{
    return auth()->user();
}

public function index()
{
    $user = auth()->user();

    return view('admin.user', [
        'currentUser' => $user
    ]);
}




   public function data(Request $request)
{
    $user = $this->currentUser();

    $query = UserApp::with([
        'group',
        'role',
        'faskes',
        'kota',
    ]);

    /*
    |--------------------------------------------------------------------------
    | USER GROUP 3
    |--------------------------------------------------------------------------
    */

    if ($user->groupid == 3) {

        $query->whereIn('groupid', [3, 4, 5])
              ->where('kodeFaskes', $user->kodeFaskes);
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER TAMBAHAN
    |--------------------------------------------------------------------------
    */

    if ($request->filled('username')) {
        $query->where(
            'username',
            'like',
            '%' . $request->username . '%'
        );
    }

    if ($request->filled('provinsi')) {
        $query->where(
            'kodePropinsi',
            $request->provinsi
        );
    }

    if ($request->filled('kota')) {
        $query->where(
            'kodeKota',
            $request->kota
        );
    }

    if ($request->filled('kecamatan')) {
        $query->where(
            'kodeKecamatan',
            $request->kecamatan
        );
    }

    if ($request->filled('faskes')) {
        $query->where(
            'kodeFaskes',
            $request->faskes
        );
    }

    return response()->json([
        'data' => $query->get()
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
    $user = $this->currentUser();

    if ($user->groupid == 3) {
        return response()->json([
            'data' => UserGroups::whereIn('group_id', [4, 5])
                ->orderBy('group_id')
                ->get()
        ]);
    }

    return response()->json([
        'data' => UserGroups::orderBy('group_id')->get()
    ]);
}
}
