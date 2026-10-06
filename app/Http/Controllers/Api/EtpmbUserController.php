<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterFaskes;
use App\Models\UsersApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EtpmbUserController extends Controller
{
    /**
     * GET /api/etpmb/users
     */
    public function index(Request $request)
    {
        $query = UsersApp::query()
            ->with([
                'faskes',
                'provinsi',
                'kota',
                'kecamatan',
            ])
            ->where('groupid', 6);

        if ($request->filled('username')) {
            $query->where(
                'username',
                'like',
                '%' . $request->username . '%'
            );
        }

        if ($request->filled('kodefaskes')) {
            $query->where(
                'kodeFaskes',
                $request->kodefaskes
            );
        }

        $perPage = min(
            max((int) $request->get('per_page', 10), 1),
            100
        );

        $users = $query
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users->getCollection()->map(
                fn ($user) => $this->transformUser($user)
            ),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    /**
     * POST /api/etpmb/users
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users_app,username',
            ],

            'kodefaskes' => [
                'required',
                'string',
                'max:255',
                'exists:master_faskes,kodeFaskes',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
            ],

            'namalengkap' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $faskes = MasterFaskes::where(
            'kodeFaskes',
            $validated['kodefaskes']
        )->first();

        if (!$faskes) {
            return response()->json([
                'success' => false,
                'message' => 'Faskes tidak ditemukan.',
            ], 404);
        }

        $user = UsersApp::create([
            'userid' => (string) Str::uuid(),
            'username' => $validated['username'],
            'groupid' => 6,

            // Email internal, tidak dikirim oleh client
            'email' => $validated['username'] . '@etpmb.local',

            'namalengkap' => $validated['namalengkap'],

            // Data faskes diambil dari master_faskes
            'kodeFaskes' => $faskes->kodeFaskes,
            'namaFaskes' => $faskes->namaFaskes,
            'kodePropinsi' => $faskes->kodePropinsi,
            'kodeKota' => $faskes->kodeKabupaten,
            'kodeKecamatan' => $faskes->kodeKecamatan,

            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat.',
            'data' => $this->transformUser($user),
        ], 201);
    }

    /**
     * GET /api/etpmb/users/{userid}
     */
    public function show(string $userid)
    {
        $user = UsersApp::with([
            'faskes',
            'provinsi',
            'kota',
            'kecamatan',
        ])
            ->where('groupid', 6)
            ->where('userid', $userid)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->transformUser($user),
        ]);
    }

    /**
     * PUT /api/etpmb/users/{userid}
     */
    public function update(Request $request, string $userid)
    {
        $user = UsersApp::where('groupid', 6)
            ->where('userid', $userid)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users_app', 'username')
                    ->ignore($user->userid, 'userid'),
            ],

            'kodefaskes' => [
                'required',
                'string',
                'max:255',
                'exists:master_faskes,kodeFaskes',
            ],

            'namalengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
            ],
        ]);

        $faskes = MasterFaskes::where(
            'kodeFaskes',
            $validated['kodefaskes']
        )->first();

        if (!$faskes) {
            return response()->json([
                'success' => false,
                'message' => 'Faskes tidak ditemukan.',
            ], 404);
        }

        $user->username = $validated['username'];

        // Email selalu mengikuti username
        $user->email =
            $validated['username'] . '@etpmb.local';

        $user->namalengkap =
            $validated['namalengkap'];

        // Update data faskes dari master_faskes
        $user->kodeFaskes =
            $faskes->kodeFaskes;

        $user->namaFaskes =
            $faskes->namaFaskes;

        $user->kodePropinsi =
            $faskes->kodePropinsi;

        $user->kodeKota =
            $faskes->kodeKabupaten;

        $user->kodeKecamatan =
            $faskes->kodeKecamatan;

        if (!empty($validated['password'])) {
            $user->password =
                Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui.',
            'data' => $this->transformUser($user),
        ]);
    }

    /**
     * DELETE /api/etpmb/users/{userid}
     */
    public function destroy(string $userid)
    {
        $user = UsersApp::where('groupid', 6)
            ->where('userid', $userid)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ]);
    }

    /**
     * Format response user.
     *
     * Jangan pernah mengembalikan password/api_token.
     */
    private function transformUser(UsersApp $user): array
    {
        return [
            'userid' => $user->userid,
            'username' => $user->username,
            'email' => $user->email,
            'namalengkap' => $user->namalengkap,

            'groupid' => $user->groupid,
            'role' => $user->role,

            'kodeFaskes' => $user->kodeFaskes,
            'namaFaskes' => $user->namaFaskes,
            'kodePropinsi' => $user->kodePropinsi,
            'kodeKota' => $user->kodeKota,
            'kodeKecamatan' => $user->kodeKecamatan,

            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
