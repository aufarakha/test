<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'std_code',
        'sch_code',
        'listening_score',
        'reading_score',
        'total_score',
        'jawaban_peserta',
        'device',
        'is_view_only',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'jawaban_peserta' => 'array',
            'is_view_only' => 'boolean',
            'is_locked' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
