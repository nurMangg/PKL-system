<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterQuesioner extends Model
{
    use HasFactory;
    protected $table = 'masterQuesioner';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'nama'
    ];
}
