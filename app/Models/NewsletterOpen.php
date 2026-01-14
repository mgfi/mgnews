<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterOpen extends Model
{
    use HasFactory;

    protected $table = 'newsletter_opens';

    protected $fillable = [
        'newsletter_issue_id',
        'subscriber_id',
        'opened_at',
        'user_agent',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
    ];

    /* =========================
     | Relacje
     ========================= */

    public function issue()
    {
        return $this->belongsTo(NewsletterIssue::class, 'newsletter_issue_id');
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
