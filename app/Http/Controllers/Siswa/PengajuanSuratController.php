<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Dudi;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;



class PengajuanSuratController extends Controller
{
    public function index(){
        return view('pkl.pengajuan-surat.index');
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'namaSiswa' => 'required', // Pastikan namaSiswa adalah array dengan minimal 4 item
        'namaSiswa.*' => 'required|string|max:255', // Validasi setiap elemen array
        'perusahaan_tujuan' => 'required|string|max:255',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
    }

    $pengajuan =Pengajuan::create([
        // 'jurusan' => Siswa::where('nis', $nama)->first()->jurusan->jurusan,
        'perusahaan_tujuan' => Dudi::where('id_dudi', $request->perusahaan_tujuan)->first()->nama,
        'tanggal_pengajuan' => date('Y-m-d'),
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'kepada_yth' => Dudi::where('id_dudi', $request->perusahaan_tujuan)->first()->nama_pimpinan,
        'status' => 'Pending',
    ]);


    // Simpan data
    foreach ($request->namaSiswa as $nama) {
       PengajuanDetail::create([
            'id_surat' => $pengajuan->id,
            'nim' => $nama,
            'jurusan' => Siswa::where('nis', $nama)->first()->jurusan->jurusan
       ]);
    };

    return response()->json(['status' => 'success', 'message' => 'Pengajuan berhasil disimpan.']);
}

