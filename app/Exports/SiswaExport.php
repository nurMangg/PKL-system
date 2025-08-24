<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    protected $jurusanId;

    /**
     * Constructor untuk menerima parameter jurusan
     */
    public function __construct($jurusanId = null)
    {
        $this->jurusanId = $jurusanId;
    }

    /**
     * Mengambil data dari model Siswa dan mengembalikannya sebagai koleksi.
     * Jika login sebagai admin jurusan, hanya tampilkan data siswa dari jurusan tersebut.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Siswa::active()->with('jurusan');

        // Filter berdasarkan jurusan yang diberikan atau role user yang login
        if ($this->jurusanId) {
            // Jika jurusan ID diberikan secara eksplisit
            $query->where('id_jurusan', $this->jurusanId);
        } elseif (Auth::check() && Auth::user()->role == 2) {
            // Jika admin jurusan, filter berdasarkan jurusan yang dia kelola
            $sessionJurusan = session('id_jurusan');
            if ($sessionJurusan) {
                $query->where('id_jurusan', $sessionJurusan);
            }
        }

        return $query->get([
                'nis',
                'nisn',
                'nama',
                'tempat_lahir',
                'tanggal_lahir',
                'golongan_darah',
                'gender',
                'foto',
                'no_kontak',
                'email',
                'alamat',
                'id_jurusan',
                'nama_wali',
                'alamat_wali',
                'no_kontak_wali',
                'status_bekerja',
            ])
            ->map(function($siswa) {
                // Generate URL asset for foto if exists
                $siswa->foto = $siswa->foto ? asset('storage/' . $siswa->foto) : null;

                // Tambahkan nama jurusan yang lebih user-friendly
                if ($siswa->jurusan) {
                    $siswa->nama_jurusan = $siswa->jurusan->jurusan;
                } else {
                    $siswa->nama_jurusan = 'N/A';
                }

                return $siswa;
            });
    }

    /**
     * Menambahkan heading pada file Excel yang diexport.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIS',
            'NISN',
            'Nama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Golongan Darah',
            'Gender',
            'Foto',
            'No Kontak',
            'Email',
            'Alamat',
            'ID Jurusan',
            'Nama Jurusan',
            'Nama Wali',
            'Alamat Wali',
            'No Kontak Wali',
            'Status Bekerja',
        ];
    }
}
