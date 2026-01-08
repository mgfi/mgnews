<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\NewsletterIssue;
use Illuminate\Support\Facades\Auth;

class NewsletterIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function create()
    {
        $newsletter = NewsletterIssue::create([
            'status' => 'draft',
            'subject' => null,
            'preview_text' => null,
            'content_json' => [],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.newsletters.edit', $newsletter);
    }

    public function render()
    {
        return view('livewire.admin.newsletter-index', [
            'newsletters' => NewsletterIssue::orderByDesc('id')->paginate(10),
        ])->layout('layouts.admin');
    }
}
