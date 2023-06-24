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
        'override_department_id',
    ];
}
