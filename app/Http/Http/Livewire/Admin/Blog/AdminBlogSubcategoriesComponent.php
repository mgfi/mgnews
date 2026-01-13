<?php

namespace App\Http\Livewire\Admin\Blog;

use Livewire\Component;
use App\Models\BlogCategory;
use App\Models\BlogSubcategory;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class AdminBlogSubcategoriesComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // 👈 dzięki temu linki będą ładne

    public $sortColumn = 'id';
    public $sortDirection = 'asc';
    public $updateMode = false;

    public $category_id, $name_pl, $slug_pl, $name_en, $slug_en, $subcategory_id;

    public $slug_pl_manual = false;
    public $slug_en_manual = false;

    protected $rules = [
        'category_id' => 'required|exists:blog_categories,id',
        'name_pl' => 'required|string|max:255',
        'slug_pl' => 'required|string|max:255|unique:blog_subcategories,slug_pl',
        'name_en' => 'nullable|string|max:255',
        'slug_en' => 'nullable|string|max:255|unique:blog_subcategories,slug_en',
    ];

    // Renderowanie komponentu
    public function render()
    {
        $subcategories = BlogSubcategory::with('category')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        $categories = BlogCategory::orderBy('name_pl')->get();

        return view('livewire.admin.blog.admin-blog-subcategories-component', compact(
            'subcategories',
            'categories'
        ))->layout('layouts.admin');
    }

    // --- Generowanie i aktualizacja slugów automatycznie ---

    public function updatedNamePl($value)
    {
        if (!$this->slug_pl_manual) {
            $this->slug_pl = $this->generateSlug($value);
        }

        if (trim($value) === '') {
            $this->slug_pl = '';
        }
    }

    public function updatedNameEn($value)
    {
        if (!$this->slug_en_manual) {
            $this->slug_en = $this->generateSlug($value);
        }

        if (trim($value) === '') {
            $this->slug_en = '';
        }
    }

    public function updatedSlugPl($value)
    {
        $this->slug_pl_manual = true;
        $this->slug_pl = $this->generateSlug($value);
    }

    public function updatedSlugEn($value)
    {
        $this->slug_en_manual = true;
        $this->slug_en = $this->generateSlug($value);
    }

    protected function generateSlug(?string $value): string
    {
        if (!$value) return '';
        return Str::slug($value, '-');
    }

    // --- Sortowanie tabeli ---
    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    // --- Resetowanie formularza ---
    public function resetInputFields()
    {
        $this->reset(['category_id', 'name_pl', 'slug_pl', 'name_en', 'slug_en', 'subcategory_id', 'updateMode']);
        $this->slug_pl_manual = false;
        $this->slug_en_manual = false;
    }

    // --- Tworzenie nowej podkategorii ---
    public function store()
    {
        $this->validate();

        BlogSubcategory::create([
            'category_id' => $this->category_id,
            'name_pl' => $this->name_pl,
            'slug_pl' => $this->slug_pl,
            'name_en' => $this->name_en,
            'slug_en' => $this->slug_en,
        ]);

        session()->flash('message', 'Podkategoria została dodana.');
        $this->resetInputFields();
    }

    // --- Edycja podkategorii ---
    public function edit($id)
    {
        $subcategory = BlogSubcategory::findOrFail($id);

        $this->subcategory_id = $id;
        $this->category_id = $subcategory->category_id;
        $this->name_pl = $subcategory->name_pl;
        $this->slug_pl = $subcategory->slug_pl;
        $this->name_en = $subcategory->name_en;
        $this->slug_en = $subcategory->slug_en;
        $this->updateMode = true;

        // reset flag
        $this->slug_pl_manual = false;
        $this->slug_en_manual = false;
    }

    // --- Aktualizacja istniejącej podkategorii ---
    public function update()
    {
        $this->validate([
            'category_id' => 'required|exists:blog_categories,id',
            'name_pl' => 'required|string|max:255',
            'slug_pl' => 'required|string|max:255|unique:blog_subcategories,slug_pl,' . $this->subcategory_id,
            'name_en' => 'nullable|string|max:255',
            'slug_en' => 'nullable|string|max:255|unique:blog_subcategories,slug_en,' . $this->subcategory_id,
        ]);

        $subcategory = BlogSubcategory::findOrFail($this->subcategory_id);

        $subcategory->update([
            'category_id' => $this->category_id,
            'name_pl' => $this->name_pl,
            'slug_pl' => $this->slug_pl,
            'name_en' => $this->name_en,
            'slug_en' => $this->slug_en,
        ]);

        session()->flash('message', 'Podkategoria została zaktualizowana.');
        $this->resetInputFields();
    }

    // --- Usuwanie podkategorii ---
    public function delete($id)
    {
        BlogSubcategory::findOrFail($id)->delete();
        session()->flash('message', 'Podkategoria została usunięta.');
    }
}
