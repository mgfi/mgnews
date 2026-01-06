<?php

namespace App\Livewire\Newsletter;

use Livewire\Component;
use App\Models\Subscriber;

class SubscribeForm extends Component
{
    public string $email = '';

    protected array $rules = [
        'email' => ['required', 'email:rfc,dns', 'max:255'],
    ];

    public function submit(): void
    {
        $this->validate();

        $subscriber = Subscriber::withTrashed()
            ->where('email', $this->email)
            ->first();

        if ($subscriber) {
            // jeśli był soft-deleted → przywracamy
            if ($subscriber->trashed()) {
                $subscriber->restore();
            }

            $subscriber->update([
                'is_active' => true,
                'source' => 'newsletter',
            ]);

            session()->flash('newsletter_message', 'Ten adres jest już zapisany.');
            $this->reset('email');
            return;
        }

        Subscriber::create([
            'email'     => $this->email,
            'is_active' => true,
            'source'    => 'newsletter',
        ]);

        session()->flash('newsletter_message', 'Dziękujemy za zapis!');
        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.newsletter.subscribe-form');
    }
}
