<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\NewsletterIssue;

class NewsletterEditor extends Component
{
    use WithFileUploads;

    public NewsletterIssue $newsletter;

    public string $subject = '';
    public ?string $preview_text = null;

    public array $rows = [];
    public array $uploads = [];

    public function mount(NewsletterIssue $newsletter)
    {
        $this->newsletter = $newsletter;

        $this->subject = $newsletter->subject;
        $this->preview_text = $newsletter->preview_text;
        $this->rows = $newsletter->content_json ?? [];
    }

    public function save()
    {
        abort_if($this->newsletter->isSent(), 403);

        $this->newsletter->update([
            'subject' => $this->subject,
            'preview_text' => $this->preview_text,
            'content_json' => $this->rows,
        ]);

        session()->flash('success', 'Newsletter zapisany (JSON).');
    }

    public function render()
    {
        return view('livewire.admin.newsletter-editor');
    }
}
