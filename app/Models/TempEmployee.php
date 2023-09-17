<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'id',
        'department_id',
        'contact_number',
        'email',
        'assigned_by',
    ];

    // TempEmployee.php

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
