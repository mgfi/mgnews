<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\NewsletterIssue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\Newsletter\NewsletterHtmlRenderer;
use App\Services\Newsletter\NewsletterStatsService;

class NewsletterIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs
    |--------------------------------------------------------------------------
    */

    protected function breadcrumbs(): array
    {
        return [
            [
                'route' => 'admin.dashboard',
                'label' => __('Dashboard'),
            ],
            [
                'label' => __('livAdmNewInd.title'),
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $newsletter = NewsletterIssue::create([
            'title_pl'        => 'Nowy newsletter',
            'title_en'        => 'New newsletter',
            'preview_text_pl' => null,
            'preview_text_en' => null,
            'status'          => 'draft',
            'content_json'    => [],
            'created_by'      => Auth::id(),
        ]);

        return redirect()->route('admin.newsletters.edit', $newsletter);
    }

    /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | Test Send
    |--------------------------------------------------------------------------
    */

    public function sendTest(int $newsletterId): void
    {
        $newsletter = NewsletterIssue::findOrFail($newsletterId);

        if (empty($newsletter->content_json) || count($newsletter->content_json) === 0) {
            session()->flash('error', 'Newsletter nie ma żadnych bloków.');
            return;
        }

        $renderer = new NewsletterHtmlRenderer();
        $html = $renderer->render($newsletter->content_json);

        Mail::html($html, function ($message) use ($newsletter) {
            $message
                ->to(Auth::user()->email)
                ->subject('[TEST] ' . ($newsletter->subject ?? 'Newsletter testowy'));
        });

        session()->flash('success', 'Testowy email został wysłany na Twój adres.');
    }

    /*
    |--------------------------------------------------------------------------
    | Send (Queue)
    |--------------------------------------------------------------------------
    */

    public function send(int $newsletterId): void
    {
        logger()->info('SEND REQUEST', ['id' => $newsletterId]);

        \App\Jobs\SendNewsletterIssueJob::dispatch($newsletterId);

        session()->flash(
            'success',
            'Newsletter został zakolejkowany do wysyłki.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $newsletters = NewsletterIssue::query()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $statsService = app(NewsletterStatsService::class);

        $stats = [];
        foreach ($newsletters as $issue) {
            $stats[$issue->id] = $statsService->getForIssue($issue);
        }

        return view('livewire.admin.newsletter-index', [
            'newsletters' => $newsletters,
            'stats'       => $stats,
        ])
            ->layout('layouts.panel', [
                'navbar'      => 'partials.navbar-admin',
                'sidebar'     => 'partials.sidebar-admin',
                'breadcrumbs' => $this->breadcrumbs(),
            ]);
    }
}
