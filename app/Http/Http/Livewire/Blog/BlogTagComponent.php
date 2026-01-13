<?php

namespace App\Http\Livewire\Blog;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogTag;

class BlogTagComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $tag; // slug tagu

    public function mount($tag)
    {
        $this->tag = $tag;
    }

    public function render()
    {
        $tagModel = BlogTag::where('slug_pl', $this->tag)->firstOrFail();

        $posts = $tagModel->posts()
            ->where('is_published', true)
            ->with(['subcategory.category', 'tags'])
            ->orderByDesc('published_at')
            ->paginate(8);

        return view('livewire.blog.blog-tag-component', [
            'tagModel' => $tagModel,
            'posts' => $posts,
        ]);
    }
}
