<?php

namespace App\Livewire\Operator;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\NewsletterIssue;

#[Layout('layouts.panel')]
class NewsletterIndex extends Component
{
    use WithPagination;

    public function render()
    {
        return view('operator.newsletters.index', [
            'newsletters' => NewsletterIssue::latest()->paginate(15),
            'navbar'  => 'partials.navbar-operator',
            'sidebar' => 'partials.sidebar-operator',
        ]);
    }
}
