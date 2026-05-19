<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestApproval extends Model
{
    protected $fillable = [
        'request_id',
        'approver_id',
        'level',
        'status',
        'comment',
        'acted_at'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
