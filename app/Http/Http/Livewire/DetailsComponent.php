<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class DetailsComponent extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function store($product_id, $product_name, $product_price)
    {
        Cart::add($product_id, $product_name, 1, $product_price)
            ->associate(Product::class);

        session()->flash('success_message', 'Produkt dodany do koszyka.');
        return redirect()->route('shop.cart');
    }

    public function render()
    {
        $locale = app()->getLocale(); // 'pl' albo 'en'
        $slugColumn = "slug_{$locale}";
        $nameColumn = "name_{$locale}";

        // Pobierz produkt po slugu w danym języku
        $product = Product::where($slugColumn, $this->slug)->firstOrFail();

        // Pobierz całą kolekcję wariantów (np. rozmiary) o tej samej nazwie
        $collection = Product::where($nameColumn, $product->$nameColumn)->get();

        // Zbierz wszystkie zdjęcia
        $allImages = [];
        foreach ($collection as $item) {
            if ($item->image) {
                $allImages[] = $item->image;
            }
            if ($item->images) {
                $images = json_decode($item->images, true);
                if (is_array($images)) {
                    $allImages = array_merge($allImages, $images);
                }
            }
        }

        return view('livewire.details-component', [
            'product' => $product,
            'collection' => $collection,
            'allImages' => $allImages,
        ]);
    }
}
