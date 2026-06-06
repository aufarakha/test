<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'std_code',
        'std_name',
        'std_nisn',
        'std_gender',
        'std_dob',
        'std_npsn',
        'sch_code',
        'std_school',
        'std_class',
        'std_email',
        'std_phone',
        'kompetensi_keahlian',
        'program_keahlian',
        'bidang_keahlian',
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'std_dob' => 'date',
            'is_banned' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function tryoutQuota()
    {
        return $this->hasOne(TryoutQuota::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }
}
