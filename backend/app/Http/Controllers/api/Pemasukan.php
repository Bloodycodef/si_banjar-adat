<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\tbPemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Pemasukan extends Controller
{
    /**
     * GET /pemasukan
     * Ambil semua data pemasukan
     */
    public function index()
    {
        $data = tbPemasukan::with(['kategori', 'creator'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * POST /pemasukan
     * Simpan pemasukan baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_transaksi' => 'required|string|unique:tbpemasukan,kode_transaksi',
            'nama_transaksi' => 'required|string|max:150',
            'kategori_id'    => 'required|exists:kategori_pemasukan,id',
            'qty'            => 'required|integer|min:1',
            'harga_satuan'   => 'required|numeric|min:0',
            'sumber_dana'    => 'nullable|string|max:100',
            'deskripsi'      => 'nullable|string',
            'bukti_pembayaran' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $pemasukan = tbPemasukan::create([
            'kode_transaksi' => $request->kode_transaksi,
            'nama_transaksi' => $request->nama_transaksi,
            'kategori_id'    => $request->kategori_id,
            'qty'            => $request->qty,
            'harga_satuan'   => $request->harga_satuan,
            'sumber_dana'    => $request->sumber_dana,
            'deskripsi'      => $request->deskripsi,
            'bukti_pembayaran' => $request->bukti_pembayaran,
            'created_by'     => auth('api')->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pemasukan berhasil ditambahkan',
            'data' => $pemasukan,
        ], 201);
    }

    /**
     * GET /pemasukan/{id}
     */
    public function show($id)
    {
        $pemasukan = tbPemasukan::with(['kategori', 'creator'])
            ->find($id);

        if (!$pemasukan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pemasukan,
        ]);
    }

    /**
     * PUT /pemasukan/{id}
     */
    public function update(Request $request, $id)
    {
        $pemasukan = tbPemasukan::find($id);

        if (!$pemasukan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_transaksi' => 'required|string|max:150',
            'kategori_id'    => 'required|exists:kategori_pemasukan,id',
            'qty'            => 'required|integer|min:1',
            'harga_satuan'   => 'required|numeric|min:0',
            'sumber_dana'    => 'nullable|string|max:100',
            'deskripsi'      => 'nullable|string',
            'bukti_pembayaran' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $pemasukan->update($request->only([
            'nama_transaksi',
            'kategori_id',
            'qty',
            'harga_satuan',
            'sumber_dana',
            'deskripsi',
            'bukti_pembayaran',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Pemasukan berhasil diperbarui',
            'data' => $pemasukan,
        ]);
    }

    /**
     * DELETE /pemasukan/{id}
     */
    public function destroy($id)
    {
        $pemasukan = tbPemasukan::find($id);

        if (!$pemasukan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $pemasukan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pemasukan berhasil dihapus',
        ]);
    }
}
