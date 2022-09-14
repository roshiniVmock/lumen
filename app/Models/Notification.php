<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title', 'description', 'hasRead', 'recipient', 'recipientType'
    ];

    protected $casts = [
        'hasRead' => 'boolean',
    ];

}
