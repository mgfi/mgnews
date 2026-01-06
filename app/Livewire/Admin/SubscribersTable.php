<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subscriber;

class SubscribersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.admin.subscribers-table', [
            'subscribers' => Subscriber::latest()->paginate(15),
        ]);
    }
}
