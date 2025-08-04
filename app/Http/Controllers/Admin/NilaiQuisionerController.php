<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NilaiQuesionerExport;
use App\Http\Controllers\Controller;

use App\Models\NilaiQuesioner;
use App\Models\Quesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ThnAkademik;

class NilaiQuisionerController extends Controller
{
    public function index()
    {
        $aktifAkademik = getActiveAcademicYear();
        $thnAkademik = ThnAkademik::where('is_active', true)->orderBy('tahun_akademik', 'desc')->get();
        $questions = Quesioner::where(['is_active' => true, 'id_ta' => $aktifAkademik['id_ta']])->get();
        return view('pkl.nilai-quesioner.index', compact('questions', 'thnAkademik', 'aktifAkademik'));
    }

    public function getQuestions(Request $request)
    {
        $questions = Quesioner::where(['is_active' => true, 'id_ta' => $request->id_ta])->get();
        return response()->json($questions);
    }

    public function data(Request $request)
    {
        $data = NilaiQuesioner::with(['instruktur', 'thnAkademik'])
            ->selectRaw('instruktur.id_instruktur, instruktur.nama as nama_instruktur, thn_akademik.id_ta, thn_akademik.tahun_akademik, nilai_quesioner.created_at')
            ->join('instruktur', 'nilai_quesioner.id_instruktur', '=', 'instruktur.id_instruktur')
            ->join('thn_akademik', 'nilai_quesioner.id_ta', '=', 'thn_akademik.id_ta')
            ->where('nilai_quesioner.id_ta', $request->id_ta)
            ->groupBy('instruktur.id_instruktur', 'thn_akademik.id_ta', 'instruktur.nama','thn_akademik.tahun_akademik', 'nilai_quesioner.created_at');

        return DataTables::of($data)
            ->addColumn('action', function ($dt) {
                return ' <button class="btn btn-sm btn-primary btn-lihat" data-id="'.$dt->id_instruktur.'"><i class="bi bi-eye-fill"></i></button> | <button class="btn btn-sm btn-danger btn-delete" data-id="'.$dt->id_instruktur.'"><i class="bi bi-trash-fill"></i></button>';
            })
            ->editColumn('nama_instruktur', function ($dt) {
                if ($dt) {
                    return '<a href="' . url("/d/instruktur?id=" . $dt->id_instruktur) . '">' . $dt->nama_instruktur . '</a>';
                }
                return '-';
            })
            ->addColumn('dudi', function ($dt) {
                if ($dt) {
                    return '<a href="' . url("/d/dudi?id=" . $dt->instruktur->dudi->id_dudi) . '">' . $dt->instruktur->dudi->nama . '</a>';
                }
                return '-';
            })
            ->editColumn('created_at', function ($dt) {
                // Tampilkan hari dan tanggal dengan nama bulan dalam bahasa Indonesia
                \Carbon\Carbon::setLocale('id');
                $hari = $dt->created_at->isoFormat('dddd'); // Nama hari dalam bahasa Indonesia
                $tanggal = $dt->created_at->format('d');
                $bulan = $dt->created_at->isoFormat('MMMM'); // Nama bulan dalam bahasa Indonesia
                $tahun = $dt->created_at->format('Y');
                return "$hari, $tanggal $bulan $tahun";
            })
            ->rawColumns(['action', 'nama_instruktur', 'dudi'])
            ->make(true);
    }

