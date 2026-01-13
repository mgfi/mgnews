<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogStatsComponent extends Component
{
    public $mainCategoriesCount;
    public $subCategoriesCount;
    public $postsCount;
    public $authorsCount;
    public $viewsCount;

    public function mount()
    {
        // Kategorie nadrzędne
        $this->mainCategoriesCount = BlogCategory::whereNull('parent_id')->count();

        // Subkategorie
        $this->subCategoriesCount  = BlogCategory::whereNotNull('parent_id')->count();

        // Wszystkie posty
        $this->postsCount = BlogPost::count();

        // Autorzy, którzy mają przynajmniej jeden wpis
        $this->authorsCount = BlogPost::distinct('user_id')->count('user_id');

        // Suma wyświetleń wszystkich wpisów
        $this->viewsCount = BlogPost::sum('views');
    }

    public function render()
    {
        return view('livewire.blog-stats');
    }
}
