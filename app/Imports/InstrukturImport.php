<?php

namespace App\Imports;

use App\Models\Instruktur;
use App\Models\User;
use App\Models\Dudi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Carbon\Carbon;

class InstrukturImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
     * Mengonversi baris Excel menjadi model Instruktur.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validasi data yang diperlukan
        if (empty($row['nama']) || empty($row['email']) || empty($row['id_dudi'])) {
            return null; // Skip baris yang tidak valid
        }

        try {
            // Cari id_dudi yang valid (misalnya, id_dudi harus ada di tabel Dudi)
            $dudi = Dudi::find(trim($row['id_dudi']));
            if (!$dudi) {
                \Log::warning('Dudi not found for id_dudi: ' . $row['id_dudi']);
                return null; // Skip jika id_dudi tidak ditemukan
            }

            $id_instruktur = random_int(100000000000000, 999999999999999);

            // Jika id_instruktur sudah ada, update atau buat data baru
            $instruktur = Instruktur::updateOrCreate(
                ['id_instruktur' => $id_instruktur], // Mencari berdasarkan id_instruktur
                [
                    'nama' => trim($row['nama']),
                    'gender' => trim($row['gender'] ?? 'L'),
                    'no_kontak' => trim($row['no_kontak'] ?? ''),
                    'email' => trim($row['email']),
                    'alamat' => trim($row['alamat'] ?? ''),
                    'id_dudi' => trim($row['id_dudi']),
                    'is_active' => 1, // Default aktif
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id() ?? 1, // Fallback jika tidak ada user
                ]
            );

            // Jika instruktur baru dibuat, buatkan user baru
            $userData = User::where('username', $id_instruktur)->first();
            if (!$userData) {
                // Membuat user baru menggunakan id_instruktur sebagai username
                $user = User::create([
                    'username' => $id_instruktur, // Gunakan id_instruktur sebagai username
                    'password' => Hash::make(trim($row['id_instruktur'])), // Gunakan id_instruktur sebagai password yang di-hash
                    'role' => '4', // Role 4, atau sesuaikan dengan kebutuhan Anda
                ]);

                // Hubungkan user dengan instruktur yang baru dibuat
                $instruktur->update([
                    'id_user' => $user->id,
                    'created_by' => Auth::id() ?? 1,
                    'created_at' => Carbon::now(),
                ]);
            }

            return $instruktur;
        } catch (\Exception $e) {
            \Log::error('Error importing instruktur: ' . $e->getMessage() . ' for row: ' . json_encode($row));
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
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'gender' => 'nullable|string|in:L,P',
            'no_kontak' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'id_dudi' => 'required|string|max:20',
        ];
    }

    /**
     * Handle errors during import
     */
    public function onError(\Throwable $e)
    {
        // Log error atau handle sesuai kebutuhan
        \Log::error('Import Instruktur Error: ' . $e->getMessage());
    }
}