    // Upsert (Insert or Update) a record
    // Function to handle upsert operation
    public function upsert(Request $request)
    {
        $isUpdate = $request->stt;

        // Validasi data
        $request->validate([
            'tanggal' => 'required|date',
            'id_ta' => 'required|exists:thn_akademik,id_ta',
            'id_instruktur' => 'required|exists:instruktur,id_instruktur',
            'quesioner' => 'required|array',
            'quesioner.*' => 'required|string|max:255',
        ]);

        // Lakukan penyimpanan untuk setiap quesioner
        foreach ($request->quesioner as $idQuesioner => $nilai) {
            $data = [
                'tanggal' => $request->tanggal,
                'id_instruktur' => $request->id_instruktur,
                'id_quesioner' => $idQuesioner,
                'nilai' => $nilai,
                'created_by' => Auth::id(),
            ];

            if ($isUpdate) {
                // Jika update, cari data yang ada dan update
                NilaiQuesioner::updateOrCreate(
                    ['id_nilai' => $request->id_nilai[$idQuesioner], 'id_quesioner' => $idQuesioner],
                    $data
                );
            } else {
                // Insert baru
                NilaiQuesioner::create($data);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan']);
    }

    public function edit(Request $request)
    {
        // Mencari data NilaiQuesioner berdasarkan nis dan id_ta
        $nilaiRecords = NilaiQuesioner::with(['instruktur', 'quesioner'])
            ->where('id_instruktur', $request->id_instruktur)
            ->whereHas('quesioner', function ($query) use ($request) {
                $query->where('id_ta', $request->id_ta);
            })
            ->where('nilai_quesioner.is_active', true)
            ->get();
            // dd($nilaiRecords);

        // Cek jika data tidak ditemukan
        if ($nilaiRecords->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        // Mengambil semua quesioner terkait dan nilainya dari NilaiQuesioner
        $quesionerData = $nilaiRecords->map(function ($nilai) {
            return [
                'id_nilai' => $nilai->id_nilai,
                'id_quesioner' => $nilai->quesioner->id_quesioner,
                'soal' => $nilai->quesioner->soal,
                'nilai' => $nilai->nilai,  // Mengambil nilai langsung dari tabel NilaiQuesioner
            ];
        });

        // Mengambil data siswa dan tanggal dari satu record (karena diasumsikan sama untuk semua quesioner terkait)
        $firstRecord = $nilaiRecords->first();

        // Mengembalikan response dalam format JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'tanggal' => $firstRecord->tanggal,
                'id_instruktur' => $firstRecord->id_instruktur,
                'nama_instruktur' => $firstRecord->instruktur->nama,
                'quesioner' => $quesionerData,
            ]
        ]);
    }

    public function view(Request $request)
    {
        // Mencari data NilaiQuesioner berdasarkan id_instruktur dan id_ta
        $nilaiRecords = NilaiQuesioner::with(['instruktur', 'quesioner'])
            ->where('id_instruktur', $request->id_instruktur)
            ->whereHas('quesioner', function ($query) use ($request) {
                $query->where('id_ta', $request->id_ta);
            })
            ->where('nilai_quesioner.is_active', true)
            ->get();

        // Cek jika data tidak ditemukan
        if ($nilaiRecords->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        // Mengambil semua quesioner terkait dan nilainya dari NilaiQuesioner
        $quesionerData = $nilaiRecords->map(function ($nilai) {
            return [
                'id_nilai' => $nilai->id_nilai,
                'id_quesioner' => $nilai->quesioner->id_quesioner,
                'soal' => $nilai->quesioner->soal,
                'nilai' => $nilai->nilai,  // Mengambil nilai langsung dari tabel NilaiQuesioner
            ];
        });

        // Mengambil data instruktur dan tanggal dari satu record
        $firstRecord = $nilaiRecords->first();

        // Mengembalikan response dalam format JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'tanggal' => $firstRecord->tanggal,
                'id_instruktur' => $firstRecord->id_instruktur,
                'nama_instruktur' => $firstRecord->instruktur->nama,
                'quesioner' => $quesionerData,
            ]
        ]);
    }

    // Delete a record
    public function destroy(Request $request)
    {
        // Mengupdate is_active menjadi false untuk semua record yang ditemukan
        NilaiQuesioner::where('id_instruktur', $request->id_instruktur)
            ->whereHas('quesioner', function ($query) use ($request) {
                $query->where('id_ta', $request->id_ta);
            })
            ->update(['nilai_quesioner.is_active' => false]);

        return response()->json([
            'status' => true,
            'message' => 'NilaiQuesioner berhasil dihapus.'
        ]);
    }

    public function downloadExcel(Request $request)
    {
        // Menggunakan export class untuk mendownload Excel
        return Excel::download(new NilaiQuesionerExport($request->id), 'data-nilai-quesioner-'.$request->id.' '.date('Y-m-d').'.xlsx');
    }
}
