<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat_pkl';

    protected $fillable = [
        'nim',
        'jurusan',
        'id_ta',
        'perusahaan_tujuan',
        'tanggal_pengajuan',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'kepada_yth',
        'file_balasan_path',
        'no_surat',
        'id_instrukturId'
    ];

    // protected $table = 'pengajuan_surat';

    // protected $fillable = [
    //     'anggota_1',
    //     'anggota_2',
    //     'anggota_3',
    //     'anggota_4',
    //     'jurusan',
    //     'perusahaan_tujuan',
    //     'tanggal_pengajuan',
    // ];

    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class, 'id_instrukturId', 'id_instruktur');
    }

    public function dudi()
    {
        return $this->belongsTo(Dudi::class, 'perusahaan_tujuan', 'id_dudi');
    }

    public function pengajuanDetail()
    {
        return $this->hasMany(PengajuanDetail::class, 'id_surat', 'id');
    }

}

