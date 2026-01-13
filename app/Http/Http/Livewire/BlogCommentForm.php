<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BlogComment;
use Illuminate\Support\Facades\Auth;

class BlogCommentForm extends Component
{
    public $post; // BlogPost model (przekazywany z widoku)
    public $content = '';

    protected $rules = [
        'content' => 'required|string|min:3|max:2000',
    ];

    public function submit()
    {
        $this->validate();

        if (!Auth::check()) {
            session()->flash('error', 'Musisz być zalogowany, aby dodać komentarz.');
            return redirect()->route('login');
        }

        BlogComment::create([
            'blog_post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'content' => $this->content,
        ]);

        // Reset formularza
        $this->reset('content');

        // Event do odświeżenia listy komentarzy
        $this->emit('commentAdded');

        session()->flash('success', 'Komentarz został dodany.');
    }

    public function render()
    {
        return view('livewire.blog-comment-form');
    }
}
