<?php

namespace App\Exports;

use App\Models\Penempatan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatusPKLExport implements FromQuery, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths
{
    protected $id_ta;

    public function __construct($id_ta)
    {
        $this->id_ta = $id_ta;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Penempatan::query()
            ->with('siswa', 'guru', 'instruktur', 'dudi', 'tahunAkademik')
            ->where('id_ta', $this->id_ta)
            ->where('is_active', 1);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIS',
            'NISN',
            'Nama Siswa',
            'Jurusan',
            'Perusahaan',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Guru',
            'Instruktur',
            'Status'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->siswa->nis ?? '-',
            $row->siswa->nisn ?? '-',
            $row->siswa->nama ?? '-',
            $row->siswa->jurusan->jurusan ?? '-',
            $row->dudi->nama ?? '-',
            $row->tanggal_mulai ?? '-',
            $row->tanggal_selesai ?? '-',
            $row->guru->nama ?? '-',
            $row->instruktur->nama ?? '-',
            $row->status ?? '-'
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Data Status PKL';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 10,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
        ];
    }
}
