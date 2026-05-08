<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\UsersApp;

class UsersAppController extends Controller
{


public function index(Request $request)
{
    $query = UsersApp::with(['group', 'faskes', 'provinsi', 'kota', 'kecamatan']);

    // ======================
    // 🔎 FILTER
    // ======================
    if ($request->username) {
        $query->where('username', 'like', '%' . $request->username . '%');
    }

    if ($request->provinsi) {
        $query->where('kodePropinsi', $request->provinsi);
    }

    if ($request->kota) {
        $query->where('kodeKota', $request->kota);
    }

    if ($request->kecamatan) {
        $query->where('kodeKecamatan', $request->kecamatan);
    }

    if ($request->faskes) {
        $query->where('kodeFaskes', $request->faskes);
    }

    // ======================
    // 🔢 DATATABLES PARAM
    // ======================
    $start  = $request->start ?? 0;
    $length = $request->length ?? 10;
    $draw   = $request->draw ?? 1;

    $total = $query->count();

    $data = $query
        ->skip($start)
        ->take($length)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        "draw" => intval($draw),
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data
    ]);
}
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users_app,username',
            'email' => 'required|email|unique:users_app,email',
            'groupid' => 'required',
            'namalengkap' => 'required',
            'password' => 'required|min:6'
        ]);

        UsersApp::create([
            'userid' => Str::uuid(),
            'username' => $request->username,
            'groupid' => $request->groupid,
            'email' => $request->email,
            'namalengkap' => $request->namalengkap,
            'kodeFaskes' => $request->kodeFaskes,
            'namaFaskes' => $request->namaFaskes,
            'kodePropinsi' => $request->kodePropinsi,
            'kodeKota' => $request->kodeKota,
            'kodeKecamatan' => $request->kodeKecamatan,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'created']);
    }

    public function update(Request $request, $id)
    {
        $data = UsersApp::findOrFail($id);

        $request->validate([
            'username' => "required|unique:users_app,username,$id,userid",
            'email' => "required|email|unique:users_app,email,$id,userid"
        ]);

        $data->update([
            'username' => $request->username,
            'groupid' => $request->groupid,
            'email' => $request->email,
            'namalengkap' => $request->namalengkap,
            'kodeFaskes' => $request->kodeFaskes,
            'namaFaskes' => $request->namaFaskes,
            'kodePropinsi' => $request->kodePropinsi,
            'kodeKota' => $request->kodeKota,
            'kodeKecamatan' => $request->kodeKecamatan
        ]);

        if ($request->password) {
            $data->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return response()->json(['message' => 'updated']);
    }

    public function destroy($id)
    {
        UsersApp::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted']);
    }

    public function show($id)
{
    $data = UsersApp::with([
        'group',
        'faskes',
        'provinsi',
        'kota',
        'kecamatan'
    ])->findOrFail($id);

    return response()->json([
        'data' => $data
    ]);
}

}
