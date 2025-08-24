<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Dudi;
use App\Models\Penempatan;
use App\Models\ThnAkademik;
use Illuminate\Http\Request;
use App\Models\Pengajuan;

use App\Models\PengajuanDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;



class PengajuanSuratController extends Controller
{
    public function index(){

        $aktifAkademik = getActiveAcademicYear();
        $thnAkademik = ThnAkademik::where('is_active', true)->orderBy('tahun_akademik', 'desc')->get();
        $tahunAkademikExcel = $thnAkademik;
        $masterExcel = asset('assets/excel/master-pengajuan-surat.xlsx');

        return view('pkl.pengajuan-surat.index', compact('thnAkademik', 'aktifAkademik','tahunAkademikExcel','masterExcel'));
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


    // Cek setiap siswa apakah sudah ada penempatan di perusahaan tujuan
    foreach ($request->namaSiswa as $nama) {
        $sudahMengajukan = PengajuanDetail::where('nis', $nama)
            ->whereHas('pengajuan', function($query) use ($request) {
                $query->where('perusahaan_tujuan', $request->perusahaan_tujuan)
                      ->where('status', '!=', 'Ditolak');
            })
            ->exists();

        if ($sudahMengajukan) {
            $siswa = Siswa::where('nis', $nama)->first();
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa ' . ($siswa ? $siswa->nama : $nama) . ' sudah mengajukan surat ke perusahaan tujuan yang sama.'
            ], 422);
        }
    }

    $pengajuan =Pengajuan::create([
        // 'jurusan' => Siswa::where('nis', $nama)->first()->jurusan->jurusan,
        'perusahaan_tujuan' => $request->perusahaan_tujuan,
        'id_ta' => getActiveAcademicYear()->id_ta,
        'tanggal_pengajuan' => date('Y-m-d'),
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'kepada_yth' => Dudi::where('id_dudi', $request->perusahaan_tujuan)->first()->nama_pimpinan,
        'status' => 'Menunggu',
    ]);


    // Simpan data
    foreach ($request->namaSiswa as $nama) {
       PengajuanDetail::create([
            'id_surat' => $pengajuan->id,
            'nis' => $nama,
            'jurusan' => Siswa::where('nis', $nama)->first()->jurusan->id_jurusan
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
            $pengajuanSurat = PengajuanDetail::where('nis', Auth::user()->username)->orderByDesc('id')->get(); // Ambil data dari model
            return DataTables::of($pengajuanSurat)
                ->addIndexColumn() // Tambahkan nomor urut
                ->editColumn('status', function ($row) {
                    if ($row->status === 'Disetujui') {
                        return '<span class="badge bg-primary">Disetujui</span>';
                    } else if ($row->status === 'Diterima') {
                        return '<span class="badge bg-secondary">Diterima</span>';
                    } else if ($row->status === 'Ditolak') {
                        return '<span class="badge bg-danger">Ditolak</span>';
                    } else if ($row->status === 'Ditempatkan') {
                        return '<span class="badge bg-success">Ditempatkan</span>';
                    } else {
                        return '<span class="badge bg-warning">Menunggu</span>';
                    }
                })
                ->editColumn('perusahaan_tujuan', function ($row) {
                    return Dudi::where('id_dudi', $row->pengajuan->perusahaan_tujuan)->first()->nama;
                })
                ->addColumn('namasiswa', function ($row) {
                    return optional(Siswa::where('nis', $row->nis)->first())->nama;
                })
                ->editColumn('tanggal_pengajuan', function ($row) {
                    return Pengajuan::where('id', $row->id_surat)->first()->tanggal_pengajuan;
                })
                ->addColumn('detailsiswa', function ($row) {
                    return '<button type="button" class="btn btn-outline-secondary btn-sm detail-btn" style="border-color: transparent;" data-id="' . $row->id_surat . '">List Siswa</button>';

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
                    $pengajuan = Pengajuan::where('id', $row->id_surat)->first();
                    $status = $pengajuan->status;
                    $keterangan = $pengajuan->keterangan ?? null;

                    if ($status === 'Disetujui' || $status === 'Diterima') {
                        return '<span class="badge bg-success">' . $status . '</span>';
                    }
                    else if ($status === 'Ditolak') {
                        // Tampilkan status dengan tombol untuk membuka modal keterangan
                        $modalButton = '';
                        if ($keterangan != null) {
                            $modalButton = ' style="cursor:pointer" onclick="showKeteranganModal(\'' . e($keterangan) . '\')"';
                        }
                        return '<span class="badge bg-danger"' . $modalButton . '>' . $status . '</span>';
                    }
                    return '<span class="badge bg-secondary">' . $status . '</span>';
                })->rawColumns(['status']) // Jangan lupa tambahkan ini jika menggunakan HTML

                ->addColumn('aksi', function ($row) {
                    $pengajuan = Pengajuan::where('id', $row->id_surat)->first();
                    if ($pengajuan->status === 'Disetujui' || $pengajuan->status === 'Diterima' || $pengajuan->status === 'Ditempatkan') {
                        return '
                            <a class="btn btn-sm btn-success" href="/surat/' . $row->id_surat . '">Download</a>
                        ';
                    } else {
                        return '';
                    }
                })
                ->addColumn('balasan_dudi', function($row) {
                    $pengajuan = Pengajuan::where('id', $row->id_surat)->first();
                    $dudi = Dudi::findOrFail($pengajuan->perusahaan_tujuan);
                    if ($pengajuan->status === 'Disetujui' || $pengajuan->status === 'Diterima') {
                        if($pengajuan->file_balasan_path == null) {
                            return '
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-success btn-diterima" data-id="' . $row->id_surat . '" data-idDudi="' . $dudi->id_dudi . '" data-namaDudi="' . $dudi->nama . '">Diterima</button>
                                <button class="btn btn-sm btn-outline-danger btn-ditolak" data-id="' . $row->id_surat . '">Ditolak</button>
                            </div>
                        ';
                        }
                        else {
                            return '<a class="btn btn-sm btn-outline-primary" href="' . asset('storage/' . $pengajuan->file_balasan_path) . '" target="_blank">Lihat Surat</a>';
                        }
                    } else if ($pengajuan->status === 'Ditempatkan') {
                        return '<a class="btn btn-sm btn-outline-primary" href="' . asset('storage/' . $pengajuan->file_balasan_path) . '" target="_blank">Lihat Surat</a>';

                    }
                    else {
                        return '';
                    }
                })
                ->rawColumns(['detailsiswa','status', 'aksi', 'balasan_dudi']) // Pastikan kolom HTML dirender
                ->make(true);
        }
    }

    public function isDitolak(Request $request){
        $validated = $request->validate([
            'id_pengajuan' => 'nullable|string',
        ]);

        $pengajuan = Pengajuan::findOrFail($request->id_pengajuan);
        $pengajuan->update(['status' => 'Ditolak']);
        return response()->json([
            'status' => true,
            'message' => 'Surat berhasil ditolak.'
        ]);
    }

    public function diTempatkan(Request $request)
    {
        \Log::info('diTempatkan called with data: ' . json_encode($request->all()));
        \Log::info('User role: ' . Auth::user()->role);
        \Log::info('User ID: ' . Auth::id());
        \Log::info('User name: ' . Auth::user()->username);

        $validated = $request->validate([
            'id' => 'required|integer',
            'guru' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            // Ambil data pengajuan
            $pengajuan = Pengajuan::findOrFail($request->id);
            \Log::info('Pengajuan found: ' . json_encode($pengajuan->toArray()));

            // Update status menjadi Ditempatkan
            $pengajuan->update(['status' => 'Ditempatkan']);

            // Ambil semua siswa yang mengajukan (detail)
            $detailSiswa = PengajuanDetail::where('id_surat', $pengajuan->id)->get();
            \Log::info('Detail siswa found: ' . $detailSiswa->count() . ' records');

            if ($detailSiswa->isEmpty()) {
                throw new \Exception('Tidak ada detail siswa yang ditemukan');
            }

            // Cari data kelompok terakhir di penempatan
            $penempatanTerakhir = Penempatan::where('id_ta', $pengajuan->id_ta)
                ->orderBy('kelompok', 'desc')
                ->first();

            $kelompokTerakhir = $penempatanTerakhir ? $penempatanTerakhir->kelompok : 0;
            $kelompokBaru = $kelompokTerakhir + 1;

            // \Log::info('Kelompok baru: ' . $kelompokBaru);

            foreach ($detailSiswa as $detail) {
                $penempatanData = [
                    'nis' => $detail->nis,
                    'id_ta' => $pengajuan->id_ta,
                    'id_guru' => $request->guru,
                    'kelompok' => $kelompokBaru,
                    'id_instruktur' => $pengajuan->id_instrukturId, // Use correct field name
                    'created_by' => Auth::id(),
                    'is_active' => 1,
                    'tanggal_mulai' => $pengajuan->tanggal_mulai,
                    'tanggal_selesai' => $pengajuan->tanggal_selesai,
                ];

                \Log::info('Creating penempatan with data: ' . json_encode($penempatanData));

                Penempatan::create($penempatanData);
            }

            DB::commit();
            // \Log::info('Penempatan completed successfully');

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditempatkan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in diTempatkan: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'status' => false,
                'message' => 'Gagal menempatkan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cekPengajuan(Request $request) {
        $validated = $request->validate([
            'nisSiswa' => 'required',
        ]);

        // Ambil semua pengajuan detail untuk siswa ini
        $pengajuanDetails = PengajuanDetail::where('nis', $request->nisSiswa)->get();

        if ($pengajuanDetails->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada pengajuan sebelumnya. Silakan lanjut.',
            ]);
        }

        // Ambil semua id_surat dari detail
        $idSuratList = $pengajuanDetails->pluck('id_surat')->toArray();

        // Ambil semua surat terkait
        $suratList = Pengajuan::whereIn('id', $idSuratList)->get();

        // Cek jika ada surat yang statusnya Approved
        $sudahApproved = $suratList->where('status', 'Disetujui')->count();
        if ($sudahApproved > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan sebelumnya sudah dalam status Disetujui dan tidak dapat diajukan kembali.',
            ], 403); // Forbidden
        }

        // Cek jika ada surat yang tanggal_mulai dan tanggal_selesai sudah lebih dari sebulan
        $lebihdarisebulan = $suratList->filter(function ($surat) {
            if ($surat->tanggal_mulai) {
                $mulai = \Carbon\Carbon::parse($surat->tanggal_mulai)->startOfDay();
                $sekarang = \Carbon\Carbon::now()->startOfDay();
                // Hitung selisih hari, termasuk hari mulai
                $diff = $mulai->diffInDays($sekarang) + 1;
                return $diff > 30;
            }
            return false;
        })->count();

        // dd($lebihdarisebulan);

        if ($lebihdarisebulan > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak dapat dilakukan karena pelaksanaan sebelumnya sudah lebih dari sebulan.',
            ], 403); // Forbidden
        }

        // Jika belum ada yang Approved, pengajuan masih bisa dilakukan
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
        // Hapus semua PengajuanDetail yang terkait dengan pengajuan ini
        PengajuanDetail::where('id_surat', $id)->delete();

        return response()->json(['status' => true, 'message' => 'Pengajuan surat berhasil dihapus.']);
    }




    // admin
    public function getDataAll(Request $request)
    {
        if ($request->ajax()) {
            $pengajuanSurat = Pengajuan::query();

            // Filter berdasarkan tahun akademik jika ada
            if ($request->has('id_ta') && !empty($request->id_ta)) {
                $pengajuanSurat = $pengajuanSurat->where('id_ta', $request->id_ta);
            }

            // Filter berdasarkan role user
            if (Auth::user()->role == 2) {
                // dd(session('id_jurusan'));
                // Untuk role 2, filter berdasarkan jurusan yang ada di session
                $pengajuanSurat = $pengajuanSurat->whereHas('pengajuanDetail.siswa', function ($query) {
                    $query->where('jurusan', session('id_jurusan'));
                });
                // dd($pengajuanSurat);
            }

            // dd($pengajuanSurat);

            return DataTables::of($pengajuanSurat)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status === 'Disetujui') {
                        return '<span class="badge bg-primary">Disetujui</span>';
                    } else if ($row->status === 'Diterima') {
                        return '<span class="badge bg-secondary">Diterima</span>';
                    } else if ($row->status === 'Ditolak') {
                        return '<span class="badge bg-danger">Ditolak</span>';
                    } else if ($row->status === 'Ditempatkan') {
                        return '<span class="badge bg-success">Ditempatkan</span>';
                    } else {
                        return '<span class="badge bg-warning">Menunggu</span>';
                    }
                })
                ->editColumn('perusahaan_tujuan', function($row) {
                    $dt = Dudi::where('id_dudi', $row->perusahaan_tujuan)->first();
                    if ($dt) {
                        return '<a href="' . url("/d/dudi?id=" . $dt->id_dudi) . '">' . $dt->nama . '</a>';
                    }
                    return '-';
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

                    // Hanya tampilkan tombol "Disetujui" jika status bukan "Ditempatkan"
                    if ($row->status !== 'Diterima' && $row->status !== 'Ditempatkan') {
                        $actionButtons .= '<li><button class="dropdown-item approve-btn" data-id="' . $row->id . '">Disetujui</button></li>';
                    }

                    if ($row->status === 'Diterima' || $row->status === 'Ditempatkan') {
                        $actionButtons .= '<li><button class="dropdown-item ditempatkan-btn" data-id="' . $row->id . '">Tempatkan</button></li>';
                        $actionButtons .= '<li><button class="dropdown-item lihatbalasan-btn" data-id="' . $row->id . '">Lihat Balasan</button></li>';
                    }

                    $actionButtons .= '
                                <li><button class="dropdown-item reject-btn" data-id="' . $row->id . '">Tolak Surat</button></li>
                                <li><a class="dropdown-item" href="/surat/' . $row->id . '">Lihat Surat</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Hapus</button>
                    </div>
                    ';
                    return $actionButtons;
                })
                ->rawColumns(['status', 'aksi', 'perusahaan_tujuan'])
                ->make(true);
        }
    }


