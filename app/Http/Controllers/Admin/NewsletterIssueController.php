<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterIssue;

class NewsletterIssueController extends Controller
{
    public function index()
    {
        return view('admin.newsletter_issues.index', [
            'issues' => NewsletterIssue::orderByDesc('id')->get(),
        ]);
    }
}
