<?php

namespace App\Http\Livewire\Blog;

use Livewire\Component;
use App\Models\BlogCategory;

class BlogSidebar extends Component
{
    public $categories;
    public $openCategory = null; // ID rozwiniętej kategorii
    public $selectedCategory = null; // Wybrana kategoria lub subkategoria

    protected $listeners = ['subcategorySelected' => 'filterPosts'];

    public function mount()
    {
        $this->categories = BlogCategory::with('subcategories.posts')->get();
    }

    public function toggleCategory($categoryId)
    {
        $this->openCategory = $this->openCategory === $categoryId ? null : $categoryId;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->emit('categorySelected', $categoryId);
    }

    public function selectSubcategory($subcategoryId)
    {
        $this->selectedCategory = $subcategoryId;
        $this->emit('subcategorySelected', $subcategoryId);
    }

    public function render()
    {
        return view('livewire.blog.blog-sidebar');
    }
}
