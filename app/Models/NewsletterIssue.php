<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        // Subject / title
        'title_pl',
        'title_en',

        // Preheader
        'preview_text_pl',
        'preview_text_en',

        // Optional slugs
        'slug_pl',
        'slug_en',

        // Content
        'content_json',
        'content_html',
        'blocks_count',

        // State
        'status',
        'sent_at',

        // Meta
        'created_by',
    ];

    protected $casts = [
        'content_json' => 'array',
        'sent_at' => 'datetime',
    ];

    /* =========================
     | Status helpers
     ========================= */

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSending(): bool
    {
        return $this->status === 'sending';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function canBeEdited(): bool
    {
        return $this->isDraft();
    }
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSending($query)
    {
        return $query->where('status', 'sending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }
}
