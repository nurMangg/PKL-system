<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class GuruImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
     * Mengonversi baris Excel menjadi model guru.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validasi data yang diperlukan
        if (empty($row['id_guru']) || empty($row['nama']) || empty($row['email'])) {
            return null; // Skip baris yang tidak valid
        }

        try {
            // Jika id_guru sudah ada, update atau buat data baru
            $guru = Guru::updateOrCreate(
                ['id_guru' => trim($row['id_guru'])], // Mencari berdasarkan id_guru
                [
                    'nama' => trim($row['nama']),
                    'gender' => trim($row['gender'] ?? 'L'),
                    'no_kontak' => trim($row['no_kontak'] ?? ''),
                    'email' => trim($row['email']),
                    'alamat' => trim($row['alamat'] ?? ''),
                    'id_jurusan' => trim($row['id_jurusan'] ?? ''),
                    'is_active' => 1, // Default aktif
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id() ?? 1, // Fallback jika tidak ada user
                ]
            );

            // Jika guru baru dibuat, buatkan user baru
            $userData = User::where('username', trim($row['id_guru']))->first();
            if (!$userData) {
                // Membuat user baru menggunakan id_guru sebagai username
                $user = User::create([
                    'username' => trim($row['id_guru']), // Gunakan id_guru sebagai username
                    'password' => Hash::make(trim($row['id_guru'])), // Gunakan id_guru sebagai password yang di-hash
                    'role' => '3', // Role 3, atau sesuaikan dengan kebutuhan Anda
                ]);

                // Hubungkan user dengan guru yang baru dibuat
                $guru->update([
                    'id_user' => $user->id,
                    'created_by' => Auth::id() ?? 1,
                    'created_at' => Carbon::now(),
                ]);
            }

            return $guru;
        } catch (\Exception $e) {
            \Log::error('Error importing guru: ' . $e->getMessage() . ' for row: ' . json_encode($row));
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
            'id_guru' => 'required|max:20',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'gender' => 'nullable|string|in:L,P',
            'no_kontak' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'id_jurusan' => 'nullable|string|max:10',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'id_guru.required' => 'ID Guru wajib diisi',
            'nama.required' => 'Nama Guru wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'gender.in' => 'Gender harus L atau P',
        ];
    }

    /**
     * Handle errors during import
     */
    public function onError(\Throwable $e)
    {
        // Log error atau handle sesuai kebutuhan
        \Log::error('Import Guru Error: ' . $e->getMessage());
    }
}
