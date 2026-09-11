<?php
namespace App\Services\NewLplpo;
use App\Models\NewLplpo\KategoriObat;
use Illuminate\Database\Eloquent\Collection;

class KategoriObatService
{
    /**
     * ==========================================================
     * GET ALL
     * ==========================================================
     */
    public function getAll(): Collection
    {
        return KategoriObat::query()
            ->orderBy('kategori', 'asc')
            ->get();
    }


    /**
     * ==========================================================
     * GET BY ID
     * ==========================================================
     */
    public function find(int $id): ?KategoriObat
    {
        return KategoriObat::find($id);
    }


    /**
     * ==========================================================
     * CREATE
     * ==========================================================
     */
    public function create(array $data): KategoriObat
    {
        return KategoriObat::create([
            'kategori' => trim($data['kategori']),
        ]);
    }


    /**
     * ==========================================================
     * UPDATE
     * ==========================================================
     */
    public function update(KategoriObat $kategori, array $data): KategoriObat
    {
        $kategori->update([
            'kategori' => trim($data['kategori']),
        ]);

        return $kategori->fresh();
    }


    /**
     * ==========================================================
     * DELETE
     * ==========================================================
     */
    public function delete(KategoriObat $kategori): bool
    {
        return $kategori->delete();
    }
}
