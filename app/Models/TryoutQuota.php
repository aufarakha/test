<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryoutQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quota',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
