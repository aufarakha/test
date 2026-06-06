<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryoutPricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'tryout_quota_cost',
        'view_answer_quota_cost',
    ];
}
