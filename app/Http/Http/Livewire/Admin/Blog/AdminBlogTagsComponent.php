<?php

namespace App\Http\Livewire\Admin\Blog;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogTag;

class AdminBlogTagsComponent extends Component
{
    use WithPagination;

    public $name_pl, $slug_pl, $name_en, $slug_en, $tag_id;
    public $updateMode = false;

    public $sortColumn = 'id';
    public $sortDirection = 'asc';

    protected $rules = [
        'name_pl' => 'required|string|max:255',
        'slug_pl' => 'required|string|max:255',
        'name_en' => 'nullable|string|max:255',
        'slug_en' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'name_pl.required' => 'Pole "Nazwa PL" jest wymagane.',
        'slug_pl.required' => 'Pole "Slug PL" jest wymagane.',
        'name_en.required' => 'Pole "Nazwa EN" jest wymagane.',
        'slug_en.required' => 'Pole "Slug EN" jest wymagane.',
    ];

    public function render()
    {
        $tags = BlogTag::orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.blog.admin-blog-tags-component', [
            'tags' => $tags
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
        $this->reset(['name_pl', 'slug_pl', 'name_en', 'slug_en', 'tag_id', 'updateMode']);
    }

    public function store()
    {
        $this->validate();

        BlogTag::create([
            'name_pl' => $this->name_pl,
            'slug_pl' => $this->slug_pl,
            'name_en' => $this->name_en,
            'slug_en' => $this->slug_en,
        ]);

        session()->flash('message', 'Tag dodany.');
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $tag = BlogTag::findOrFail($id);
        $this->tag_id = $id;
        $this->name_pl = $tag->name_pl;
        $this->slug_pl = $tag->slug_pl;
        $this->name_en = $tag->name_en;
        $this->slug_en = $tag->slug_en;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate();

        $tag = BlogTag::findOrFail($this->tag_id);
        $tag->update([
            'name_pl' => $this->name_pl,
            'slug_pl' => $this->slug_pl,
            'name_en' => $this->name_en,
            'slug_en' => $this->slug_en,
        ]);

        session()->flash('message', 'Tag zaktualizowany.');
        $this->resetInputFields();
    }

    public function delete($id)
    {
        BlogTag::findOrFail($id)->delete();
        session()->flash('message', 'Tag usunięty.');
    }
}
