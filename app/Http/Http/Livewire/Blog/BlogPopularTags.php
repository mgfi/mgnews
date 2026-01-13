<?php

namespace App\Http\Livewire\Blog;

use Livewire\Component;
use App\Models\BlogTag;

class BlogPopularTags extends Component
{
    public $selectedTags = [];

    protected $listeners = ['toggleTag'];

    public function mount()
    {
        // Sprawdzenie w URL, które tagi są zaznaczone
        if (request()->has('selectedTags')) {
            $this->selectedTags = request()->query('selectedTags', []);
        }
    }

    public function toggleTag($tagId)
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_values(array_diff($this->selectedTags, [$tagId]));
        } else {
            $this->selectedTags[] = $tagId;
        }

        $this->emitUp('tagsUpdated', $this->selectedTags);
    }

    public function render()
    {
        // Pobieramy tylko tagi przypisane do jakichkolwiek postów
        $tags = BlogTag::whereHas('posts')->get();

        return view('livewire.blog.blog-popular-tags', [
            'tags' => $tags,
            'selectedTags' => $this->selectedTags,
        ]);
    }
}
