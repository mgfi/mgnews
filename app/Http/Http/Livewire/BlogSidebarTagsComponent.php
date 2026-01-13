<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BlogTag;

class BlogSidebarTagsComponent extends Component
{
    public $tags;

    public function mount()
    {
        $this->tags = BlogTag::orderBy('name_pl')->get();
    }

    public function render()
    {
        return view('livewire.blog-sidebar-tags');
    }
}
