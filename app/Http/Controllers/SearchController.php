<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dudi;

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
}
