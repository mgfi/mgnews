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

    /* =====================================================
     | BASIC META
     ===================================================== */

    public string $title_pl = '';
    public ?string $preview_text_pl = null;

    /**
     * Sekcje newslettera (NOWA STRUKTURA)
     */
    public array $sections = [];

    /**
     * Temporary uploads
     * Key: section_column_block
     */
    public array $uploads = [];

    /**
     * Generated preview HTML (manual)
     */
    public string $previewHtml = '';

    /* =====================================================
     | MOUNT
     ===================================================== */

    public function mount(NewsletterIssue $newsletter): void
    {
        $this->newsletter = $newsletter;

        $this->title_pl = $newsletter->title_pl ?? '';
        $this->preview_text_pl = $newsletter->preview_text_pl;

        // NOWY FORMAT (sections)
        $this->sections = $newsletter->content_json ?? [];
    }

    /* =====================================================
     | SAVE / GENERATE
     ===================================================== */

    public function save(): void
    {
        abort_if($this->newsletter->isSent(), 403);

        $this->persistImages();

        $blocksCount = 0;
        foreach ($this->sections as $section) {
            foreach ($section['columns_data'] as $column) {
                $blocksCount += count($column);
            }
        }

        $this->newsletter->update([
            'title_pl'         => $this->title_pl,
            'preview_text_pl'  => $this->preview_text_pl,
            'content_json'     => $this->sections,
            'blocks_count'     => $blocksCount,
        ]);

        session()->flash('success', 'Newsletter saved');
    }

    public function generate(): void
    {
        $this->persistImages();

        $renderer = new NewsletterHtmlRenderer();

        $this->previewHtml = $renderer->render(
            $this->sections,
            $this->newsletter->id
        );
    }

    /* =====================================================
     | SECTIONS
     ===================================================== */

    public function addSection(int $columns): void
    {
        $this->sections[] = [
            'columns' => $columns,
            'columns_data' => array_fill(0, $columns, []),
        ];
    }

    public function removeSection(int $index): void
    {
        unset($this->sections[$index]);
        $this->sections = array_values($this->sections);
    }

    /* =====================================================
     | BLOCKS
     ===================================================== */

    public function addBlock(int $section, int $column, string $type): void
    {
        $block = match ($type) {
            'h1', 'h2', 'h3' => ['type' => $type, 'text' => ''],
            'p'              => ['type' => 'p', 'html' => ''],
            'img'            => ['type' => 'img', 'image_path' => null, 'alt' => ''],
            'button'         => ['type' => 'button', 'label' => '', 'url' => ''],
            default          => null,
        };

        if ($block) {
            $this->sections[$section]['columns_data'][$column][] = $block;
        }
    }

    public function removeBlock(int $section, int $column, int $block): void
    {
        unset($this->sections[$section]['columns_data'][$column][$block]);
        $this->sections[$section]['columns_data'][$column] =
            array_values($this->sections[$section]['columns_data'][$column]);
    }

    /* =====================================================
     | IMAGE HANDLING
     ===================================================== */

    protected function persistImages(): void
    {
        foreach ($this->sections as $sIndex => $section) {
            foreach ($section['columns_data'] as $cIndex => $column) {
                foreach ($column as $bIndex => $block) {
                    $key = "{$sIndex}_{$cIndex}_{$bIndex}";

                    if (
                        ($block['type'] ?? null) === 'img'
                        && isset($this->uploads[$key])
                    ) {
                        $path = $this->uploads[$key]
                            ->store('newsletter', 'public');

                        $this->sections[$sIndex]['columns_data'][$cIndex][$bIndex]['image_path'] = $path;

                        unset($this->uploads[$key]);
                    }
                }
            }
        }
    }

    /* =====================================================
     | RENDER
     ===================================================== */

    public function render()
    {
        return view('livewire.admin.newsletter-editor')
            ->layout('layouts.panel', [
                'navbar'  => 'partials.navbar-admin',
                'sidebar' => 'partials.sidebar-admin',
            ]);
    }
}
