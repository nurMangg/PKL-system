<?php

namespace App\Imports;

use App\Models\Jurusan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class JurusanImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
     * Transform each row into a Jurusan model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validasi data yang diperlukan
        if (empty($row['id_jurusan']) || empty($row['jurusan']) || empty($row['singkatan'])) {
            return null; // Skip baris yang tidak valid
        }

        // Cek apakah id_jurusan sudah ada, jika ada update, jika tidak insert
        return Jurusan::updateOrCreate(
            ['id_jurusan' => trim($row['id_jurusan'])], // Kunci untuk upsert
            [
                'jurusan' => trim($row['jurusan']),
                'singkatan' => trim($row['singkatan']),
                'is_active' => 1, // Default value
                'updated_by' => Auth::id() ?? 1, // Fallback jika tidak ada user
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'id_jurusan' => 'required|string|max:10',
            'jurusan' => 'required|string|max:100',
            'singkatan' => 'required|string|max:100',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'id_jurusan.required' => 'ID Jurusan wajib diisi',
            'jurusan.required' => 'Nama Jurusan wajib diisi',
            'singkatan.required' => 'Singkatan wajib diisi',
        ];
    }

    /**
     * Handle errors during import
     */
    public function onError(\Throwable $e)
    {
        // Log error atau handle sesuai kebutuhan
        \Log::error('Import Jurusan Error: ' . $e->getMessage());
    }
}
