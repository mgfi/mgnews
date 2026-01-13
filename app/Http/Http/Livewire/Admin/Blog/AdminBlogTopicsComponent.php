<?php

namespace App\Http\Livewire\Admin\Blog;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogAdminTopic;

class AdminBlogTopicsComponent extends Component
{
    use WithPagination;

    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function togglePublished($id)
    {
        $topic = BlogAdminTopic::findOrFail($id);
        $topic->published = !$topic->published;
        $topic->published_at = $topic->published ? now() : null;
        $topic->save();

        session()->flash('message', 'Status tematu został zmieniony.');
    }

    public function render()
    {
        $topics = BlogAdminTopic::orderBy('published_at', 'desc')->paginate($this->perPage);

        return view('livewire.admin.blog.admin-blog-topics-component', [
            'topics' => $topics,
        ])->layout('layouts.admin');
    }
}
