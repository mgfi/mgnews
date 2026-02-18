<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterIssue;

class NewsletterIssueController extends Controller
{
    public function index()
    {
        // $issues = NewsletterIssue::with('campaign')
        //     ->orderByDesc('id')
        //     ->paginate(20);
        $issues = NewsletterIssue::with('campaign')
            ->orderByDesc('id')
            ->paginate(5);

        return view('admin.newsletter_issues.index', compact('issues'));
    }
}
