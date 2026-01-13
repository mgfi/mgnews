<?php

namespace App\Http\Livewire\Admin\Blog;

use Livewire\Component;

class AdminBlogSettingsComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.blog.admin-blog-settings-component')
            ->layout('layouts.admin');
    }
}
