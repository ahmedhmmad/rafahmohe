<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanRestriction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'can_override_last_week',
        'can_override_department',
        'override_start_date',
        'override_end_date',
        'override_week_number',
        'can_override_multi_department',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function overrideDepartment()
    {
        return $this->belongsTo(Department::class, 'override_department_id');
    }
}
