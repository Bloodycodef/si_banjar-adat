<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriPengeluaran;

class KategoriPengeluaranController extends Controller
{
    // GET /kategori-pengeluaran
    public function index()
    {
        $data = KategoriPengeluaran::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    // POST /kategori-pengeluaran
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = KategoriPengeluaran::create($request->only([
            'nama_kategori',
            'deskripsi',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Kategori pengeluaran berhasil ditambahkan',
            'data' => $kategori,
        ], 201);
    }

    // GET /kategori-pengeluaran/{id}
    public function show($id)
    {
        $kategori = KategoriPengeluaran::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kategori,
        ]);
    }

    // PUT /kategori-pengeluaran/{id}
    public function update(Request $request, $id)
    {
        $kategori = KategoriPengeluaran::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($request->only([
            'nama_kategori',
            'deskripsi',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Kategori pengeluaran berhasil diupdate',
            'data' => $kategori,
        ]);
    }

    // DELETE /kategori-pengeluaran/{id}
    public function destroy($id)
    {
        $kategori = KategoriPengeluaran::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori pengeluaran berhasil dihapus',
        ]);
    }
}
