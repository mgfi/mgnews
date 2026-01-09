<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subscriber;

class SubscribersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $email = '';

    protected $rules = [
        'email' => 'required|email|unique:subscribers,email',
    ];

    public function add(): void
    {
        $this->validate();

        Subscriber::create([
            'email' => $this->email,
            'is_active' => true,
            'source' => 'admin',
        ]);

        $this->reset('email');
        session()->flash('success', 'Subskrybent dodany.');
    }

    public function render()
    {
        return view('livewire.admin.subscribers-table', [
            'subscribers' => Subscriber::latest()->paginate(15),
        ])->layout('layouts.admin');
    }
}
