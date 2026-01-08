<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'preview_text',
        'content_json',
        'content_html',
        'status',
        'scheduled_at',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'content_json' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /* =========================
     | Status helpers
     ========================= */

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function canBeEdited(): bool
    {
        return ! $this->isSent();
    }
}
