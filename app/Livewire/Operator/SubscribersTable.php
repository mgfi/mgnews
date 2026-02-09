<?php

namespace App\Livewire\Operator;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subscriber;
use Illuminate\Support\Str;

class SubscribersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $email = '';

    protected $rules = [
        'email' => 'required|email|unique:subscribers,email',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function add()
    {
        $this->validate();

        $subscriber = Subscriber::create([
            'email' => $this->email,
            'is_active' => true,
            'source' => 'operator',
            'unsubscribe_token' => Str::uuid(),
        ]);

        // TU później podepniesz wysyłkę maila potwierdzającego
        // SubscriptionService::sendConfirmation($subscriber);

        $this->reset('email');

        session()->flash('success', 'Subskrybent dodany.');
        $this->dispatch('close-modal');
    }

    public function delete(int $id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        session()->flash('success', 'Subskrybent usunięty.');
    }

    public function render()
    {
        $subscribers = Subscriber::query()
            ->when(
                $this->search,
                fn($q) =>
                $q->where('email', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(15);

        return view('livewire.operator.subscribers-table', [
            'subscribers' => $subscribers,
        ]);
    }
}
