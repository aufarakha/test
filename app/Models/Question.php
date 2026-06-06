<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'type',
        'question',
        'options',
        'answer',
        'score',
        'audio_url',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }
}
