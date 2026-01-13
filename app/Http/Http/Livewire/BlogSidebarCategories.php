<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BlogCategory;

class BlogSidebarCategories extends Component
{
    public $categories;

    /**
     * Pobieramy maksymalnie 5 kategorii nadrzędnych wraz z podkategoriami.
     * Dodatkowo zliczamy posty (withCount) aby uniknąć N+1.
     */
    public function mount()
    {
        $this->categories = BlogCategory::with([
            // ładujemy podkategorie i dla nich licznik postów
            'children' => function ($q) {
                $q->withCount('posts')->orderBy('name_pl');
            },
        ])
            ->withCount('posts') // liczba postów przypisanych bezpośrednio do kategorii nadrzędnej
            ->whereNull('parent_id')
            ->orderBy('name_pl')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.blog-sidebar-categories');
    }
}
