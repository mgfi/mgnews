<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\NewsletterIssue;
use App\Services\Newsletter\NewsletterHtmlRenderer;
use Illuminate\Support\Facades\Storage;

class NewsletterEditor extends Component
{
    use WithFileUploads;

    public NewsletterIssue $newsletter;

    /**
     * Rendered HTML preview (optional, future use)
     */
    public string $previewHtml = '';

    /**
     * Email subject (PL)
     */
    public string $title_pl = '';

    /**
     * Email preview text / preheader (PL)
     */
    public ?string $preview_text_pl = null;

    /**
     * Newsletter rows structure (blocks layout)
     */
    public array $rows = [];

    /**
     * Temporary uploads (Livewire)
     * Key format: rowIndex_colIndex
     */
    public array $uploads = [];

    public function mount(NewsletterIssue $newsletter): void
    {
        $this->newsletter = $newsletter;

        $this->title_pl = $newsletter->title_pl ?? '';
        $this->preview_text_pl = $newsletter->preview_text_pl;
        $this->rows = $newsletter->content_json ?? [];
    }

    /**
     * Save newsletter draft
     */
    public function save()
    {
        abort_if($this->newsletter->isSent(), 403);

        // Persist uploaded images to storage and update rows
        $this->persistImages();

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

    /* =====================================================
     | ROW BUILDERS
     ===================================================== */

    public function addRowImgImg(): void
    {
        $this->rows[] = [
            ['type' => 'img', 'alt' => ''],
            ['type' => 'img', 'alt' => ''],
        ];
    }

    public function addRowPP(): void
    {
        $this->rows[] = [
            ['type' => 'p', 'html' => ''],
            ['type' => 'p', 'html' => ''],
        ];
    }

    public function addRowImgP(): void
    {
        $this->rows[] = [
            ['type' => 'img', 'alt' => ''],
            ['type' => 'p', 'html' => ''],
        ];
    }

    public function addRowPImg(): void
    {
        $this->rows[] = [
            ['type' => 'p', 'html' => ''],
            ['type' => 'img', 'alt' => ''],
        ];
    }

    public function addRowSingleImg(): void
    {
        $this->rows[] = [
            ['type' => 'img', 'alt' => ''],
        ];
    }

    public function addRowSingleP(): void
    {
        $this->rows[] = [
            ['type' => 'p', 'html' => ''],
        ];
    }

    public function removeRow(int $index): void
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    /* =====================================================
     | IMAGE PERSISTENCE (CRITICAL FOR EMAILS)
     ===================================================== */

    /**
     * Persist uploaded images to public storage
     * and replace temporary uploads with image_path
     */
    protected function persistImages(): void
    {
        foreach ($this->rows as $rIndex => $row) {
            foreach ($row as $cIndex => $block) {
                $key = $rIndex . '_' . $cIndex;

                if (
                    ($block['type'] ?? null) === 'img'
                    && isset($this->uploads[$key])
                ) {
                    // Store image in /storage/newsletter
                    $path = $this->uploads[$key]->store('newsletter', 'public');

                    // Save public path into content_json
                    $this->rows[$rIndex][$cIndex]['image_path'] = $path;

                    // Ensure alt key exists
                    if (!isset($this->rows[$rIndex][$cIndex]['alt'])) {
                        $this->rows[$rIndex][$cIndex]['alt'] = '';
                    }

                    // Remove temp upload
                    unset($this->uploads[$key]);
                }
            }
        }
    }

    /**
     * Optional HTML preview rendering (not used in sending)
     */
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
