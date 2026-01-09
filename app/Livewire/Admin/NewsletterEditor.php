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

    /**
     * Email subject (PL)
     */
    public string $title_pl = '';

    /**
     * Email preview text (preheader, PL)
     */
    public ?string $preview_text_pl = null;

    /**
     * Newsletter rows structure
     */
    public array $rows = [];

    public array $uploads = [];

    public function mount(NewsletterIssue $newsletter)
    {
        $this->newsletter = $newsletter;

        $this->title_pl = $newsletter->title_pl;
        $this->preview_text_pl = $newsletter->preview_text_pl;
        $this->rows = $newsletter->content_json ?? [];
    }

    public function save()
    {
        abort_if($this->newsletter->isSent(), 403);

        // Count total blocks (for listing & sorting)
        $blocksCount = 0;

        foreach ($this->rows as $row) {
            if (is_array($row)) {
                $blocksCount += count($row);
            }
        }

        $this->newsletter->update([
            'title_pl' => $this->title_pl,
            'preview_text_pl' => $this->preview_text_pl,
            'content_json' => $this->rows,
            'blocks_count' => $blocksCount,
        ]);

        return redirect()
            ->route('admin.newsletters.index')
            ->with('success', 'Newsletter zapisany');
    }

    /* =========================
     | ROW BUILDERS
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
        return view('livewire.admin.newsletter-editor')
            ->layout('layouts.admin');
    }
}
