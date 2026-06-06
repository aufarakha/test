<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiKeahlian extends Model
{
    use HasFactory;

    protected $fillable = [
        'kompetensi_id',
        'kompetensi_name',
        'program_id',
        'program_name',
        'bidang_id',
        'bidang_name',
    ];
}
