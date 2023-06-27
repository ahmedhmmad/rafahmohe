<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolVisit extends Model
{

    use HasFactory;
    protected $fillable = [
        'school_id',
        'user_id',
        'date',
        'coming_time',
        'leaving_time',
        'purpose',
        'activities',
    ];
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
