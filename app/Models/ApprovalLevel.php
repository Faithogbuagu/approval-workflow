<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLevel extends Model
{
    protected $fillable = [
        'approval_hierarchy_id',
        'approver_id',
        'level'
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
