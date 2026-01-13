<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class HomeComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 12;

    public function render()
    {
        $locale = app()->getLocale(); // 'pl' lub 'en'
        $nameColumn = "name_{$locale}";

        // Pobierz ID pierwszego produktu dla każdej unikalnej nazwy/modelu
        $productIds = Product::selectRaw("MIN(id) as id")
            ->groupBy($nameColumn)
            ->pluck('id');

        // Pobierz produkty z relacją do kategorii i paginacją
        $products = Product::with('category')
            ->whereIn('id', $productIds)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $categories = Category::all();

        return view('livewire.home-component', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
