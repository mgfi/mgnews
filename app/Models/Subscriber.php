<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    use SoftDeletes;

    protected $table = 'subscribers';

    protected $fillable = [
        'email',
        'is_active',
        'source',
        'user_id',
        'subscribed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Subscriber $subscriber) {
            // token do 1-click unsubscribe
            $subscriber->unsubscribe_token = Str::random(40);

            // timestamp zapisu (jeśli nie ustawiony ręcznie)
            if (is_null($subscriber->subscribed_at)) {
                $subscriber->subscribed_at = now();
            }
        });
    }

    /* =========================
     | Helpers (czytelność)
     ========================= */

    public function unsubscribe(): void
    {
        $this->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);
    }

    public function isSubscribed(): bool
    {
        return $this->is_active === true;
    }
}
