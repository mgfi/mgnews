<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'is_active',
        'last_sent_at',
    ];

    public function newsletters()
    {
        return $this->hasMany(NewsletterIssue::class);
    }
}
