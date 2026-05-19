<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'status', 'created_by', 'deleted_by'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function hierarchy()
    {
        return $this->hasOne(ApprovalHierarchy::class);
    }
}
