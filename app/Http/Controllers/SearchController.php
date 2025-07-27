<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dudi;
use App\Models\Instruktur;

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


        if ($search) {
            $results = Instruktur::where('is_active', 1)

                ->where(function($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->select('id_instruktur as id', 'nama')
                ->limit(20)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama' => $item->nama,
                    ];
                });
        }

        return response()->json(['results' => $results]);
    }
}
