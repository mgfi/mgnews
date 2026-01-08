<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\NewsletterIssue;

class NewsletterIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.admin.newsletter-index', [
            'newsletters' => NewsletterIssue::orderByDesc('id')->paginate(10),
        ])->layout('layouts.admin');
    }
}
