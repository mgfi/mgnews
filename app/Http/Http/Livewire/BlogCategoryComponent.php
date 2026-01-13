<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogCategory;
use App\Models\BlogSubcategory;
use App\Models\BlogPost;
use App\Models\BlogTag;

class BlogCategoryComponent extends Component
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

    public function mount($category, $subcategory = null)
    {
        $this->category = BlogCategory::where('slug_pl', $category)->firstOrFail();

        if ($subcategory) {
            $this->subcategory = BlogSubcategory::where('slug_pl', $subcategory)
                ->where('category_id', $this->category->id)
                ->firstOrFail();
        }
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
        $query = BlogPost::query()->with('tags')->where('is_published', true);

        if ($this->subcategory) {
            $query->where('subcategory_id', $this->subcategory->id);
        } else {
            $subcategoryIds = BlogSubcategory::where('category_id', $this->category->id)->pluck('id');
            $query->whereIn('subcategory_id', $subcategoryIds);
        }

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

        return view('livewire.blog-category-component', [
            'posts' => $posts,
            'selectedTags' => $this->selectedTags,
        ]);
    }
}
