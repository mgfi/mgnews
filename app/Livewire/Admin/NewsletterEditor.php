<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\NewsletterIssue;
use App\Services\Newsletter\NewsletterHtmlRenderer;

class NewsletterEditor extends Component
{
    use WithFileUploads;

    public NewsletterIssue $newsletter;

    public string $previewHtml = '';

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

        $renderer = new NewsletterHtmlRenderer();

        $this->newsletter->update([
            'subject' => $this->subject,
            'preview_text' => $this->preview_text,
            'content_json' => $this->rows,
            'content_html' => $renderer->render($this->rows),
            'status' => 'draft',
        ]);

        session()->flash('success', 'Newsletter zapisany (JSON).');
    }
    /* =========================
 | ROW BUILDERS (z bloga)
 ========================= */

    public function addRowImgImg()
    {
        $this->rows[] = [
            ['type' => 'img'],
            ['type' => 'img'],
        ];
    }

    public function addRowPP()
    {
        $this->rows[] = [
            ['type' => 'p', 'html' => ''],
            ['type' => 'p', 'html' => ''],
        ];
    }

    public function addRowImgP()
    {
        $this->rows[] = [
            ['type' => 'img'],
            ['type' => 'p', 'html' => ''],
        ];
    }

    public function addRowPImg()
    {
        $this->rows[] = [
            ['type' => 'p', 'html' => ''],
            ['type' => 'img'],
        ];
    }

    public function addRowSingleImg()
    {
        $this->rows[] = [
            ['type' => 'img'],
        ];
    }

    public function addRowSingleP()
    {
        $this->rows[] = [
            ['type' => 'p', 'html' => ''],
        ];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }
    public function saveBlock(int $rowIndex): void
    {
        if (!isset($this->rows[$rowIndex])) {
            return;
        }

        $renderer = new NewsletterHtmlRenderer();
        $this->previewHtml = $renderer->render($this->rows);
    }



    public function render()
    {
        return view('livewire.admin.newsletter-editor');
    }
}