public function search(Request $request)
{
    $query = $request->get('q');
    $students = Siswa::where('nama', 'like', '%' . $query . '%')
        ->orWhere('nis', 'like', '%' . $query . '%')
        ->with('penempatan') // Assuming 'penempatan' is a relationship in your Siswa model
        ->get();

    return response()->json($students->map(function ($student) {
        return [
            'id' => $student->nis,
            'nama' => $student->nama,
            'nis' => $student->nis,
            'penempatan' => $student->penempatan,
        ];
    }));
}

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $siswa = Siswa::where('nis', Auth::user()->username)->first();
            $pengajuanSurat = PengajuanDetail::where('nim', Auth::user()->username)->orderByDesc('id')->get(); // Ambil data dari model
            return DataTables::of($pengajuanSurat)
                ->addIndexColumn() // Tambahkan nomor urut
                ->editColumn('status', function ($row) {
                    if ($row->status === 'Approved') {
                        return '<span class="badge bg-success">Disetujui</span>';
                    } else if ($row->status === 'Rejected') {
                        return '<span class="badge bg-danger">Ditolak</span>';
                    } else {
                        return '<span class="badge bg-warning">Menunggu</span>';
                    }
                })
                ->addColumn('namasiswa', function ($row) {
                    return optional(Siswa::where('nis', $row->nim)->first())->nama;
                })
                ->editColumn('perusahaan_tujuan', function ($row) {
                    return Pengajuan::where('id', $row->id_surat)->first()->perusahaan_tujuan;
                })
                ->editColumn('tanggal_pengajuan', function ($row) {
                    return Pengajuan::where('id', $row->id_surat)->first()->tanggal_pengajuan;
                })
                ->editColumn('tanggal_mulai', function ($row) {
                    return Pengajuan::where('id', $row->id_surat)->first()->tanggal_mulai;
                })
                ->editColumn('tanggal_selesai', function ($row) {
                    return Pengajuan::where('id', $row->id_surat)->first()->tanggal_selesai;
                })
                ->editColumn('kepada_yth', function ($row) {
                    return Pengajuan::where('id', $row->id_surat)->first()->kepada_yth;
                })
                ->editColumn('status', function ($row) {
                    $status = Pengajuan::where('id', $row->id_surat)->first()->status;
                    if ($status === 'Approved') {
                        $statusText = 'Disetujui';
                    } elseif ($status === 'Placed') {
                        $statusText = 'Ditempatkan';
                    } elseif ($status === 'Pending') {
                        $statusText = 'Menunggu';
                    } elseif ($status === 'Rejected') {
                        $statusText = 'Ditolak';
                    } else {
                        $statusText = $status;
                    }

                    // Tambahkan elemen span dengan kelas khusus
                    if ($statusText === 'Disetujui' || $statusText === 'Ditempatkan') {
                        return '<span style="color: green; padding: 2px; border-radius: 5px; ">' . $statusText . '</span>';
                    }

                    return $statusText;
                })->rawColumns(['status']) // Jangan lupa tambahkan ini jika menggunakan HTML

                ->addColumn('aksi', function ($row) {
                    $pengajuan = Pengajuan::where('id', $row->id_surat)->first();
                    if ($pengajuan->status === 'Approved' || $pengajuan->status === 'Placed') {
                        return '
                            <a class="btn btn-sm btn-success" href="/surat/' . $row->id_surat . '">Download</a>
                        ';
                    } else {
                        return '';
                    }
                })
                ->addColumn('balasan_dudi', function($row) {
                    $pengajuan = Pengajuan::where('id', $row->id_surat)->first();
                    if ($pengajuan->status === 'Approved' || $pengajuan->status === 'Placed') {
                        if($pengajuan->file_balasan_path == null) {
                            return '
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-success btn-diterima" data-id="' . $row->id_surat . '">Diterima</button>
                                <button class="btn btn-sm btn-outline-danger btn-ditolak" data-id="' . $row->id_surat . '">Ditolak</button>
                            </div>
                        ';
                        }
                        else {
                            return '<a class="btn btn-sm btn-outline-primary" href="' . asset('storage/' . $pengajuan->file_balasan_path) . '" target="_blank">Lihat Surat</a>';
                        }
                    } else {
                        return '';
                    }
                })
                ->rawColumns(['status', 'aksi', 'balasan_dudi']) // Pastikan kolom HTML dirender
                ->make(true);
        }
    }

    public function isDitolak(Request $request){
        $validated = $request->validate([
            'id_pengajuan' => 'nullable|string',
        ]);

        $pengajuan = Pengajuan::findOrFail($request->id_pengajuan);
        $pengajuan->update(['status' => 'Rejected']);
        return response()->json([
            'status' => true,
            'message' => 'Surat berhasil ditolak.'
        ]);
    }

    public function cekPengajuan(Request $request) {
        $validated = $request->validate([
            'nisSiswa' => 'required',
        ]);

        $p_suratDetail = PengajuanDetail::where('nim', $request->nisSiswa)->first();

        if (!$p_suratDetail) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada pengajuan sebelumnya. Silakan lanjut.',
            ]);
        }

        // Ambil surat terkait dari tabel Pengajuan
        $surat = Pengajuan::where('id', $p_suratDetail->id_surat)->first();
        // dd($surat);

        if (!$surat) {
            return response()->json([
                'success' => true,
                'message' => 'Data surat tidak ditemukan. Silakan lanjut.',
            ]);
        }

        // Cek status surat
        if ($surat->status === 'Approved') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan sebelumnya sudah dalam status Approved dan tidak dapat diajukan kembali.',
            ], 403); // Forbidden
        }

        // Jika belum Placed, dianggap aman
        return response()->json([
            'success' => true,
            'message' => 'Pengajuan masih bisa dilakukan.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $validated = $request->validate([
            'nim' => 'required|string|max:255',
            'jurusan' => 'required|string|max:50',
            'perusahaan_tujuan' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'status' => 'required|string|in:Pending,Disetujui,Ditolak',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesao' => 'required|date',
            'kepada_yth' => 'required|string|max:100',
            'aksi' => 'nullable|string',
        ]);

        $pengajuan->update($validated);

        return response()->json(['status' => true, 'message' => 'Pengajuan surat berhasil diperbarui.']);
    }

    public function delete($id)
    {
        Pengajuan::findOrFail($id)->delete();

        return response()->json(['status' => true, 'message' => 'Pengajuan surat berhasil dihapus.']);
    }




    // admin
    public function getDataAll(Request $request)
{
    if ($request->ajax()) {
        $pengajuanSurat = Pengajuan::all();
        // dd($pengajuanSurat);
        return DataTables::of($pengajuanSurat)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                if ($row->status === 'Approved') {
                    return '<span class="badge bg-success">Disetujui</span>';
                } else if ($row->status === 'Rejected') {
                    return '<span class="badge bg-danger">Ditolak</span>';
                } else if ($row->status === 'Placed') {
                    return '<span class="badge bg-primary">Ditempatkan</span>';
                } else {
                    return '<span class="badge bg-warning">Menunggu</span>';
                }
            })
            ->addColumn('namasiswa', function ($row) {
                return optional(Siswa::where('nis', $row->nim)->first())->nama;
            })
            ->addColumn('aksi', function ($row) {
                $actionButtons = '
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-primary detail-btn" data-id="' . $row->id . '">Detail</button>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu">';

                // Hanya tampilkan tombol "Disetujui" jika status bukan "Placed"
                if ($row->status !== 'Placed') {
                    $actionButtons .= '<li><button class="dropdown-item approve-btn" data-id="' . $row->id . '">Disetujui</button></li>';
                }

                $actionButtons .= '
                            <li><button class="dropdown-item reject-btn" data-id="' . $row->id . '">Ditolak</button></li>
                            <li><a class="dropdown-item" href="/surat/' . $row->id . '">Lihat</a></li>
                        </ul>
                    </div>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Hapus</button>
                </div>
                ';
                return $actionButtons;
            })
            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }
}
public function reject(Request $request, $id)
{
    $request->validate([
        'keterangan' => 'required|string|max:255',
    ]);

    $pengajuan = Pengajuan::findOrFail($id);
    $pengajuan->status = 'Rejected';
    $pengajuan->keterangan = $request->keterangan; // Simpan keterangan
    $pengajuan->save();

    return response()->json(['message' => 'Pengajuan berhasil ditolak dengan keterangan.']);
}

public function approve($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    // Ubah status menjadi "Disetujui"
    $pengajuan->status = 'Approved';
    $pengajuan->save();

    // Ambil data untuk surat
    $surat = [
        'id' => $pengajuan->id,
        'nama_siswa' => $pengajuan->nama_siswa,
        'jurusan' => $pengajuan->jurusan,
        'perusahaan_tujuan' => $pengajuan->perusahaan_tujuan,
        'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan,
    ];

    return response()->json([
        'message' => 'Pengajuan berhasil disetujui.',
        'surat' => $surat,
    ]);
}

// detail siswa
public function details($id)
{
    $pengajuan = PengajuanDetail::where('id_surat', $id)->with('siswa')->get();

    $siswa = $pengajuan->map(function ($item) {
        return [
            'nim' => $item->siswa->nis ?? null,
            'nama' => $item->siswa->nama ?? 'Tidak Ditemukan',
            'kelas' => $item->siswa->kelas ?? 'Tidak Ditemukan',
            'jurusan' => $item->jurusan ?? 'Tidak Ditemukan',
        ];
    });

    return response()->json([
        'siswa' => $siswa
    ]);
}






}
