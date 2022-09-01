<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'assignee', 'creator', 'assignedDate', 'dueDate', 'description', 'status', 
    ];

    protected $casts = [
        'assignedDate' => 'date',
        'dueDate' => 'date',
    ];
    public function assignedUser()
    {
        return $this->belongsTo('App\Models\User','name','assignee');
    }

    public function createdUser()
    {
        return $this->belongsTo('App\Models\User','name','creator');
    }

    protected $primaryKey = 'title';
    public $incrementing = false;
}
