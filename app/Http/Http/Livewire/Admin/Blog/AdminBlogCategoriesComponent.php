<?php

namespace App\Http\Livewire\Admin\Blog;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogCategory;

class AdminBlogCategoriesComponent extends Component
{
    use WithPagination;

    public $name_pl, $name_en, $slug_pl, $slug_en, $category_id;
    public $updateMode = false;

    public $sortColumn = 'id';
    public $sortDirection = 'asc';

    protected $rules = [
        'name_pl' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'slug_pl' => 'required|string|max:255',
        'slug_en' => 'required|string|max:255',
    ];

    public function render()
    {
        $categories = BlogCategory::orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.blog.admin-blog-categories-component', [
            'categories' => $categories
        ])->layout('layouts.admin');
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function resetInputFields()
    {
        $this->reset(['name_pl', 'name_en', 'slug_pl', 'slug_en', 'category_id', 'updateMode']);
    }

    public function store()
    {
        $this->validate();

        BlogCategory::create([
            'name_pl' => $this->name_pl,
            'name_en' => $this->name_en,
            'slug_pl' => $this->slug_pl,
            'slug_en' => $this->slug_en,
        ]);

        session()->flash('message', 'Kategoria dodana pomyślnie.');
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);
        $this->category_id = $id;
        $this->name_pl = $category->name_pl;
        $this->name_en = $category->name_en;
        $this->slug_pl = $category->slug_pl;
        $this->slug_en = $category->slug_en;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate();

        $category = BlogCategory::findOrFail($this->category_id);
        $category->update([
            'name_pl' => $this->name_pl,
            'name_en' => $this->name_en,
            'slug_pl' => $this->slug_pl,
            'slug_en' => $this->slug_en,
        ]);

        session()->flash('message', 'Kategoria zaktualizowana.');
        $this->resetInputFields();
    }

    public function delete($id)
    {
        BlogCategory::findOrFail($id)->delete();
        session()->flash('message', 'Kategoria usunięta.');
    }
}
