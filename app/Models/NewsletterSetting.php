<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSetting extends Model
{
    protected $fillable = [
        'locale',
        'company_name',
        'company_address',
        'company_email',
        'privacy_url',
        'footer_text',
    ];
}
