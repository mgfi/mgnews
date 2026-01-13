<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BlogComponentSearch extends Component
{
    public $search = '';

    public function updatedSearch()
    {
        $this->emitUp('searchUpdated', $this->search);
    }

    public function render()
    {
        return view('livewire.blog-component-search');
    }
}
