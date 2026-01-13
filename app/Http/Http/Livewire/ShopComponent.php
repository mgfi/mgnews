<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Gloudemans\Shoppingcart\Facades\Cart;

class ShopComponent extends Component
{
    use WithPagination;

    // Ustawiamy Bootstrap jako szablon paginacji
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    protected $updatesQueryString = ['search'];

    protected $listeners = [
        'searchUpdated' => 'onSearchUpdated',
    ];

    // Resetuje paginację przy zmianie wyszukiwania
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Aktualizacja wyszukiwania z Livewire
    public function onSearchUpdated($query)
    {
        $this->search = $query;
        $this->resetPage();
    }

    // Dodawanie produktu do koszyka
    public function store($product_id, $product_name, $product_price)
    {
        Cart::add($product_id, $product_name, 1, $product_price)
            ->associate(Product::class);

        session()->flash('success_message', 'Produkt dodany do koszyka.');
        return redirect()->route('shop.cart');
    }

    public function render()
    {
        $locale     = app()->getLocale();
        $nameColumn = "name_{$locale}";

        // Budujemy zapytanie dla produktów
        $productQuery = Product::with('category');

        if ($this->search) {
            $productQuery->where($nameColumn, 'like', '%' . $this->search . '%');
        }

        // Zwracamy tylko jeden produkt na nazwę
        $productIds = $productQuery->selectRaw('MIN(id) as id')
            ->groupBy($nameColumn)
            ->pluck('id');

        $products = Product::with('category')
            ->whereIn('id', $productIds)
            ->orderBy('created_at', 'desc')
            ->paginate(12)       // ilość produktów na stronie
            ->onEachSide(3);     // ograniczenie linków paginacji do 7

        // Nowe produkty – po jednym najnowszym na model
        $newIds = Product::selectRaw('MAX(id) as id')
            ->groupBy($nameColumn)
            ->orderByRaw('MAX(created_at) desc')
            ->limit(3)
            ->pluck('id');

        $newProducts = Product::whereIn('id', $newIds)
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::all();

        return view('livewire.shop-component', [
            'products'    => $products,
            'categories'  => $categories,
            'newProducts' => $newProducts,
        ]);
    }
}
