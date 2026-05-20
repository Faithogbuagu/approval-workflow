<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalHierarchy extends Model
{
     protected $fillable = [
        'department_id',
        'name'
    ];

    public function levels()
    {
        return $this->hasMany(ApprovalLevel::class)
            ->orderBy('level');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
