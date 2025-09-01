<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaSekolah extends Model
{
    use HasFactory;
    protected $table = 'kepala_sekolah';
    protected $primaryKey = 'id_kepala_sekolah';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_kepala_sekolah',
        'nama',
        'nip',
        'jabatan',
        'signature_pad',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
