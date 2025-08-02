<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dudi;
use App\Models\Guru;
use App\Models\Instruktur;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\Siswa;

class SearchController extends Controller
{
    public function searchPerusahaan(Request $request)
    {
        $search = $request->get('q');
        $results = [];

        if ($search) {
            $results = Dudi::where('is_active', 1)
                ->where(function($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%')
                          ->orWhere('alamat', 'like', '%' . $search . '%');
                })
                ->select('id_dudi', 'nama as text')
                ->limit(20)
                ->get();
        }

        return response()->json(['results' => $results]);
    }

    public function searchInstruktur(Request $request)
    {

        $search = $request->get('q');
        $results = [];



        $pengajuan = Pengajuan::findOrFail($request->pengajuan_id);
        $search = $request->q;

        $results = Instruktur::with('dudi:id_dudi,nama') // relasi akan include nama DUDI
            ->where('is_active', 1)
            ->where('id_dudi', $pengajuan->perusahaan_tujuan)
            ->where(function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->select('id_instruktur as id', 'nama', 'id_dudi') // penting: include id_dudi agar relasi bisa dicocokkan
            ->limit(20)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama . ' - ' . ($item->dudi ? $item->dudi->nama : '-'),
                ];
            });



        return response()->json(['results' => $results]);
    }


    public function getDataByGuru(Request $request, $id)
    {

        $results = [];
        $siswa = PengajuanDetail::where('id_surat', $id)->first()->nis;
        $jurusan = Siswa::where('nis', $siswa)->first()->id_jurusan;

        $results = Guru::where('id_jurusan', $jurusan)->get();

        return response()->json(['results' => $results]);
    }
}
