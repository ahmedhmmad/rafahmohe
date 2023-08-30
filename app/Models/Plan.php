<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'school_id',
        'user_id',
        'department_id',
        'start',
        'end',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function visitor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
