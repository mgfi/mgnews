<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogTag;
use App\Models\BlogPost;

class BlogPostTagSearchComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $tags; // wszystkie dostępne tagi
    public $selectedTags = []; // wybrane tagi
    public $post; // aktualny post, jeśli chcesz zaznaczyć tagi przypisane do niego

    protected $queryString = ['selectedTags'];

    public function mount(BlogPost $post = null)
    {
        $this->tags = BlogTag::all();

        if ($post) {
            $this->post = $post;
            $this->selectedTags = $post->tags->pluck('id')->toArray();
        }
    }

    public function updatedSelectedTags()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = BlogPost::query()->where('is_published', true);

        if (!empty($this->selectedTags)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('id', $this->selectedTags);
            });
        }

        $posts = $query->orderByDesc('published_at')->paginate(6);

        return view('livewire.blog-post-tag-search-component', [
            'posts' => $posts,
            'tags' => $this->tags,
            'selectedTags' => $this->selectedTags,
        ]);
    }
}
