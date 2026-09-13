<?php
namespace App\Http\Controllers\NewLplpo;
use App\Http\Controllers\Controller;
use App\Models\NewLplpo\KategoriObat;
use App\Services\NewLplpo\KategoriObatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriObatController extends Controller
{
    protected KategoriObatService $service;

    public function __construct(KategoriObatService $service)
    {
        $this->service = $service;
    }


    /**
     * ==========================================================
     * INDEX
     * ==========================================================
     */
    public function index()
    {
        return view('newlplpo.kategoriobat.index');
    }


    /**
     * ==========================================================
     * DATATABLE
     * ==========================================================
     */
    public function datatable(): JsonResponse
    {
        $data = $this->service->getAll();

        $result = $data->values()->map(function ($item, $index) {

            return [
                'id'       => $item->id,
                'no'       => $index + 1,
                'kategori' => $item->kategori,
            ];

        });

        return response()->json([
            'success' => true,
            'data'    => $result,
        ]);
    }


    /**
     * ==========================================================
     * SHOW
     * ==========================================================
     */
    public function show(int $id): JsonResponse
    {
        $kategori = $this->service->find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori obat tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $kategori,
        ]);
    }


    /**
     * ==========================================================
     * STORE
     * ==========================================================
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori' => [
                'required',
                'string',
                'max:100',
                'unique:new_lplpo_kategori,kategori',
            ],
        ], [
            'kategori.required' => 'Kategori obat wajib diisi.',
            'kategori.max'      => 'Kategori obat maksimal 100 karakter.',
            'kategori.unique'   => 'Kategori obat tersebut sudah tersedia.',
        ]);

        $kategori = $this->service->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori obat berhasil ditambahkan.',
            'data'    => $kategori,
        ]);
    }


    /**
     * ==========================================================
     * UPDATE
     * ==========================================================
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $kategori = $this->service->find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori obat tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('new_lplpo_kategori', 'kategori')
                    ->ignore($kategori->id),
            ],
        ], [
            'kategori.required' => 'Kategori obat wajib diisi.',
            'kategori.max'      => 'Kategori obat maksimal 100 karakter.',
            'kategori.unique'   => 'Kategori obat tersebut sudah tersedia.',
        ]);

        $kategori = $this->service->update(
            $kategori,
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Kategori obat berhasil diperbarui.',
            'data'    => $kategori,
        ]);
    }


    /**
     * ==========================================================
     * DELETE
     * ==========================================================
     */
    public function destroy(int $id): JsonResponse
    {
        $kategori = $this->service->find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori obat tidak ditemukan.',
            ], 404);
        }

        $this->service->delete($kategori);

        return response()->json([
            'success' => true,
            'message' => 'Kategori obat berhasil dihapus.',
        ]);
    }
}

