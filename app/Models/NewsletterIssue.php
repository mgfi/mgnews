<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Newsletter\NewsletterHtmlRenderer;
use App\Models\NewsletterOpen;
use App\Models\NewsletterClick;
use App\Models\Campaign;

class NewsletterIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        // Campaign
        'campaign_id',

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
     | Campaign
     ========================= */

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function hasCampaign(): bool
    {
        return $this->campaign_id !== null;
    }

    public function scopeInCampaign($query, int $campaignId)
    {
        return $query->where('campaign_id', $campaignId);
    }

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
        return $this->hasMany(NewsletterOpen::class);
    }

    public function clicks()
    {
        return $this->hasMany(NewsletterClick::class);
    }

    public function uniqueOpens(): int
    {
        return $this->opens()
            ->whereNotNull('subscriber_id')
            ->distinct('subscriber_id')
            ->count('subscriber_id');
    }

    public function uniqueClicks(): int
    {
        return $this->clicks()
            ->whereNotNull('subscriber_id')
            ->distinct(['subscriber_id', 'target_url'])
            ->count();
    }

    public function ctr(): float
    {
        $opens = $this->uniqueOpens();

        if ($opens === 0) {
            return 0.0;
        }

        return round(($this->uniqueClicks() / $opens) * 100, 2);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
