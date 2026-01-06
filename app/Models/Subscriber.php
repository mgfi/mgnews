<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use SoftDeletes;

    protected $table = 'subscribers';

    protected $fillable = [
        'email',
        'is_active',
        'source',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
