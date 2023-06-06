<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

   public function department()
   {
       return $this->hasMany(Department::class, 'department_id');
   }



}

