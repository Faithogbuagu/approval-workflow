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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}
