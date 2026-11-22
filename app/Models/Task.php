<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'assigned_to', 'created_by',
        'status', 'priority', 'progress', 'due_date', 'remarks',
        'submitted_at', 'verified_at',
    ];

    protected $casts = [
        'remarks'      => 'encrypted',
        'due_date'     => 'date',
        'submitted_at' => 'datetime',
        'verified_at'  => 'datetime',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}