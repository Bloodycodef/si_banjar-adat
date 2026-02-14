<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\KategoriPemasukan;
use Illuminate\Http\Request;
use PDO;

class KategoriPemasukanController extends Controller
{
    //GET CATEGORY
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => KategoriPemasukan::orderBy('nama_kategori')->get()
        ]);
    }

    //POST CATEGORY
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = KategoriPemasukan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'kategori berhasil di tambahkan',
            'data' => $kategori
        ], 201);
    }

    //GET DETAIL CATEGORY
    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data' => KategoriPemasukan::findOrFail($id)
        ]);
    }

    //PUT CATEGORY
    public function update(Request $request, $id)
    {
        $kategori = KategoriPemasukan::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'kategori berhasil di update',
            'data' => $kategori
        ]);
    }

    //HAPUS CATEGORI
    public function destroy($id)
    {
        KategoriPemasukan::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'kategori berhasil di hapus'
        ]);
    }
}
