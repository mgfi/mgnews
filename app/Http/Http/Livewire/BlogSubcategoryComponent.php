<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogCategory;
use App\Models\BlogSubcategory;
use App\Models\BlogPost;

class BlogSubcategoryComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $category;
    public $subcategory;
    public $search = '';
    public $selectedTags = [];

    protected $updatesQueryString = ['search'];
    protected $listeners = [
        'searchUpdated' => 'updateSearch',
        'tagsUpdated' => 'updateTags'
    ];

    public function mount($category, $subcategory)
    {
        $this->category = BlogCategory::where('slug_pl', $category)->firstOrFail();
        $this->subcategory = BlogSubcategory::where('slug_pl', $subcategory)
            ->where('category_id', $this->category->id)
            ->firstOrFail();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function updateTags($tags)
    {
        $this->selectedTags = $tags;
        $this->resetPage();
    }

    public function render()
    {
        $query = BlogPost::query()->with('tags')
            ->where('is_published', true)
            ->where('subcategory_id', $this->subcategory->id);

        if (!empty($this->search)) {
            $query->where(
                fn($q) =>
                $q->where('title_pl', 'like', "%{$this->search}%")
                    ->orWhere('content_pl', 'like', "%{$this->search}%")
            );
        }

        if (!empty($this->selectedTags)) {
            $query->whereHas('tags', fn($q) => $q->whereIn('blog_tags.id', $this->selectedTags));
        }

        $posts = $query->orderByDesc('published_at')->paginate(6);

        return view('livewire.blog-subcategory-component', [
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'posts' => $posts,
            'selectedTags' => $this->selectedTags,
        ]);
    }
}