public function reject(Request $request, $id)
{
    $request->validate([
        'keterangan' => 'required|string|max:255',
    ]);

    $pengajuan = Pengajuan::findOrFail($id);
    $pengajuan->status = 'Ditolak';
    $pengajuan->keterangan = $request->keterangan; // Simpan keterangan
    $pengajuan->save();

    return response()->json(['message' => 'Pengajuan berhasil ditolak dengan keterangan.']);
}

public function approve(Request $request)
{

    $request->validate([
        'noPengajuanSurat' => 'required',
        'nomorSurat' => 'required'
    ]);

    $pengajuan = Pengajuan::findOrFail($request->noPengajuanSurat);

    // Ubah status menjadi "Disetujui"
    $pengajuan->status = 'Disetujui';
    $pengajuan->no_surat = $request->nomorSurat;
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
        'status' => 'success',
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
            'nis' => $item->siswa->nis ?? null,
            'nama' => $item->siswa->nama ?? 'Tidak Ditemukan',
            'kelas' => $item->siswa->kelas ?? 'Tidak Ditemukan',
            'jurusan' => $item->siswa->jurusan->jurusan . ' (' . $item->jurusan . ')' ?? 'Tidak Ditemukan',
        ];
    });

    return response()->json([
        'siswa' => $siswa
    ]);
}

public function lihatbalasan($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    // Bentuk link URL di kolom balasan_path
    $balasanPath = $pengajuan->file_balasan_path
        ? url('storage/' . ltrim($pengajuan->file_balasan_path, '/'))
        : null;

    return response()->json([
        'pengajuan' => $pengajuan,
        'balasan_url' => $balasanPath
    ]);
}







}
