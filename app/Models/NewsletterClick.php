<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterClick extends Model
{
    use HasFactory;

    protected $table = 'newsletter_clicks';

    protected $fillable = [
        'newsletter_issue_id',
        'subscriber_id',
        'target_type',
        'target_id',
        'target_url',
        'hash',
        'clicked_at',
        'user_agent',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
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
