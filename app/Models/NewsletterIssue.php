<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Newsletter\NewsletterHtmlRenderer;
use App\Models\NewsletterOpen;
use App\Models\NewsletterClick;

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
    /* =========================
 | Snapshot HTML
 ========================= */

    public function snapshotHtml(): void
    {
        // snapshot robimy tylko raz
        if (! empty($this->content_html)) {
            return;
        }

        $html = app(NewsletterHtmlRenderer::class)
            ->render($this->content_json ?? []);

        $this->update([
            'content_html' => $html,
        ]);
    }
    /* =========================
 | Tracking
 ========================= */

    public function opens()
    {
        return $this->hasMany(NewsletterOpen::class, 'newsletter_issue_id');
    }
    /* =========================
 | Click tracking
 ========================= */

    public function clicks()
    {
        return $this->hasMany(NewsletterClick::class, 'newsletter_issue_id');
    }
    /**
     * Unikalne otwarcia (per subscriber)
     */
    public function uniqueOpens(): int
    {
        return $this->opens()
            ->whereNotNull('subscriber_id')
            ->distinct('subscriber_id')
            ->count('subscriber_id');
    }

    /**
     * Unikalne kliknięcia (per subscriber + target)
     */
    public function uniqueClicks(): int
    {
        return $this->clicks()
            ->whereNotNull('subscriber_id')
            ->distinct(['subscriber_id', 'target_url'])
            ->count();
    }
    // public function scopeUnique($query)
    // {
    //     return $query
    //         ->whereNotNull('subscriber_id')
    //         ->distinct(['subscriber_id', 'target_url']);
    // }
    /**
     * Click Through Rate (CTR) in %
     */
    public function ctr(): float
    {
        $opens = $this->uniqueOpens();

        if ($opens === 0) {
            return 0.0;
        }

        $clicks = $this->uniqueClicks();

        return round(($clicks / $opens) * 100, 2);
    }
}
