<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use App\Models\ThnAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatusPKLExport;

class StatusPklController extends Controller
{
    public function index()
    {
        // Ambil tahun akademik yang aktif
        $aktifAkademik = getActiveAcademicYear();
        $thnAkademik = ThnAkademik::where('is_active', true)->orderBy('tahun_akademik', 'desc')->get();
        $tahunAkademikExcel = $thnAkademik;
        $masterExcel = asset('assets/excel/master-penempatan.xlsx');
        return view('pkl.status-pkl.index', compact('thnAkademik', 'aktifAkademik','tahunAkademikExcel','masterExcel'));
    }

    public function data(Request $request)
    {
        $data = Penempatan::with(['siswa', 'guru', 'instruktur', 'tahunAkademik', 'dudi'])
            ->where('penempatan.is_active', true)->where('id_ta', $request->id_ta);

        if (Auth::user()->role == 2) {
            $data = $data->whereHas('siswa', function ($query) {
                $query->where('id_jurusan', session('id_jurusan'));
            });
        }

        return DataTables::of($data)
            ->addColumn('nis', function ($dt) {
                return $dt->siswa->nis;
            })
            ->addColumn('jurusan', function ($dt) {
                return $dt->siswa->jurusan->jurusan;
            })
            ->addColumn('nisn', function ($dt) {
                return $dt->siswa->nisn;
            })
            ->addColumn('nama_siswa', function ($dt) {
                return '<a href="' . url("/d/siswa?nis=" . $dt->siswa->nis) . '">' . $dt->siswa->nama . '</a>';
            })
            ->addColumn('nama_guru', function ($dt) {
                return '<a href="' . url("/d/guru?id=" . $dt->guru->id_guru) . '">' . $dt->guru->nama . '</a>';
            })
            ->addColumn('nama_instruktur', function ($dt) {
                return '<a href="' . url("/d/instruktur?id=" . $dt->instruktur->id_instruktur) . '">' . $dt->instruktur->nama . '</a>';
            })
            ->addColumn('nama_dudi', function ($dt) {
                return '<a href="' . url("/d/dudi?id=" . $dt->dudi->id_dudi) . '">' . $dt->dudi->nama . '</a>';
            })
            ->editColumn('tanggal_mulai', function ($dt) {
                return \Carbon\Carbon::parse($dt->tanggal_mulai)->translatedFormat('d F Y');
            })
            ->editColumn('tanggal_selesai', function ($dt) {
                return \Carbon\Carbon::parse($dt->tanggal_selesai)->translatedFormat('d F Y');
            })
            ->editColumn('status', function ($dt) {
                return ucfirst($dt->status);
            })
            ->addColumn('tahun_akademik', function ($dt) {
                return $dt->tahunAkademik->tahun_akademik;
            })
            ->addColumn('action', function ($dt) {
                return ' <button class="btn btn-sm btn-primary btn-edit"><i class="bi bi-pencil-fill"></i></button> | <button class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash-fill"></i></button>';
            })
            ->rawColumns([
                'action',
                'nama_dudi',
                'nama_siswa',
                'nama_guru',
                'nama_instruktur'
            ])
            ->make(true);
    }

    public function downloadExcel(Request $request)
    {
        // Menggunakan export class untuk mendownload Excel
        return Excel::download(new StatusPKLExport($request->id_ta), 'data-status-pkl ' . date('Y-m-d') . '.xlsx');
    }
}
