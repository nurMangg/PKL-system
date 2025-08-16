<?php

namespace App\Imports;

use App\Models\Dudi;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Carbon\Carbon;

class DudiImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
     * Mengonversi baris Excel menjadi model Dudi.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validasi data yang diperlukan
        if (empty($row['nama'])) {
            \Log::warning('Skipping row due to missing required fields: ' . json_encode($row));
            return null; // Skip baris yang tidak valid
        }

        try {
            // Melakukan updateOrCreate data Dudi berdasarkan nama (karena id_dudi auto increment)
            $dudi = Dudi::updateOrCreate(
                ['nama' => trim($row['nama'])], // Mencari berdasarkan nama
                [
                    'alamat' => trim($row['alamat'] ?? ''),
                    'no_kontak' => trim($row['no_kontak'] ?? ''),
                    'longitude' => trim($row['longitude'] ?? ''),
                    'latitude' => trim($row['latitude'] ?? ''),
                    'radius' => trim($row['radius'] ?? ''),
                    'nama_pimpinan' => trim($row['nama_pimpinan'] ?? ''),
                    'is_active' => isset($row['is_active']) ? (int)$row['is_active'] : 1, // Default aktif
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id() ?? 1, // Fallback jika tidak ada user
                ]
            );

            // Jika dudi baru dibuat, set created_by dan created_at
            if ($dudi->wasRecentlyCreated) {
                $dudi->update([
                    'created_by' => Auth::id() ?? 1,
                    'created_at' => Carbon::now(),
                ]);
            }

            \Log::info('Dudi created/updated: ' . $dudi->id_dudi . ' - ' . $dudi->nama);
            return $dudi;
        } catch (\Exception $e) {
            \Log::error('Error importing dudi: ' . $e->getMessage() . ' for row: ' . json_encode($row));
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
            'alamat' => 'nullable|string|max:255',
            'no_kontak' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'latitude' => 'nullable|string|max:20',
            'radius' => 'nullable|string|max:20',
            'nama_pimpinan' => 'nullable|string|max:100',
            'is_active' => 'nullable|integer|in:0,1',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama Dudi wajib diisi',
            'is_active.in' => 'Status aktif harus 0 atau 1',
        ];
    }

    /**
     * Handle errors during import
     */
    public function onError(\Throwable $e)
    {
        // Log error atau handle sesuai kebutuhan
        \Log::error('Import Dudi Error: ' . $e->getMessage());
    }
}
