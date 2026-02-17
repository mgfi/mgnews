<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\NewsletterIssue;
use App\Models\Campaign;
use App\Services\Newsletter\NewsletterHtmlRenderer;

class NewsletterEditor extends Component
{
    use WithFileUploads;

    public NewsletterIssue $newsletter;

    /* =====================================================
     | BASIC META
     ===================================================== */

    public ?int $campaign_id = null;
    public $campaigns = [];

    public bool $creatingCampaign = false;
    public string $newCampaignTitle = '';

    public string $title_pl = '';
    public ?string $preview_text_pl = null;

    public array $sections = [];
    public array $uploads = [];

    public string $previewHtml = '';

    /* =====================================================
     | MOUNT
     ===================================================== */

    public function mount(NewsletterIssue $newsletter): void
    {
        $this->newsletter = $newsletter;

        $this->title_pl = $newsletter->title_pl ?? '';
        $this->preview_text_pl = $newsletter->preview_text_pl;

        $this->sections = $newsletter->content_json ?? [];

        $this->campaigns = Campaign::orderBy('title')->get();
        $this->campaign_id = $newsletter->campaign_id;
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
            'title_pl'        => $this->title_pl,
            'preview_text_pl' => $this->preview_text_pl,
            'content_json'    => $this->sections,
            'blocks_count'    => $blocksCount,
            'campaign_id'     => $this->campaign_id,
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
     | CREATE CAMPAIGN (INLINE)
     ===================================================== */

    public function createCampaign(): void
    {
        $this->validate([
            'newCampaignTitle' => 'required|string|max:255',
        ]);

        $campaign = Campaign::create([
            'title' => $this->newCampaignTitle,
            'is_active' => true,
        ]);

        $this->campaigns = Campaign::orderBy('title')->get();
        $this->campaign_id = $campaign->id;

        $this->newCampaignTitle = '';
        $this->creatingCampaign = false;
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
