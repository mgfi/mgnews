<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogPost;
use App\Models\BlogTag;

class BlogComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $latestPost;
    public $search = '';
    public $selectedTags = [];

    protected $queryString = ['search', 'selectedTags'];

    protected $listeners = ['searchUpdated', 'tagsUpdated'];

    public function searchUpdated($query)
    {
        $this->search = $query;
        $this->resetPage();
    }

    public function tagsUpdated($tags)
    {
        $this->selectedTags = $tags;
        $this->resetPage();
    }

    public function toggleTag($tagId)
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_values(array_diff($this->selectedTags, [$tagId]));
        } else {
            $this->selectedTags[] = $tagId;
        }
        $this->emit('tagsUpdated', $this->selectedTags);
        $this->resetPage();
    }

    public function render()
    {
        // Pobieramy tylko tagi przypisane do postów
        $tags = BlogTag::whereHas('posts')->get();

        // Najnowszy post
        $this->latestPost = BlogPost::where('is_published', true)
            ->orderByDesc('published_at')
            ->first();

        $postsQuery = BlogPost::with(['subcategory.category', 'tags'])
            ->where('is_published', true)
            ->when($this->latestPost, fn($q) => $q->where('id', '!=', $this->latestPost->id))
            ->when(
                $this->search,
                fn($q) =>
                $q->where('title_pl', 'like', "%{$this->search}%")
                    ->orWhere('content_pl', 'like', "%{$this->search}%")
            )
            ->when(
                !empty($this->selectedTags),
                fn($q) =>
                $q->whereHas('tags', fn($query) => $query->whereIn('blog_tags.id', $this->selectedTags))
            )
            ->orderByDesc('published_at');

        $posts = $postsQuery->paginate(8);

        return view('livewire.blog-component', [
            'latestPost' => $this->latestPost,
            'posts' => $posts,
            'tags' => $tags,
            'selectedTags' => $this->selectedTags,
        ]);
    }
}
