<?php

namespace App\Http\Livewire\Admin\Blog;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\BlogPost;
use App\Models\BlogSubcategory;
use App\Models\BlogPostImage;
use App\Models\BlogTag;

class AdminBlogPostsComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $subcategory_id;
    public $title_pl;
    public $title_en;
    public $slug_pl;
    public $slug_en;
    public $featured_image;
    public $featured_image_path;
    public $featured_image_alt;
    public $tags = [];

    public $rows = [];
    public $uploads = [];

    public $rowError = null;
    public $updateMode = false;
    public $post_id;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        $slugPlRule = 'required|string|max:255|unique:blog_posts,slug_pl';
        $slugEnRule = 'nullable|string|max:255|unique:blog_posts,slug_en';

        if ($this->updateMode && $this->post_id) {
            $slugPlRule .= ',' . $this->post_id;
            $slugEnRule .= ',' . $this->post_id;
        }

        return [
            'subcategory_id' => 'required|integer|exists:blog_subcategories,id',
            'title_pl' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'slug_pl' => $slugPlRule,
            'slug_en' => $slugEnRule,
            'featured_image' => 'nullable|image|max:4096',
            'featured_image_alt' => 'nullable|string|max:255',
            'tags' => 'required|array|min:1',
            'tags.*' => 'exists:blog_tags,id',
        ];
    }

    public function render()
    {
        $query = BlogPost::with('subcategory', 'tags')->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title_pl', 'like', '%' . $this->search . '%')
                    ->orWhere('title_en', 'like', '%' . $this->search . '%');
            });
        }

        $posts = $query->paginate(10);
        $subcategories = BlogSubcategory::with('category')->orderBy('name_pl')->get();
        $allTags = BlogTag::orderBy('name_pl')->get();

        return view('livewire.admin.blog.admin-blog-posts-component', compact('posts', 'subcategories', 'allTags'))
            ->layout('layouts.admin');
    }

    public function resetInputFields()
    {
        $this->resetValidation();
        $this->reset([
            'subcategory_id',
            'title_pl',
            'title_en',
            'slug_pl',
            'slug_en',
            'featured_image',
            'featured_image_path',
            'featured_image_alt',
            'tags',
            'rows',
            'uploads',
            'rowError',
            'post_id',
            'updateMode'
        ]);
    }

    public function generateSlug($lang)
    {
        if ($lang === 'pl' && $this->title_pl) $this->slug_pl = Str::slug($this->title_pl);
        if ($lang === 'en' && $this->title_en) $this->slug_en = Str::slug($this->title_en);
    }

    private function lastRowIsFilled(): bool
    {
        if (empty($this->rows)) return true;
        $last = end($this->rows);

        foreach ($last as $el) {
            if ($el['type'] === 'img') {
                $hasExisting = !empty($el['image_path']);
                $hasUpload = isset($this->uploads[$el['temp_id']]);
                if (!$hasExisting && !$hasUpload) {
                    $this->rowError = 'Proszę wypełnić lub usunąć pole obrazu w ostatnim wierszu.';
                    return false;
                }
            }
            if ($el['type'] === 'p' && trim($el['paragraph_pl'] ?? '') === '') {
                $this->rowError = 'Proszę wypełnić lub usunąć pole tekstu PL w ostatnim wierszu.';
                return false;
            }
        }

        $this->rowError = null;
        return true;
    }

    private function makeImgElement(): array
    {
        return [
            'type' => 'img',
            'temp_id' => (string) Str::uuid(),
            'image_path' => null,
            'alt' => null,
            'caption' => null,
        ];
    }

    private function makePElement(): array
    {
        return [
            'type' => 'p',
            'temp_id' => (string) Str::uuid(),
            'paragraph_pl' => null,
            'paragraph_en' => null,
        ];
    }

    // Dodawanie wierszy
    public function addRowImgImg()
    {
        if ($this->lastRowIsFilled()) $this->rows[] = [$this->makeImgElement(), $this->makeImgElement()];
    }
    public function addRowPP()
    {
        if ($this->lastRowIsFilled()) $this->rows[] = [$this->makePElement(), $this->makePElement()];
    }
    public function addRowImgP()
    {
        if ($this->lastRowIsFilled()) $this->rows[] = [$this->makeImgElement(), $this->makePElement()];
    }
    public function addRowPImg()
    {
        if ($this->lastRowIsFilled()) $this->rows[] = [$this->makePElement(), $this->makeImgElement()];
    }
    public function addRowSingleImg()
    {
        if ($this->lastRowIsFilled()) $this->rows[] = [$this->makeImgElement()];
    }
    public function addRowSingleP()
    {
        if ($this->lastRowIsFilled()) $this->rows[] = [$this->makePElement()];
    }

    private function hasAtLeastOneImage(): bool
    {
        foreach ($this->rows as $row) {
            foreach ($row as $el) {
                if ($el['type'] === 'img') {
                    $hasExisting = !empty($el['image_path']);
                    $hasUpload = isset($this->uploads[$el['temp_id']]);
                    if ($hasExisting || $hasUpload) return true;
                }
            }
        }
        return false;
    }

    public function removeRow($index)
    {
        if (!isset($this->rows[$index])) return;
        foreach ($this->rows[$index] as $el) {
            if ($el['type'] === 'img' && isset($this->uploads[$el['temp_id']])) unset($this->uploads[$el['temp_id']]);
        }
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
        $this->rowError = null;
    }

    public function store()
    {
        $this->validate();

        if (!$this->hasAtLeastOneImage()) {
            $this->rowError = 'Musisz dodać przynajmniej jedno zdjęcie w treści!';
            return;
        }

        $featuredPath = $this->featured_image ? $this->featured_image->store('uploads/blog/featured', 'public') : null;

        $post = BlogPost::create([
            'subcategory_id' => $this->subcategory_id,
            'title_pl' => $this->title_pl,
            'title_en' => $this->title_en,
            'slug_pl' => $this->slug_pl,
            'slug_en' => $this->slug_en,
            'featured_image' => $featuredPath,
            'featured_image_alt' => $this->featured_image_alt,
            'is_published' => true,
        ]);

        $this->saveRows($post->id);
        $post->tags()->sync(array_values(array_unique($this->tags)));

        session()->flash('message', 'Post został zapisany ✅');
        $this->resetInputFields();
    }

    public function update()
    {
        $this->validate();

        if (!$this->hasAtLeastOneImage()) {
            $this->rowError = 'Musisz dodać przynajmniej jedno zdjęcie w treści!';
            return;
        }

        $post = BlogPost::findOrFail($this->post_id);

        if ($this->featured_image) {
            $post->featured_image = $this->featured_image->store('uploads/blog/featured', 'public');
        }

        $post->subcategory_id = $this->subcategory_id;
        $post->title_pl = $this->title_pl;
        $post->title_en = $this->title_en;
        $post->slug_pl = $this->slug_pl;
        $post->slug_en = $this->slug_en;
        $post->featured_image_alt = $this->featured_image_alt;
        $post->save();

        BlogPostImage::where('blog_post_id', $post->id)->delete();
        $this->saveRows($post->id);
        $post->tags()->sync(array_values(array_unique($this->tags)));

        session()->flash('message', 'Post został zaktualizowany ✅');
        $this->resetInputFields();
    }

    private function saveRows($postId)
    {
        foreach ($this->rows as $rIndex => $row) {
            foreach ($row as $colIndex => $el) {
                if ($el['type'] === 'img') {
                    $path = null;

                    // 🔹 Jeśli plik został przesłany tymczasowo (Livewire upload)
                    if (isset($this->uploads[$el['temp_id']])) {
                        $path = $this->uploads[$el['temp_id']]->store('uploads/blog/content', 'public');
                        unset($this->uploads[$el['temp_id']]);
                    }
                    // 🔹 Jeśli obrazek już istnieje (np. edycja)
                    elseif (!empty($el['image_path'])) {
                        $path = $el['image_path'];
                    }

                    // 🔹 Pomijamy puste obrazki
                    if (empty($path)) {
                        continue;
                    }

                    BlogPostImage::create([
                        'blog_post_id' => $postId,
                        'image_path' => $path,
                        'caption' => $el['caption'] ?? null,
                        'paragraph' => null,
                        'paragraph_en' => null,
                        'order' => $rIndex * 10 + $colIndex,
                    ]);
                }

                if ($el['type'] === 'p' && trim($el['paragraph_pl'] ?? '') !== '') {
                    BlogPostImage::create([
                        'blog_post_id' => $postId,
                        'image_path' => null,
                        'caption' => null,
                        'paragraph' => $el['paragraph_pl'],
                        'paragraph_en' => $el['paragraph_en'] ?? null,
                        'order' => $rIndex * 10 + $colIndex,
                    ]);
                }
            }
        }
    }


    public function edit($id)
    {
        $post = BlogPost::with('images', 'tags')->findOrFail($id);

        $this->post_id = $post->id;
        $this->subcategory_id = $post->subcategory_id;
        $this->title_pl = $post->title_pl;
        $this->title_en = $post->title_en;
        $this->slug_pl = $post->slug_pl;
        $this->slug_en = $post->slug_en;
        $this->featured_image_path = $post->featured_image;
        $this->featured_image_alt = $post->featured_image_alt;
        $this->tags = $post->tags->pluck('id')->toArray();

        $rows = [];
        foreach ($post->images->sortBy('order') as $img) {
            $lastRowIndex = count($rows) - 1;
            if ($lastRowIndex < 0 || count($rows[$lastRowIndex]) >= 2) {
                $rows[] = [];
                $lastRowIndex++;
            }

            if ($img->image_path) {
                $rows[$lastRowIndex][] = [
                    'type' => 'img',
                    'temp_id' => (string) Str::uuid(),
                    'image_path' => $img->image_path,
                    'alt' => $img->caption,
                    'caption' => $img->caption,
                ];
            } else {
                $rows[$lastRowIndex][] = [
                    'type' => 'p',
                    'temp_id' => (string) Str::uuid(),
                    'paragraph_pl' => $img->paragraph,
                    'paragraph_en' => $img->paragraph_en ?? null,
                ];
            }
        }

        $this->rows = $rows;
        $this->updateMode = true;
        $this->rowError = null;
        $this->resetValidation();
    }

    public function delete($id)
    {
        $post = BlogPost::findOrFail($id);

        if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
            Storage::disk('public')->delete($post->featured_image);
        }

        foreach ($post->images as $img) {
            if ($img->image_path && Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $post->delete();
        session()->flash('message', 'Post został usunięty ✅');
    }
}
