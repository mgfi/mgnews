<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProductSearch extends Component
{
    public $query = '';

    // emitujemy event dla ShopComponent przy każdej zmianie
    public function updatedQuery()
    {
        $this->emit('searchUpdated', $this->query);
    }

    // nowa metoda do czyszczenia pola wyszukiwania
    public function clear()
    {
        $this->query = '';
        $this->emit('searchUpdated', $this->query);
    }

    public function render()
    {
        return view('livewire.product-search');
    }
}
