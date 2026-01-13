<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogCategory;

class BlogCategories extends Component
{
    use WithPagination;

    public $name_pl, $name_en, $slug_pl, $slug_en, $categoryId;
    public $isEdit = false;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name_pl' => 'required|string|max:255',
        'slug_pl' => 'required|string|max:255|unique:blog_categories,slug_pl',
        'name_en' => 'nullable|string|max:255',
        'slug_en' => 'nullable|string|max:255|unique:blog_categories,slug_en',
    ];

    public function render()
    {
        $categories = BlogCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.blog-categories', compact('categories'));
    }

    public function resetForm()
    {
        $this->reset(['name_pl', 'name_en', 'slug_pl', 'slug_en', 'categoryId', 'isEdit']);
        $this->resetValidation();
    }

    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);
        $this->categoryId = $id;
        $this->name_pl = $category->name_pl;
        $this->name_en = $category->name_en;
        $this->slug_pl = $category->slug_pl;
        $this->slug_en = $category->slug_en;
        $this->isEdit = true;
    }

    public function save()
    {
        $validated = $this->validate($this->rules);

        if ($this->isEdit) {
            $category = BlogCategory::findOrFail($this->categoryId);
            $category->update($validated);
        } else {
            BlogCategory::create($validated);
        }

        $this->resetForm();
        session()->flash('message', 'Kategoria zapisana pomyślnie!');
    }

    public function delete($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();
        session()->flash('message', 'Kategoria usunięta!');
    }
}
