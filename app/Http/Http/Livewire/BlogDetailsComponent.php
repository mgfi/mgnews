<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BlogPost;

class BlogDetailsComponent extends Component
{
    public $post;
    public $subcategory;
    public $category;
    public $tags;
    public $selectedTags = []; // zaznaczone tagi dla sidebaru
    public $comments;
    public $relatedPosts;

    public function mount($slug)
    {
        $locale = app()->getLocale();

        // Pobranie posta wraz z relacjami
        $this->post = BlogPost::with([
            'tags',
            'comments.user',
            'subcategory.category',
            'user'
        ])
            ->where($locale === 'en' ? 'slug_en' : 'slug_pl', $slug)
            ->where('is_published', 1)
            ->firstOrFail();

        // Subkategoria i kategoria
        $this->subcategory = $this->post->subcategory;
        $this->category = $this->subcategory ? $this->subcategory->category : null;

        // Tagi przypisane do posta
        $this->tags = $this->post->tags;

        // Zaznaczenie tagów w sidebarze
        $this->selectedTags = $this->tags->pluck('id')->toArray();

        // Komentarze
        $this->comments = $this->post->comments()->with('user')->get();

        // Polecane posty: ostatnie 5 z tej samej subkategorii, poza bieżącym postem
        $this->relatedPosts = BlogPost::where('subcategory_id', $this->post->subcategory_id)
            ->where('id', '!=', $this->post->id)
            ->where('is_published', 1)
            ->latest()
            ->take(5)
            ->get();

        // Zliczanie wyświetleń
        $this->post->increment('views_count');
    }

    /**
     * Toggle zaznaczenie tagu w sidebarze
     */
    public function toggleTag($tagId)
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_diff($this->selectedTags, [$tagId]);
        } else {
            $this->selectedTags[] = $tagId;
        }

        // Możesz tu np. odświeżyć listę powiązanych postów
        $this->emit('tagsUpdated', $this->selectedTags);
    }

    public function render()
    {
        return view('livewire.blog-details-component', [
            'post' => $this->post,
            'subcategory' => $this->subcategory,
            'category' => $this->category,
            'tags' => $this->tags,
            'selectedTags' => $this->selectedTags,
            'comments' => $this->comments,
            'relatedPosts' => $this->relatedPosts,
            'activeTags' => $this->tags->pluck('id')->toArray(), // <- dodajemy
        ]);
    }
}
