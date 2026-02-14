<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tbPengeluaran;
use Illuminate\Support\Facades\Validator;


class Pengeluaran extends Controller
{
    //Get Pengeluaran
    /**
     * GET /pengeluaran
     * Ambil semua data pengeluaran
     */
    public function index()
    {
        $data = tbPengeluaran::with(['kategori', 'creator'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * POST /pengeluaran
     * Simpan pengeluaran baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_transaksi' => 'required|string|unique:tbpengeluaran,kode_transaksi',
            'nama_transaksi' => 'required|string|max:150',
            'kategori_id'    => 'required|exists:kategori_pengeluaran,id',
            'qty'            => 'required|integer|min:1',
            'harga_satuan'   => 'required|numeric|min:0',
            'penerima'       => 'nullable|string|max:100',
            'deskripsi'      => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $pengeluaran = tbPengeluaran::create([
            'kode_transaksi' => $request->kode_transaksi,
            'nama_transaksi' => $request->nama_transaksi,
            'kategori_id'    => $request->kategori_id,
            'qty'            => $request->qty,
            'harga_satuan'   => $request->harga_satuan,
            'penerima'       => $request->penerima,
            'deskripsi'      => $request->deskripsi,
            'bukti_pengeluaran' => $request->bukti_pengeluaran,
            'created_by'     => auth('api')->id(), // ✅ penting
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil ditambahkan',
            'data' => $pengeluaran
        ], 201);
    }

    /**
     * GET /pengeluaran/{id}
     */
    public function show($id)
    {
        $pengeluaran = tbPengeluaran::with(['kategori', 'creator'])
            ->find($id);

        if (!$pengeluaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pengeluaran
        ]);
    }

    /**
     * PUT /pengeluaran/{id}
     */
    public function update(Request $request, $id)
    {
        $pengeluaran = tbPengeluaran::find($id);

        if (!$pengeluaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_transaksi' => 'required|string|max:150',
            'kategori_id'    => 'required|exists:kategori_pengeluaran,id',
            'qty'            => 'required|integer|min:1',
            'harga_satuan'   => 'required|numeric|min:0',
            'penerima'       => 'nullable|string|max:100',
            'deskripsi'      => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $pengeluaran->update($request->only([
            'nama_transaksi',
            'kategori_id',
            'qty',
            'harga_satuan',
            'penerima',
            'deskripsi',
            'bukti_pengeluaran',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil diperbarui',
            'data' => $pengeluaran
        ]);
    }

    /**
     * DELETE /pengeluaran/{id}
     */
    public function destroy($id)
    {
        $pengeluaran = tbPengeluaran::find($id);

        if (!$pengeluaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $pengeluaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dihapus'
        ]);
    }
}
