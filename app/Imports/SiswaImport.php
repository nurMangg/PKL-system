<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Jurusan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class SiswaImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
     * Mengonversi baris Excel menjadi model siswa.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Log the incoming row for debugging
        \Log::info('Processing row: ' . json_encode($row));

        // Validasi data yang diperlukan
        if (empty($row['nis']) || empty($row['nama']) || empty($row['email']) || empty($row['id_jurusan'])) {
            \Log::warning('Skipping row due to missing required fields: ' . json_encode($row));
            return null; // Skip baris yang tidak valid
        }

        try {
            // Cari id_jurusan yang valid
            $jurusan = Jurusan::find(trim($row['id_jurusan']));
            if (!$jurusan) {
                \Log::warning('Jurusan not found for id_jurusan: ' . $row['id_jurusan']);
                return null; // Skip jika id_jurusan tidak ditemukan
            }

            // Proses update atau create siswa berdasarkan NIS
            $siswa = Siswa::updateOrCreate(
                ['nis' => trim($row['nis'])], // Mencari berdasarkan NIS
                [
                    'nisn' => trim($row['nisn'] ?? $row['nis']), // Use nis as nisn if nisn is empty
                    'nama' => trim($row['nama']),
                    'tempat_lahir' => trim($row['tempat_lahir'] ?? ''),
                    'tanggal_lahir' => !empty($row['tanggal_lahir']) ? Carbon::parse($row['tanggal_lahir'])->format('Y-m-d') : null,
                    'golongan_darah' => trim($row['golongan'] ?? ''), // Fix: use 'golongan' from CSV
                    'gender' => trim($row['gender'] ?? 'L'),
                    'no_kontak' => trim($row['no_kontak'] ?? ''),
                    'email' => trim($row['email']),
                    'alamat' => trim($row['alamat'] ?? ''),
                    'id_jurusan' => trim($row['id_jurusan']),
                    'nama_wali' => trim($row['nama_wali'] ?? ''),
                    'alamat_wali' => trim($row['alamat_wali'] ?? ''),
                    'no_kontak_wali' => trim($row['no_kontak_wali'] ?? ''),
                    'status_bekerja' => trim($row['status_bekerja'] ?? 'WFO'),
                    'is_active' => 1, // Default aktif
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id() ?? 1, // Fallback jika tidak ada user
                ]
            );

            \Log::info('Siswa created/updated: ' . $siswa->nis . ' - ' . $siswa->nama);

            // Jika siswa baru dibuat, buatkan user baru
            $userData = User::where('username', trim($row['nis']))->first();
            if (!$userData) {
                // Membuat user baru menggunakan nis sebagai username
                $user = User::create([
                    'username' => trim($row['nis']), // Gunakan nis sebagai username
                    'password' => Hash::make(trim($row['nis'])), // Gunakan nis sebagai password yang di-hash
                    'role' => '5', // Role 5, atau sesuaikan dengan kebutuhan Anda
                ]);

                \Log::info('User created for siswa: ' . $user->username);

                // Hubungkan user dengan siswa yang baru dibuat
                $siswa->update([
                    'id_user' => $user->id,
                    'created_by' => Auth::id() ?? 1,
                    'created_at' => Carbon::now(),
                ]);
            }

            return $siswa;
        } catch (\Exception $e) {
            \Log::error('Error importing siswa: ' . $e->getMessage() . ' for row: ' . json_encode($row));
            return null; // Skip baris yang error
        }
    }

    /**
     * Menentukan ukuran chunk saat membaca data dari file Excel.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 500; // Menggunakan chunk sebesar 500 baris
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'nis' => 'required|max:20',
            'nisn' => 'nullable|max:20',
            'nama' => 'required|max:100',
            'tempat_lahir' => 'nullable|max:100',
            'tanggal_lahir' => 'nullable',
            'golongan' => 'nullable|max:5', // Changed from golongan_darah to golongan
            'gender' => 'nullable|in:L,P',
            'no_kontak' => 'nullable|max:20',
            'email' => 'required|email|max:100',
            'alamat' => 'nullable|max:255',
            'id_jurusan' => 'required|max:10',
            'nama_wali' => 'nullable|max:100',
            'alamat_wali' => 'nullable|max:255',
            'no_kontak_wali' => 'nullable|max:20',
            'status_bekerja' => 'nullable|max:10',
        ];
    }

    /**
     * Handle errors during import
     */
    public function onError(\Throwable $e)
    {
        // Log error atau handle sesuai kebutuhan
        \Log::error('Import Siswa Error: ' . $e->getMessage());
    }
}
