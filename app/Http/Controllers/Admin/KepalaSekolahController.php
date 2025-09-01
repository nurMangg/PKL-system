<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KepalaSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class KepalaSekolahController extends Controller
{
    public function index()
    {
        return view('master.kepala-sekolah.index');
    }

    public function data(Request $request)
    {
        $data = KepalaSekolah::where('is_active', true)->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function list(Request $request)
    {
        $data = KepalaSekolah::where('is_active', true)->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'jabatan' => 'required|string|max:255',
            'signature_pad' => 'required|string'
        ]);

        $id = 'KS' . date('YmdHis') . Str::random(4);

        $kepalaSekolah = KepalaSekolah::create([
            'id_kepala_sekolah' => $id,
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'signature_pad' => $request->signature_pad,
            'is_active' => true,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data kepala sekolah berhasil ditambahkan',
            'data' => $kepalaSekolah
        ]);
    }

    public function show($id)
    {
        $kepalaSekolah = KepalaSekolah::where('id_kepala_sekolah', $id)
            ->where('is_active', true)
            ->first();

        if (!$kepalaSekolah) {
            return response()->json([
                'success' => false,
                'message' => 'Data kepala sekolah tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kepalaSekolah
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'jabatan' => 'required|string|max:255',
            'signature_pad' => 'required|string'
        ]);

        $kepalaSekolah = KepalaSekolah::where('id_kepala_sekolah', $id)
            ->where('is_active', true)
            ->first();

        if (!$kepalaSekolah) {
            return response()->json([
                'success' => false,
                'message' => 'Data kepala sekolah tidak ditemukan'
            ], 404);
        }

        $kepalaSekolah->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'signature_pad' => $request->signature_pad,
            'updated_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data kepala sekolah berhasil diperbarui',
            'data' => $kepalaSekolah
        ]);
    }

    public function destroy($id)
    {
        $kepalaSekolah = KepalaSekolah::where('id_kepala_sekolah', $id)
            ->where('is_active', true)
            ->first();

        if (!$kepalaSekolah) {
            return response()->json([
                'success' => false,
                'message' => 'Data kepala sekolah tidak ditemukan'
            ], 404);
        }

        $kepalaSekolah->update([
            'is_active' => false,
            'updated_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data kepala sekolah berhasil dihapus'
        ]);
    }
}
