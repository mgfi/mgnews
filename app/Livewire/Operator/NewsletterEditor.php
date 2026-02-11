<?php

namespace App\Livewire\Operator;

use Livewire\Component;
use App\Models\NewsletterIssue;

class NewsletterEditor extends Component
{
    public NewsletterIssue $newsletterIssue;
    public string $content = '';

    public function mount(NewsletterIssue $newsletterIssue)
    {
        $this->newsletterIssue = $newsletterIssue;
        $this->content = $newsletterIssue->content ?? '';
    }

    public function render()
    {
        return view('livewire.operator.newsletter-editor')
            ->layout('layouts.panel');
    }
}
