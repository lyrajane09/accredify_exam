<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'verification_result' => 'json',
        'created_at'          => 'datetime',
        'update_dat'          => 'datetime',
    ];
}
